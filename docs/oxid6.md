# Oxid recipe

## Installing

Include recipe in `deploy.php` file.

```php
require 'recipe/oxid6.php';
```


### Optional Usage

* Update shared dirs + parameters from repo - see: [update_shared](deploy/update_shared.md)

    ```php
    before('deploy:shared', 'deploy:update_shared_dirs');
    ```

  Default dirs:
  ```php
  'source/out/pictures',
  'source/out/media',
  'source/out/downloads',
  'source/export',
  'source/log'
  ```

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



