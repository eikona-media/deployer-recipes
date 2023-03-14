# Cleanup helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/cleanup.php';
```

### Usage

```php
  before('deploy:cleanup', 'cleanup:previous_release');
```

## Tasks & Configuration

* `cleanup:previous_release` - cleanup dirs in previous release with config:
    * `cleanup_previous_release_dirs` as array - default: `[]`

  Removes all defined dirs from `{{previous_release}}`, to cleanup previous release folder after release.
