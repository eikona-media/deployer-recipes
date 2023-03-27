# Akeneo 7 recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/akeneo7.php';
```

Add the nodejs path of the deploy server:
```php
set('deploy_nodejs_path', '/opt/plesk/node/18/bin/');
```

### Optional Usage

* Add `--ignore-platform-reqs` to avoid missing libraries errors in build stage:

    ```php
    set('build_composer_options_extra', '--ignore-platform-reqs');
    ```

* For updating/replacing stage specific files see: [stage_specific_files](deploy/stage_specific_files.md)
* For Gitlab-CI see: [gitlab_ci](deploy/gitlab_ci.md)
