# Contao recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/contao.php';
```

The recipe extends the symfony3 recipe of core deployer so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/symfony3.php';
```

### Optional Usage

* Database Backup while deployment

    ```php
    add('shared_dirs', ['var/db_backups']);
    before('deploy:symlink', 'contao:database:backup');
    ```

* Update shared dirs + parameters from repo - see: [update_shared](deploy/update_shared.md)

    ```php
    before('deploy:shared', 'deploy:update_shared_dirs');
    ```

    Default dirs: ``['files', 'templates']``

    ```php
    after('deploy:shared', 'deploy:update_shared_parameters');
    ```

    Default file:  ``app/config/parameters.yml``

* For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)

## Tasks

* ``contao:version`` - integrity check called before ``deploy:symlink``

* ``contao:database:backup`` - backup database while deployment

    Requires non contao-core package [``bwein-net/contao-database-backup``](https://github.com/bwein-net/contao-database-backup) to be installed!
