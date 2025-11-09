# DigestRenderer - Theme Development Guide

## Creating Custom Themes

### 1. Basic Theme Structure

```php
<?php declare(strict_types=1);

namespace YourApp\Themes;

use VitexSoftware\DigestRenderer\Core\AbstractTheme;

class CustomTheme extends AbstractTheme
{
    protected string $themeName = 'custom';
    protected string $themeTitle = 'Custom Business Theme';

    public function renderModule(array $moduleData): string
    {
        // Validate input data
        $this->validateModuleData($moduleData);
        
        // Get appropriate renderer for this module type
        $renderer = $this->getModuleRenderer($moduleData);
        
        // Generate HTML content
        $content = $renderer->render($moduleData);
        
        // Wrap in theme layout
        return $this->wrapInLayout($content, $moduleData);
    }

    public function renderPage(array $modules): string
    {
        $html = $this->getPageHeader();
        
        foreach ($modules as $moduleData) {
            $html .= $this->renderModule($moduleData);
        }
        
        $html .= $this->getPageFooter();
        
        return $html;
    }

    public function getCSS(): string
    {
        return '
        .custom-theme {
            font-family: "Arial", sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        
        .module-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            padding: 24px;
        }
        
        .module-header {
            border-bottom: 2px solid #667eea;
            margin-bottom: 16px;
            padding-bottom: 12px;
        }
        
        .module-title {
            color: #667eea;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin: 20px 0;
        }
        
        .summary-item {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 16px;
            border-radius: 4px;
        }
        
        .summary-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 4px;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .details-table th,
        .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .details-table th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        
        .details-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        @media (max-width: 768px) {
            .module-container {
                margin: 10px;
                padding: 16px;
            }
            
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .details-table {
                font-size: 14px;
            }
        }
        ';
    }

    protected function wrapInLayout(string $content, array $moduleData): string
    {
        return sprintf(
            '<div class="custom-theme">
                <div class="module-container">
                    <div class="module-header">
                        <h2 class="module-title">%s</h2>
                        <div class="module-meta">Generated: %s</div>
                    </div>
                    %s
                </div>
            </div>',
            $this->escapeHtml($moduleData['heading'] ?? 'Analytics Report'),
            $this->formatDateTime($moduleData['metadata']['generated_at'] ?? date('c')),
            $content
        );
    }

    protected function getPageHeader(): string
    {
        return sprintf(
            '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Business Analytics Report</title>
                <style>%s</style>
            </head>
            <body class="custom-theme">',
            $this->getCSS()
        );
    }

    protected function getPageFooter(): string
    {
        return '</body></html>';
    }
}
```

### 2. Creating Module-Specific Renderers

```php
<?php declare(strict_types=1);

namespace YourApp\Renderers;

use VitexSoftware\DigestRenderer\Core\ModuleRendererInterface;

class CustomInvoiceRenderer implements ModuleRendererInterface
{
    public function canRender(array $data): bool
    {
        return isset($data['module']) && 
               in_array($data['module'], ['outcoming_invoices', 'incoming_invoices']);
    }

    public function getModuleType(): string
    {
        return 'invoice_analysis';
    }

    public function render(array $data): string
    {
        $html = $this->renderSummary($data['summary'] ?? []);
        $html .= $this->renderInvoiceChart($data['details'] ?? []);
        $html .= $this->renderDetailsTable($data['details'] ?? []);
        
        return $html;
    }

    private function renderSummary(array $summary): string
    {
        return sprintf(
            '<div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Amount</div>
                    <div class="summary-value">%s %s</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Invoice Count</div>
                    <div class="summary-value">%d</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Processing Time</div>
                    <div class="summary-value">%.3fs</div>
                </div>
            </div>',
            number_format($summary['total_amount'] ?? 0, 2),
            $summary['currency'] ?? 'USD',
            $summary['count'] ?? 0,
            $summary['processing_time'] ?? 0
        );
    }

    private function renderInvoiceChart(array $details): string
    {
        // Simple ASCII bar chart for email compatibility
        $chartData = $this->prepareChartData($details);
        
        $html = '<div class="chart-container"><h3>Invoice Distribution</h3>';
        
        foreach ($chartData as $item) {
            $barWidth = $this->calculateBarWidth($item['value'], $chartData);
            $html .= sprintf(
                '<div class="chart-bar">
                    <span class="chart-label">%s</span>
                    <div class="chart-bar-bg">
                        <div class="chart-bar-fill" style="width: %d%%"></div>
                    </div>
                    <span class="chart-value">%s</span>
                </div>',
                htmlspecialchars($item['label']),
                $barWidth,
                number_format($item['value'], 2)
            );
        }
        
        $html .= '</div>';
        return $html;
    }

    private function renderDetailsTable(array $details): string
    {
        if (empty($details)) {
            return '<p>No details available.</p>';
        }

        $html = '<table class="details-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Invoice #</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($details as $invoice) {
            $html .= sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s %s</td>
                    <td>%s</td>
                    <td><span class="status status-%s">%s</span></td>
                </tr>',
                htmlspecialchars($invoice['customer'] ?? ''),
                htmlspecialchars($invoice['invoice_number'] ?? ''),
                number_format($invoice['amount'] ?? 0, 2),
                htmlspecialchars($invoice['currency'] ?? 'USD'),
                $this->formatDate($invoice['date'] ?? ''),
                strtolower($invoice['status'] ?? 'unknown'),
                ucfirst($invoice['status'] ?? 'Unknown')
            );
        }

        $html .= '</tbody></table>';
        return $html;
    }

    private function prepareChartData(array $details): array
    {
        $customerTotals = [];
        
        foreach ($details as $invoice) {
            $customer = $invoice['customer'] ?? 'Unknown';
            $amount = $invoice['amount'] ?? 0;
            
            if (!isset($customerTotals[$customer])) {
                $customerTotals[$customer] = 0;
            }
            $customerTotals[$customer] += $amount;
        }
        
        arsort($customerTotals);
        
        $chartData = [];
        $count = 0;
        foreach ($customerTotals as $customer => $total) {
            if ($count >= 10) break; // Top 10 customers only
            
            $chartData[] = [
                'label' => $customer,
                'value' => $total
            ];
            $count++;
        }
        
        return $chartData;
    }

    private function calculateBarWidth(float $value, array $allData): int
    {
        $maxValue = max(array_column($allData, 'value'));
        return $maxValue > 0 ? (int) (($value / $maxValue) * 100) : 0;
    }

    private function formatDate(string $date): string
    {
        try {
            return (new \DateTime($date))->format('M j, Y');
        } catch (\Exception $e) {
            return $date;
        }
    }
}
```

## Email Theme Best Practices

### Email-Compatible CSS

```css
/* Use table-based layouts for maximum compatibility */
.email-table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    background-color: #ffffff;
}

/* Inline styles are preferred, but CSS can be used as fallback */
.email-header {
    background-color: #2c5282;
    color: #ffffff;
    padding: 20px;
    text-align: center;
}

.email-content {
    padding: 20px;
    line-height: 1.6;
}

/* Use web-safe fonts */
.email-text {
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    font-size: 14px;
    color: #333333;
}

/* Avoid CSS Grid and Flexbox */
.email-grid {
    width: 100%;
}

.email-grid td {
    vertical-align: top;
    padding: 10px;
}

/* Use background colors instead of borders when possible */
.email-summary-item {
    background-color: #f7fafc;
    padding: 15px;
    margin: 10px 0;
    border-left: 4px solid #2c5282;
}
```

### Email Theme Implementation

```php
<?php
class EmailTheme extends AbstractTheme
{
    protected string $themeName = 'email';
    
    public function renderModule(array $moduleData): string
    {
        return sprintf(
            '<table class="email-table" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="email-header">
                        <h1 style="margin: 0; color: #ffffff; font-size: 24px;">%s</h1>
                    </td>
                </tr>
                <tr>
                    <td class="email-content">
                        %s
                    </td>
                </tr>
            </table>',
            htmlspecialchars($moduleData['heading'] ?? 'Report'),
            $this->renderModuleContent($moduleData)
        );
    }
    
    protected function renderModuleContent(array $moduleData): string
    {
        $html = $this->renderEmailSummary($moduleData['summary'] ?? []);
        $html .= $this->renderEmailDetails($moduleData['details'] ?? []);
        return $html;
    }
    
    private function renderEmailSummary(array $summary): string
    {
        $html = '<table width="100%" cellpadding="10" cellspacing="0">';
        
        foreach ($summary as $key => $value) {
            if (in_array($key, ['processing_time'])) continue; // Skip technical fields
            
            $label = ucwords(str_replace('_', ' ', $key));
            $formattedValue = $this->formatSummaryValue($key, $value);
            
            $html .= sprintf(
                '<tr style="background-color: #f7fafc;">
                    <td style="font-weight: bold; width: 30%%;">%s:</td>
                    <td>%s</td>
                </tr>',
                htmlspecialchars($label),
                htmlspecialchars($formattedValue)
            );
        }
        
        $html .= '</table>';
        return $html;
    }
    
    private function renderEmailDetails(array $details): string
    {
        if (empty($details)) {
            return '<p style="color: #666;">No details available.</p>';
        }
        
        $html = '<h3 style="color: #2c5282; margin-top: 30px;">Details</h3>';
        $html .= '<table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; border: 1px solid #e2e8f0;">';
        
        // Header
        $firstItem = reset($details);
        $html .= '<tr style="background-color: #2c5282; color: #ffffff;">';
        foreach (array_keys($firstItem) as $column) {
            $html .= sprintf('<th style="padding: 12px; text-align: left;">%s</th>', 
                           htmlspecialchars(ucwords(str_replace('_', ' ', $column))));
        }
        $html .= '</tr>';
        
        // Rows
        foreach ($details as $index => $row) {
            $bgColor = $index % 2 === 0 ? '#ffffff' : '#f7fafc';
            $html .= sprintf('<tr style="background-color: %s;">', $bgColor);
            
            foreach ($row as $value) {
                $html .= sprintf('<td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">%s</td>', 
                               htmlspecialchars($this->formatValue($value)));
            }
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }
    
    private function formatSummaryValue(string $key, mixed $value): string
    {
        if ($key === 'total_amount' && is_numeric($value)) {
            return number_format($value, 2);
        }
        
        if ($key === 'count' && is_numeric($value)) {
            return number_format($value);
        }
        
        return (string) $value;
    }
    
    private function formatValue(mixed $value): string
    {
        if (is_numeric($value) && strpos((string)$value, '.') !== false) {
            return number_format((float)$value, 2);
        }
        
        if (is_numeric($value)) {
            return number_format((int)$value);
        }
        
        return (string) $value;
    }
}
```

## Integration Examples

### Using DigestRenderer with DigestModules

```php
<?php
use VitexSoftware\DigestModules\Core\ModuleRunner;
use VitexSoftware\DigestRenderer\BootstrapTheme;
use YourApp\Themes\CustomTheme;

// Get analytics data
$moduleRunner = new ModuleRunner($dataProvider);
$invoiceData = $moduleRunner->runModule('outcoming_invoices');
$debtorData = $moduleRunner->runModule('debtors');

// Render with different themes
$bootstrapTheme = new BootstrapTheme();
$customTheme = new CustomTheme();

// Single module rendering
$webReport = $bootstrapTheme->renderModule($invoiceData);
$customReport = $customTheme->renderModule($invoiceData);

// Multi-module page
$allData = [$invoiceData, $debtorData];
$completePage = $bootstrapTheme->renderPage($allData);

// Save reports
file_put_contents('web_report.html', $webReport);
file_put_contents('custom_report.html', $customReport);
file_put_contents('complete_report.html', $completePage);
```

### Email Report Generation

```php
<?php
use VitexSoftware\DigestRenderer\EmailTheme;

$emailTheme = new EmailTheme();

// Generate email-compatible HTML
$emailContent = $emailTheme->renderPage([$invoiceData, $debtorData]);

// Send via email
$subject = 'Daily Business Analytics Report';
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: reports@yourcompany.com',
    'Reply-To: noreply@yourcompany.com'
];

mail('manager@yourcompany.com', $subject, $emailContent, implode("\r\n", $headers));
```

### Custom Theme Registration

```php
<?php
use VitexSoftware\DigestRenderer\Core\DigestRenderer;

$renderer = new DigestRenderer();

// Register custom theme
$renderer->registerTheme(new CustomTheme());

// Register custom module renderer
$renderer->registerModuleRenderer(new CustomInvoiceRenderer());

// Use the registered components
$html = $renderer->render($analyticsData, 'custom');
```