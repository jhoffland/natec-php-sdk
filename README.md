# Natec Klantportaal API Client Library for PHP


[![Run tests](https://github.com/jhoffland/natec-php-sdk/actions/workflows/testing.yml/badge.svg)](https://github.com/jhoffland/natec-php-sdk/actions/workflows/testing.yml)
[![codecov](https://codecov.io/gh/jhoffland/natec-php-sdk/branch/main/graph/badge.svg?token=L2QKKTY5G4)](https://codecov.io/gh/jhoffland/natec-php-sdk)

## Usage

```php
use NatecSdk\Client;
use NatecSdk\Resources\Types\AssortmentUpdateType;
use NatecSdk\Resources\AssortmentUpdate;
use NatecSdk\Resources\Invoice;
use NatecSdk\Resources\Shipment;

$apiToken = 'xxx';
$client = new Client($apiToken);

// Get an iterator with all resources, matching the filter (query).
$assortmentUpdates = AssortmentUpdate::get($client, [
    'type' => AssortmentUpdateType::PRODUCT_EXPECTED_ARRIVAL->value
]);

foreach($assortmentUpdates as $assortmentUpdate) {
    var_dump($assortmentUpdate);
}

// Get one resource, by the primary key value (id). The primary key for e.g. invoices is documentNo.
var_dump(Invoice::find($client, 'GVFN22-12345'));

// Save an order confirmation PDF file.
$confirmationFile = fopen(__DIR__ . sprintf('/confirmation-%s.pdf', $order->no), 'w+');
$order->confirmation($client, $confirmation);

// Make an API request
var_dump($client->get(Shipment::endpoint()));
var_dump($client->post('/orders', [ 'reference' => 'Example order' ]));
```


## Contributing

Feel free to contribute to this library. Contribute by forking the GitHub repository and opening a pull request.
When opening a pull request, please make sure that:

* The pull request has a clear title;
* The pull request does not consist of too many (unnecessary/small) commits;
* Tests are added or updated to test the added/improved functionality;
* The code complies to the coding standard, passes the code analysis and passes all the tests (`composer run-tests`):
  * PHP_CodeSniffer (`vendor/bin/phpcs`);
  * PHPStan (`vendor/bin/phpstan`);
  * PHPUnit (`vendor/bin/phpunit`).


## ToDo's

- [ ] Give every assertion in the tests a descriptive message.
- [ ] Add a test for `NatecSdk\Client::getPdf()` and `NatecSdk\Resources\Order::confirmation()`
- [ ] Add a test for every resource?
