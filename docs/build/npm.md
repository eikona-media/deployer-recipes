# Npm build helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/build/npm.php';
```

## Tasks & Configuration

* ``build:npm`` - build npm packages with config:
    * ``local/bin/npm`` - default: ``which npm``
    * ``build_npm_path`` - default: ``./``
    * ``build_npm_action`` - default: ``install``
    * ``build_npm_options`` - default: ``{{build_npm_action}}``
