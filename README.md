README
======

YepsuaGeneratorBundle by @oyepez003
---------------------

The RICH CRUD Generator for Symfony2.

The generate:doctrine:richcrud generates a basic controller for a given entity located in a given bundle. 
This controller allows to perform the five basic operations on a model.

    Listing all records with pager and filters,
    Showing one given record identified by its primary key,
    Creating a new record,
    Editing an existing record,
    Deleting an existing record.

By default the command is run in the interactive mode and asks questions to determine the entity name, the route prefix or whether or not to generate write actions:

``` bash
php app/console generate:doctrine:richcrud
```

To deactivate the interactive mode, use the --no-interaction option but don't forget to pass all needed options:

``` bash
php app/console generate:doctrine:richcrud --entity=AcmeBlogBundle:Post --format=annotation --with-write --no-interaction
```

## Installation

Download and make sure you have the composer.phar latest version running the command:

``` bash
$ php composer.phar self-update
```

Add the next dependency in the composer.json file

``` yml
"require": {
        ...
        "yepsua/generator-bundle": "dev-master"
        ...
    },
```

### For install and up to date the bundle

``` bash
$ php composer.phar update yepsua/generator-bundle
```

# Configuration

## 1) Add the required Bundles to your application kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        ...
        new Yepsua\SmarTwigBundle\YepsuaSmarTwigBundle(),
        new Yepsua\CommonsBundle\YepsuaCommonsBundle(),
        new Yepsua\GeneratorBundle\YepsuaGeneratorBundle(),
        ...
    );
}
```

## 2) Publishing assets

Run the symfony command

``` bash
$ php app/console assets:install web
```

## 3) Run

The next command and follow the steps:

``` bash
$ php app/console generate:doctrine:richcrud
```

Enjoy
