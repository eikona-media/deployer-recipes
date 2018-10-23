# Composer build helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/build/composer.php';
```

## Tasks & Configuration

* ``build:composer`` - build composer packages with config:
    * ``local/bin/php`` - default: ``which php``
    * ``local/bin/composer`` - default: ``which composer``
    * ``build_composer_path`` - default: ``./``
    * ``build_composer_action`` - default: ``install``
    * ``build_composer_options`` - default: ``{{build_composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-scripts``
