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
 * Bootstrap-based theme for digest rendering
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class BootstrapTheme extends AbstractTheme
{
    /**
     * {@inheritDoc}
     */
    protected string $name = 'bootstrap';

    /**
     * {@inheritDoc}
     */
    public function getCss(): string
    {
        return '
        /* Bootstrap-like styles for digest */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.5;
            color: #212529;
            background-color: #f8f9fa;
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
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            border: 1px solid #e5e5e5;
        }
        
        .digest-header h1 {
            margin: 0 0 15px 0;
            color: #0d6efd;
            font-size: 2.5rem;
            font-weight: 300;
        }
        
        .period {
            color: #6c757d;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .digest-modules {
            display: grid;
            grid-gap: 30px;
        }
        
        .module-wrapper {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            border: 1px solid #e5e5e5;
            overflow: hidden;
        }
        
        .card {
            border: none;
            border-radius: 8px;
        }
        
        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e5e5e5;
            padding: 20px 30px;
            margin: 0;
        }
        
        .card-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #495057;
            font-weight: 500;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 12px;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: left;
        }
        
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,0.05);
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #0d6efd;
        }
        
        .summary-item .label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .summary-item .value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #212529;
        }
        
        .error {
            border-left-color: #dc3545 !important;
            background-color: #f8d7da !important;
        }
        
        .error-message {
            color: #721c24;
            margin: 0;
        }
        
        .digest-footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
            color: #6c757d;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .currency {
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .digest-header {
                padding: 20px;
            }
            
            .digest-header h1 {
                font-size: 2rem;
            }
            
            .card-header,
            .card-body {
                padding: 20px;
            }
            
            .table {
                font-size: 0.9rem;
            }
            
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
        ';
    }

    /**
     * {@inheritDoc}
     */
    public function renderTable(array $headers, array $rows, array $options = []): string
    {
        $class = $options['class'] ?? 'table table-striped';
        
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
                $html .= '<td>' . $this->escapeHtml((string)$cell) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        
        return $html;
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function renderSummary(string $title, array $data): string
    {
        $html = "<div class=\"summary-section\">
            <h3>" . $this->escapeHtml($title) . "</h3>
            <div class=\"summary-grid\">";
        
        foreach ($data as $key => $value) {
            $displayKey = ucwords(str_replace('_', ' ', $key));
            $displayValue = is_array($value) ? json_encode($value) : (string)$value;
            
            $html .= "<div class=\"summary-item\">
                <div class=\"label\">$displayKey</div>
                <div class=\"value\">" . $this->escapeHtml($displayValue) . "</div>
            </div>";
        }
        
        $html .= "</div></div>";
        
        return $html;
    }

    /**
     * {@inheritDoc}
     */
    protected function renderHtmlStart(array $templateVars): string
    {
        return '<div class="container">' . parent::renderHtmlStart($templateVars);
    }

    /**
     * {@inheritDoc}
     */
    protected function renderHtmlEnd(): string
    {
        return '</div>' . parent::renderHtmlEnd();
    }
}