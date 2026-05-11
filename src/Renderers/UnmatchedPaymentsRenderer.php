<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Unmatched payments module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class UnmatchedPaymentsRenderer extends InvoiceListRenderer
{
    protected string $listKey = 'payments';
}
