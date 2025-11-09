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
 * Email-friendly theme for digest rendering
 *
 * Uses table-based layouts and inline styles for maximum email client compatibility
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class EmailTheme extends AbstractTheme
{
    /**
     * {@inheritDoc}
     */
    protected string $name = 'email';

    /**
     * {@inheritDoc}
     */
    public function getCss(): string
    {
        return '
        /* Email-compatible styles with fallbacks */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        .digest-header {
            background-color: #0066cc;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        
        .digest-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: normal;
        }
        
        .period {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .module-section {
            border-bottom: 1px solid #eeeeee;
            padding: 20px;
        }
        
        .module-section:last-child {
            border-bottom: none;
        }
        
        .module-title {
            color: #0066cc;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 15px 0;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background-color: #f9f9f9;
        }
        
        .summary-table td {
            padding: 10px;
            border: 1px solid #eeeeee;
            font-size: 14px;
        }
        
        .summary-label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 40%;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .data-table th {
            background-color: #0066cc;
            color: #ffffff;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
        }
        
        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eeeeee;
            font-size: 13px;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .error-section {
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .error-title {
            color: #c62828;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .error-message {
            color: #d32f2f;
            margin: 0;
        }
        
        .digest-footer {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }
        
        .currency {
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        ';
    }

    /**
     * {@inheritDoc}
     */
    protected function renderHtmlStart(array $templateVars): string
    {
        $digestInfo = $templateVars['digest'] ?? [];
        $title = $digestInfo['company']['name'] ?? 'Digest Report';
        
        $customCss = $templateVars['customCss'] ?? '';
        
        return "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>$title - Digest Report</title>
    <style type=\"text/css\">
        {$this->getCss()}
        $customCss
    </style>
</head>
<body>
<div class=\"email-container\">";
    }

    /**
     * {@inheritDoc}
     */
    protected function renderHtmlEnd(): string
    {
        return "</div></body></html>";
    }

    /**
     * {@inheritDoc}
     */
    protected function renderModules(array $renderedModules): string
    {
        $html = '';
        
        foreach ($renderedModules as $moduleKey => $moduleHtml) {
            $html .= "<div class=\"module-section\">$moduleHtml</div>";
        }

        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function renderTable(array $headers, array $rows, array $options = []): string
    {
        $class = $options['class'] ?? 'data-table';
        
        $html = "<table class=\"$class\" cellspacing=\"0\" cellpadding=\"0\">";
        
        if (!empty($headers)) {
            $html .= '<tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . $this->escapeHtml($header) . '</th>';
            }
            $html .= '</tr>';
        }
        
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . $this->escapeHtml((string)$cell) . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function renderCard(string $title, string $content, array $options = []): string
    {
        $isError = isset($options['class']) && strpos($options['class'], 'error') !== false;
        
        if ($isError) {
            return "<div class=\"error-section\">
                <div class=\"error-title\">" . $this->escapeHtml($title) . "</div>
                $content
            </div>";
        }
        
        return "<div class=\"module-section\">
            <h2 class=\"module-title\">" . $this->escapeHtml($title) . "</h2>
            $content
        </div>";
    }

    /**
     * {@inheritDoc}
     */
    public function renderSummary(string $title, array $data): string
    {
        $html = "<h3 class=\"module-title\">" . $this->escapeHtml($title) . "</h3>";
        $html .= "<table class=\"summary-table\" cellspacing=\"0\" cellpadding=\"0\">";
        
        foreach ($data as $key => $value) {
            $displayKey = ucwords(str_replace('_', ' ', $key));
            $displayValue = is_array($value) ? json_encode($value) : (string)$value;
            
            $html .= "<tr>
                <td class=\"summary-label\">$displayKey</td>
                <td>" . $this->escapeHtml($displayValue) . "</td>
            </tr>";
        }
        
        $html .= "</table>";
        
        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function renderError(string $title, string $message): string
    {
        return "<div class=\"error-section\">
            <div class=\"error-title\">" . $this->escapeHtml($title) . "</div>
            <div class=\"error-message\">" . $this->escapeHtml($message) . "</div>
        </div>";
    }
}