# Changelog

All notable changes to `QueryPipeline` will be documented in this file.

## v1.2.0 - 2025-06-17

### What's Changed

* build(deps): bump dependabot/fetch-metadata from 2.3.0 to 2.4.0 by @dependabot in https://github.com/crazynds/LaravelQueryPipeline/pull/18
* feat: Created a StringQuery
* impr: Improved BetweenQuery

**Full Changelog**: https://github.com/crazynds/LaravelQueryPipeline/compare/v1.1.4...v1.2.0

## v1.1.4 - 2025-04-07

- Fix: Between query fixed
  **Full Changelog**: https://github.com/crazynds/LaravelQueryPipeline/compare/v1.1.3...v1.1.4

## v1.1.3 - 2025-03-23

* Fix a bug when using joins.

## v1.1.2 - 2025-03-01

* Added suport to laravel framework ^12.0

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.1.1...v1.1.2

## v1.1.1 - 2025-02-24

* fix: correction on passing correctly the where's to the child query

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.1.0...v1.1.1

## v1.1.0 - 2025-02-23

### What's Changed

* build(deps): bump aglipanci/laravel-pint-action from 2.3.1 to 2.4 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/13
* build(deps): bump dependabot/fetch-metadata from 1.6.0 to 2.0.0 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/12
* build(deps): bump dependabot/fetch-metadata from 2.0.0 to 2.1.0 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/14
* build(deps): bump dependabot/fetch-metadata from 2.1.0 to 2.2.0 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/15
* build(deps): bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/16
* build(deps): bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/17
* feat: added DatetimeQuery to filter datetime columns.

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.0.9...v1.1.0

## Fix queryRay - 2024-04-18

### What's Changed

* build(deps): bump aglipanci/laravel-pint-action from 2.3.1 to 2.4 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/13

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.0.9...1.0.10

## Fix order by - 2024-03-24

### What's Changed

* build(deps): bump aglipanci/laravel-pint-action from 2.3.0 to 2.3.1 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/11

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.0.8...v1.0.9

## Bug Fix - 2024-01-13

Fixed a bug when you use `whereIn` and a clousure in value, the bindings are deleted.

## Bug fix - 2023-03-07

Where in and Where not in is working as intentded.

## Atualização laravel 10 - 2023-02-15

### What's Changed

- build(deps): bump dependabot/fetch-metadata from 1.3.5 to 1.3.6 by @dependabot in https://github.com/crazynds/QueryPipeline-Laravel/pull/3

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.0.5...v1.0.6

## Suport laravel 10 - 2023-02-15

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.0.7...v1.0.8

## Suporte laravel 10 - 2023-02-15

**Full Changelog**: https://github.com/crazynds/QueryPipeline-Laravel/compare/v1.0.6...v1.0.7

## v1.0.6 - 2023-02-15

Add support for laravel 10

## v1.0.5 - 2023-01-09

Fix for complex queries.

## v1.0.4 - 2022-12-21

Solved bugs introduced in version 1.0.3

## v1.0.3 - 2022-12-21

Created OrderByQuery for use order by if the parameter 'sortBy' is in array $data.

```php

// Check the 'sortBy' in $data to sort
// Check the 'sortDesc' (bool) if is asc or desc 
// sortDest = 1 => desc 
// sortDesc = 0 => asc
$query = $this->runPipeline($query, $data, [
    OrderByQuery::class => [
        Client::class => [
            'name',
            'age',
        ],
        'default' => 'clients.id'
    ],
]);


















```
## v1.0.2 - 2022-11-10

Instead of needing to put the name of the table, it is also possible to pass the model that the table will be used for.

Now is possible to do the follow:

```php
$query = $this->runPipeline($query, $data, [
    ILIKEQuery::class => [
        Question::class => [
            'type',
            'title',
            'description',
        ],
    ],

]);


















```
## v1.0.1 - 2022-10-16

Irrelevant UPDATES.

## v1.0.0 - 2022-10-16

Primeira versão oficial.

## First Release - 2022-08-29

Release of the first version of the package.

## v1.0.0 - 2022-10-16

New:

- Added BetweenDatesQuery filter

Fix:

- Removed all calls of env function
