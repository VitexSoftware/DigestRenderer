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
 * Rainbow theme — each module card cycles through the spectrum.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class RainbowTheme extends AbstractTheme
{
    protected string $name = 'rainbow';

    /** @var string[] Hues cycling through the rainbow (HSL) */
    private const HUES = ['0', '30', '60', '120', '180', '210', '270', '300'];

    private int $cardIndex = 0;

    public function getCss(): string
    {
        return '
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.5;
            color: #1a1a2e;
            background: linear-gradient(135deg, #fff0f0 0%, #fffff0 25%, #f0fff0 50%, #f0f8ff 75%, #f8f0ff 100%);
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .digest-header {
            background: linear-gradient(135deg,
                hsl(0,   80%, 95%) 0%,
                hsl(60,  80%, 95%) 25%,
                hsl(120, 80%, 95%) 50%,
                hsl(210, 80%, 95%) 75%,
                hsl(270, 80%, 95%) 100%);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-top: 6px solid transparent;
            border-image: linear-gradient(90deg,
                hsl(0,85%,55%),
                hsl(45,85%,55%),
                hsl(90,85%,45%),
                hsl(180,85%,45%),
                hsl(240,85%,60%),
                hsl(300,85%,55%),
                hsl(360,85%,55%)) 1;
        }

        .digest-header h1 {
            margin: 0 0 10px 0;
            font-size: 2.2rem;
            font-weight: 400;
            background: linear-gradient(90deg,
                hsl(0,80%,45%),
                hsl(45,80%,40%),
                hsl(120,70%,35%),
                hsl(210,80%,45%),
                hsl(270,70%,50%));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .period {
            color: #555;
            font-size: 1rem;
            margin: 0;
        }

        .digest-modules {
            display: grid;
            grid-gap: 24px;
        }

        /* Each .module-wrapper gets a hue via nth-child */
        .module-wrapper {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .module-wrapper:nth-child(8n+1) { border-top: 4px solid hsl(0,   75%, 55%); }
        .module-wrapper:nth-child(8n+2) { border-top: 4px solid hsl(30,  80%, 52%); }
        .module-wrapper:nth-child(8n+3) { border-top: 4px solid hsl(60,  70%, 42%); }
        .module-wrapper:nth-child(8n+4) { border-top: 4px solid hsl(120, 65%, 40%); }
        .module-wrapper:nth-child(8n+5) { border-top: 4px solid hsl(180, 70%, 38%); }
        .module-wrapper:nth-child(8n+6) { border-top: 4px solid hsl(210, 75%, 50%); }
        .module-wrapper:nth-child(8n+7) { border-top: 4px solid hsl(270, 65%, 55%); }
        .module-wrapper:nth-child(8n+8) { border-top: 4px solid hsl(300, 70%, 50%); }

        /* Card header tints match the border hue */
        .module-wrapper:nth-child(8n+1) .card-header { background: hsl(0,   75%, 97%); border-bottom: 1px solid hsl(0,   75%, 88%); }
        .module-wrapper:nth-child(8n+2) .card-header { background: hsl(30,  80%, 97%); border-bottom: 1px solid hsl(30,  80%, 88%); }
        .module-wrapper:nth-child(8n+3) .card-header { background: hsl(60,  70%, 97%); border-bottom: 1px solid hsl(60,  70%, 88%); }
        .module-wrapper:nth-child(8n+4) .card-header { background: hsl(120, 65%, 97%); border-bottom: 1px solid hsl(120, 65%, 88%); }
        .module-wrapper:nth-child(8n+5) .card-header { background: hsl(180, 70%, 97%); border-bottom: 1px solid hsl(180, 70%, 88%); }
        .module-wrapper:nth-child(8n+6) .card-header { background: hsl(210, 75%, 97%); border-bottom: 1px solid hsl(210, 75%, 88%); }
        .module-wrapper:nth-child(8n+7) .card-header { background: hsl(270, 65%, 97%); border-bottom: 1px solid hsl(270, 65%, 88%); }
        .module-wrapper:nth-child(8n+8) .card-header { background: hsl(300, 70%, 97%); border-bottom: 1px solid hsl(300, 70%, 88%); }

        .card { border: none; border-radius: 10px; }

        .card-header {
            padding: 16px 24px;
            margin: 0;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #2a2a3e;
        }

        .card-body { padding: 24px; }

        table, .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #1a1a2e;
            border-collapse: collapse;
        }

        table th, table td,
        .table th, .table td {
            padding: 10px 14px;
            vertical-align: top;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        table thead th,
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
            background: linear-gradient(90deg, hsl(240,30%,96%), hsl(300,30%,96%));
            font-weight: 600;
            color: #2a2a3e;
        }

        table tbody tr:nth-of-type(odd),
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fafafa;
        }

        table tbody tr:hover,
        .table tbody tr:hover {
            background: linear-gradient(90deg, hsl(0,60%,97%), hsl(60,60%,97%), hsl(120,60%,97%));
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .summary-item {
            padding: 14px 16px;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .summary-item:nth-child(8n+1) { background: hsl(0,   80%, 97%); border-color: hsl(0,   75%, 55%); }
        .summary-item:nth-child(8n+2) { background: hsl(30,  80%, 97%); border-color: hsl(30,  80%, 52%); }
        .summary-item:nth-child(8n+3) { background: hsl(60,  70%, 97%); border-color: hsl(60,  70%, 42%); }
        .summary-item:nth-child(8n+4) { background: hsl(120, 65%, 97%); border-color: hsl(120, 65%, 40%); }
        .summary-item:nth-child(8n+5) { background: hsl(180, 70%, 97%); border-color: hsl(180, 70%, 38%); }
        .summary-item:nth-child(8n+6) { background: hsl(210, 75%, 97%); border-color: hsl(210, 75%, 50%); }
        .summary-item:nth-child(8n+7) { background: hsl(270, 65%, 97%); border-color: hsl(270, 65%, 55%); }
        .summary-item:nth-child(8n+8) { background: hsl(300, 70%, 97%); border-color: hsl(300, 70%, 50%); }

        .summary-item .label {
            font-size: 0.82rem;
            color: #666;
            margin-bottom: 4px;
        }

        .summary-item .value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .error {
            border-top: 4px solid #e53935 !important;
        }

        .error .card-header {
            background: #fff5f5;
            border-bottom: 1px solid #ffcdd2;
        }

        .error .card-header h2 { color: #c62828; }
        .error-message { color: #b71c1c; margin: 0; }

        .digest-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 3px solid transparent;
            border-image: linear-gradient(90deg,
                hsl(0,85%,65%),
                hsl(60,85%,55%),
                hsl(120,75%,45%),
                hsl(210,85%,60%),
                hsl(270,75%,60%),
                hsl(360,85%,65%)) 1;
            text-align: center;
            color: #666;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .currency    { font-weight: 700; }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .digest-header { padding: 20px; }
            .digest-header h1 { font-size: 1.7rem; }
            .card-header, .card-body { padding: 16px; }
            table { font-size: 0.88rem; }
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
        $hue   = self::HUES[$this->cardIndex % \count(self::HUES)];
        ++$this->cardIndex;

        $headerStyle = "background:hsl({$hue},75%,97%);border-bottom:1px solid hsl({$hue},75%,88%);";

        return "<div class=\"$class\"$id>
            <div class=\"card-header\" style=\"$headerStyle\">
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

        $i = 0;
        foreach ($data as $key => $value) {
            $hue         = self::HUES[$i % \count(self::HUES)];
            $displayKey  = ucwords(str_replace('_', ' ', $key));
            $displayVal  = is_array($value) ? json_encode($value) : (string) $value;
            $style       = "background:hsl({$hue},80%,97%);border-color:hsl({$hue},75%,52%);";

            $html .= "<div class=\"summary-item\" style=\"$style\">
                <div class=\"label\">$displayKey</div>
                <div class=\"value\">" . $this->escapeHtml($displayVal) . "</div>
            </div>";
            ++$i;
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
