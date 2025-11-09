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
 * Generic module renderer for unknown module types
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class GenericModuleRenderer extends AbstractModuleRenderer
{
    /**
     * {@inheritDoc}
     */
    protected function renderSuccess(array $moduleData): string
    {
        $title = $moduleData['heading'] ?? $this->getModuleName();
        $data = $moduleData['data'] ?? [];

        $content = '';

        // Render summary if available
        if (isset($data['summary'])) {
            $content .= $this->theme->renderSummary('Summary', $data['summary']);
        }

        // Render other data sections
        foreach ($data as $key => $value) {
            if ($key === 'summary') {
                continue; // Already rendered
            }

            $sectionTitle = ucwords(str_replace('_', ' ', $key));
            
            if (is_array($value) && $this->isTableData($value)) {
                $content .= "<h4>$sectionTitle</h4>";
                $content .= $this->renderTableFromArray($value);
            } elseif (is_array($value)) {
                $content .= $this->theme->renderSummary($sectionTitle, $value);
            } else {
                $content .= "<p><strong>$sectionTitle:</strong> " . htmlspecialchars((string)$value) . "</p>";
            }
        }

        return $this->theme->renderCard($title, $content);
    }

    /**
     * Check if array data represents table structure
     *
     * @param array<mixed> $data Data to check
     * @return bool Whether data is table-like
     */
    private function isTableData(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $firstItem = reset($data);
        
        return is_array($firstItem) && !empty($firstItem);
    }

    /**
     * Render table from array data
     *
     * @param array<array<string, mixed>> $data Array data
     * @return string Table HTML
     */
    private function renderTableFromArray(array $data): string
    {
        if (empty($data)) {
            return '<p>No data available</p>';
        }

        $firstRow = reset($data);
        
        if (!is_array($firstRow)) {
            return '<p>Invalid data format</p>';
        }

        $headers = array_keys($firstRow);
        $rows = [];

        foreach ($data as $item) {
            if (is_array($item)) {
                $row = [];
                foreach ($headers as $header) {
                    $value = $item[$header] ?? '';
                    $row[] = is_array($value) ? json_encode($value) : (string)$value;
                }
                $rows[] = $row;
            }
        }

        return $this->theme->renderTable($headers, $rows);
    }
}