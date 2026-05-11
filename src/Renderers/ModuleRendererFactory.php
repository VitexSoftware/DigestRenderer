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
        'incoming_payments' => CurrencyTotalsRenderer::class,
        'outcoming_payments' => CurrencyTotalsRenderer::class,
        'new_customers' => NewCustomersRenderer::class,
        'without_email' => WithoutEmailRenderer::class,
        'without_tel' => WithoutTelRenderer::class,
        'waiting_income' => WaitingIncomeRenderer::class,
        'waiting_payments' => WaitingPaymentsRenderer::class,
        'reminds' => RemindsRenderer::class,
        'best_sellers' => BestSellersRenderer::class,
        'unmatched_payments' => UnmatchedPaymentsRenderer::class,
        'unmatched_invoices' => UnmatchedInvoicesRenderer::class,
        'outcoming_invoices_hidden' => OutcomingInvoicesHiddenRenderer::class,
        'daily_income_chart' => IncomeChartRenderer::class,
        'weekly_income_chart' => IncomeChartRenderer::class,
        'purchase_price_lower_than_sales' => PurchasePriceLowerThanSalesRenderer::class,
    ];

    /**
     * Create renderer for module
     *
     * @param string $moduleName Module name
     * @return ModuleRendererInterface
     * @throws \InvalidArgumentException If no renderer found for module
     */
    public function createRenderer(string $moduleName): ModuleRendererInterface
    {
        if (!isset($this->rendererClasses[$moduleName])) {
            // Fall back to generic renderer
            return new GenericModuleRenderer($moduleName);
        }

        $rendererClass = $this->rendererClasses[$moduleName];

        if (!class_exists($rendererClass)) {
            throw new \InvalidArgumentException("Renderer class not found: $rendererClass");
        }

        return new $rendererClass();
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