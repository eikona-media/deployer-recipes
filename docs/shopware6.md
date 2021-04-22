# Shopware recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/shopware6.php';
```

The recipe extends the symfony recipe of core deployer, so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/symfony4.php';
```

### Optional Usage

* Update shared dirs + parameters from repo - see: [update_shared](deploy/update_shared.md)

    ```php
    before('deploy:shared', 'deploy:update_shared_dirs');
    ```

  Default dirs: ``['custom/plugins', 'files']``

    ```php
    after('deploy:shared', 'deploy:update_shared_parameters');
    ```

  Default file:  ``.env`` - for every Stage: `.env.ci_{{stage}}`

    ```php
    before('deploy:update_shared_dirs', 'deploy:clear_shared_dirs');
    ```

* For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)

* Clear cache

    ```php
    after('deploy:symlink', 'deploy:cache_status_clear');
    after('deploy:symlink', 'deploy:cache_accelerator_clear');
    ```

## Tasks

* ``build`` - build project and dump autoload after `composer install --no-scripts`

* ``deploy:generate-jwt-secret`` - generate JWT Secret, if it does not exist (called after ``deploy:vendors``)
