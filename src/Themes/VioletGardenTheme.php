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
 * VioletGarden theme — deep violets and lavender with rose and sage accents,
 * evoking an elegant botanical garden atmosphere.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class VioletGardenTheme extends AbstractTheme
{
    protected string $name = 'violet-garden';

    public function getCss(): string
    {
        return '
        :root {
            --vg-deep:      #3d1a6e;
            --vg-violet:    #6a1b9a;
            --vg-mid:       #9c4dcc;
            --vg-lavender:  #ce93d8;
            --vg-blush:     #f8bbd0;
            --vg-rose:      #e91e8c;
            --vg-sage:      #6a8f6a;
            --vg-sage-lt:   #c8dfc8;
            --vg-petal:     #fce4ec;
            --vg-mist:      #f3e5f5;
            --vg-frost:     #faf4ff;
            --vg-line:      #e1bee7;
            --vg-line-dark: #ce93d8;
            --vg-text:      #1e0a30;
            --vg-muted:     #7b5a8a;
            --vg-bg:        #f7f0fb;
            --vg-white:     #ffffff;
            --vg-error:     #b71c1c;
            --vg-error-bg:  #fce4ec;
            --vg-error-ln:  #f48fb1;
        }

        * { box-sizing: border-box; }

        body {
            font-family: "Georgia", "Times New Roman", serif;
            font-size: 15px;
            line-height: 1.65;
            color: var(--vg-text);
            background-color: var(--vg-bg);
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(156,77,204,0.07) 0%, transparent 55%),
                radial-gradient(ellipse at 90% 80%, rgba(233,30,140,0.05) 0%, transparent 55%),
                radial-gradient(ellipse at 50% 50%, rgba(106,143,106,0.04) 0%, transparent 70%);
            background-attachment: fixed;
            margin: 0;
            padding: 28px 20px;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
        }

        /* ── Header ── */
        .digest-header {
            background: linear-gradient(135deg, var(--vg-deep) 0%, var(--vg-violet) 60%, #8e24aa 100%);
            border-radius: 14px;
            padding: 36px 40px 30px;
            margin-bottom: 28px;
            color: var(--vg-white);
            box-shadow: 0 8px 32px rgba(61,26,110,0.25);
            position: relative;
            overflow: hidden;
        }

        /* petal overlays */
        .digest-header::before {
            content: "";
            position: absolute;
            top: -60px; right: -60px;
            width: 260px; height: 260px;
            border-radius: 50% 30% 60% 40%;
            background: rgba(206,147,216,0.18);
            pointer-events: none;
        }

        .digest-header::after {
            content: "";
            position: absolute;
            bottom: -50px; left: -40px;
            width: 200px; height: 200px;
            border-radius: 40% 60% 30% 70%;
            background: rgba(248,187,208,0.12);
            pointer-events: none;
        }

        .digest-header h1 {
            margin: 0 0 10px 0;
            font-size: 2.1rem;
            font-weight: normal;
            font-style: italic;
            letter-spacing: 0.3px;
            color: var(--vg-white);
            text-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .period {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin: 0;
            font-style: italic;
        }

        /* ── Module grid ── */
        .digest-modules {
            display: grid;
            grid-gap: 20px;
        }

        /* ── Cards ── */
        .module-wrapper {
            background: var(--vg-white);
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(61,26,110,0.08);
            overflow: hidden;
            border: 1px solid var(--vg-line);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .module-wrapper:hover {
            box-shadow: 0 6px 28px rgba(61,26,110,0.14);
            transform: translateY(-1px);
        }

        /* Cycle card accent colours through violet→rose→sage→lavender */
        .module-wrapper:nth-child(4n+1) .card-header { border-top: 4px solid var(--vg-violet); background: linear-gradient(90deg, var(--vg-mist), var(--vg-frost)); }
        .module-wrapper:nth-child(4n+2) .card-header { border-top: 4px solid #d81b60;          background: linear-gradient(90deg, var(--vg-petal), var(--vg-frost)); }
        .module-wrapper:nth-child(4n+3) .card-header { border-top: 4px solid var(--vg-sage);   background: linear-gradient(90deg, #eef4ee, var(--vg-frost)); }
        .module-wrapper:nth-child(4n+4) .card-header { border-top: 4px solid var(--vg-mid);    background: linear-gradient(90deg, #f0e6f8, var(--vg-frost)); }

        .card { border: none; border-radius: 12px; }

        .card.error .card-header {
            border-top: 4px solid var(--vg-error) !important;
            background: var(--vg-error-bg) !important;
        }

        .card.error .card-header h2 { color: var(--vg-error); }

        .card-header {
            padding: 16px 26px 14px;
            margin: 0;
            border-bottom: 1px solid var(--vg-line);
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            font-style: normal;
            color: var(--vg-deep);
            letter-spacing: 0.1px;
        }

        .card-body {
            padding: 24px 26px;
        }

        /* ── Tables ── */
        table, .table {
            width: 100%;
            margin-bottom: 1rem;
            color: var(--vg-text);
            border-collapse: collapse;
            font-size: 0.93rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        table th, table td,
        .table th, .table td {
            padding: 10px 15px;
            vertical-align: top;
            border-bottom: 1px solid var(--vg-line);
            text-align: left;
        }

        table thead th,
        .table thead th {
            background: linear-gradient(90deg, var(--vg-deep), var(--vg-violet));
            color: rgba(255,255,255,0.92);
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            border-bottom: 2px solid var(--vg-mid);
            font-family: inherit;
        }

        table tbody tr:nth-of-type(odd),
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--vg-frost);
        }

        table tbody tr:hover,
        .table tbody tr:hover {
            background-color: var(--vg-mist);
        }

        /* ── Summary ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .summary-item {
            padding: 16px 18px;
            border-radius: 10px;
            border: 1px solid var(--vg-line);
            position: relative;
            overflow: hidden;
        }

        .summary-item::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }

        .summary-item:nth-child(4n+1) { background: var(--vg-mist); }
        .summary-item:nth-child(4n+1)::before { background: var(--vg-violet); }

        .summary-item:nth-child(4n+2) { background: var(--vg-petal); }
        .summary-item:nth-child(4n+2)::before { background: #d81b60; }

        .summary-item:nth-child(4n+3) { background: #eef4ee; }
        .summary-item:nth-child(4n+3)::before { background: var(--vg-sage); }

        .summary-item:nth-child(4n+4) { background: #f0e6f8; }
        .summary-item:nth-child(4n+4)::before { background: var(--vg-mid); }

        .summary-item .label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--vg-muted);
            margin-bottom: 6px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .summary-item .value {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--vg-deep);
            line-height: 1.2;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        /* ── Error ── */
        .error-message { color: var(--vg-error); margin: 0; }

        /* ── Footer ── */
        .digest-footer {
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid var(--vg-line);
            text-align: center;
            color: var(--vg-muted);
            font-size: 0.85rem;
            font-style: italic;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .currency {
            font-weight: 700;
            color: var(--vg-deep);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-variant-numeric: tabular-nums;
        }

        @media (max-width: 768px) {
            body { padding: 12px 8px; }
            .digest-header { padding: 22px 20px; }
            .digest-header h1 { font-size: 1.6rem; }
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
            <h3 style=\"font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--vg-muted);margin:0 0 14px;\">"
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
