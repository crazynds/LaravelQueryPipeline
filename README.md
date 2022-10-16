
# Query Pipeline Description

[![Latest Version on Packagist](https://img.shields.io/packagist/v/crazynds/query-pipeline.svg?style=flat-square)](https://packagist.org/packages/crazynds/query-pipeline)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/crazynds/QueryPipeline-Laravel/run-tests?label=tests)](https://github.com/crazynds/QueryPipeline-Laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/crazynds/QueryPipeline-Laravel/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/crazynds/QueryPipeline-Laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/crazynds/query-pipeline.svg?style=flat-square)](https://packagist.org/packages/crazynds/query-pipeline)

This package contains a collection of class that can be used with Laravel Pipeline.

Can do the same as the package [pipeline-query-collection](https://github.com/l3aro/pipeline-query-collection) however the syntax differs, and gives the user a little more possibilities.

Allows you to implement conditionals for joins and add where clauses if certain data was passed.


## Installation

You can install the package via composer:

```bash
composer require crazynds/query-pipeline
```

## Usage

 See the example below:
``` php
use Crazynds\QueryPipeline\Middleware\ILIKEQuery;
use Crazynds\QueryPipeline\Middleware\JoinQuery;
use Crazynds\QueryPipeline\QueryPipeline;

class ClientController extends Controller
{
    use QueryPipeline;

    public function index(Request $request){
        $page = $request->input('page', 1);
        $qtd = $request->input('qtd', 20);
        $query = Client::query();
        $data = $request->all();
        $query = $this->runPipeline($query,$data,[
            JoinQuery::class => [
                //table to join
                'addresses' => [
                    'on' => [ // required
                        'clients.address_id',
                        '=',
                        'addresses.id',
                    ],
                    'checkParameters'=>[ // optional, if any items from this array exist in the data keys, the join will be added to the query
                        // if not passed checkParameters, the join will be added in any condition
                        'country',
                        'state',
                        'city',
                    ],
                ]
            ],
            ILIKEQuery::class => [
                //table name
                'clients'=>[
                    //columns
                    'name',
                    'code',
                    'email',
                ],
                'addresses'=>[
                    'country',
                    'state',
                    'city',
                ]
            ],
        ]);
        return $query->paginate($qtd);
    }
}
```

### Steps

1. First add the trait QueryPipeline from Crazynds\QueryPipeline\QueryPipeline to your class.
2. Setup your middleware stack variable
3. Send the base query, your data values and your stack to $this->runPipeline and the query pipeline will run.
4. Be happy :>


## Middlewares

A middleware in this context means a step in which the query will be processed. See all the middlewares in folder src/middleware.

Each middleware has your stack of parameters, see below the specifications:

### Join Query

The join query middleware add a join query based on conditions passed as parameters. Each join query can include multiples joins.

The base object recived by each iten in array is represented below:
```php
<?php

$middlewareStack = [
    JoinQuery::class => [
        //table to join
        'table' => [
            'on' => [ // required
                'table.foreign_id',
                '=',
                'othertable.id',
            ],
            'checkParameters'=>[ 
            ],
        ]
    ]
];
```

The _on_ array is limited to only simple verification, without multiple wheres. Because of this, the array recive only 3 parameter, the left column, the comparator, and the right column.

The _checkParameters_ array, if not defined, the join will be added to query every time. If defined, the join will only be added to array if any of the string in the array is a key of array data;


### ILIKE Query

The ilike query middleware add a where in the query with a comparation of type _LIKE_ no case sensitive. The query add automatically the % in the start and end the string in the value.

The base object recived by each iten in array is represented below:
```php
<?php

$middlewareStack = [
    ILIKEQuery::class => [
        //table name
        'clients'=>[
            'name', // this iten will check clients.name ilike %.$data['name'].%
            'code' => 'ssn', // this iten will check clients.name ilike %.$data['ssn'].%
            'email',
        ],
    ],
];
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Crazynds](https://github.com/crazynds)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
