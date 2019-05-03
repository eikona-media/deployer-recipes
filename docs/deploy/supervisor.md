# Supervisor deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/supervisor.php';
```

## Tasks & Configuration

Overwrites the following task:

* ``deploy:restart_supervisor_daemon`` - restarts supervisor daemons:
    * ``supervisor_daemons`` as array - default: ``[]``

Calls always ``deploy:restart_supervisor_daemon`` after ``deploy:symlink``.
