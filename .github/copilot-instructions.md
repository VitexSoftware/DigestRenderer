---
description: DigestRenderer library for converting JSON analytics data to themed HTML
applyTo: '**'
---

# DigestRenderer Library - Copilot Instructions

## Project Overview
DigestRenderer converts structured JSON analytics data into themed HTML output:
- Multiple theme support (Bootstrap, Email-compatible)
- Module-specific renderers for different data types
- Reusable across different accounting systems
- Clean separation between data and presentation

## Architecture Guidelines
- **ThemeInterface**: All themes must implement this interface
- **ModuleRendererInterface**: Renderers for specific module types
- **Factory Pattern**: ModuleRendererFactory creates appropriate renderers
- **Template System**: Themes define HTML structure and CSS
- **Data-driven**: Accepts JSON from DigestModules library

## Development Best Practices
- Use strict types: `declare(strict_types=1);`
- Follow PSR-4 autoloading standards
- Implement responsive CSS for web themes
- Use inline CSS for email themes
- Sanitize all data before HTML output
- Support multiple currencies and locales

## Key Components
1. **Core** (`src/`):
   - DigestRenderer: Main renderer class
   - Theme management and selection
   - HTML generation orchestration

2. **Themes** (`src/Themes/`):
   - BootstrapTheme: Modern responsive theme
   - EmailTheme: Email client compatible theme
   - AbstractTheme: Base implementation

3. **Renderers** (`src/Renderers/`):
   - ModuleRendererFactory: Creates specific renderers
   - OutcomingInvoicesRenderer: Invoice analysis rendering
   - DebtorsRenderer: Overdue analysis rendering
   - GenericModuleRenderer: Fallback renderer

## Theme Development Pattern
```php
class CustomTheme extends AbstractTheme
{
    public function getName(): string { return 'custom'; }
    public function getDisplayName(): string { return 'Custom Theme'; }
    
    public function render(array $digestData): string
    {
        // Generate HTML with custom styling
        // Include CSS and responsive design
    }
}
```

## Module Renderer Pattern
```php
class CustomModuleRenderer extends AbstractModuleRenderer
{
    protected function renderModuleData(array $moduleData): string
    {
        // Convert specific module JSON to HTML
        // Handle tables, charts, summaries
    }
}
```

## Supported Themes
1. **Bootstrap Theme**:
   - Responsive design with CSS Grid/Flexbox
   - Modern typography and colors
   - Interactive elements and hover states
   - Print-friendly styles

2. **Email Theme**:
   - Inline CSS for maximum compatibility
   - Table-based layouts for older clients
   - Limited color palette and fonts
   - No external dependencies

## Data Input Format
Expects JSON structure from DigestModules:
```json
{
    "digest": {
        "company": {"name": "string"},
        "period": {"start": "date", "end": "date"}
    },
    "modules": {
        "module_name": {
            "success": true,
            "heading": "string",
            "data": {} // Module-specific data
        }
    }
}
```

## HTML Output Features
- Responsive design (Bootstrap theme)
- Email compatibility (Email theme)
- Currency formatting with locale support
- Data tables with sorting capabilities
- Summary cards and statistics
- Print optimization
- Accessibility features (ARIA labels, semantic HTML)

## Integration
Works with:
- **DigestModules**: Source of JSON data
- **AbraFlexi-Digest**: Legacy system integration  
- **Pohoda-Digest**: Pohoda system integration
- **Email systems**: Direct HTML email sending
- **Web applications**: Embedded HTML reports