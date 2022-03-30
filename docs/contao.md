# Contao recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/contao.php';
```

The recipe extends the symfony recipe of core deployer, so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/symfony4.php';
```

### Optional Usage

* Database Backup while deployment

    This config is useful for Contao < 4.13 without backup while database migration
    ```php
    add('shared_dirs', ['var/backups']);
    // or before Contao 4.13
    add('shared_dirs', ['var/db_backups']);
    before('deploy:symlink', 'contao:database:backup');
    ```

* Database migration while deployment

    with automatic backup manager of Contao 4.13:
    ```php
    add('shared_dirs', ['var/backups']);
    before('deploy:symlink', 'contao:migrate');
    ```
    or with activated database backup before Contao 4.13:
    ```php
    after('contao:database:backup', 'contao:migrate');
    ```

* Database Update while deployment

    ```php
    before('deploy:symlink', 'contao:database:update');
    ```
    or with activated database backup:
    ```php
    after('contao:database:backup', 'contao:database:update');
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

    ```php
    before('deploy:update_shared_dirs', 'deploy:clear_shared_dirs');
    ```

* For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)

* Clear cache

    ```php
    after('deploy:symlink', 'deploy:cache_status_clear');
    after('deploy:symlink', 'deploy:cache_accelerator_clear');
    ```

* Maintenance mode while deployment
    For previous release:
    ```php
    before('deploy:clear_shared_dirs', 'maintenance:enable:previous_release');
    // or
    before('deploy:update_shared_dirs', 'maintenance:enable:previous_release');
    // or
    before('contao:migrate', 'maintenance:enable:previous_release');
    ```

    ```php
    after('deploy:vendors', 'maintenance:enable');
    after('deploy:symlink', 'maintenance:disable');
    ```

* Cleanup previous release

    ```php
    set('cleanup_previous_release_dirs', ['var/cache']);
    before('cleanup', 'cleanup:previous_release');
    ```

## Tasks

* ``contao:version`` - integrity check called before ``deploy:symlink``

* ``contao:database:backup`` - backup database while deployment

    Requires non contao-core package [``bwein-net/contao-database-backup``](https://github.com/bwein-net/contao-database-backup) to be installed!

* ``contao:database:update`` - update database while deployment

    Requires non contao-core package [``fuzzyma/contao-database-commands-bundle``](https://github.com/fuzzyma/contao-database-commands-bundle) to be installed!

    > This Task is deprecated with `contao:migrate` since Contao 4.9

* ``contao:migrate`` - execute contao migration while deployment
