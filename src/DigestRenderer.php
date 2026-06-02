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

namespace VitexSoftware\DigestRenderer;

use VitexSoftware\DigestRenderer\Converters\MarkdownConverter;
use VitexSoftware\DigestRenderer\Converters\PdfConverter;
use VitexSoftware\DigestRenderer\Renderers\ModuleRendererFactory;
use VitexSoftware\DigestRenderer\Themes\BootstrapTheme;
use VitexSoftware\DigestRenderer\Themes\EmailTheme;
use VitexSoftware\DigestRenderer\Themes\GreenTheme;
use VitexSoftware\DigestRenderer\Themes\NordicBlueTheme;
use VitexSoftware\DigestRenderer\Themes\RainbowTheme;
use VitexSoftware\DigestRenderer\Themes\VioletGardenTheme;
use VitexSoftware\DigestRenderer\Themes\ThemeInterface;

/**
 * Main digest renderer
 *
 * Primary output is **Markdown**. Optionally converts to HTML (via
 * MarkdownConverter + theme CSS) or PDF (via PdfConverter + Dompdf).
 *
 * Supported output formats (pass as second argument to render()):
 *   - `md`   — Markdown (default)
 *   - `html` — Markdown → HTML with theme styles
 *   - `pdf`  — Markdown → HTML → PDF
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class DigestRenderer
{
    /**
     * Theme used for HTML/PDF output
     */
    private ThemeInterface $theme;

    /**
     * Module renderer factory
     */
    private ModuleRendererFactory $moduleFactory;

    /**
     * Custom CSS styles (HTML/PDF only)
     */
    private string $customCss = '';

    /**
     * Extra HTML injected into the report header (HTML/PDF only)
     */
    private string $headerExtra = '';

    /**
     * Extra HTML injected into the report footer (HTML/PDF only)
     */
    private string $footerExtra = '';

    /**
     * Constructor
     *
     * @param ThemeInterface|null $theme Theme for HTML/PDF output (defaults to Bootstrap)
     */
    public function __construct(?ThemeInterface $theme = null)
    {
        $this->theme = $theme ?: new BootstrapTheme();
        $this->moduleFactory = new ModuleRendererFactory();
    }

    /**
     * Set theme by name
     *
     * @param string $themeName Theme name (bootstrap, email)
     * @return self
     */
    public function setTheme(string $themeName): self
    {
        $this->theme = match ($themeName) {
            'email' => new EmailTheme(),
            'green' => new GreenTheme(),
            'rainbow' => new RainbowTheme(),
            'nordic-blue', 'nordic' => new NordicBlueTheme(),
            'violet-garden', 'violet' => new VioletGardenTheme(),
            'bootstrap', 'default', 'happy' => new BootstrapTheme(),
            default => throw new \InvalidArgumentException("Unknown theme: $themeName"),
        };

        return $this;
    }

    /**
     * Set custom CSS (affects HTML and PDF output only)
     *
     * @param string $css Custom CSS styles
     * @return self
     */
    public function setCustomCss(string $css): self
    {
        $this->customCss = $css;

        return $this;
    }

    /**
     * Set extra HTML injected at the top of the report header.
     * Use this to add an application logo, banner, or any branding markup.
     *
     * @param string $html Raw HTML (inline SVG, <img>, etc.)
     * @return self
     */
    public function setHeaderExtra(string $html): self
    {
        $this->headerExtra = $html;

        return $this;
    }

    /**
     * Set extra HTML injected at the bottom of the report footer.
     * Use this to add application name, version, and homepage link.
     *
     * @param string $html Raw HTML
     * @return self
     */
    public function setFooterExtra(string $html): self
    {
        $this->footerExtra = $html;

        return $this;
    }

    /**
     * Render digest data
     *
     * @param array<string, mixed> $digestData Structured digest data
     * @param string               $format     Output format: md, html, pdf
     * @return string Rendered output
     */
    public function render(array $digestData, string $format = 'md'): string
    {
        $this->validateDigestData($digestData);

        $markdown = $this->renderMarkdown($digestData);

        return match ($format) {
            'md' => $markdown,
            'html' => $this->convertToHtml($markdown, $digestData['digest'] ?? []),
            'pdf' => $this->convertToPdf($markdown, $digestData['digest'] ?? []),
            default => throw new \InvalidArgumentException("Unsupported format: $format"),
        };
    }

    /**
     * Render all modules to a single Markdown document
     *
     * @param array<string, mixed> $digestData Full digest data
     * @return string Markdown document
     */
    private function renderMarkdown(array $digestData): string
    {
        $digest = $digestData['digest'] ?? [];
        $companyName = $digest['company']['name'] ?? _('Digest Report');
        $periodStart = $digest['period']['start'] ?? '';
        $periodEnd = $digest['period']['end'] ?? '';

        $md = "# $companyName\n";

        if ($periodStart && $periodEnd) {
            $md .= sprintf("%s: %s – %s\n\n", _('Period'), $periodStart, $periodEnd);
        }

        $modules = $digestData['modules'] ?? [];

        foreach ($modules as $moduleKey => $moduleData) {
            try {
                $renderer = $this->moduleFactory->createRenderer(
                    $moduleData['module_name'] ?? $moduleKey,
                );

                $md .= $renderer->render($moduleData);
            } catch (\Throwable $e) {
                $md .= "## $moduleKey\n\n> **" . _('Error') . ":** {$e->getMessage()}\n\n";
            }
        }

        $timestamp = $digest['timestamp'] ?? date('c');
        $md .= "---\n";
        $md .= sprintf("*%s %s*\n", _('Generated on'), date('Y-m-d H:i:s', strtotime($timestamp)));

        return $md;
    }

    /**
     * Convert Markdown to themed HTML
     *
     * @param string               $markdown Markdown content
     * @param array<string, mixed> $meta     Digest metadata
     * @return string HTML document
     */
    private function convertToHtml(string $markdown, array $meta): string
    {
        $converter = new MarkdownConverter($this->theme, $this->customCss, $this->headerExtra, $this->footerExtra);

        return $converter->convert($markdown, $meta);
    }

    /**
     * Convert Markdown to PDF (Markdown → HTML → PDF)
     *
     * @param string               $markdown Markdown content
     * @param array<string, mixed> $meta     Digest metadata
     * @return string Raw PDF content
     */
    private function convertToPdf(string $markdown, array $meta): string
    {
        $html = $this->convertToHtml($markdown, $meta);
        $pdfConverter = new PdfConverter();

        return $pdfConverter->convert($html);
    }

    /**
     * Validate digest data structure
     *
     * @param array<string, mixed> $digestData Data to validate
     * @throws \InvalidArgumentException If data is invalid
     */
    private function validateDigestData(array $digestData): void
    {
        if (!isset($digestData['digest']) || !is_array($digestData['digest'])) {
            throw new \InvalidArgumentException('Missing or invalid digest metadata');
        }

        if (!isset($digestData['modules']) || !is_array($digestData['modules'])) {
            throw new \InvalidArgumentException('Missing or invalid modules data');
        }
    }
}
