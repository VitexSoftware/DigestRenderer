<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Income chart renderer for daily/weekly income visualization
 *
 * Renders a CSS-based vertical bar chart showing income per day
 * with percentage relative to average.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class IncomeChartRenderer extends AbstractModuleRenderer
{
    /** @var array<string, string> Currency → CSS color mapping */
    private const CURRENCY_COLORS = [
        'CZK' => '#97f464',
        'EUR' => '#ab64f4',
        'USD' => '#4ecdc4',
    ];

    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? _('Incoming payments chart');
        $data = $moduleData['data'] ?? [];
        $days = $data['days'] ?? [];
        $averages = $data['averages'] ?? [];

        if (empty($days)) {
            return $this->markdownSection($title, _('No data available') . "\n");
        }

        $md = '';

        // Averages info
        foreach ($averages as $currency => $avgData) {
            $md .= sprintf(
                "100%% — %s: %s %s  \n",
                _('average income is'),
                number_format((float) ($avgData['average'] ?? 0), 0, ',', ' '),
                $currency,
            );
        }

        $md .= "\n";

        // ASCII bar chart as Markdown table
        $headers = [_('Date'), _('Currency'), _('Amount'), _('% avg'), _('Bar')];
        $rows = [];

        foreach (array_reverse($days) as $dayData) {
            $date = $dayData['date'] ?? '';

            foreach ($dayData['currencies'] ?? [] as $currency => $currencyData) {
                $percent = (int) ($currencyData['percent_of_average'] ?? 0);
                $amount = (float) ($currencyData['amount'] ?? 0);
                $barLength = max((int) ($percent / 5), 1);
                $bar = str_repeat('█', $barLength);

                $rows[] = [
                    $date,
                    $currency,
                    number_format($amount, 0, ',', ' '),
                    $percent . '%',
                    $bar,
                ];
            }
        }

        $md .= $this->markdownTable($headers, $rows);

        return $this->markdownSection($title, $md);
    }
}
