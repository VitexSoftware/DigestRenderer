<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Without email module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class WithoutEmailRenderer extends ContactListRenderer
{
    protected string $listKey = 'contacts';
}
