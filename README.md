monolog-bigquery-handler
===========================

Current Version: 0.0

## Installation

Add the following to your composer.json and run `composer update`

```json
{
    "require": {
        "hkar/monolog-bigquery-handler": "master"
    }
}
```

## Usage

```php
$monolog->pushHandler(new Logentries\Handler\BigQueryHandler('YOUR_TOKEN'));
```

#### Full example
```php
$monolog = new Logger('TestLog');
$monolog->pushHandler(new BigQueryHandler('YOUR_TOKEN'));
$monolog->addWarning('This is a warning logging message');
```
