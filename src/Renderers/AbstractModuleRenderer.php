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

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Abstract base module renderer — produces Markdown output
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
abstract class AbstractModuleRenderer implements ModuleRendererInterface
{
    /**
     * Module name
     */
    protected string $moduleName = '';

    /**
     * Constructor
     *
     * @param string $moduleName Module name (optional, auto-detected)
     */
    public function __construct(string $moduleName = '')
    {
        $this->moduleName = $moduleName ?: $this->getModuleName();
    }

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        if ($this->moduleName) {
            return $this->moduleName;
        }

        // Auto-detect from class name
        $className = basename(str_replace('\\', '/', static::class));
        $moduleName = str_replace('Renderer', '', $className);

        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $moduleName));
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $moduleData): string
    {
        if (!$moduleData['success']) {
            return $this->renderError($moduleData);
        }

        return $this->renderSuccess($moduleData);
    }

    /**
     * Render successful module data
     *
     * @param array<string, mixed> $moduleData Module data
     * @return string Markdown output
     */
    abstract protected function renderSuccess(array $moduleData): string;

    /**
     * Render error state
     *
     * @param array<string, mixed> $moduleData Module data with error
     * @return string Markdown error block
     */
    protected function renderError(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? $this->getModuleName();
        $error = $moduleData['error'] ?? $moduleData['metadata']['error'] ?? [];
        $message = is_string($error) ? $error : ($error['message'] ?? 'Unknown error occurred');

        return $this->markdownHeading($title, 2) . "\n> **" . _('Error') . ":** $message\n";
    }

    /**
     * Format currency value
     *
     * @param array<string, mixed>|float $currencyData Currency data or amount
     * @return string Formatted currency
     */
    protected function formatCurrency(array|float $currencyData): string
    {
        if (is_array($currencyData)) {
            return $currencyData['formatted']
                ?? ($currencyData['amount'] . ' ' . ($currencyData['currency'] ?? 'CZK'));
        }

        return number_format($currencyData, 2, ',', ' ') . ' CZK';
    }

    // ---- Markdown helpers ----

    /**
     * Render a Markdown heading
     *
     * @param string $text  Heading text
     * @param int    $level 1-6
     * @return string
     */
    protected function markdownHeading(string $text, int $level = 2): string
    {
        return str_repeat('#', $level) . ' ' . $text . "\n";
    }

    /**
     * Render a Markdown table
     *
     * @param array<string>        $headers Column headers
     * @param array<array<string>> $rows    Row data
     * @return string
     */
    protected function markdownTable(array $headers, array $rows): string
    {
        if (empty($headers)) {
            return '';
        }

        $md = '| ' . implode(' | ', $headers) . " |\n";
        $md .= '|' . implode('|', array_fill(0, count($headers), ' --- ')) . "|\n";

        foreach ($rows as $row) {
            // Pad row to match header count
            $row = array_pad($row, count($headers), '');
            $md .= '| ' . implode(' | ', array_map(fn ($v) => str_replace('|', '\|', (string) $v), $row)) . " |\n";
        }

        return $md . "\n";
    }

    /**
     * Render a Markdown summary (key-value list)
     *
     * @param string               $title Title for the summary
     * @param array<string, mixed> $data  Key-value pairs
     * @return string
     */
    protected function markdownSummary(string $title, array $data): string
    {
        $md = $this->markdownHeading($title, 4);

        foreach ($data as $key => $value) {
            $md .= "- **$key:** " . (string) $value . "\n";
        }

        return $md . "\n";
    }

    /**
     * Render a Markdown unordered list
     *
     * @param array<string> $items List items
     * @return string
     */
    protected function markdownList(array $items): string
    {
        $md = '';

        foreach ($items as $item) {
            $md .= "- $item\n";
        }

        return $md . "\n";
    }

    /**
     * Wrap content in a Markdown module section (heading + content)
     *
     * @param string $title   Section title
     * @param string $content Section Markdown content
     * @return string
     */
    protected function markdownSection(string $title, string $content): string
    {
        return $this->markdownHeading($title, 2) . $content . "\n";
    }
}
