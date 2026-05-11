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
 * Base renderer for modules that display currency totals
 *
 * Used by IncomingPayments, OutcomingPayments, and similar modules
 * that show totals grouped by currency.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class CurrencyTotalsRenderer extends AbstractModuleRenderer
{
    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? $this->getModuleName();
        $data = $moduleData['data'] ?? [];
        $summary = $data['summary'] ?? [];

        $md = $this->markdownSummary(_('Summary'), [
            _('Total count') => $summary['total_count'] ?? 0,
        ]);

        if (!empty($data['totals_by_currency'])) {
            $headers = [_('Currency'), _('Amount')];
            $rows = [];

            foreach ($data['totals_by_currency'] as $currency => $currencyData) {
                $rows[] = [$currency, $this->formatCurrency($currencyData)];
            }

            $md .= $this->markdownTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
