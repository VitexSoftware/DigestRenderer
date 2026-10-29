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
 * Outcoming invoices module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class OutcomingInvoicesRenderer extends AbstractModuleRenderer
{
    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? I18n::t('Outcoming invoices');
        $data = $moduleData['data'] ?? [];

        $md = '';

        if (isset($data['summary'])) {
            $summary = $data['summary'];
            $md .= $this->markdownSummary(I18n::t('Invoice summary'), [
                I18n::t('Total invoices') => $summary['total_count'] ?? 0,
                I18n::t('Active invoices') => $summary['active_count'] ?? 0,
                I18n::t('Cancelled invoices') => $summary['cancelled_count'] ?? 0,
                I18n::t('Document types') => $summary['document_types_count'] ?? 0,
                I18n::t('Currencies') => is_array($summary['currencies'] ?? null)
                    ? implode(', ', $summary['currencies']) : 'N/A',
            ]);
        }

        if (isset($data['totals_by_currency'])) {
            $rows = [];

            foreach ($data['totals_by_currency'] as $currency => $currencyData) {
                $rows[] = [$currency, $this->formatCurrency($currencyData)];
            }

            $md .= $this->markdownHeading(I18n::t('Totals by currency'), 4);
            $md .= $this->markdownTable([I18n::t('Currency'), I18n::t('Amount')], $rows);
        }

        if (isset($data['by_document_type'])) {
            $md .= $this->renderDocumentTypeBreakdown($data['by_document_type']);
        }

        return $this->markdownSection($title, $md);
    }

    /**
     * Render document type breakdown
     *
     * @param array<string, mixed> $docTypes Document type data
     * @return string Markdown table
     */
    private function renderDocumentTypeBreakdown(array $docTypes): string
    {
        $headers = [I18n::t('Document type'), I18n::t('Count')];

        $allCurrencies = [];

        foreach ($docTypes as $docTypeData) {
            if (isset($docTypeData['totals'])) {
                $allCurrencies = array_merge($allCurrencies, array_keys($docTypeData['totals']));
            }
        }

        $allCurrencies = array_unique($allCurrencies);

        foreach ($allCurrencies as $currency) {
            $headers[] = sprintf(I18n::t('Total') . ' (%s)', $currency);
        }

        $rows = [];

        foreach ($docTypes as $docType => $docTypeData) {
            $row = [
                $docType,
                (string) ($docTypeData['count'] ?? 0),
            ];

            foreach ($allCurrencies as $currency) {
                $currencyData = $docTypeData['totals'][$currency] ?? null;
                $row[] = $currencyData ? $this->formatCurrency($currencyData) : '-';
            }

            $rows[] = $row;
        }

        return $this->markdownHeading(I18n::t('Breakdown by document type'), 4)
            . $this->markdownTable($headers, $rows);
    }
}
