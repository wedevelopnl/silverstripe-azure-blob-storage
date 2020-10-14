# Silverstripe Azure Blob Storage

[![Version](http://img.shields.io/packagist/v/fullscreeninteractive/silverstripe-azure-blob-storage.svg?style=flat-square)](https://packagist.org/packages/fullscreeninteractive/silverstripe-azure-blob-storage)
[![License](http://img.shields.io/packagist/l/fullscreeninteractive/silverstripe-azure-blob-storage.svg?style=flat-square)](LICENSE)

Silverstripe module to store assets in Azure Blob Storage rather than on the
local filesystem. This leverages Flysystem in Silverstripe 4.

## Environment setup

The module requires a few environment variables to be set

* `AZURE_CONNECTION_URL`: The connection URL as from the dashboard
* `AZURE_CONTAINER_NAME`: The name of the container to store public assets in.
* `AZURE_PROTECTED_CONTAINER_NAME`: The name of the container for protected
assets

By default the module will serve public files from the URL provided in
`AZURE_CONNECTION_URL` (e.g silverstripe-assets.blob.core.windows.net) unless
 `AZURE_PUBLIC_BLOB_DOMAIN` is set. Protected assets are always served from the
 local domain and routed through the permission checking.

## Installation

```
composer require fullscreeninteractive/silverstripe-azure-blob-storage
```

**Note:** This currently immediately replaces the built-in local asset store that comes with
SilverStripe with one based on Azure. Any files that had previously been uploaded to an existing
asset store will be unavailable (though they won't be lost - just run `composer remove
fullscreeninteractive/silverstripe-azure-blob-storage` to remove the module and restore access).

## Configuration

Assets are classed as either 'public' or 'protected' by SilverStripe. Public assets can be
freely downloaded, whereas protected assets (e.g. assets not yet published) shouldn't be
directly accessed.

The module supports this by streaming the contents of protected files down to the browser
via the web server (as opposed to linking directly) by default. To ensure that
protected assets can't be accessed, ensure you setup an appropriate policy.

## CORS

CORS is an HTTP feature that enables a web application running under one domain
to access resources in another domain. Make sure your storage account has
allowed the websites hostname with `GET` as an allowed method.
