<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * New customers module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class NewCustomersRenderer extends ContactListRenderer
{
    protected string $listKey = 'customers';
}
