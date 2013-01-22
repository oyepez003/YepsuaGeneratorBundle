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