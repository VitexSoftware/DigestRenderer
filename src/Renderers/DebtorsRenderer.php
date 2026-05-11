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
        $title = $moduleData['heading'] ?? _('Debtors');
        $data = $moduleData['data'] ?? [];

        $md = '';

        if (isset($data['summary'])) {
            $md .= $this->markdownSummary(_('Debtor summary'), [
                _('Total debtors') => $data['summary']['total_debtors'] ?? 0,
                _('Total invoices') => $data['summary']['total_invoices'] ?? 0,
                _('Currencies') => is_array($data['summary']['currencies'] ?? null)
                    ? implode(', ', $data['summary']['currencies']) : 'N/A',
            ]);
        }

        if (isset($data['totals_by_currency'])) {
            $rows = [];

            foreach ($data['totals_by_currency'] as $currency => $currencyData) {
                $rows[] = [$currency, $this->formatCurrency($currencyData)];
            }

            $md .= $this->markdownHeading(_('Outstanding amounts'), 4);
            $md .= $this->markdownTable([_('Currency'), _('Amount')], $rows);
        }

        if (isset($data['overdue_ranges'])) {
            $rows = [];

            foreach ($data['overdue_ranges'] as $range => $count) {
                $rows[] = [$range . ' ' . _('days'), (string) $count];
            }

            $md .= $this->markdownHeading(_('Invoices by overdue period'), 4);
            $md .= $this->markdownTable([_('Overdue period'), _('Number of invoices')], $rows);
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
            return $this->markdownHeading(_('Top debtors'), 4) . _('No debtors data available') . "\n\n";
        }

        $headers = [_('Company'), _('Invoices count'), _('Max overdue days')];

        $allCurrencies = [];

        foreach ($topDebtors as $debtor) {
            if (isset($debtor['total_amount'])) {
                $allCurrencies = array_merge($allCurrencies, array_keys($debtor['total_amount']));
            }
        }

        $allCurrencies = array_unique($allCurrencies);

        foreach ($allCurrencies as $currency) {
            $headers[] = sprintf(_('Amount') . ' (%s)', $currency);
        }

        $rows = [];

        foreach ($topDebtors as $debtor) {
            $row = [
                $debtor['company'] ?? _('Unknown'),
                (string) ($debtor['invoices_count'] ?? 0),
                (string) ($debtor['overdue_days_max'] ?? 0),
            ];

            foreach ($allCurrencies as $currency) {
                $currencyData = $debtor['total_amount'][$currency] ?? null;
                $row[] = $currencyData ? $this->formatCurrency($currencyData) : '-';
            }

            $rows[] = $row;
        }

        return $this->markdownHeading(_('Top debtors'), 4) . $this->markdownTable($headers, $rows);
    }
}
