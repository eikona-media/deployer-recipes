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

## Tasks

* ``contao:version`` - integrity check called before ``deploy:symlink``

* ``contao:database:backup`` - backup database while deployment

    Requires non contao-core package [``bwein-net/contao-database-backup``](https://github.com/bwein-net/contao-database-backup) to be installed!

* ``contao:database:update`` - update database while deployment

    Requires non contao-core package [``fuzzyma/contao-database-commands-bundle``](https://github.com/fuzzyma/contao-database-commands-bundle) to be installed!

    > This Task is deprecated with `contao:migrate` since Contao 4.9

* ``contao:migrate`` - execute contao migration while deployment
