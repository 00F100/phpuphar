# PHPUPhar

## Instalation

```
$ composer require 00f100/phpuphar
```

or add in your composer.json

```
"require": {
    "00f100/phpuphar": "*"
},
```

## Usage

```php
# Url to view current VERSION in GIT
$urlUpdate = array(
	'base' => 'https://example.com',
	'path' => '/path/to/version',
);

# Version current
$version = '1.0.0';

# Url to download Phar file
$urlDownloadPhar = 'https://github.com/00F100/phpatr/raw/master/dist/phpatr.phar';

# Name of phar file
$namePhar = 'example.phar';

# New instance of PHPUPhar
use PHPUPhar\PHPUPhar;
$selfUpdate = new PHPUPhar($urlUpdate, false, $version, $urlDownloadPhar, $namePhar);

# Test number of version
if ($version != $selfUpdate->getVersion() && $selfUpdate->update()) {
    # Your version has updated ...
}

```