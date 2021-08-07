# SOCS PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fredbradley/socs-php.svg?style=flat-square)](https://packagist.org/packages/fredbradley/socs-php)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/fredbradley/socs-php/run-tests?label=tests)](https://github.com/fredbradley/socs-php/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/fredbradley/socs-php/Check%20&%20fix%20styling?label=code%20style)](https://github.com/fredbradley/socs-php/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/fredbradley/socs-php.svg?style=flat-square)](https://packagist.org/packages/fredbradley/socs-php)

This is a PHP wrapper for the [SOCS](https://misocs.com) XML feeds. 

## If you find this useful...

This package was developed by [Fred Bradley](https://twitter.com/fredbradley), a developer at [Cranleigh School](https://www.cranleigh.org) and [Cranleigh Prep School](https://www.cranprep).
If you find it useful, I'd really appreciate [a tweet](https://twitter.com/fredbradley) to let me know. Or even better, feel free to fund further developments.
## Installation

You can install the package via composer:

```bash
composer require fredbradley/socs-php
```

## Example Usage

```php
$socs = new FredBradley\SOCS\Calendar($socsId, $apiKey);
$xmlObject = $socs->getCalendar(\Carbon\Carbon::today(), \Carbon\Carbon::tomorrow()); // will get all the calendar items between today and tomorrow.
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
