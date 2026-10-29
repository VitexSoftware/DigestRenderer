<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

use VitexSoftware\DigestRenderer\I18n;

/**
 * Best sellers module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class BestSellersRenderer extends AbstractModuleRenderer
{
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? I18n::t('Best selling products');
        $data = $moduleData['data'] ?? [];
        $products = $data['products'] ?? [];

        $md = $this->markdownSummary(I18n::t('Summary'), [
            I18n::t('Products') => $data['summary']['total_products'] ?? 0,
        ]);

        if (!empty($products)) {
            $headers = [I18n::t('Product'), I18n::t('Quantity'), I18n::t('Total')];
            $rows = [];

            foreach ($products as $product) {
                $rows[] = [
                    $product['code'] ?? '',
                    (string) ($product['quantity'] ?? 0),
                    number_format((float) ($product['total'] ?? 0), 2, ',', ' '),
                ];
            }

            $md .= $this->markdownItemsTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
