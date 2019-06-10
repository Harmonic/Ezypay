# Ezypay

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

A Laravel/PHP SDK for the Ezypay v2 API. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require harmonic/ezypay
```

Publish config file

``` bash
php artisan vendor:publish --provider="harmonic\Ezypay"
```

## Usage

Add Ezypay credentials to your .env file

```
EZY_PAY_API_URL_SANDBOX=https://api-sandbox.ezypay.com
EZY_PAY_API_URL=https://api.ezypay.com/
EZY_PAY_USER=your@email.com
EZY_PAY_PASSWORD=PASSWORD
EZY_PAY_API_CLIENT_ID=YOUR_CLIENT_ID
EZY_PAY_CLIENT_SECRET=YOUR_SECRET
EZY_PAY_MERCHANT_ID=YOUR_MERCHANT_ID
MIX_VAULT_URL=https://vault-sandbox.ezypay.com
```

All Ezypay API mthods are availble via the Ezypay facade.

Simply call

```
Ezypay::methodName
```
Where methodName is any method from https://developer.ezypay.com/reference
eg. createCustomer()

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email craig@harmonic.com.au instead of using the issue tracker.

## Credits

- [Craig Harman][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/harmonic/ezypay.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/harmonic/ezypay.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/harmonic/ezypay/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/harmonic/ezypay
[link-downloads]: https://packagist.org/packages/harmonic/ezypay
[link-travis]: https://travis-ci.org/harmonic/ezypay
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/harmonic
[link-contributors]: ../../contributors
