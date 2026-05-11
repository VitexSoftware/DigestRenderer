<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Unmatched invoices (non-deducted proformas) module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class UnmatchedInvoicesRenderer extends InvoiceListRenderer
{
    protected string $listKey = 'invoices';
}
