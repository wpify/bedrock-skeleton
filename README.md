# WordPress Skeleton by WPify

WordPress skeleton is based on [Bedrock](https://roots.io/bedrock/) and uses WPify development stack and libraries. The skeleton always installs the latest version of all dependencies.

## Requirements

- PHP >= 8.0
- Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
- DDEV for local development
- Node.js 18

## Usage

Create a new project:
```sh
$ composer create-project wpify/bedrock-skeleton <project-name>
```

Start dev server:
```sh
$ ddev start
```

Start continuous rebuild of assets:
```sh
$ npm run start
```

Build assets for production:
```sh
$ npm run build
```

Generate POT file for translations:
```sh
$ composer run make-pot
```

Generate JSON file for frontend translations from translated PO/MO files:
```sh
$ composer run make-json
```
