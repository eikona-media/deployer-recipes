# Akeneo 6 recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/akeneo6.php';
```

The recipe extends the symfony recipe of core deployer, so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/symfony.php';
```

The recipe extends the yarn recipe of core deployer recipes, so you have to include this recipe in `deploy.php` too:

```php
require 'contrib/yarn.php';
```

Add the nodejs path of the deploy server:
```php
set('deploy_nodejs_path', '/opt/plesk/node/16/bin/');
```

### Optional Usage

* Add `--ignore-platform-reqs` to avoid missing libraries errors in build stage:

    ```php
    set('build_composer_options_extra', '--ignore-platform-reqs');
    ```

* Update parameters from repo - see: [update_shared](deploy/update_shared.md)

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

* For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)

## Tasks

* ``build`` - build project

* ``pim:installer:assets`` - install assets (called after ``deploy:vendors``)

* ``pim:installer:dump-require-paths`` - dump required paths (called after ``pim:installer:assets``)

* ``deploy:yarn_install`` - install dependencies on deploy server to rebuild binaries if neccessary (called after ``pim:installer:dump-require-paths``)

* ``deploy:yarn_compile`` - compile javascript and css (called after ``deploy:yarn_install``)

* ``doctrine:schema:update:dump-sql`` - dump database schema updates

* ``doctrine:schema:update:force`` - update database schema
