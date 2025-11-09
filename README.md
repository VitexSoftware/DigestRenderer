# DigestRenderer

A standalone PHP library for rendering digest data as HTML reports and dashboards.

## Overview

This library takes structured data (associative arrays or JSON) from DigestModules and generates HTML output including tables, charts, and summary reports. It's designed to be reusable across different accounting systems and digest implementations.

## Features

- **Multiple Output Formats**: HTML pages, email-friendly HTML, PDF-ready markup
- **Responsive Design**: Bootstrap-based responsive templates
- **Customizable Themes**: Easy theme and styling customization
- **Chart Support**: Built-in chart generation for data visualization
- **Email Integration**: Optimized HTML for email clients
- **Modular Renderers**: Separate renderers for different module types

## Installation

```bash
composer require vitexsoftware/digest-renderer
```

## Basic Usage

```php
use VitexSoftware\DigestRenderer\DigestRenderer;

// Load data from DigestModules
$digestData = json_decode(file_get_contents('digest_data.json'), true);

// Create renderer
$renderer = new DigestRenderer();

// Configure theme (optional)
$renderer->setTheme('bootstrap'); // or 'email', 'print'

// Render HTML
$html = $renderer->render($digestData);

// Save to file
file_put_contents('digest.html', $html);

// Or send by email
$emailRenderer = new DigestRenderer();
$emailRenderer->setTheme('email');
$emailHtml = $emailRenderer->render($digestData);
```

## Themes

### Bootstrap Theme (Default)
- Responsive design
- Modern Bootstrap 5 components
- Interactive elements
- Chart.js integration

### Email Theme
- Email client compatibility
- Inline CSS styles
- Table-based layouts
- Outlook compatibility

### Print Theme
- Print-optimized styling
- Page break handling
- Monochrome-friendly colors

## Module Renderers

Each module type has a dedicated renderer:

```php
use VitexSoftware\DigestRenderer\Renderers\OutcomingInvoicesRenderer;
use VitexSoftware\DigestRenderer\Renderers\DebtorsRenderer;

// Render specific module
$invoiceRenderer = new OutcomingInvoicesRenderer();
$html = $invoiceRenderer->render($moduleData);
```

## Customization

### Custom Themes

```php
$renderer = new DigestRenderer();
$renderer->setCustomCss('
    .digest-header { background: #custom-color; }
    .module-card { border: 1px solid #ccc; }
');
```

### Custom Templates

```php
$renderer = new DigestRenderer();
$renderer->setTemplate('custom_template.php');
```

## Data Structure

The renderer expects data in this format:

```json
{
    "digest": {
        "period": {"start": "2024-01-01", "end": "2024-02-01"},
        "company": {"name": "Company Name"},
        "timestamp": "2024-01-15T10:30:00Z"
    },
    "modules": {
        "outcoming_invoices": {
            "module_name": "outcoming_invoices",
            "heading": "Outcoming Invoices", 
            "success": true,
            "data": {...}
        }
    }
}
```

## License

GPL-2.0-or-later