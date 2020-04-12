# Settings Manager

[![Build Status](https://travis-ci.org/wovosoft/settings-manager.svg?branch=master)](https://travis-ci.org/wovosoft/settings-manager)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/wovosoft/settings-manager/badge.svg?branch=master)](https://coveralls.io/github/wovosoft/settings-manager?branch=master)

[![Packagist](https://img.shields.io/packagist/v/wovosoft/settings-manager.svg)](https://packagist.org/packages/wovosoft/settings-manager)
[![Packagist](https://poser.pugx.org/wovosoft/settings-manager/d/total.svg)](https://packagist.org/packages/wovosoft/settings-manager)
[![Packagist](https://img.shields.io/packagist/l/wovosoft/settings-manager.svg)](https://packagist.org/packages/wovosoft/settings-manager)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require wovosoft/settings-manager
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="Wovosoft\SettingsManager\ServiceProvider" --tag="config"
```

## Usage
By Default all the Backend and Front-End integration is done out of the box. But if you still want to modifiy
the process, then please include the  Facade `Wovosoft\SettingsManager\Facades\Settings` in your
controller or view, and use it like the instructions explained in the interface below.
```php
 interface SettingsInterface
 {
     /**
      * @param number | string | array $key When Number (ID) is provided Query is operated by find() method, else by
      *                                      where('key',$key)->first(); for array returns key=>value paired aray
      * @param string|null $group
      * @param bool $getModel If true returns Model instead value
      * @return string | array | null
      */
     public function get($key, string $group = null, bool $getModel = false);

     /**
      * @param string | number $key Should be unique value. NOTE: Do not use number for creating new Settings.
      *                      Only use it when updating. Otherwise, the numeric value will be used as key's value
      * @param mixed $value mixed Arrays will be parsed as JSON
      * @param string|null $group Group Name of the Settings Option
      * @param string $type bootstrap-vue form fields or any kind of fields you want for front-end. This doesn't
      *                      related to backend. In this package we are aiming bootstrap-vue framework for front-end.
      * @param array $options Options usable for Front-End Manipulation. Like Enum DataType
      * @param bool $getModel If true returns Model instead value
      * @param null $model Due the the issue of "key as numeric", $item Model parameter is added. The function
      *                  should get the model by id or check if it is instance of the Settings.php Model. If it is already a
      *                  model do not find it, just perform the operation
      * @return mixed
      */
     public function set($key, $value, string $group = null, $type = "b-form-input", $options = [], bool $getModel = false, $model = null);

     /**
      * @param array $item ['key'=>string | number,'value'=> mixed, 'group'=>string, type'=>string, 'options'=>string | array]
      * @param bool $getModel If true returns Model instead value
      * @return mixed
      */
     public function setArray($item = [], bool $getModel = false);

     /**
      * @param array $key_values [['key'=>string | number, 'value'=>mixed, 'group'=>string, type'=>string, 'options'=>string | array]]
      * @param bool $getModel If true returns Model instead value
      * @return bool
      */
     public function setBatch($key_values = [], bool $getModel = false);

     /**
      * @param string $key
      * @param string|null $group
      * @param bool $getModel If true returns Model instead value
      * @return bool
      */
     public function has($key, string $group = null, bool $getModel = false);

     /**
      * @param string | number $key number from ID and $key for key
      * @param string|null $group
      * @param bool $getModel If true returns Model instead value
      * @return bool Found && Deleted Condition applicable.
      */
     public function delete($key, string $group = null, bool $getModel = false);

     /**
      * @param string|null $group
      * @param bool $getModel If true returns Model instead value
      * @return mixed
      */
     public function all(string $group = null, bool $getModel = false);

     /**
      * @param bool $getModel
      * @return mixed
      */
     public function allGrouped(bool $getModel = false);
 }

```

## Security

If you discover any security related issues, please email narayanadhikary24@gmail.com
instead of using the issue tracker.

## Credits

- [Narayan Adhikary](https://github.com/wovosoft/settings-manager)
- [All contributors](https://github.com/wovosoft/settings-manager/graphs/contributors)

This package is bootstrapped with the help of
[wovosoft/crud](https://github.com/wovosoft/crud).
