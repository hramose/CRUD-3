# Sone\CRUD

Quickly build an admin interface for your Eloquent models, using Laravel 5. Erect a complete CMS at 10 minutes/model, max.

Features:
- 49+ field types
- 23+ column types
- 1-1, 1-n and n-n relationships
- Table view with search, pagination, click column to sort by it
- Reordering (nested sortable)
- Back-end validation using Requests
- Translatable models (multi-language)
- Easily extend fields (customising a field type or adding a new one is as easy as creating a new view with a particular name)
- Easily overwrite functionality (customising how the create/update/delete/reorder process works is as easy as creating a new function with the proper name in your EntityCrudCrontroller)


## Getting started

If you have never used Sone before, the best place to understand it and get started is [sequel.one](https://sequel.one/). 

## Install

Please note you need to install Sone\Base before you can use Sone\CRUD. It will provide you with the AdminLTE design.

``` bash
composer require sone/crud
php artisan sone:crud:install
```

## Usage

At the end of the page you'll also find a way you can do everything in 1-2 minutes, using the command line and [sone/generators](https://github.com/laravel-sone/generators).

In short:

1. Make your model use the CrudTrait.

2. Create a controller that extends CrudController.

3. Create a new resource route.

4. **(optional)** Define your validation rules in a Request files.


## Screenshots

- List view pictured above.
- Create/update view.
- File manager (elFinder).


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email admin@sequel.one instead of using the issue tracker.

## Credits

- [Andrej Kopp](https://sequel.one)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
