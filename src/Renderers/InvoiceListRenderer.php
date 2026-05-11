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
 * Base renderer for modules that display a list of invoices with totals
 *
 * Used by WaitingIncome, WaitingPayments, UnmatchedPayments, and similar.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class InvoiceListRenderer extends AbstractModuleRenderer
{
    /**
     * Data key containing the list items (override in subclasses)
     */
    protected string $listKey = 'invoices';

    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? $this->getModuleName();
        $data = $moduleData['data'] ?? [];
        $summary = $data['summary'] ?? [];

        // Summary
        $summaryItems = [_('Count') => $summary['total_count'] ?? 0];

        if (isset($summary['total_amount'])) {
            $summaryItems[_('Total amount')] = $this->formatCurrency($summary['total_amount']);
        }

        $md = $this->markdownSummary(_('Summary'), $summaryItems);

        // Currency totals
        if (!empty($data['totals_by_currency'])) {
            $headers = [_('Currency'), _('Amount')];
            $rows = [];

            foreach ($data['totals_by_currency'] as $currency => $currencyData) {
                $rows[] = [$currency, $this->formatCurrency($currencyData)];
            }

            $md .= $this->markdownHeading(_('Totals by currency'), 4);
            $md .= $this->markdownTable($headers, $rows);
        }

        // Item list
        $items = $data[$this->listKey] ?? [];

        if (!empty($items)) {
            $firstItem = reset($items);
            $headers = array_map(
                static fn (string $k) => _(ucwords(str_replace('_', ' ', $k))),
                array_keys($firstItem),
            );
            $rows = [];

            foreach ($items as $item) {
                $row = [];

                foreach ($item as $value) {
                    $row[] = \is_array($value)
                        ? ($value['formatted'] ?? json_encode($value))
                        : (string) $value;
                }

                $rows[] = $row;
            }

            $md .= $this->markdownTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
