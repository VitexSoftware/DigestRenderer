<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

use VitexSoftware\DigestRenderer\I18n;

/**
 * Purchase price lower than sales price renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class PurchasePriceLowerThanSalesRenderer extends AbstractModuleRenderer
{
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? I18n::t('Product purchase price lower than sales');
        $data = $moduleData['data'] ?? [];
        $products = $data['products'] ?? [];

        $md = $this->markdownSummary(I18n::t('Summary'), [
            I18n::t('Disadvantageous products') => $data['summary']['total_count'] ?? 0,
        ]);

        if (!empty($products)) {
            $headers = [I18n::t('Code'), I18n::t('Name'), I18n::t('Buy'), I18n::t('Sell'), I18n::t('Difference')];
            $rows = [];

            foreach ($products as $product) {
                $rows[] = [
                    $product['code'] ?? '',
                    $product['name'] ?? '',
                    number_format((float) ($product['buy_price'] ?? 0), 2, ',', ' '),
                    number_format((float) ($product['sell_price'] ?? 0), 2, ',', ' '),
                    number_format((float) ($product['difference'] ?? 0), 2, ',', ' '),
                ];
            }

            $md .= $this->markdownItemsTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
