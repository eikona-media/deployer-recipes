# Akeneo recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/akeneo2.php';
```

The recipe extends the symfony3 recipe of core deployer so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/symfony3.php';
```

The recipe extends the yarn recipe of core deployer recipes so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/yarn.php';
```

### Optional Usage

* Update parameters from repo - see: [update_shared](deploy/update_shared.md)

    ```php
    after('deploy:shared', 'deploy:update_shared_parameters');
    ```

    Default files:  ``['app/config/parameters.yml']``

* For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)

## Tasks

* ``build`` - build project

* ``pim:installer:assets`` - install assets (called after ``deploy:vendors``)

* ``pim:installer:dump-require-paths`` - dump required paths (called after ``pim:installer:assets``)

* ``yarn:compile`` - run webpack (called after ``pim:installer:dump-require-paths``)
