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
  after('deploy:symlink', 'deploy:opcache_reset');
```

## Tasks & Configuration

* `deploy:cache_status_clear` - clear status cache

* `deploy:opcache_reset` - reset OPcache with config:
    * `public_url` - default: `` - url with `https://` and without trailing slash
    * `opcache_webroot` - default: `web`
    * `opcache_filename` - default: `opcache.php`
