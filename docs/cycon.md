# Cycon recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/cycon.php';
```

The recipe extends the symfony3 recipe of core deployer so you have to include this recipe in `deploy.php` too:

```php
require 'recipe/common.php';
```

### Optional Usage

* For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)

## Tasks

* ``build`` - build project
