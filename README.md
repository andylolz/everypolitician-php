# everypolitician-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A Python library for easy access to EveryPolitician data. This is essentially a port of [everypolitician-python](https://github.com/everypolitician/everypolitician-python), which is itself a port of [everypolitican-ruby](https://github.com/everypolitician/everypolitician-ruby).

## Install

Via Composer

``` bash
$ composer require andylolz/everypolitician
```

## Usage

Creating an instance of the EveryPolitican class allows you to access information on countries, their legislatures and legislative periods. Each country and legislature has a slug that can be used to reference them via the country and legislature methods:

``` php
use \EveryPolitician\EveryPolitician\EveryPolitician
$ep = new EveryPolitician();

$australia = $ep->country('Australia');
$senate = $australia->legislature('Senate');
echo (string) $senate; // <Legislature: Senate in Australia>

$uk = $ep->country('UK');
$houseOfCommons = $uk->legislature('Commons');

$americanSamoa = $ep->country('American-Samoa');
$houseOfRepresentatives = $americanSamoa->legislature('House');

foreach ($ep->countries() as $country) {
    echo $country->name.' has '.count($country->legislatures()).'legislatures';
}
```

By default this will get the EveryPolitician data and returns the most recent data. This data is found from the index file, called `countries.json`, which links to specific versions of other data files.

If you want want to point to a different `countries.json` file, you can override the default URL using `::fromUrl`:

``` php
$ep = EveryPolitician::fromUrl('https://cdn.rawgit.com/everypolitician/everypolitician-data/080cb46/countries.json');
```

The example above is using a specific commit (indicated by the hash `080cb46`). If you want to use a local copy of `countries.json` you can instead create the object using the `::fromFilename` method, e.g.:

``` php
$ep = EveryPolitician::fromFilename('/home/andy/tmp/countries.json');
```

For more about `countries.json`, see [this description](http://docs.everypolitician.org/repo_structure.html).

Remember that EveryPolitician data is frequently updated — see this information about [using EveryPolitician data](http://docs.everypolitician.org/use_the_data.html).

More information on [the EveryPolitician site](http://docs.everypolitician.org/).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/andylolz/everypolitician.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/andylolz/everypolitician-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/andylolz/everypolitician-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/andylolz/everypolitician-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/andylolz/everypolitician.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/andylolz/everypolitician
[link-travis]: https://travis-ci.org/andylolz/everypolitician-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/andylolz/everypolitician-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/andylolz/everypolitician-php
[link-downloads]: https://packagist.org/packages/andylolz/everypolitician
[link-author]: https://github.com/andylolz
[link-contributors]: https://github.com/andylolz/everypolitician-php/contributors
