<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Reminders module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class RemindsRenderer extends InvoiceListRenderer
{
    protected string $listKey = 'invoices';
}
