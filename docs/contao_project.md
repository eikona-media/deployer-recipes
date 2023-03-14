# Contao project recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/contao_project.php';
```

The recipe extends the contao recipe of core deployer, so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/contao.php';
```

see the docs: https://deployer.org/docs/7.x/recipe/contao

### Optional Usage

* Set the public directory - default form: `composer.json`
    ```php
    set('public_path', 'public');
    ```

* Update shared dirs + parameters from repo - see: [update_shared](deploy/update_shared.md)

    ```php
    before('deploy:shared', 'deploy:update_shared_dirs');
    ```

  Default dirs: ``['files', 'templates']``

    ```php
    set('shared_files', []);
    before('deploy:shared', 'deploy:stage_specific_files');
    ```
  **Attention:** The shared files may have to be reset!

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
    after('deploy:symlink', 'deploy:opcache_reset'); // don't forget to set `public_url` per stage
    ```

* Cleanup previous release

    ```php
    set('cleanup_previous_release_dirs', ['var/cache']);
    before('deploy:cleanup', 'cleanup:previous_release');
    ```
