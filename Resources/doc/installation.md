Installation
============

This bundle can be installed using Composer. Tell composer to install the extension:

```bash
$ php composer.phar require prezent/grid-bundle
```

Then, activate the bundle in your kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Prezent\GridBundle\PrezentGridBundle(),
    );
}
```

## Configuration

You can set the theme which will be used by the renderer:

```yml
prezent_grid:
    theme: MyBundle:Grid:custom.html.twig
```
