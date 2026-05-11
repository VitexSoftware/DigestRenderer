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
        $title = $moduleData['heading'] ?? _('Outcoming invoices');
        $data = $moduleData['data'] ?? [];

        $md = '';

        if (isset($data['summary'])) {
            $summary = $data['summary'];
            $md .= $this->markdownSummary(_('Invoice summary'), [
                _('Total invoices') => $summary['total_count'] ?? 0,
                _('Active invoices') => $summary['active_count'] ?? 0,
                _('Cancelled invoices') => $summary['cancelled_count'] ?? 0,
                _('Document types') => $summary['document_types_count'] ?? 0,
                _('Currencies') => is_array($summary['currencies'] ?? null)
                    ? implode(', ', $summary['currencies']) : 'N/A',
            ]);
        }

        if (isset($data['totals_by_currency'])) {
            $rows = [];

            foreach ($data['totals_by_currency'] as $currency => $currencyData) {
                $rows[] = [$currency, $this->formatCurrency($currencyData)];
            }

            $md .= $this->markdownHeading(_('Totals by currency'), 4);
            $md .= $this->markdownTable([_('Currency'), _('Amount')], $rows);
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
        $headers = [_('Document type'), _('Count')];

        $allCurrencies = [];

        foreach ($docTypes as $docTypeData) {
            if (isset($docTypeData['totals'])) {
                $allCurrencies = array_merge($allCurrencies, array_keys($docTypeData['totals']));
            }
        }

        $allCurrencies = array_unique($allCurrencies);

        foreach ($allCurrencies as $currency) {
            $headers[] = sprintf(_('Total') . ' (%s)', $currency);
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

        return $this->markdownHeading(_('Breakdown by document type'), 4)
            . $this->markdownTable($headers, $rows);
    }
}
