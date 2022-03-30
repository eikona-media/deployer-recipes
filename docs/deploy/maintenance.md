# Maintenance deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/maintenance.php';
```

### Usage

```php
  after('deploy:vendors', 'maintenance:enable:previous_release');
  after('deploy:vendors', 'maintenance:enable');
  after('deploy:symlink', 'maintenance:disable');
```

## Tasks & Configuration

For contao >= 4.13 this helper tries to execute the contao maintenance mode command before the lexik maintenance mode command.

* `maintenance:enable:previous_release` - enable the contao or lexik maintenance mode for the previous release

* `maintenance:enable` - enable the contao or lexik maintenance mode

* `maintenance:disable` - disable the contao or lexik maintenance mode
