<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Outcoming invoices hidden to customer renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class OutcomingInvoicesHiddenRenderer extends InvoiceListRenderer
{
    protected string $listKey = 'invoices';
}
