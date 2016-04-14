Service configuration
=====================

There are multiple tags that you can use in your service configuration to add your own
services to the grid system.

## `prezent_grid.grid`

Tag your grid classes to add them to the grid factory. Example:

```xml
<service id="my_bundle.grid.users" class="My\Bundle\Grid\UsersGridType">
    <tag name="prezent_grid.grid" />
</service>
```

You can access your grid by name from your controller:

```php
<?php

use My\Bundle\Grid\UserGridType;

class UserController extends Controller
{
    /**
     * @Template
     */
    public function indexAction(Request $request)
    {
        $grid = $this->get('grid_factory')->createGrid(UserGridType::class);

        return [
            'grid' => $grid->createView(),
        ];
    }
}
```

## `prezent_grid.grid_extension`

Tag your custom grid extensions to add them to the element factory:

```xml
<service id="my_bundle.grid_extension" class="My\Bundle\Grid\Extension" public="false">
    <tag name="prezent_grid.grid_extension" extended_type="My\Bundle\Grid\UserGridType" />
</service>
```

## `prezent_grid.element_type` and `prezent_grid.element_type_extensions`

Add your own types and type extensions to the built-in grid extension:

```xml
<service id="my_bundle.my_grid_type" class="My\Bundle\Grid\MyType">
    <tag name="prezent_grid.element_type" />
</service>

<service id="my_bundle.my_grid_type_extension" class="My\Bundle\Grid\MyTypeExtension">
    <tag name="prezent_grid.element_type_extension" extended_type="My\Bundle\Grid\MyType" />
</service>
```

## `prezent_grid.variable_resolver`

Add your own variable resolver (used to parse URLs on a row-by-row bases) to the built-in chain resolver:

```xml
<service id="my_bundle.variable_resolver" class="My\Bundle\Grid\VariableResolver" public="false">
    <tag name="prezent_grid.variable_resolver" />
</service>
```
