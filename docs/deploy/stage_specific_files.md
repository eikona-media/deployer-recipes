# Stage specific files helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/stage_specific_files.php';
```

### Usage

```php
  before('deploy:shared', 'deploy:stage_specific_files');
```


## Tasks & Configuration

* `deploy:stage_specific_files` - copies files for stage with config:

    * `stage_specific_files` array - default `[]` (can include multiple configurations)
        * `target` file path as string
        * `source` file path as string
        * `delete` (optional) file path as string or an array of strings

   Copies the `source` file to `target` file. Then deletes files configured in `delete`.

   __Does not work with shared files because the files are replaced in the release directory (not the shared directory)__

  > Example for `.env` files (`.env.ci_{{stage}}`)
  > ```php
  > set('stage_specific_files', [
  >     [
  >          'source' => '.env.ci_{{stage}}',
  >          'target' => '.env.local',
  >          'delete' => ['.env.ci_*', '.env_lando']
  >     ]
  > ]);
  > ```

  > Example for `parameters.yml` files
  > ```php
  > set('stage_specific_files', [
  >     [
  >         'source' => 'config/parameters_{{stage}}.yml',
  >         'target' => 'config/parameters.yml',
  >         'delete' => 'config/parameters_*.yml'
  >     ]
  > ]);
  > ```

  > Example for multiple files
  > ```php
  > set('stage_specific_files', [
  >     [
  >          'source' => '.env.ci_{{stage}}',
  >          'target' => '.env',
  >          'delete' => ['.env.ci_*', '.env_lando']
  >     ],
  >     [
  >         'source' => 'config/parameters_{{stage}}.yml',
  >         'target' => 'config/parameters.yml',
  >         'delete' => 'config/parameters_*.yml'
  >     ]
  > ]);
  > ```
