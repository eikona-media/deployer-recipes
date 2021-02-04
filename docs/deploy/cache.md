# Cache helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/cache.php';
```

### Usage

```php
  after('deploy:symlink', 'deploy:cache_status_clear');
```

```php
  after('deploy:symlink', 'deploy:cache_accelerator_clear');
```

## Tasks & Configuration

* `deploy:cache_status_clear` - clear status cache

* `deploy:cache_accelerator_clear` - clear accelerator cache
