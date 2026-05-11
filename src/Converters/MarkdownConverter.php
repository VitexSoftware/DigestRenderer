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

namespace VitexSoftware\DigestRenderer\Converters;

use League\CommonMark\CommonMarkConverter;
use VitexSoftware\DigestRenderer\Themes\ThemeInterface;

/**
 * Converts Markdown content to a styled HTML document
 *
 * Uses league/commonmark for Markdown → HTML conversion and wraps
 * the result in a themed HTML document with CSS styles.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class MarkdownConverter
{
    /**
     * Theme providing CSS and page structure
     */
    private ThemeInterface $theme;

    /**
     * Optional custom CSS to inject
     */
    private string $customCss;

    /**
     * Constructor
     *
     * @param ThemeInterface $theme     Theme for HTML wrapping
     * @param string         $customCss Additional CSS styles
     */
    public function __construct(ThemeInterface $theme, string $customCss = '')
    {
        $this->theme = $theme;
        $this->customCss = $customCss;
    }

    /**
     * Convert Markdown string to a full HTML document
     *
     * @param string               $markdown Markdown content
     * @param array<string, mixed> $meta     Digest metadata (company, period, timestamp)
     * @return string HTML document
     */
    public function convert(string $markdown, array $meta = []): string
    {
        $converter = new CommonMarkConverter();
        $bodyHtml = $converter->convert($markdown)->getContent();

        return $this->wrapInHtmlDocument($bodyHtml, $meta);
    }

    /**
     * Wrap converted HTML body in a full HTML document with theme styles
     *
     * @param string               $bodyHtml Converted HTML body
     * @param array<string, mixed> $meta     Digest metadata
     * @return string Full HTML document
     */
    private function wrapInHtmlDocument(string $bodyHtml, array $meta): string
    {
        $companyName = $meta['company']['name'] ?? _('Digest Report');
        $periodStart = $meta['period']['start'] ?? '';
        $periodEnd = $meta['period']['end'] ?? '';
        $timestamp = $meta['timestamp'] ?? date('c');
        $css = $this->theme->getCss() . "\n" . $this->customCss;

        $header = '';

        if ($periodStart && $periodEnd) {
            $header = sprintf(
                '<p class="period">%s: %s – %s</p>',
                _('Period'),
                htmlspecialchars($periodStart),
                htmlspecialchars($periodEnd),
            );
        }

        $footer = sprintf(
            '<p><small>%s %s</small></p>',
            _('Generated on'),
            date('Y-m-d H:i:s', strtotime($timestamp)),
        );

        return <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>{$companyName} - Digest Report</title>
                <style>{$css}</style>
            </head>
            <body>
                <div class="digest-header">
                    <h1>{$companyName}</h1>
                    {$header}
                </div>
                <div class="digest-modules">
                    {$bodyHtml}
                </div>
                <div class="digest-footer">
                    {$footer}
                </div>
            </body>
            </html>
            HTML;
    }
}
