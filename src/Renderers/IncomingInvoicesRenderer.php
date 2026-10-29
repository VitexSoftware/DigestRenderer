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
 * Incoming invoices module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class IncomingInvoicesRenderer extends AbstractModuleRenderer
{
    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? I18n::t('Incoming invoices');
        $data = $moduleData['data'] ?? [];
        $summary = $data['summary'] ?? [];

        $md = $this->markdownSummary(I18n::t('Summary'), [
            I18n::t('Total count') => $summary['total_count'] ?? 0,
            I18n::t('Active') => $summary['active_count'] ?? 0,
            I18n::t('Cancelled') => $summary['cancelled_count'] ?? 0,
        ]);

        if (!empty($data['by_document_type'])) {
            $headers = [I18n::t('Count'), I18n::t('Type'), I18n::t('Total')];
            $rows = [];

            foreach ($data['by_document_type'] as $type => $typeData) {
                $totals = [];

                foreach ($typeData['totals'] ?? [] as $currencyData) {
                    $totals[] = $this->formatCurrency($currencyData);
                }

                $rows[] = [
                    (string) ($typeData['count'] ?? 0),
                    $type,
                    implode(', ', $totals),
                ];
            }

            $md .= $this->markdownTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
