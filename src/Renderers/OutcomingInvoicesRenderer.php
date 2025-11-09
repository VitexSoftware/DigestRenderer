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
        $title = $moduleData['heading'] ?? 'Outcoming Invoices';
        $data = $moduleData['data'] ?? [];
        
        $content = '';

        // Render summary
        if (isset($data['summary'])) {
            $content .= $this->renderInvoiceSummary($data['summary']);
        }

        // Render totals by currency
        if (isset($data['totals_by_currency'])) {
            $content .= $this->renderCurrencyTotals($data['totals_by_currency']);
        }

        // Render document type breakdown
        if (isset($data['by_document_type'])) {
            $content .= $this->renderDocumentTypeBreakdown($data['by_document_type']);
        }

        return $this->theme->renderCard($title, $content);
    }

    /**
     * Render invoice summary
     *
     * @param array<string, mixed> $summary Summary data
     * @return string Summary HTML
     */
    private function renderInvoiceSummary(array $summary): string
    {
        $summaryData = [
            'Total Invoices' => $summary['total_count'] ?? 0,
            'Active Invoices' => $summary['active_count'] ?? 0,
            'Cancelled Invoices' => $summary['cancelled_count'] ?? 0,
            'Document Types' => $summary['document_types_count'] ?? 0,
            'Currencies' => is_array($summary['currencies'] ?? null) ? 
                implode(', ', $summary['currencies']) : 'N/A',
        ];

        return $this->theme->renderSummary('Invoice Summary', $summaryData);
    }

    /**
     * Render currency totals
     *
     * @param array<string, mixed> $currencyTotals Currency totals data
     * @return string Currency totals HTML
     */
    private function renderCurrencyTotals(array $currencyTotals): string
    {
        $content = '<h4>Totals by Currency</h4>';
        
        $summaryData = [];
        foreach ($currencyTotals as $currency => $currencyData) {
            $summaryData[$currency] = $this->formatCurrency($currencyData);
        }

        return $content . $this->theme->renderSummary('Currency Totals', $summaryData);
    }

    /**
     * Render document type breakdown
     *
     * @param array<string, mixed> $docTypes Document type data
     * @return string Document type breakdown HTML
     */
    private function renderDocumentTypeBreakdown(array $docTypes): string
    {
        $content = '<h4>Breakdown by Document Type</h4>';
        
        $headers = ['Document Type', 'Count'];
        $rows = [];

        // Collect all currencies
        $allCurrencies = [];
        foreach ($docTypes as $docTypeData) {
            if (isset($docTypeData['totals'])) {
                $allCurrencies = array_merge($allCurrencies, array_keys($docTypeData['totals']));
            }
        }
        $allCurrencies = array_unique($allCurrencies);

        // Add currency headers
        foreach ($allCurrencies as $currency) {
            $headers[] = "Total ($currency)";
        }

        // Build table rows
        foreach ($docTypes as $docType => $docTypeData) {
            $row = [
                htmlspecialchars($docType),
                $docTypeData['count'] ?? 0,
            ];

            foreach ($allCurrencies as $currency) {
                $currencyData = $docTypeData['totals'][$currency] ?? null;
                $row[] = $currencyData ? $this->formatCurrency($currencyData) : '-';
            }

            $rows[] = $row;
        }

        return $content . $this->theme->renderTable($headers, $rows, ['class' => 'table table-striped']);
    }
}