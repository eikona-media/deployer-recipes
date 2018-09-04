# Nextcloud recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/nextcloud.php';
```

The recipe extends the common recipe of core deployer so you have to include this recipe in `deploy.php` too:

```php
require 'deployer/recipe/common.php';
```

### Usage

Update shared dirs + parameters from repo - see: [update_shared](deploy/update_shared.md)

```php
before('deploy:shared', 'deploy:update_shared_dirs');
```
```php
after('deploy:shared', 'deploy:update_shared_parameters');
```

For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)