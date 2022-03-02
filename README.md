# Getting Started

## Requirements

* php 8.1 or higher
* composer
* yarn or npm

## Installation

```bash
git clone https://github.com/inmanturbo/event-sourced-laravel-jetstream.git
cd event-sourced-laravel-jetstream
composer install
npm install && npm run dev
```

## Building Docs

* install vuepress

```bash
yarn global add vuepress
```

* build (from root directory)
  
> Note:
> If you are on a late version of node and you get the error "error:0308010C:digital envelope routines::unsupported"
> you must first set node options with `export NODE_OPTIONS=--openssl-legacy-provider`
> If you are on windows or need more info see [This Issue](https://github.com/webpack/webpack/issues/14532)

```bash
vuepress build docs
```
