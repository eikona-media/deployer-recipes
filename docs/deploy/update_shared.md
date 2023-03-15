# Update shared deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/update_shared.php';
```

### Usage

```php
  before('deploy:shared', 'deploy:update_shared_dirs');
```

```php
  before('deploy:update_shared_dirs', 'deploy:clear_shared_dirs');
```

## Tasks & Configuration

* `deploy:update_shared_dirs` - updates shared dirs with config:
   * `update_shared_dirs` as array - default: `[]`

  Copies all defined dirs from `{{release_path}}/$dir` to `{{deploy_path}}/shared`, to transfer changes from repo to shared folders.

* `clear:update_shared_dirs` - clear shared dirs with config:
  * `clear_shared_dirs` as array - default: `[]`

  Removes all defined dirs from `{{deploy_path}}/shared`, to cleanup shared folders before updating from repo.
