# Zasilkovna client in PHP using SOAP or REST API

[![Integrity check](https://github.com/Salamek/zasilkovna/actions/workflows/main.yml/badge.svg)](https://github.com/Salamek/zasilkovna/actions/workflows/main.yml)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=D8LQ4XTBLV3C4&lc=CZ&item_number=SalamekZasilkovna&currency_code=EUR)

This library provides SOAP and REST API implementations.
Additionaly Branch implementation to fetch and store branch data and label implementation to generate labels.

## Installation

Install salamek/zasilkovna using  [Composer](http://getcomposer.org/)

```sh
$ composer require salamek/zasilkovna
```

or if you want master branch code:

```sh
$ composer require salamek/zasilkovna
```

## Documentation

See http://www.zasilkovna.cz/popis-api/ for more info

## Example code

```php
require "vendor/autoload.php";

$api = new Salamek\Zasilkovna\ApiRest($apiKey);
// OR Soap implementation $api = new Salamek\Zasilkovna\ApiSoap($apiKey);
$branch = new Branch($apiKey, new BranchStorageSqLite()); // There are multiple implementations of IBranchStorage BranchStorageSqLite using SQLite, BranchStorageFile using file in /tmp and BranchStorageMemory using simple variable (SLOW), You can implement your own by implementing IBranchStorage interface
$label = new Label($api, $branch);

// To greate new packet
$transporterPackage = new PacketAttributes(
	'ORDERID',
	'FirstName',
	'LastName',
	null,
	'addressId',
	null,
	'Company',
	'Email',
	'Phone',
	null,
	null,
	null,
	'www',
	false,
	'Street',
	'StreetNumber',
	'City',
	'ZipCode'
);

$api->createPacket($transporterPackage);

// Generate A4 label
$label->generateLabelFull($pdf, $transporterPackage);

// Generate A2 label
$label->generateLabelQuarter($pdf, $transporterPackage);

// Get full branch list as array
$branch->getBranchList();

// Returns branch detail by ID
$branch->find($branchId);
```
