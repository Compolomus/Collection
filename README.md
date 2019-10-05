# Compolomus Collection

[![License](https://poser.pugx.org/compolomus/Collection/license)](https://packagist.org/packages/compolomus/Collection)

[![Build Status](https://scrutinizer-ci.com/g/Compolomus/Collection/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/Collection/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Compolomus/Collection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/Collection/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Compolomus/Collection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/Collection/?branch=master)
[![Code Climate](https://codeclimate.com/github/Compolomus/Collection/badges/gpa.svg)](https://codeclimate.com/github/Compolomus/Collection)
[![Downloads](https://poser.pugx.org/compolomus/Collection/downloads)](https://packagist.org/packages/compolomus/Collection)

# Install:

composer require compolomus/Collection

# Usage:

```php

use Compolomus\Collection\Collection;

require __DIR__ . '/vendor/autoload.php';

```

## New collection

### Single add

```php

$collection = new Collection('stdClass');

for ($i = 0; $i <= 42; $i++) {
    $add = new stdClass();
    $add->test = $i;
    $collection->addOne($add);
}

```

### Batch add

```php

$array = [];
for ($i = 0; $i <= 42; $i++) {
    $add = new stdClass();
    $add->test = $i;
    $array[] = $add;
}
$collection->addAll($array);

```

## Limit

### Count limit

```php

$limit1 = $collection->immutable()->limit(5);

echo '<pre>' . print_r($limit1->get(), true) . '</pre>';
/*
Array
(
    [0] => stdClass Object
        (
            [test] => 0
        )

    [1] => stdClass Object
        (
            [test] => 1
        )

    [2] => stdClass Object
        (
            [test] => 2
        )

    [3] => stdClass Object
        (
            [test] => 3
        )

    [4] => stdClass Object
        (
            [test] => 4
        )
)
 */
 
```

### Limit with offset

```php

$limit2 = $collection->immutable()->limit(3, 3);

echo '<pre>' . print_r($limit2->get(), true) . '</pre>';
/*
Array
(
    [0] => stdClass Object
        (
            [test] => 3
        )

    [1] => stdClass Object
        (
            [test] => 4
        )

    [2] => stdClass Object
        (
            [test] => 5
        )
)
 */

```

## Count

```php

echo $collection->count(); //43
echo $limit1->count(); // 5
echo $limit2->count(); // 3

```

## Sort

```php

$sort = $limit2->immutable()->sort('test', Collection::DESC);

echo '<pre>' . print_r($sort->get(), true) . '</pre>';
/*
Array
(
    [2] => stdClass Object
        (
            [test] => 5
        )

    [1] => stdClass Object
        (
            [test] => 4
        )

    [0] => stdClass Object
        (
            [test] => 3
        )
)
 */

```

## LINQ

```php

$linq = $collection->where('test > 33');

echo '<pre>' . print_r($linq->get(), true) . '</pre>';
/*
Array
(
    [0] => stdClass Object
        (
            [test] => 34
        )

    [1] => stdClass Object
        (
            [test] => 35
        )

    [2] => stdClass Object
        (
            [test] => 36
        )

    [3] => stdClass Object
        (
            [test] => 37
        )

    [4] => stdClass Object
        (
            [test] => 38
        )

    [5] => stdClass Object
        (
            [test] => 39
        )

    [6] => stdClass Object
        (
            [test] => 40
        )

    [7] => stdClass Object
        (
            [test] => 41
        )

    [8] => stdClass Object
        (
            [test] => 42
        )
) 
 */

```
