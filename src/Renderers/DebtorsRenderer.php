<?php

declare(strict_types=1);

/**
 * This file is part of the DigestRenderer package
 *
 * https://github.com/VitexSoftware/DigestRenderer/
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VitexSoftware\DigestRenderer\Renderers;

use VitexSoftware\DigestRenderer\I18n;

/**
 * Debtors module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class DebtorsRenderer extends AbstractModuleRenderer
{
    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? I18n::t('Debtors');
        $data = $moduleData['data'] ?? [];

        $md = '';

        if (isset($data['summary'])) {
            $md .= $this->markdownSummary(I18n::t('Debtor summary'), [
                I18n::t('Total debtors') => $data['summary']['total_debtors'] ?? 0,
                I18n::t('Total invoices') => $data['summary']['total_invoices'] ?? 0,
                I18n::t('Currencies') => is_array($data['summary']['currencies'] ?? null)
                    ? implode(', ', $data['summary']['currencies']) : 'N/A',
            ]);
        }

        if (isset($data['totals_by_currency'])) {
            $rows = [];

            foreach ($data['totals_by_currency'] as $currency => $currencyData) {
                $rows[] = [$currency, $this->formatCurrency($currencyData)];
            }

            $md .= $this->markdownHeading(I18n::t('Outstanding amounts'), 4);
            $md .= $this->markdownTable([I18n::t('Currency'), I18n::t('Amount')], $rows);
        }

        if (isset($data['overdue_ranges'])) {
            $rows = [];

            foreach ($data['overdue_ranges'] as $range => $count) {
                $rows[] = [$range . ' ' . I18n::t('days'), (string) $count];
            }

            $md .= $this->markdownHeading(I18n::t('Invoices by overdue period'), 4);
            $md .= $this->markdownTable([I18n::t('Overdue period'), I18n::t('Number of invoices')], $rows);
        }

        if (isset($data['top_debtors'])) {
            $md .= $this->renderTopDebtors($data['top_debtors']);
        }

        return $this->markdownSection($title, $md);
    }

    /**
     * Render top debtors table
     *
     * @param array<int, array<string, mixed>> $topDebtors Top debtors data
     * @return string Markdown table
     */
    private function renderTopDebtors(array $topDebtors): string
    {
        if (empty($topDebtors)) {
            return $this->markdownHeading(I18n::t('Top debtors'), 4) . I18n::t('No debtors data available') . "\n\n";
        }

        $headers = [I18n::t('Company'), I18n::t('Invoices count'), I18n::t('Max overdue days')];

        $allCurrencies = [];

        foreach ($topDebtors as $debtor) {
            if (isset($debtor['total_amount'])) {
                $allCurrencies = array_merge($allCurrencies, array_keys($debtor['total_amount']));
            }
        }

        $allCurrencies = array_unique($allCurrencies);

        foreach ($allCurrencies as $currency) {
            $headers[] = sprintf(I18n::t('Amount') . ' (%s)', $currency);
        }

        $rows = [];

        foreach ($topDebtors as $debtor) {
            $row = [
                $debtor['company'] ?? I18n::t('Unknown'),
                (string) ($debtor['invoices_count'] ?? 0),
                (string) ($debtor['overdue_days_max'] ?? 0),
            ];

            foreach ($allCurrencies as $currency) {
                $currencyData = $debtor['total_amount'][$currency] ?? null;
                $row[] = $currencyData ? $this->formatCurrency($currencyData) : '-';
            }

            $rows[] = $row;
        }

        return $this->markdownHeading(I18n::t('Top debtors'), 4) . $this->markdownItemsTable($headers, $rows);
    }
}
