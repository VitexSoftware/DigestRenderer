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
 * Graphite theme — charcoal and slate for a restrained executive look.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class GraphiteTheme extends PaletteTheme
{
    protected string $name = 'graphite';

    protected function palette(): array
    {
        return [
            'accent' => '#37474f',
            'accentSoft' => '#607d8b',
            'accentBorder' => '#cfd8dc',
            'headerBg' => '#eceff1',
            'headerBorder' => '#cfd8dc',
            'bg' => '#f4f5f6',
            'surface' => '#ffffff',
            'text' => '#212121',
            'muted' => '#78909c',
            'tableLine' => '#e0e0e0',
            'tableHeaderBg' => '#eceff1',
            'tableHeaderText' => '#263238',
            'tableHeaderBorder' => '#90a4ae',
            'tableOdd' => '#fafafa',
            'tableHover' => '#eceff1',
            'summaryBg' => '#eceff1',
            'shadow' => 'rgba(0,0,0,0.08)',
            'error' => '#c62828',
            'errorBg' => '#ffebee',
            'errorBorder' => '#ef9a9a',
            'errorText' => '#b71c1c',
        ];
    }
}
