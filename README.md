# Silverstripe Azure Blob Storage

Silverstripe module to store assets in Azure Blob Storage rather than on the
local filesystem. This leverages Flysystem in Silverstripe 4.

## Environment setup

The module requires a few environment variables to be set

* `AZURE_CONNECTION_URL`: The connection URL as from the dashboard
* `AZURE_CONTAINER_NAME`: The name of the container to store assets in.

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