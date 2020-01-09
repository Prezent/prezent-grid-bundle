Service configuration
=====================

There are multiple tags that you can use in your service configuration to add your own
services to the grid system. If you have autoconfiguration set up in Symfony then these tags
should all be added automatically.

## `prezent_grid.grid`

Tag your grid classes to add them to the grid factory. Example:

```xml
<service id="App\Grid\UsersGridType" public="true">
    <tag name="prezent_grid.grid" />
</service>
```

You can access your grid by name from your controller:

```php
<?php

use App\Grid\UserGridType;
use Prezent\Grid\GridFactory;

class UserController implements Controller
{
    /**
     * @Template
     */
    public function index(GridFactory $factory, Request $request)
    {
        $grid = $factory->createGrid(UserGridType::class);

        return [
            'grid' => $grid->createView(),
        ];
    }
}
```

## `prezent_grid.grid_extension`

Tag your custom grid extensions to add them to the element factory:

```xml
<service id="App\Grid\Extension" public="true">
    <tag name="prezent_grid.grid_extension" extended_type="My\Bundle\Grid\UserGridType" />
</service>
```

## `prezent_grid.element_type` and `prezent_grid.element_type_extensions`

Add your own types and type extensions to the built-in grid extension:

```xml
<service id="App\Grid\MyType" public="true">
    <tag name="prezent_grid.element_type" />
</service>

<service id="My\Bundle\Grid\MyTypeExtension" public="true">
    <tag name="prezent_grid.element_type_extension" extended_type="My\Bundle\Grid\MyType" />
</service>
```

## `prezent_grid.variable_resolver`

Add your own variable resolver (used to parse URLs on a row-by-row bases) to the built-in chain resolver:

```xml
<service id="App\Grid\VariableResolver">
    <tag name="prezent_grid.variable_resolver" />
</service>
```
