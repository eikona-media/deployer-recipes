# Scp deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/scp.php';
```

## Tasks & Configuration

* ``scp:upload`` - uploads a file via scp with config:
    * ``local/bin/scp`` - default: ``which scp``
    * ``scp_upload_source`` - default: ``./``
    * ``scp_upload_destination`` - default: ``{{release_path}}/``
    * ``scp_upload_config`` - default: ``[]`` - ``'options'`` will be added to command

   Uses ``port``, ``configFile``, ``identityfile``, ``forwardAgent`` of host for ssh connection!

   experimental: ``localhost`` should just use ``cp`` - but not fully tested!

* ``scp:upload:tar`` - uploads a file via scp + tar with config:
    * ``scp_upload_source`` as ``tar_source``
    * ``scp_upload_destination`` as ``tar_destination``
    * ``tar_file_local`` as ``scp_upload_source``
    * ``tar_file_host`` as ``scp_upload_destination``

    Invokes tasks ``tar:create``,  ``scp:upload``,  ``tar:extract``,  ``tar:cleanup``.
