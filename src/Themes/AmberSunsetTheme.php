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
 * Amber Sunset theme — warm amber and gold accents on a soft sand background.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class AmberSunsetTheme extends PaletteTheme
{
    protected string $name = 'amber-sunset';

    protected function palette(): array
    {
        return [
            'accent' => '#c47800',
            'accentSoft' => '#e6a23c',
            'accentBorder' => '#f0d9a8',
            'headerBg' => '#fff8eb',
            'headerBorder' => '#f0d9a8',
            'bg' => '#faf6ef',
            'surface' => '#ffffff',
            'text' => '#3d2e14',
            'muted' => '#9a7b4f',
            'tableLine' => '#f0e0c0',
            'tableHeaderBg' => '#fff3d6',
            'tableHeaderText' => '#8a5a00',
            'tableHeaderBorder' => '#e6a23c',
            'tableOdd' => '#fffbf2',
            'tableHover' => '#fff3d6',
            'summaryBg' => '#fff8eb',
            'shadow' => 'rgba(100,60,0,0.08)',
            'error' => '#c62828',
            'errorBg' => '#ffebee',
            'errorBorder' => '#ef9a9a',
            'errorText' => '#b71c1c',
        ];
    }
}
