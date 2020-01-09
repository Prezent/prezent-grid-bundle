Installation
============

This bundle can be installed using Composer. Tell composer to install the extension:

```bash
$ php composer.phar require prezent/grid-bundle
```

Then, activate the bundle (Symfony 4+):

```php
<?php
// config/bundles.php

return [
    // ...
    Prezent\GridBundle\PrezentGridBundle::class => ['all' => true],
];

```
or (Symfony 3):

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
### Twig Extensions
Since the ```prezent/grid``` library depends on the Twig string-extra extension, you have to load it in your project. 

```yml
// config/services.yml
...
services:
   Twig\Extra\String\StringExtension:
       tags:
           - { name: twig.extension }
```

or you can use the [twig/extra-bundle](https://github.com/twigphp/twig-extra-bundle) on Symfony 4+. See the documentation for that bundle on how to install it.

### Themes
You can set the themes which will be used by the renderer:

```yml
prezent_grid:
    themes: 
        - @PrezentGrid/grid/grid.html.twig
        - @MyBundle/grid/custom.html.twig
```
