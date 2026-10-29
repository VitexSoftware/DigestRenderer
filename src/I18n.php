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

namespace VitexSoftware\DigestRenderer;

/**
 * Translations of the renderer texts ("digest-renderer" gettext domain).
 *
 * The locale itself is chosen by the application (e.g. \Ease\Locale).
 */
final class I18n
{
    public const DOMAIN = 'digest-renderer';

    private static bool $bound = false;

    /**
     * Translate a message.
     *
     * @param string $message Message in English
     */
    public static function t(string $message): string
    {
        self::bind();

        return dgettext(self::DOMAIN, $message);
    }

    /**
     * Translate a message with plural forms.
     *
     * @param string $singular Message for n == 1
     * @param string $plural   Message for other n
     * @param int    $count    Number deciding the plural form
     */
    public static function n(string $singular, string $plural, int $count): string
    {
        self::bind();

        return dngettext(self::DOMAIN, $singular, $plural, $count);
    }

    private static function bind(): void
    {
        if (!self::$bound) {
            $localeDir = getenv('DIGEST_LOCALEDIR') ?: '/usr/share/locale';
            bindtextdomain(self::DOMAIN, $localeDir);
            bind_textdomain_codeset(self::DOMAIN, 'UTF-8');
            self::$bound = true;
        }
    }
}
