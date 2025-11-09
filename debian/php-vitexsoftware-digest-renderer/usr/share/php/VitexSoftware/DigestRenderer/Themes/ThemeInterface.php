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

namespace VitexSoftware\DigestRenderer\Themes;

/**
 * Interface for digest themes
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
interface ThemeInterface
{
    /**
     * Render complete digest HTML
     *
     * @param array<string, mixed> $templateVars Template variables
     * @return string HTML output
     */
    public function render(array $templateVars): string;

    /**
     * Get theme CSS styles
     *
     * @return string CSS styles
     */
    public function getCss(): string;

    /**
     * Get theme name
     *
     * @return string Theme name
     */
    public function getName(): string;

    /**
     * Render error message
     *
     * @param string $title Error title
     * @param string $message Error message
     * @return string Error HTML
     */
    public function renderError(string $title, string $message): string;

    /**
     * Render table
     *
     * @param array<string> $headers Table headers
     * @param array<array<string>> $rows Table rows
     * @param array<string, mixed> $options Table options
     * @return string Table HTML
     */
    public function renderTable(array $headers, array $rows, array $options = []): string;

    /**
     * Render card/panel
     *
     * @param string $title Card title
     * @param string $content Card content
     * @param array<string, mixed> $options Card options
     * @return string Card HTML
     */
    public function renderCard(string $title, string $content, array $options = []): string;

    /**
     * Render summary box
     *
     * @param string $title Summary title
     * @param array<string, mixed> $data Summary data
     * @return string Summary HTML
     */
    public function renderSummary(string $title, array $data): string;
}