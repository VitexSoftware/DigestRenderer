<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Base renderer for contact-list modules (WithoutEmail, WithoutTel, NewCustomers)
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class ContactListRenderer extends AbstractModuleRenderer
{
    /**
     * Data key containing the list items
     */
    protected string $listKey = 'contacts';

    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? $this->getModuleName();
        $data = $moduleData['data'] ?? [];
        $summary = $data['summary'] ?? [];

        $md = $this->markdownSummary(_('Summary'), [
            _('Total') => $summary['total_count'] ?? 0,
        ]);

        $items = $data[$this->listKey] ?? [];

        if (!empty($items)) {
            $firstItem = reset($items);
            $headers = array_map(
                static fn (string $k) => _(ucwords(str_replace('_', ' ', $k))),
                array_keys($firstItem),
            );
            $rows = [];

            foreach ($items as $item) {
                $row = [];

                foreach ($item as $value) {
                    $row[] = (string) $value;
                }

                $rows[] = $row;
            }

            $md .= $this->markdownTable($headers, $rows);
        }

        return $this->markdownSection($title, $md);
    }
}
