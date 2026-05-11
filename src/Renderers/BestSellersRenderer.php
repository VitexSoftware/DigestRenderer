<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Best sellers module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class BestSellersRenderer extends AbstractModuleRenderer
{
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? _('Best selling products');
        $data = $moduleData['data'] ?? [];
        $products = $data['products'] ?? [];

        $md = $this->markdownSummary(_('Summary'), [
            _('Products') => $data['summary']['total_products'] ?? 0,
        ]);

        if (!empty($products)) {
            $headers = [_('Product'), _('Quantity'), _('Total')];
            $rows = [];

            foreach ($products as $product) {
                $rows[] = [
                    $product['code'] ?? '',
                    (string) ($product['quantity'] ?? 0),
                    number_format((float) ($product['total'] ?? 0), 2, ',', ' '),
                ];
            }

            $md .= $this->markdownTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
