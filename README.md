README
======

YepsuaGeneratorBundle by @oyepez003
-----------------------------------

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
v1.0.0 for sf2.2.x or Old versions

v1.1.0 for sf2.3.x or Higher

Info: Use always the Last Stable Release of Symfony2.

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

## 4) Translation

Uncomment the translator in the configuration file ( config.yml ):

``` yaml
# app/config/config.yml

# ...
framework:
    # ...
    translator:      { fallback: %locale% }
```

Set the locale in the configuration file ( parameters.yml ):

``` yaml
# app/config/parameters.yml

parameters:
	# ...
    locale: en
```

Now, for each created module you need create a translation file for the managed entity.

If you run:

$ php app/console generate:doctrine:richcrud --entity:AcmeDemoBundle:Post

You must create the AcmeDemoBundle_Post.en.yml file in Acme/DemoBundle/Resources/translations 
to translate the Module for the Post Entity

``` xml
<?xml version="1.0"?>
<xliff version="1.2" xmlns="urn:oasis:names:tc:xliff:document:1.2">
    <file source-language="en" datatype="plaintext" original="file.ext">
        <body>
            <trans-unit id="entity.label">
                <source>entity.label</source>
                <target>Post</target>
            </trans-unit>
            <trans-unit id="list.view.title">
                <source>list.view.title</source>
                <target>Post Module</target>
            </trans-unit>
            <trans-unit id="list.view.grid.title">
                <source>list.view.grid.title</source>
                <target>Post List</target>
            </trans-unit>
            <trans-unit id="kanban.view.title">
                <source>kanban.view.title</source>
                <target>Post Kanban</target>
            </trans-unit>
            <trans-unit id="PostFooAttribute">
                <source>PostFooAttribute</source>
                <target>Post Foo Attribute</target>
            </trans-unit>
            <trans-unit id="PostBarAttribute">
                <source>PostBarAttribute</source>
                <target>Post Bar Attribute</target>
            </trans-unit>
        </body>
    </file>
</xliff>
```

## New options console

The doctrine:generate:richcrud command has 3 new options (layout, multipart and with-kanban) 
please use the help for more information.

``` bash
php app/console generate:doctrine:richcrud --help
```

Enjoy
