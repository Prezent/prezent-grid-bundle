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
### Twig Extensions
Since the ```prezent/grid``` library depends on the Twig Extensions, specifically the TextExtesion, you have to load them in your project. 

You can do this either manually, e.g. in you appliation config, like this:

```yml
// app/config/config.yml
...
services:
   twig.extension.text:
       class: Twig_Extensions_Extension_Text
       tags:
           - { name: twig.extension }
```

or you can use the [dms/twig-extension-bundle](https://github.com/rdohms/dms-twig-extension-bundle). See the documentation for that bundle on how to install it.

### Themes
You can set the themes which will be used by the renderer:

```yml
prezent_grid:
    themes: 
        - PrezentGridBundle:Grid:grid.html.twig
        - MyBundle:Grid:custom.html.twig
```
