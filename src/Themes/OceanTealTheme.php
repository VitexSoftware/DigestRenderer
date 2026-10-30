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
 * Ocean Teal theme — cool teal and seafoam accents on a misty blue-green ground.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class OceanTealTheme extends PaletteTheme
{
    protected string $name = 'ocean-teal';

    protected function palette(): array
    {
        return [
            'accent' => '#0d7377',
            'accentSoft' => '#14a3a8',
            'accentBorder' => '#b2dfdb',
            'headerBg' => '#e8f6f5',
            'headerBorder' => '#b2dfdb',
            'bg' => '#f0f7f6',
            'surface' => '#ffffff',
            'text' => '#1a3332',
            'muted' => '#5a8a88',
            'tableLine' => '#cce8e6',
            'tableHeaderBg' => '#e0f2f1',
            'tableHeaderText' => '#00695c',
            'tableHeaderBorder' => '#4db6ac',
            'tableOdd' => '#f5fbfb',
            'tableHover' => '#e0f2f1',
            'summaryBg' => '#e8f6f5',
            'shadow' => 'rgba(0,80,70,0.08)',
            'error' => '#c62828',
            'errorBg' => '#ffebee',
            'errorBorder' => '#ef9a9a',
            'errorText' => '#b71c1c',
        ];
    }
}
