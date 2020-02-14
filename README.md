# wp-scan

Scan a URL to detect and retrieve WordPress data.

## Checks

To date, only two types of checks are done: `endpoints` and `version`

You can modify the checks by passing in the configuration to the constructor or using the `check()` method:

```php
$checks = ['endpoints'=>false, 'version' => true];
$scan = new WpScan($url, $checks);

// OR
$scan = new WpScan($url);
$scan->checks($checks);
```

## Usage
Run your checks with `$scan->run()` and retrieve your results with `$scan->get()`. You can also retrieve as Json with `$scan->json()`.