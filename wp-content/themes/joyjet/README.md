# Piassi Theme

This theme was developed as a test for HIAE.

Bellow, you can find more about project structure, development, and building for production.

## Project Structure

It was developed using [Solidpress](https://github.com/piassi/solidpress) project structure, all PHP dependencies are loaded using composer and follows OOP best practices.

## Theme folders

    .
    ├── assets          # Global assets
    ├── components      # Components templates and assets
    ├── includes        # PHP dependencies which are not available through composer
    ├── languages       # Translation files
    ├── pages           # Pages tempaltes and assets
    └── src             # Register Pages, Components and Hooks

## Src folder

Every folder inside 'src' is a namespace inside 'Theme' vendor namespace.

    .
    ├── Components        # Register components classes
    ├── FieldsGroups      # Register ACF field groups
    ├── Helper            # PHP Helpers
    ├── Hooks             # Register Hooks (wordpress actions and filters)
    ├── Models            # Content Models
    ├── Options           # Register options pages
    ├── Pages             # Register pages classes
    ├── PostTypes         # Register custom post types
    └── Taxonomies        # Register Taxonomies

## Setup

1.  From the project root, run the docker container

        docker-composer up -d

1.  From the theme root, install php dependencies

        composer install

1.  From the theme root, install javascript dependencies

        npm install

## Development

1.  From theme root, start webpack with

        ```npm run dev```

2.  Enjoy your development with webpack assets bundling and browser-sync hot-reload at http://localhost:3000

**Pro-tip: If you do use Visual Studio Code, this theme contains a workspace file.**

## Building for production

`npm run build`
