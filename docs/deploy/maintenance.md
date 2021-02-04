# Maintenance deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/maintenance.php';
```

### Usage

```php
  after('deploy:vendors', 'maintenance:enable');
  after('deploy:symlink', 'maintenance:disable');
```

## Tasks & Configuration

* `maintenance:enable` - enable the lexik maintenance mode

* `maintenance:disable` - disable the lexik maintenance mode
