# Contao recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/contao.php';
```

The recipe extends the symfony recipe of core deployer, so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/symfony.php';
```

### Optional Usage

* Set the webroot directory - default: `web`
    ```php
    set('contao_webroot', 'public');
    ```

* Database migration while deployment

    with automatic backup manager of Contao 4.13:
    ```php
    add('shared_dirs', ['var/backups']);
    before('deploy:symlink', 'contao:migrate');
    ```

* Update shared dirs + parameters from repo - see: [update_shared](deploy/update_shared.md)

    ```php
    before('deploy:shared', 'deploy:update_shared_dirs');
    ```

    Default dirs: ``['files', 'templates']``

    ```php
    before('deploy:shared', 'deploy:stage_specific_files');
    ```

    Example for `.env` files (`.env.ci_{{stage}}`)
    ```php
    set('stage_specific_files', [
      [
        'source' => '.env.ci_{{stage}}',
        'target' => '.env',
        'delete' => '.env.*'
      ]
    ]);
    ```

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

* ``contao:migrate`` - execute contao migration while deployment
