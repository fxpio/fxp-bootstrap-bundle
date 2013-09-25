Getting Started With Sonatra BootstrapBundle
============================================

## Prerequisites

This version of the bundle requires Symfony 2.3+.

## Installation

Installation is a quick, 2 step process:

1. Download Sonatra BootstrapBundle using composer
2. Enable the bundle
3. Configure the bundle (optionnal)

### Step 1: Download Sonatra BootstrapBundle using composer

Add Sonatra BootstrapBundle in your composer.json:

``` js
{
    "require": {
        "sonatra/bootstrap-bundle": "~1.0"
    }
}
```

Or tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update sonatra/bootstrap-bundle
```

Composer will install the bundle to your project's `vendor/sonatra` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sonatra\Bundle\BootstrapBundle\SonatraBootstrapBundle(),
    );
}
```

### Step 3: Configure the bundle (optionnal)

You can override the default configuration adding `sonatra_bootstrap` tree in `app/config/config.yml`.
For see the reference of Sonatra Bootstrap Configuration, execute command:

``` bash
$ php app/console config:dump-reference SonatraBootstrapBundle 
```

### Next Steps

Now that you have completed the basic installation and configuration of the
Sonatra BootstrapBundle, you are ready to learn about usages of the bundle.
