<?php

declare(strict_types=1);

namespace VitexSoftware\DigestRenderer\Renderers;

/**
 * Without phone module renderer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class WithoutTelRenderer extends ContactListRenderer
{
    protected string $listKey = 'contacts';
}
