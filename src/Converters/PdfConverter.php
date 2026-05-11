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

namespace VitexSoftware\DigestRenderer\Converters;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Converts HTML content to PDF using Dompdf
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class PdfConverter
{
    /**
     * Dompdf options
     */
    private Options $options;

    /**
     * Constructor
     *
     * @param array<string, mixed> $dompdfOptions Dompdf configuration options
     */
    public function __construct(array $dompdfOptions = [])
    {
        $this->options = new Options();
        $this->options->setIsRemoteEnabled($dompdfOptions['isRemoteEnabled'] ?? false);
        $this->options->setDefaultFont($dompdfOptions['defaultFont'] ?? 'DejaVu Sans');

        if (isset($dompdfOptions['tempDir'])) {
            $this->options->setTempDir($dompdfOptions['tempDir']);
        }
    }

    /**
     * Convert HTML to PDF
     *
     * @param string $html   Full HTML document
     * @param string $paper  Paper size (letter, A4, etc.)
     * @param string $orient Orientation (portrait, landscape)
     * @return string Raw PDF content
     */
    public function convert(string $html, string $paper = 'A4', string $orient = 'portrait'): string
    {
        $dompdf = new Dompdf($this->options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orient);
        $dompdf->render();

        return $dompdf->output() ?: '';
    }
}
