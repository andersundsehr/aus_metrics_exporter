# What does it do?

This extension provides a metrics collector for your TYPO3 application and exposes an endpoint that outputs the collected metrics in a format compatible with Prometheus. This allows you to monitor application performance and behavior using Prometheus or similar monitoring tools.

## Code Example: Collecting Metrics

Here's how to inject the `CollectorService` into your own class and collect metrics:

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyExtension\Service;

use AUS\MetricsExporter\Service\CollectorService;

class MyCustomService
{
    public function __construct(
        private readonly CollectorService $collectorService
    ) {
    }

    public function doSomething(): void
    {
        // Collect a gauge metric (represents a value that can go up and down)
        $gauge = $this->collectorService->getOrRegisterGauge(
            'my_extension_prefix',
            'my_custom_gauge',
            'Description of my custom gauge metric',
            ['label1', 'label2'] // Optional labels
        );
        $gauge->set(42.5, ['value1', 'value2']); // Set gauge value with label values

        // Collect a counter metric (represents a value that only increases)
        $counter = $this->collectorService->getOrRegisterCounter(
            'my_extension_prefix',
            'my_custom_counter',
            'Description of my custom counter metric',
            ['status'] // Optional labels
        );
        $counter->inc(['success']); // Increment counter by 1
        $counter->incBy(5, ['error']); // Increment counter by 5
    }
}
```

# Configuration

1. Install the extension via composer:

```bash
composer require andersundsehr/metrics_exporter
```

2. Optional: Define the data endpoint in your site configuration:

```YAML
routeEnhancers:
  PageTypeSuffix:
    type: PageType
    map:
      metrics.txt: 1717676395
```

3. Database configuration if wanted, see below.

## Database Configuration

All tables of the extension must remain in the same database connection. However, that connection can be changed by configuring the tables to use a different database driver.

The extension creates a new connection using PDO.

### Extension Tables

The following tables are used by the metrics exporter extension:
- `tx_metricsexporter_histograms`
- `tx_metricsexporter_metadata`
- `tx_metricsexporter_summaries`
- `tx_metricsexporter_values`

### Example: Using PostgreSQL Connection

To use a different database connection (e.g., PostgreSQL), configure it in your `LocalConfiguration.php` or `AdditionalConfiguration.php`:

```php
// Define the PostgreSQL connection
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['postgres'] = [
    'driver' => 'pdo_pgsql',
    'host' => 'postgres',
    'port' => 5432,
    'dbname' => 'postgres',
    'user' => 'postgres',
    'password' => 'password',
    'charset' => 'utf8',
];

// Map all extension tables to the PostgreSQL connection
$GLOBALS['TYPO3_CONF_VARS']['DB']['TableMapping'] = [
    'tx_metricsexporter_histograms' => 'postgres',
    'tx_metricsexporter_metadata' => 'postgres',
    'tx_metricsexporter_summaries' => 'postgres',
    'tx_metricsexporter_values' => 'postgres',
];
```

# Visibility

Remember that metrics may contain sensitive data and should be protected from public access.

# TODO

Support more storage adapters, at least redis!
