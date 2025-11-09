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
 * Factory for creating module renderers
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class ModuleRendererFactory
{
    /**
     * Registered renderer classes
     *
     * @var array<string, string>
     */
    private array $rendererClasses = [
        'outcoming_invoices' => OutcomingInvoicesRenderer::class,
        'incoming_invoices' => IncomingInvoicesRenderer::class,
        'debtors' => DebtorsRenderer::class,
        'new_customers' => NewCustomersRenderer::class,
        'best_sellers' => BestSellersRenderer::class,
        'waiting_payments' => WaitingPaymentsRenderer::class,
    ];

    /**
     * Create renderer for module
     *
     * @param string $moduleName Module name
     * @param ThemeInterface $theme Theme to use
     * @return ModuleRendererInterface
     * @throws \InvalidArgumentException If no renderer found for module
     */
    public function createRenderer(string $moduleName, ThemeInterface $theme): ModuleRendererInterface
    {
        if (!isset($this->rendererClasses[$moduleName])) {
            // Fall back to generic renderer
            return new GenericModuleRenderer($theme, $moduleName);
        }

        $rendererClass = $this->rendererClasses[$moduleName];
        
        if (!class_exists($rendererClass)) {
            throw new \InvalidArgumentException("Renderer class not found: $rendererClass");
        }

        return new $rendererClass($theme);
    }

    /**
     * Register custom renderer
     *
     * @param string $moduleName Module name
     * @param string $rendererClass Renderer class name
     * @return self
     */
    public function registerRenderer(string $moduleName, string $rendererClass): self
    {
        $this->rendererClasses[$moduleName] = $rendererClass;
        
        return $this;
    }

    /**
     * Get registered renderers
     *
     * @return array<string, string>
     */
    public function getRegisteredRenderers(): array
    {
        return $this->rendererClasses;
    }
}