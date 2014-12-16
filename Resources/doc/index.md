Getting Started
===============

## Prerequisites

This version of the bundle requires Symfony 2.4+.

## Installation

Installation is a quick, 3 step process:

1. Download the bundle using composer
2. Enable the bundle
3. Configure your application's config.yml
4. Configure the bundle (optional)

### Step 1: Download the bundle using composer

Install the Composer plugin `fxp/composer-asset-plugin` in global:

```bash
$ php composer.phar global require fxp/composer-asset-plugin:@dev
```

Add Sonatra BootstrapBundle in your composer.json:

```js
{
    "require": {
        "sonatra/bootstrap-bundle": "~1.0"
    }
}
```

Or tell composer to download the bundle by running the command:

```bash
$ php composer.phar update sonatra/bootstrap-bundle
```

Composer will install the bundle to your project's `vendor/sonatra` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sonatra\Bundle\BootstrapBundle\SonatraBootstrapBundle(),
    );
}
```

### Step 3: Configure your application's config.yml

Add the following configuration to your `config.yml`.

```yaml
# app/config/config.yml
assetic:
    bundles: [ "SonatraBootstrapBundle" ]
    filters:
        cssrewrite: ~
        cssmin: ~
```

### Step 4: Configure the bundle (optionnal)

You can override the default configuration adding `sonatra_bootstrap` tree in `app/config/config.yml`.
For see the reference of Sonatra Bootstrap Configuration, execute command:

```bash
$ php app/console config:dump-reference SonatraBootstrapBundle 
```

### Next Steps

Now that you have completed the basic installation and configuration of the
Sonatra BootstrapBundle, you are ready to learn about usages of the bundle.
