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
    * ``build_composer_options_extra`` - default: ````
    * ``build_composer_run_options`` - default: ``['timeout' => null]``

* ``build:composer:dump-autoload`` - dump composer autoload with config:
    * ``local/bin/php`` - default: ``which php``
    * ``local/bin/composer`` - default: ``which composer``
    * ``build_composer_path`` - default: ``./``
    * ``build_composer_dump_autoload_action`` - default: ``dump-autoload``
    * ``build_composer_dump_autoload_options`` - default: ``{{build_composer_dump_autoload_action}} -o``
    * ``build_composer_dump_autoload_options_extra`` - default: ````
    * ``build_composer_run_options`` - default: ``['timeout' => null]``
