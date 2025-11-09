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

use VitexSoftware\DigestRenderer\Themes\ThemeInterface;
use VitexSoftware\DigestRenderer\Themes\BootstrapTheme;
use VitexSoftware\DigestRenderer\Themes\EmailTheme;
use VitexSoftware\DigestRenderer\Renderers\ModuleRendererFactory;

/**
 * Main digest renderer
 *
 * Converts structured digest data into HTML output
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class DigestRenderer
{
    /**
     * Current theme
     */
    private ThemeInterface $theme;

    /**
     * Module renderer factory
     */
    private ModuleRendererFactory $moduleFactory;

    /**
     * Custom CSS styles
     */
    private string $customCss = '';

    /**
     * Custom template path
     */
    private ?string $customTemplate = null;

    /**
     * Constructor
     *
     * @param ThemeInterface|null $theme Theme to use (defaults to Bootstrap)
     */
    public function __construct(?ThemeInterface $theme = null)
    {
        $this->theme = $theme ?: new BootstrapTheme();
        $this->moduleFactory = new ModuleRendererFactory();
    }

    /**
     * Set theme by name
     *
     * @param string $themeName Theme name (bootstrap, email, print)
     * @return self
     */
    public function setTheme(string $themeName): self
    {
        $this->theme = match ($themeName) {
            'email' => new EmailTheme(),
            'bootstrap', 'default' => new BootstrapTheme(),
            default => throw new \InvalidArgumentException("Unknown theme: $themeName"),
        };

        return $this;
    }

    /**
     * Set custom CSS
     *
     * @param string $css Custom CSS styles
     * @return self
     */
    public function setCustomCss(string $css): self
    {
        $this->customCss = $css;
        
        return $this;
    }

    /**
     * Set custom template path
     *
     * @param string $templatePath Path to custom template file
     * @return self
     */
    public function setTemplate(string $templatePath): self
    {
        $this->customTemplate = $templatePath;
        
        return $this;
    }

    /**
     * Render digest data to HTML
     *
     * @param array<string, mixed> $digestData Structured digest data
     * @return string HTML output
     */
    public function render(array $digestData): string
    {
        try {
            // Validate data structure
            $this->validateDigestData($digestData);

            // Prepare template variables
            $templateVars = [
                'digest' => $digestData['digest'] ?? [],
                'modules' => $digestData['modules'] ?? [],
                'benchmarks' => $digestData['benchmarks'] ?? [],
                'theme' => $this->theme,
                'customCss' => $this->customCss,
                'renderedModules' => $this->renderModules($digestData['modules'] ?? []),
            ];

            // Use custom template or default
            if ($this->customTemplate && file_exists($this->customTemplate)) {
                return $this->renderTemplate($this->customTemplate, $templateVars);
            }

            return $this->theme->render($templateVars);

        } catch (\Throwable $e) {
            return $this->renderError($e);
        }
    }

    /**
     * Render all modules
     *
     * @param array<string, mixed> $modules Module data
     * @return array<string, string> Rendered module HTML
     */
    private function renderModules(array $modules): array
    {
        $rendered = [];

        foreach ($modules as $moduleKey => $moduleData) {
            try {
                $renderer = $this->moduleFactory->createRenderer(
                    $moduleData['module_name'] ?? $moduleKey,
                    $this->theme
                );

                $rendered[$moduleKey] = $renderer->render($moduleData);

            } catch (\Throwable $e) {
                $rendered[$moduleKey] = $this->theme->renderError(
                    $moduleData['heading'] ?? $moduleKey,
                    $e->getMessage()
                );
            }
        }

        return $rendered;
    }

    /**
     * Validate digest data structure
     *
     * @param array<string, mixed> $digestData Data to validate
     * @throws \InvalidArgumentException If data is invalid
     */
    private function validateDigestData(array $digestData): void
    {
        if (!is_array($digestData)) {
            throw new \InvalidArgumentException('Digest data must be an array');
        }

        // Check basic structure
        if (!isset($digestData['digest']) || !is_array($digestData['digest'])) {
            throw new \InvalidArgumentException('Missing or invalid digest metadata');
        }

        if (!isset($digestData['modules']) || !is_array($digestData['modules'])) {
            throw new \InvalidArgumentException('Missing or invalid modules data');
        }
    }

    /**
     * Render custom template
     *
     * @param string $templatePath Template file path
     * @param array<string, mixed> $vars Template variables
     * @return string Rendered HTML
     */
    private function renderTemplate(string $templatePath, array $vars): string
    {
        extract($vars);
        
        ob_start();
        include $templatePath;
        
        return ob_get_clean() ?: '';
    }

    /**
     * Render error message
     *
     * @param \Throwable $error Error to render
     * @return string Error HTML
     */
    private function renderError(\Throwable $error): string
    {
        return $this->theme->renderError(
            'Digest Rendering Error',
            $error->getMessage()
        );
    }
}