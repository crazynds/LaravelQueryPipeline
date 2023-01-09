# Changelog

All notable changes to `QueryPipeline` will be documented in this file.

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
