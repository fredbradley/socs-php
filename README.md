# SOCS PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fredbradley/socs-php.svg?style=flat-square)](https://packagist.org/packages/fredbradley/socs-php)
[![Tests](https://github.com/fredbradley/socs-php/actions/workflows/pest.yml/badge.svg)](https://github.com/fredbradley/socs-php/actions/workflows/pest.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/fredbradley/socs-php.svg?style=flat-square)](https://packagist.org/packages/fredbradley/socs-php)


This is a PHP wrapper for the [SOCS](https://misocs.com) XML feeds. 

## If you find this useful...

This package was developed by [Fred Bradley](https://twitter.com/fredbradley), a developer at [Cranleigh School](https://www.cranleigh.org) and [Cranleigh Prep School](https://www.cranprep.org).
If you find it useful, I'd really appreciate [a tweet](https://twitter.com/fredbradley) to let me know. Or even better, feel free to fund further developments.
## Installation

You can install the package via composer:

```bash
composer require fredbradley/socs-php
```

## Example Usage

```php
$config = new FredBradley\SOCS\Config($socsId, $apiKey);
$socs = new FredBradley\SOCS\CoCurricular($config);

// Get All Clubs from CoCurricular
$clubs = $socs->getClubs();
// Get all Events from CoCurricular
$events = $socs->getEvents();

// ... more documentation to follow
```
Full docs will be released soon.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fred Bradley](https://github.com/fredbradley)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
