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
 * Green-accented theme for digest rendering
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class GreenTheme extends AbstractTheme
{
    protected string $name = 'green';

    public function getCss(): string
    {
        return '
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.5;
            color: #1a2e1a;
            background-color: #f2f7f2;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .digest-header {
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 0.125rem 0.25rem rgba(0,60,0,0.08);
            border-left: 6px solid #2e7d32;
        }

        .digest-header h1 {
            margin: 0 0 10px 0;
            color: #2e7d32;
            font-size: 2.2rem;
            font-weight: 400;
        }

        .period {
            color: #558b5a;
            font-size: 1rem;
            margin: 0;
        }

        .digest-modules {
            display: grid;
            grid-gap: 24px;
        }

        .module-wrapper {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0.125rem 0.25rem rgba(0,60,0,0.08);
            border-top: 3px solid #81c784;
            overflow: hidden;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        .card.error {
            border-top-color: #c62828;
        }

        .card-header {
            background: #f1f8f1;
            border-bottom: 1px solid #c8e6c9;
            padding: 16px 24px;
            margin: 0;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.3rem;
            color: #2e7d32;
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        table, .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #1a2e1a;
            border-collapse: collapse;
        }

        table th, table td,
        .table th, .table td {
            padding: 10px 14px;
            vertical-align: top;
            border-bottom: 1px solid #dcedc8;
            text-align: left;
        }

        table thead th,
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #aed581;
            background-color: #f1f8e9;
            color: #33691e;
            font-weight: 600;
        }

        table tbody tr:nth-of-type(odd),
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9fdf5;
        }

        table tbody tr:hover,
        .table tbody tr:hover {
            background-color: #f1f8e9;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-item {
            background: #f1f8f1;
            padding: 16px;
            border-radius: 6px;
            border-left: 4px solid #2e7d32;
        }

        .summary-item .label {
            font-size: 0.85rem;
            color: #558b5a;
            margin-bottom: 4px;
        }

        .summary-item .value {
            font-size: 1.4rem;
            font-weight: 600;
            color: #1a2e1a;
        }

        .error {
            border-top-color: #c62828 !important;
        }

        .error .card-header {
            background: #ffebee;
            border-bottom-color: #ef9a9a;
        }

        .error .card-header h2 {
            color: #c62828;
        }

        .error-message {
            color: #b71c1c;
            margin: 0;
        }

        .digest-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #c8e6c9;
            text-align: center;
            color: #558b5a;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .currency { font-weight: 600; color: #2e7d32; }

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
            $displayKey = ucwords(str_replace('_', ' ', $key));
            $displayValue = is_array($value) ? json_encode($value) : (string) $value;

            $html .= "<div class=\"summary-item\">
                <div class=\"label\">$displayKey</div>
                <div class=\"value\">" . $this->escapeHtml($displayValue) . "</div>
            </div>";
        }

        $html .= "</div></div>";

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
