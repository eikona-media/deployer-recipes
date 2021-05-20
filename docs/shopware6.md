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
    // or
    after('deploy:symlink', 'deploy:opcache_reset'); // don't forget to set `public_url` per stage
    ```

## Tasks

* `build` - build project with `build:composer, shopware:build:recovery, shopware:build:js`

* `shopware:build:recovery` - build shopware recovery (called while `build`)

* `shopware:build:js` - build shopware Javascript (called while `build`)

* `shopware:install:lock` - touch shopware `install.lock` (called before `deploy:vendors`)

* `shopware:bundle:dump` - dump bundle config (called before `deploy:vendors`)

* `shopware:generate-jwt-secret` - generate JWT Secret, if it does not exist (called after `deploy:vendors`)

* `shopware:asset:install` - install assets (called after `deploy:vendors`)

* `shopware:compile:theme` - compile shopware theme (called after `deploy:cache:clear`)

* `shopware:migrate` - migrate shopware database

* `shopware:cache:warmup` - warmup shopware http cache (called after `deploy:cache:warmup`)

