---
description: DigestRenderer library for converting JSON analytics data to themed HTML
applyTo: '**'
---

# DigestRenderer Library - Copilot Instructions

## Project Overview
DigestRenderer is the **presentation layer** that converts structured JSON analytics data into beautiful, themed HTML output:
- **Multiple Themes**: Bootstrap (modern web), Email (client-compatible), Custom themes
- **Module-Specific Rendering**: Smart renderers for different analytics data types  
- **System-Agnostic**: Works with any JSON data following DigestModules format
- **Responsive Design**: Mobile-first approach with modern CSS
- **Email-Safe HTML**: Compatible with email clients and newsletter systems

## 🎨 Core Architecture
This library implements a **theme-based rendering system** where:
- **Themes** = Complete HTML/CSS frameworks (Bootstrap, Email, Custom)
- **Module Renderers** = Specialized HTML generators for specific data types
- **DigestRenderer** = Main orchestrator that combines themes with data
- **Factory Pattern** = Automatic renderer selection based on module type

## 📋 Key Interfaces & Components

### Core Contracts (`src/`)
- **ThemeInterface**: Contract for all HTML themes
  - `renderModule(array $moduleData)`: Convert JSON to themed HTML
  - `getThemeName()`: Return theme identifier (e.g., 'bootstrap', 'email')
  - `renderPage(array $modules)`: Generate complete HTML page
  - `getCSS()`: Return theme-specific CSS styles

- **ModuleRendererInterface**: Contract for data-specific renderers  
  - `canRender(array $data)`: Check if renderer supports this data type
  - `render(array $data)`: Convert module JSON to HTML fragment
  - `getModuleType()`: Return supported module type

### Built-in Themes (`src/Themes/`)
- **BootstrapTheme**: Modern, responsive web theme using Bootstrap 5
  - Mobile-first responsive design
  - Interactive tables and charts
  - Modern color scheme and typography
  - Print-friendly CSS

- **EmailTheme**: Email client compatible theme
  - Inline CSS for maximum compatibility
  - Table-based layouts (no CSS Grid/Flexbox)
  - Safe color palette and fonts
  - Outlook/Gmail/Apple Mail tested

- **AbstractTheme**: Base implementation with common functionality
  - HTML escaping and sanitization
  - Date/number formatting helpers
  - Common template methods

### Module Renderers (`src/Renderers/`)  
- **ModuleRendererFactory**: Auto-selects appropriate renderer
- **OutcomingInvoicesRenderer**: Specialized for invoice analytics
- **DebtorsRenderer**: Specialized for overdue receivables  
- **GenericModuleRenderer**: Fallback for unknown module types

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