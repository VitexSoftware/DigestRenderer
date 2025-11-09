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

use VitexSoftware\DigestRenderer\Themes\ThemeInterface;

/**
 * Abstract base module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
abstract class AbstractModuleRenderer implements ModuleRendererInterface
{
    /**
     * Theme to use for rendering
     */
    protected ThemeInterface $theme;

    /**
     * Module name
     */
    protected string $moduleName;

    /**
     * Constructor
     *
     * @param ThemeInterface $theme Theme to use
     * @param string $moduleName Module name (optional, auto-detected)
     */
    public function __construct(ThemeInterface $theme, string $moduleName = '')
    {
        $this->theme = $theme;
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
     * @return string HTML output
     */
    abstract protected function renderSuccess(array $moduleData): string;

    /**
     * Render error state
     *
     * @param array<string, mixed> $moduleData Module data with error
     * @return string Error HTML
     */
    protected function renderError(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? $this->getModuleName();
        $error = $moduleData['error'] ?? [];
        $message = $error['message'] ?? 'Unknown error occurred';

        return $this->theme->renderError($title, $message);
    }

    /**
     * Format currency value
     *
     * @param array<string, mixed>|float $currencyData Currency data or amount
     * @return string Formatted currency
     */
    protected function formatCurrency($currencyData): string
    {
        if (is_array($currencyData)) {
            return $currencyData['formatted'] ?? 
                   ($currencyData['amount'] . ' ' . ($currencyData['currency'] ?? 'CZK'));
        }

        return number_format((float)$currencyData, 2, ',', ' ') . ' CZK';
    }

    /**
     * Create summary data array
     *
     * @param array<string, mixed> $data Source data
     * @return array<string, mixed> Summary data
     */
    protected function createSummary(array $data): array
    {
        return $data['summary'] ?? [];
    }
}