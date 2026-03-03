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

## 🔧 Development Guidelines

### Coding Standards
- **PHP 8.1+**: Use modern PHP features and strict types: `declare(strict_types=1);`
- **PSR-12**: Follow PHP-FIG coding standards for consistency
- **Type Safety**: Include type hints for all parameters and return types
- **Documentation**: PHPDoc blocks for all public methods and classes
- **Testing**: PHPUnit tests for all new functionality
- **Internationalization**: Use `_()` functions for translatable strings

### Code Quality Requirements
- **Syntax Validation**: After every PHP file edit, run `php -l filename.php` for syntax checking
- **HTML Safety**: Sanitize all data before HTML output to prevent XSS
- **Responsive Design**: Implement mobile-first responsive CSS for web themes
- **Email Compatibility**: Use inline CSS and table-based layouts for email themes
- **Performance**: Optimize rendering for large datasets and multiple modules
- **Accessibility**: Implement ARIA labels and semantic HTML

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

## ⚠️ Important Notes for Copilot

1. **Presentation Layer Only**: This library focuses solely on HTML rendering - no data processing
2. **Theme Flexibility**: Support multiple themes for different use cases (web, email, print)
3. **Data Safety**: Always sanitize input data before HTML output
4. **Email Compatibility**: Use table-based layouts and inline CSS for email themes
5. **Responsive Design**: Implement mobile-first approach for web themes
6. **Performance**: Optimize rendering for large datasets and multiple modules

### Development Best Practices
- **Code Comments**: Write in English using complete sentences and proper grammar
- **Variable Names**: Use meaningful names that describe their purpose
- **Constants**: Avoid magic numbers/strings; define constants instead
- **Exception Handling**: Always provide meaningful error messages
- **Commit Messages**: Use imperative mood and keep them concise
- **Security**: Ensure code is secure and doesn't expose sensitive information
- **Compatibility**: Maintain compatibility with latest PHP and library versions
- **Testing**: Create/update PHPUnit test files for all new/modified classes
- **Maintainability**: Follow best practices for maintainable code

### Testing Requirements
- **PHPUnit Integration**: All new classes require corresponding test files
- **Test Coverage**: Aim for comprehensive test coverage of all functionality
- **Mock Usage**: Use mocks for external dependencies during testing
- **Test Structure**: Follow PSR-12 coding standards in test files

### CSS/HTML Guidelines
- **Web Themes**: Use modern CSS features (Grid, Flexbox) with fallbacks
- **Email Themes**: Use table-based layouts with inline CSS only
- **Responsive**: Mobile-first design with proper breakpoints
- **Accessibility**: Include ARIA labels, semantic HTML, proper color contrast
- **Performance**: Optimize CSS delivery, minimize DOM complexity

When working with this codebase:
- Always implement theme and renderer interfaces when creating new components
- Use the AbstractTheme base class for common functionality
- Follow email-safe HTML/CSS practices for email themes
- Include comprehensive error handling and validation
- Add PHPUnit tests for new themes and renderers
- **Email systems**: Direct HTML email sending
- **Web applications**: Embedded HTML reports