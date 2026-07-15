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

use League\CommonMark\GithubFlavoredMarkdownConverter;
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
     * Extra HTML injected at the top of the header section
     */
    private string $headerExtra;

    /**
     * Extra HTML injected at the bottom of the footer section
     */
    private string $footerExtra;

    /**
     * Constructor
     *
     * @param ThemeInterface $theme       Theme for HTML wrapping
     * @param string         $customCss   Additional CSS styles
     * @param string         $headerExtra Extra HTML for the report header (logo, banner)
     * @param string         $footerExtra Extra HTML for the report footer (app info)
     */
    public function __construct(ThemeInterface $theme, string $customCss = '', string $headerExtra = '', string $footerExtra = '')
    {
        $this->theme = $theme;
        $this->customCss = $customCss;
        $this->headerExtra = $headerExtra;
        $this->footerExtra = $footerExtra;
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
        $converter = new GithubFlavoredMarkdownConverter();
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

        $dataSource = $this->buildDataSourceHtml($meta);

        if ($dataSource !== '') {
            $footer .= $dataSource;
        }

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
                    {$this->headerExtra}
                    <h1>{$companyName}</h1>
                    {$header}
                </div>
                <div class="digest-modules">
                    {$bodyHtml}
                </div>
                <div class="digest-footer">
                    {$footer}
                    {$this->footerExtra}
                </div>
            </body>
            </html>
            HTML;
    }

    /**
     * Build a footer line unambiguously identifying which AbraFlexi/Pohoda
     * server (and company) the digest data was pulled from.
     *
     * @param array<string, mixed> $meta Digest metadata (provider/company block)
     * @return string HTML snippet (empty if no source info is available)
     */
    private function buildDataSourceHtml(array $meta): string
    {
        $company = $meta['company'] ?? [];
        $system = $company['system'] ?? $meta['provider'] ?? '';
        $name = $company['name'] ?? '';
        $identifier = $company['code'] ?? $company['ico'] ?? '';
        $url = $company['url'] ?? $company['server_url'] ?? '';

        if (!$system && !$name && !$url) {
            return '';
        }

        $parts = array_filter([
            $system,
            $name,
            $identifier !== '' ? "#{$identifier}" : '',
        ], static fn ($part) => $part !== '' && $part !== null);

        $label = htmlspecialchars(implode(' — ', $parts));
        $link = $url !== '' ? sprintf(' — <a href="%s">%s</a>', htmlspecialchars($url), htmlspecialchars($url)) : '';

        return sprintf('<p class="data-source"><small>%s: %s%s</small></p>', _('Data source'), $label, $link);
    }
}
