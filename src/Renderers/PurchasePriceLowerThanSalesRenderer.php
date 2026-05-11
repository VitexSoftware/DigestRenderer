<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Purchase price lower than sales price renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class PurchasePriceLowerThanSalesRenderer extends AbstractModuleRenderer
{
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? _('Product purchase price lower than sales');
        $data = $moduleData['data'] ?? [];
        $products = $data['products'] ?? [];

        $md = $this->markdownSummary(_('Summary'), [
            _('Disadvantageous products') => $data['summary']['total_count'] ?? 0,
        ]);

        if (!empty($products)) {
            $headers = [_('Code'), _('Name'), _('Buy'), _('Sell'), _('Difference')];
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

            $md .= $this->markdownTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
