monolog-bigquery-handler
===========================

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
$monolog->pushHandler(new BigQuery\Handler\BigQueryHandler($client_email, $private_key, $project_id, $dataset_id, $table_id, $level = Logger::DEBUG));
```

#### Full example
```php
$monolog = new Logger('TestLog');
$monolog->pushHandler(new BigQueryHandler($client_email, $private_key, $project_id, $dataset_id, $table_id, $level = Logger::DEBUG));
$monolog->addWarning('This is a warning logging message');
```
