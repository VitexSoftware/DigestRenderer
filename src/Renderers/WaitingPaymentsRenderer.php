<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Waiting payments module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class WaitingPaymentsRenderer extends InvoiceListRenderer
{
    protected string $listKey = 'invoices';
}
