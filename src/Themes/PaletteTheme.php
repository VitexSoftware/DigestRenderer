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

namespace VitexSoftware\DigestRenderer\Themes;

/**
 * Shared card/table theme driven by a color palette.
 *
 * Subclasses only declare a name and palette(); layout CSS stays in one place.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
abstract class PaletteTheme extends AbstractTheme
{
    /**
     * Color tokens for this theme.
     *
     * @return array{
     *     accent: string,
     *     accentSoft: string,
     *     accentBorder: string,
     *     headerBg: string,
     *     headerBorder: string,
     *     bg: string,
     *     surface: string,
     *     text: string,
     *     muted: string,
     *     tableLine: string,
     *     tableHeaderBg: string,
     *     tableHeaderText: string,
     *     tableHeaderBorder: string,
     *     tableOdd: string,
     *     tableHover: string,
     *     summaryBg: string,
     *     shadow: string,
     *     error: string,
     *     errorBg: string,
     *     errorBorder: string,
     *     errorText: string
     * }
     */
    abstract protected function palette(): array;

    public function getCss(): string
    {
        $p = $this->palette();

        return '
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.5;
            color: ' . $p['text'] . ';
            background-color: ' . $p['bg'] . ';
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .digest-header {
            background: ' . $p['surface'] . ';
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 0.125rem 0.25rem ' . $p['shadow'] . ';
            border-left: 6px solid ' . $p['accent'] . ';
        }

        .digest-header h1 {
            margin: 0 0 10px 0;
            color: ' . $p['accent'] . ';
            font-size: 2.2rem;
            font-weight: 400;
        }

        .period {
            color: ' . $p['muted'] . ';
            font-size: 1rem;
            margin: 0;
        }

        .digest-modules {
            display: grid;
            grid-gap: 24px;
        }

        .module-wrapper {
            background: ' . $p['surface'] . ';
            border-radius: 8px;
            box-shadow: 0 0.125rem 0.25rem ' . $p['shadow'] . ';
            border-top: 3px solid ' . $p['accentSoft'] . ';
            overflow: hidden;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        .card.error {
            border-top-color: ' . $p['error'] . ';
        }

        .card-header {
            background: ' . $p['headerBg'] . ';
            border-bottom: 1px solid ' . $p['headerBorder'] . ';
            padding: 16px 24px;
            margin: 0;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.3rem;
            color: ' . $p['accent'] . ';
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        table, .table {
            width: 100%;
            margin-bottom: 1rem;
            color: ' . $p['text'] . ';
            border-collapse: collapse;
        }

        table th, table td,
        .table th, .table td {
            padding: 10px 14px;
            vertical-align: top;
            border-bottom: 1px solid ' . $p['tableLine'] . ';
            text-align: left;
        }

        table thead th,
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid ' . $p['tableHeaderBorder'] . ';
            background-color: ' . $p['tableHeaderBg'] . ';
            color: ' . $p['tableHeaderText'] . ';
            font-weight: 600;
        }

        table tbody tr:nth-of-type(odd),
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: ' . $p['tableOdd'] . ';
        }

        table tbody tr:hover,
        .table tbody tr:hover {
            background-color: ' . $p['tableHover'] . ';
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-item {
            background: ' . $p['summaryBg'] . ';
            padding: 16px;
            border-radius: 6px;
            border-left: 4px solid ' . $p['accent'] . ';
        }

        .summary-item .label {
            font-size: 0.85rem;
            color: ' . $p['muted'] . ';
            margin-bottom: 4px;
        }

        .summary-item .value {
            font-size: 1.4rem;
            font-weight: 600;
            color: ' . $p['text'] . ';
        }

        .error {
            border-top-color: ' . $p['error'] . ' !important;
        }

        .error .card-header {
            background: ' . $p['errorBg'] . ';
            border-bottom-color: ' . $p['errorBorder'] . ';
        }

        .error .card-header h2 {
            color: ' . $p['error'] . ';
        }

        .error-message {
            color: ' . $p['errorText'] . ';
            margin: 0;
        }

        .digest-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid ' . $p['accentBorder'] . ';
            text-align: center;
            color: ' . $p['muted'] . ';
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .currency { font-weight: 600; color: ' . $p['accent'] . '; }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .digest-header { padding: 20px; }
            .digest-header h1 { font-size: 1.7rem; }
            .card-header, .card-body { padding: 16px; }
            table { font-size: 0.9rem; }
            .summary-grid { grid-template-columns: 1fr; }
        }
        ';
    }

    public function renderTable(array $headers, array $rows, array $options = []): string
    {
        $class = $options['class'] ?? 'table';

        $html = "<table class=\"$class\">";

        if (!empty($headers)) {
            $html .= '<thead><tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . $this->escapeHtml($header) . '</th>';
            }
            $html .= '</tr></thead>';
        }

        $html .= '<tbody>';
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . $this->escapeHtml((string) $cell) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }

    public function renderCard(string $title, string $content, array $options = []): string
    {
        $class = $options['class'] ?? 'card';
        $id = isset($options['id']) ? ' id="' . $this->escapeHtml($options['id']) . '"' : '';

        return "<div class=\"$class\"$id>
            <div class=\"card-header\">
                <h2>" . $this->escapeHtml($title) . "</h2>
            </div>
            <div class=\"card-body\">
                $content
            </div>
        </div>";
    }

    public function renderSummary(string $title, array $data): string
    {
        $html = "<div class=\"summary-section\">
            <h3>" . $this->escapeHtml($title) . "</h3>
            <div class=\"summary-grid\">";

        foreach ($data as $key => $value) {
            $displayKey = ucwords(str_replace('_', ' ', (string) $key));
            $displayValue = is_array($value) ? json_encode($value) : (string) $value;

            $html .= "<div class=\"summary-item\">
                <div class=\"label\">$displayKey</div>
                <div class=\"value\">" . $this->escapeHtml($displayValue) . "</div>
            </div>";
        }

        $html .= '</div></div>';

        return $html;
    }

    protected function renderHtmlStart(array $templateVars): string
    {
        return '<div class="container">' . parent::renderHtmlStart($templateVars);
    }

    protected function renderHtmlEnd(): string
    {
        return '</div>' . parent::renderHtmlEnd();
    }
}
