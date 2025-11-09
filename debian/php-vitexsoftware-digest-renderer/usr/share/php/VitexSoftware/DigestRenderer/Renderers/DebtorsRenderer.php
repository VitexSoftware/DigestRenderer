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
        $title = $moduleData['heading'] ?? 'Debtors';
        $data = $moduleData['data'] ?? [];
        
        $content = '';

        // Render summary
        if (isset($data['summary'])) {
            $content .= $this->renderDebtorSummary($data['summary']);
        }

        // Render totals by currency
        if (isset($data['totals_by_currency'])) {
            $content .= $this->renderCurrencyTotals($data['totals_by_currency']);
        }

        // Render overdue ranges
        if (isset($data['overdue_ranges'])) {
            $content .= $this->renderOverdueRanges($data['overdue_ranges']);
        }

        // Render top debtors
        if (isset($data['top_debtors'])) {
            $content .= $this->renderTopDebtors($data['top_debtors']);
        }

        return $this->theme->renderCard($title, $content);
    }

    /**
     * Render debtor summary
     *
     * @param array<string, mixed> $summary Summary data
     * @return string Summary HTML
     */
    private function renderDebtorSummary(array $summary): string
    {
        $summaryData = [
            'Total Debtors' => $summary['total_debtors'] ?? 0,
            'Total Invoices' => $summary['total_invoices'] ?? 0,
            'Currencies' => is_array($summary['currencies'] ?? null) ? 
                implode(', ', $summary['currencies']) : 'N/A',
        ];

        return $this->theme->renderSummary('Debtor Summary', $summaryData);
    }

    /**
     * Render currency totals
     *
     * @param array<string, mixed> $currencyTotals Currency totals data
     * @return string Currency totals HTML
     */
    private function renderCurrencyTotals(array $currencyTotals): string
    {
        $content = '<h4>Total Outstanding by Currency</h4>';
        
        $summaryData = [];
        foreach ($currencyTotals as $currency => $currencyData) {
            $summaryData[$currency] = $this->formatCurrency($currencyData);
        }

        return $content . $this->theme->renderSummary('Outstanding Amounts', $summaryData);
    }

    /**
     * Render overdue ranges
     *
     * @param array<string, mixed> $overdueRanges Overdue ranges data
     * @return string Overdue ranges HTML
     */
    private function renderOverdueRanges(array $overdueRanges): string
    {
        $content = '<h4>Invoices by Overdue Period</h4>';
        
        $headers = ['Overdue Period', 'Number of Invoices'];
        $rows = [];

        foreach ($overdueRanges as $range => $count) {
            $rows[] = [
                $range . ' days',
                (string)$count,
            ];
        }

        return $content . $this->theme->renderTable($headers, $rows, ['class' => 'table table-striped']);
    }

    /**
     * Render top debtors
     *
     * @param array<string, mixed> $topDebtors Top debtors data
     * @return string Top debtors HTML
     */
    private function renderTopDebtors(array $topDebtors): string
    {
        $content = '<h4>Top Debtors</h4>';
        
        if (empty($topDebtors)) {
            return $content . '<p>No debtors data available</p>';
        }

        $headers = ['Company', 'Invoices Count', 'Max Overdue Days'];
        
        // Collect all currencies
        $allCurrencies = [];
        foreach ($topDebtors as $debtor) {
            if (isset($debtor['total_amount'])) {
                $allCurrencies = array_merge($allCurrencies, array_keys($debtor['total_amount']));
            }
        }
        $allCurrencies = array_unique($allCurrencies);

        // Add currency headers
        foreach ($allCurrencies as $currency) {
            $headers[] = "Amount ($currency)";
        }

        $rows = [];
        foreach ($topDebtors as $debtor) {
            $row = [
                htmlspecialchars($debtor['company'] ?? 'Unknown'),
                (string)($debtor['invoices_count'] ?? 0),
                (string)($debtor['overdue_days_max'] ?? 0),
            ];

            foreach ($allCurrencies as $currency) {
                $currencyData = $debtor['total_amount'][$currency] ?? null;
                $row[] = $currencyData ? $this->formatCurrency($currencyData) : '-';
            }

            $rows[] = $row;
        }

        return $content . $this->theme->renderTable($headers, $rows, ['class' => 'table table-striped']);
    }
}

// Create placeholder classes for other renderers to prevent errors
class IncomingInvoicesRenderer extends GenericModuleRenderer {}
class NewCustomersRenderer extends GenericModuleRenderer {}
class BestSellersRenderer extends GenericModuleRenderer {}
class WaitingPaymentsRenderer extends GenericModuleRenderer {}