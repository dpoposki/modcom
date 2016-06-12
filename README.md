ModCom - Modman to Composer converter
=====================================

Dev share talk on how to convert a modman project to a composer ready one. This does not represent a
production ready code (well.. you can still use it on your projects, but this just means that I provide
no guarantee for the use).

The purpose of the talk was to show a quick way of converting a modman project (in the given case a
Magento project) to a composer ready project. Basically in most of the cases you should be able to
run the convert command and then `composer install`. Of course, after the convert you will need to
clean up your composer file and make it better (read below) if you want to go live with it, but the
point is that you can immediately run `composer install` after the conversion is done.

Installation
------------

    composer require poposki/modcom "~0.1"

Configuration
-------------

In order to prepare the project for converting you need a small configuration file which you can copy
from `app/config/config.yml`. This is the default configuration file of the tool, but your project
specific one should go under the project root you want to convert and should be named `modcom.yml`.
Most of the config settings are self-explanatory, but I have explained some of them below. Whatever
you don't need in the config you can remove.

    # Project specific config
    project:
        source_dir: 'src'
        vendor: 'Vendor'
        name: 'vendor/project'
        description: 'Here goes some fancy description'
        homepage: 'http://www.example.com'
        license: ''
        packages:
            php: '>=5.5'
            magento-hackathon/magento-composer-installer: '~3.0'
        repositories:
            "https://packages.firegento.com": 'composer'

    # Local module specific config
    module:
        homepage: 'http://www.example.com'
        license: ''
        packages:
            php: '>=5.5'
        repositories: []

    # Modman config
    modman:
        dir: ''
        dir_separator: '_'

Project specific config
 - the project `source_dir` is the directory where the converted packages will be placed at the end
 - the `vendor` is a fallback in case your directories under modman don't have vendor defined in their
   names. E.g., if your module is `ModuleName` instead of `Vendor_ModuleName`, then the `vendor` setting
   will be used when deciding where to place the package and which name to assign in the composer file
 - under `packages` you can define any default packages that your project should end up with (same goes
   for `repositories`)

Module specific config
 - these ones are basically the same as the project settings with the difference that these will be applied
   on the converted modules' composer files

Modman config
 - the `dir` is where the modman modules are to be found
 - the `dir_separator` (if any) is the one that separates the vendor from the module name (check the example)
   for the project `vendor` setting

Usage
-----

There is only one command to run

    modcom convert

Git submodules
--------------

If the project has git submodules (with the .gitmodule file found in the root of the project) then
the tool will read from it and it will add as a repository to the composer.json file any package
found in the system which is already composer ready.

This will help you with any private repository you have in the project and is composer ready, or with
any 3rd party package which is not added to any of the official repositories.

On the other hand it might be the case that these packages are already on packagist, packages.firegento
or on any other repository, but in this moment the tool doesn't check for that, so if that is the case the
suggestion is to manually remove the repository urls from the composer file.

Composer package
----------------

Any module found under the specified modman directory that is composer ready will not be moved to the
source directory, but it will be just added as a package to the composer file. At this moment the tool
is not checking for the checked out commit (if it is a submodule), and it doesn't run any checks on the
public repos for tags which means it will be added with a `*` as a version. This is very project specific,
so the dev will need to go in the composer file afterwards and set the wanted versions for each of the
external packages.

Local package
-------------

The local modman modules will now be transferred to local composer packages with a `*@dev` added as a
package version. All of them will be moved under the source directory under their own vendor namespaces,
with each package having its own composer file. Also the corresponding local paths will be added to the
composer file.

Folder structure
----------------

This is the suggested folder structure that you will end up with:

    root
      ├── src
      | ├── vendor1
      | | ├── module1
      | └── vendor2
      ├── vendor
      └── composer.json

Ideally the magento source should go as a dependency in the composer file and then together with the
project specific source found under the `src` directory will be deployed under the `htdocs` (or `web`
or anything) directory. The example for the dev share was using the
[magento hackathon composer installer](https://github.com/Cotya/magento-composer-installer),
which will deploy all of the packages under the Magento installation.

Other use cases
---------------

Although the converter initially called modman to composer, as a matter of fact it doesn't do anything
modman specific. This means that you can apply this converter to any other project that has a similar
structure.

License
-------

ModCom is licensed under the MIT License - see the LICENSE file for details
