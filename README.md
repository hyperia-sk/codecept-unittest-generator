# Codecept UnitTest generator

This package allows you to generate PHPUnit tests from annotations, which you can write in your methods documentation.

- Basic usage of this package is to generate your tests skeleton and basic tests.
- You must check and complete all tests you generate, including the most basic methods.
- Files to parse must declare one class, abstract class, trait or interface to be accepted.

![screenshot_2017-12-08_17-12-32-github](https://user-images.githubusercontent.com/6382002/33774260-0aaacebc-dc3b-11e7-8a97-34265a4818cc.png)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```shell
composer require hyperia/codecept-unittest-generator:"^1.0"
```

or add

```
"hyperia/codecept-unittest-generator": "^1.0"
```

to the require section of your composer.json.

## Configuration

enable `UnitGenerator` extension in base `/codeception.yml` config file:

```yaml
extensions:
    enabled:
        - Codeception\Extension\RunFailed
    commands:
        - Codeception\Command\UnitGenerator
unitgenerator:
    config: 
        # erase old target files with new one
        overwrite: true 
        # if you want to generate tests for Interface too
        interface: false
        # automaticaly generate tests for getter / setter method
        auto: true
        # ignore errors that can be ignored
        ignore: true
        # regex (/.*config.php/ for example) that files must not match to have a tests generation
        exclude: '/.*Trait.*/'
        # regex (/.*.php/ for example) that files should match to have a tests generation
        include: '/.*.php/'
    dirs:
        # source directory => target directory
        - common/models: tests/unit/unitgen/common/models/
        - console/models: tests/unit/unitgen/console/models/
```

## Usage 

`./vendor/bin/codecept generate:unit`

### Annotations

```php
/**
 * @PHPUnitGen\<phpunit_assertion_method>(<expectation>:{<parameters...>}) 
 */
```

This annotation use some parameters:

* __phpunit_assertion_method__: It is the PHPUnit assertion method
you want ot use (like _assertEquals_, _assertInstanceOf_, _assertTrue_ ...).

* __expectation__: The method return expected value. Some PHPUnit methods
does not need it (like _assertTrue_), so in those cases, it can be null.

* __parameters__: The method parameters.

See this example, we want to auto generate some simple test for this method:

```php
<?php
// The class to test content

/** @PHPUnitGen\AssertEquals('expected string':{1, 2, 'a string'}) */
/** @PHPUnitGen\AssertTrue(:{4, 5, 'another'}) */
/** @PHPUnitGen\AssertEquals(null) */
/** @PHPUnitGen\AssertNull() */
public function method(int $arg1 = 0, int $arg2 = 0, string $arg3 = 'default') {}
```

Those annotations will create basic PHPUnit tests:

```php
<?php
// The generated test for "method" in tests class

$this->assertEquals('expectation string', $this->method(1, 2, 'a string'));
$this->assertTrue($this->method(4, 5, 'another'));
$this->AssertEquals(null, $this->method());
$this->assertNull($this->method());
```

#### Getter and setter annotation

```php
<?php
// The class to test content

/** @PHPUnitGen\Get() */
/** @PHPUnitGen\Set() */
```

Those two annotations will allow you to auto-generate tests for simple getter / setter.
Your getter / setter needs to be named like the following:

```
get<MyProperty>() {}
set<MyProperty>() {}
```

PHPUnit Generator has an "auto" option: If you activate it, 
it will search for the property when it find a method beginning 
with "get" or "set", and if the property exists, it will generate tests.

#### Private or protected method

If the method to test is private or protected, PHPUnit Generator will access the method with PHP ReflectionClass.


## Requirements

UnitGenerator needs the following components to run:

- Codeception UnitGenerator is a module for **Codeception**. It will need a running version of this tool.
- [Phpunit-generator](https://github.com/paul-thebaud/phpunit-generator) package.
