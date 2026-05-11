<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Waiting income module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class WaitingIncomeRenderer extends InvoiceListRenderer
{
    protected string $listKey = 'invoices';
}
