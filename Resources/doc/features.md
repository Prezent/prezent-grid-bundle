Features
========

The grid bundle adds several new features on top of the base grid library.

## Localization

The output of the boolean column type is localized (in the `PrezentGrid` translation domain).

Furthermore, column header labels and action labels are localized as well. By default this uses the main `messages` translation
domain, but you can set a custom domain using the `label_translation_domain` option.

## Routes

You can generate URLs from routes for columns and actions. Two new options have been added to teh base element type:

### `route`

The route name to generate an URL from

### `route_parameters`

An array of route parameters. Parameter values which contain braces are interpreted as property paths into your row
and will be replaced. You can also suply a callback which will recieve the row as it's only parameter:

Example:

```php

$builder->addAction('edit', [
    'route' => 'your_bundle_edit',
    'route_parameters' => [
        'id' => '{id}',
        'slug' => function ($item) {
            return slugify($item->getName());
        }
    ]
]);
```

## Sortable columns

Columns can be made sortable by enabling the `sortable` option. This turns the column header into a link. Sorting parameters are extracted
from the current request to alternate between ascending and descending sorting.

Note that you need to extract the sorting parameters from the request yourself and apply it to whatever query you are using to fetch the data.

Various new options are added to the base column type to configure the sorting behaviour:

### `sortable`

Set to `true` to enable the column to be sortable.

### `sort_field`

Which field should be sorted on. The value of this option is the value of the HTTP request parameter when sorting on this column.
defaults to teh column name.

### `sort_route`

Which route to generate the sorting URL with. defaults to the current route.

### `sort_route_parameters`

Parameters for the sorting route.

### `sort_field_parameter`

The name of the request attribute that specifies which field you should sort on. Defaults to `'sort_by'`.

### `sort_order_parameter`

The name of the request attribute that specifies which direction you should sort in (ASC or DESC). Defaults to `'sort_order'`.

Example usage:

```php
<?php

$builder->addColumn('name', 'string', [
    'sortable' => true,
    'sort_field' => 'name.raw',
]);
```
