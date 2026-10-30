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
 * Crimson Ledger theme — deep crimson accents reminiscent of classic ledgers.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class CrimsonLedgerTheme extends PaletteTheme
{
    protected string $name = 'crimson-ledger';

    protected function palette(): array
    {
        return [
            'accent' => '#8b1e3f',
            'accentSoft' => '#c2185b',
            'accentBorder' => '#f3c4d4',
            'headerBg' => '#fce8ef',
            'headerBorder' => '#f3c4d4',
            'bg' => '#faf5f7',
            'surface' => '#ffffff',
            'text' => '#2c1219',
            'muted' => '#9a6074',
            'tableLine' => '#f0d4de',
            'tableHeaderBg' => '#fce4ec',
            'tableHeaderText' => '#6a1b3a',
            'tableHeaderBorder' => '#e5739a',
            'tableOdd' => '#fff8fa',
            'tableHover' => '#fce4ec',
            'summaryBg' => '#fce8ef',
            'shadow' => 'rgba(80,10,30,0.08)',
            'error' => '#b71c1c',
            'errorBg' => '#ffebee',
            'errorBorder' => '#ef9a9a',
            'errorText' => '#7f0000',
        ];
    }
}
