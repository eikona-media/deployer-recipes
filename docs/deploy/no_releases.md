# No releases deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/no_releases.php';
```

## Tasks & Configuration

Overwrites the following tasks:

* ``deploy:prepare`` - creates no current symlink + no releases + no shared dir
* ``deploy:release`` - does nothing
* ``deploy:shared`` - does nothing
* ``deploy:symlink`` - does nothing
* ``cleanup`` - does nothing
* ``rollback`` - does nothing

Sets ``release_path`` to ``deploy_path``.
