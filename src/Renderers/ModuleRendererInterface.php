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
 * Interface for module renderers
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
interface ModuleRendererInterface
{
    /**
     * Render module data to HTML
     *
     * @param array<string, mixed> $moduleData Module data from DigestModules
     * @return string HTML output
     */
    public function render(array $moduleData): string;

    /**
     * Get supported module name
     *
     * @return string Module name this renderer supports
     */
    public function getModuleName(): string;
}