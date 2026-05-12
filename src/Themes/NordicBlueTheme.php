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
 * Nordic Blue theme — clean Scandinavian aesthetic with cool blue tones,
 * generous whitespace, and understated typography.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class NordicBlueTheme extends AbstractTheme
{
    protected string $name = 'nordic-blue';

    public function getCss(): string
    {
        return '
        :root {
            --nb-navy:      #2c5282;
            --nb-blue:      #3182ce;
            --nb-mid:       #63b3ed;
            --nb-light:     #ebf8ff;
            --nb-frost:     #f0f7ff;
            --nb-ice:       #deeaf7;
            --nb-line:      #cfe0f0;
            --nb-line-dark: #a8c4df;
            --nb-text:      #2d4050;
            --nb-muted:     #6a8aaa;
            --nb-bg:        #f5f8fc;
            --nb-white:     #ffffff;
            --nb-error:     #c53030;
            --nb-error-bg:  #fff5f5;
            --nb-error-ln:  #feb2b2;
        }

        * { box-sizing: border-box; }

        body {
            font-family: "Inter", "Helvetica Neue", Arial, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            color: var(--nb-text);
            background-color: var(--nb-bg);
            margin: 0;
            padding: 28px 20px;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
        }

        /* ── Header ── */
        .digest-header {
            background: linear-gradient(135deg, #3a6fa8 0%, #5b9bd5 60%, #7eb8e8 100%);
            border-radius: 10px 10px 0 0;
            padding: 32px 36px 28px;
            margin-bottom: 0;
            color: var(--nb-white);
            position: relative;
            overflow: hidden;
        }

        .digest-header::after {
            content: "";
            position: absolute;
            right: -40px;
            top: -40px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(66,153,225,0.15);
            pointer-events: none;
        }

        .digest-header h1 {
            margin: 0 0 8px 0;
            font-size: 2rem;
            font-weight: 300;
            letter-spacing: -0.5px;
            color: var(--nb-white);
        }

        .period {
            color: rgba(255,255,255,0.65);
            font-size: 0.9rem;
            margin: 0;
            letter-spacing: 0.4px;
        }

        /* thin accent bar between header and modules */
        .digest-modules {
            background: var(--nb-mid);
            padding: 3px 0 0;
            border-radius: 0 0 10px 10px;
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        /* ── Module cards ── */
        .module-wrapper {
            background: var(--nb-white);
            border-left: 4px solid var(--nb-mid);
            transition: border-color 0.15s;
        }

        .module-wrapper:first-child  { border-radius: 0; }
        .module-wrapper:last-child   { border-radius: 0 0 9px 9px; }

        .module-wrapper:hover {
            border-left-color: var(--nb-navy);
        }

        .card { border: none; }

        .card-header {
            background: var(--nb-frost);
            border-bottom: 1px solid var(--nb-ice);
            padding: 14px 24px;
            margin: 0;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--nb-navy);
            letter-spacing: 0.2px;
            text-transform: uppercase;
            font-size: 0.82rem;
        }

        .card.error .card-header {
            background: var(--nb-error-bg);
            border-bottom-color: var(--nb-error-ln);
        }

        .card.error .card-header h2 { color: var(--nb-error); }

        .card-body { padding: 22px 24px; }

        /* ── Tables ── */
        table, .table {
            width: 100%;
            margin-bottom: 1rem;
            color: var(--nb-text);
            border-collapse: collapse;
            font-size: 0.93rem;
        }

        table th, table td,
        .table th, .table td {
            padding: 9px 14px;
            vertical-align: top;
            border-bottom: 1px solid var(--nb-line);
            text-align: left;
        }

        table thead th,
        .table thead th {
            background: var(--nb-ice);
            color: var(--nb-navy);
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--nb-mid);
        }

        table tbody tr:nth-of-type(even),
        .table-striped tbody tr:nth-of-type(even) {
            background-color: var(--nb-frost);
        }

        table tbody tr:hover,
        .table tbody tr:hover {
            background-color: var(--nb-light);
        }

        /* ── Summary grid ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .summary-item {
            background: var(--nb-frost);
            border: 1px solid var(--nb-ice);
            border-top: 3px solid var(--nb-mid);
            border-radius: 6px;
            padding: 14px 16px;
        }

        .summary-item .label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--nb-muted);
            margin-bottom: 6px;
        }

        .summary-item .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--nb-blue);
            line-height: 1.2;
        }

        /* ── Error ── */
        .module-wrapper:has(.card.error) {
            border-left-color: var(--nb-error);
        }

        .error-message { color: var(--nb-error); margin: 0; }

        /* ── Footer ── */
        .digest-footer {
            margin-top: 32px;
            padding: 16px 0 0;
            border-top: 1px solid var(--nb-line);
            text-align: center;
            color: var(--nb-muted);
            font-size: 0.85rem;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .currency {
            font-weight: 700;
            color: var(--nb-navy);
            font-variant-numeric: tabular-nums;
        }

        @media (max-width: 768px) {
            body { padding: 12px 8px; }
            .digest-header { padding: 22px 20px; }
            .digest-header h1 { font-size: 1.5rem; }
            .card-header, .card-body { padding: 14px 16px; }
            table { font-size: 0.85rem; }
            table th, table td { padding: 7px 10px; }
            .summary-grid { grid-template-columns: 1fr 1fr; }
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
        $id    = isset($options['id']) ? ' id="' . $this->escapeHtml($options['id']) . '"' : '';

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
            <h3 style=\"font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--nb-muted);margin:0 0 12px;\">"
            . $this->escapeHtml($title) . "</h3>
            <div class=\"summary-grid\">";

        foreach ($data as $key => $value) {
            $displayKey = ucwords(str_replace('_', ' ', $key));
            $displayVal = is_array($value) ? json_encode($value) : (string) $value;

            $html .= "<div class=\"summary-item\">
                <div class=\"label\">$displayKey</div>
                <div class=\"value\">" . $this->escapeHtml($displayVal) . "</div>
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
