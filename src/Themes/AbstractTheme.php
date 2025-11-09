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
 * Abstract base theme
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
abstract class AbstractTheme implements ThemeInterface
{
    /**
     * Theme name
     */
    protected string $name;

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name ?? strtolower(basename(str_replace('\\', '/', static::class), 'Theme'));
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $templateVars): string
    {
        $html = $this->renderHtmlStart($templateVars);
        $html .= $this->renderHeader($templateVars['digest'] ?? []);
        $html .= $this->renderModules($templateVars['renderedModules'] ?? []);
        $html .= $this->renderFooter($templateVars);
        $html .= $this->renderHtmlEnd();

        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function renderError(string $title, string $message): string
    {
        return $this->renderCard($title, "<p class=\"error-message\">$message</p>", ['class' => 'error']);
    }

    /**
     * Render HTML document start
     *
     * @param array<string, mixed> $templateVars Template variables
     * @return string HTML start
     */
    protected function renderHtmlStart(array $templateVars): string
    {
        $customCss = $templateVars['customCss'] ?? '';
        $digestInfo = $templateVars['digest'] ?? [];
        $title = $digestInfo['company']['name'] ?? 'Digest Report';
        
        return "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>$title - Digest Report</title>
    <style>
        {$this->getCss()}
        $customCss
    </style>
</head>
<body>";
    }

    /**
     * Render HTML document end
     *
     * @return string HTML end
     */
    protected function renderHtmlEnd(): string
    {
        return "</body></html>";
    }

    /**
     * Render digest header
     *
     * @param array<string, mixed> $digestInfo Digest metadata
     * @return string Header HTML
     */
    protected function renderHeader(array $digestInfo): string
    {
        $companyName = $digestInfo['company']['name'] ?? 'Company';
        $period = $digestInfo['period'] ?? [];
        $startDate = $period['start'] ?? '';
        $endDate = $period['end'] ?? '';

        return "<div class=\"digest-header\">
            <h1>$companyName - Digest Report</h1>
            <p class=\"period\">Period: $startDate - $endDate</p>
        </div>";
    }

    /**
     * Render all modules
     *
     * @param array<string, string> $renderedModules Rendered module HTML
     * @return string Modules HTML
     */
    protected function renderModules(array $renderedModules): string
    {
        $html = '<div class="digest-modules">';
        
        foreach ($renderedModules as $moduleKey => $moduleHtml) {
            $html .= "<div class=\"module-wrapper\" id=\"module-$moduleKey\">$moduleHtml</div>";
        }
        
        $html .= '</div>';

        return $html;
    }

    /**
     * Render digest footer
     *
     * @param array<string, mixed> $templateVars Template variables
     * @return string Footer HTML
     */
    protected function renderFooter(array $templateVars): string
    {
        $timestamp = $templateVars['digest']['timestamp'] ?? date('c');
        
        return "<div class=\"digest-footer\">
            <p><small>Generated on " . date('Y-m-d H:i:s', strtotime($timestamp)) . "</small></p>
        </div>";
    }

    /**
     * Escape HTML
     *
     * @param string $text Text to escape
     * @return string Escaped text
     */
    protected function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}