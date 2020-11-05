# ZxpLib

## Installation

```
composer require zxplib/zxplib
```

## Basic Usage

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use ZxpLib\Hello;

echo 'test: ';
echo Hello::world();
```

## Arrays

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use ZxpLib\Arrays;

$roles = [
    ['id' => 1, 'name' => '张无忌', 'age' => 23],
    ['id' => 6, 'name' => '向问天', 'age' => 51],
    ['id' => 8, 'name' => '韦一笑', 'age' => 49],
];

$result = Arrays::column($roles, 'name');
// ['向问天', '张无忌', '韦一笑']

$result = Arrays::column($roles, 'name', 'id');
// [1 => '张无忌', 6 => '向问天', 8 => '韦一笑']

$result = Arrays::column($roles, ['name', 'age']);
// [
//     'name' => ['张无忌', '向问天', '韦一笑'],
//     'age' => [23, 51, 49] 
// ]

$result = Arrays::column($roles, ['name', 'age'], 'id');
// [
//     1 => ['name' => '张无忌', 'age' => 23],
//     6 => ['name' => '向问天', 'age' => 51],
//     8 => ['name' => '韦一笑', 'age' => 49]
// ]

Arrays::multisort($roles, ['age' => 'SORT_ASC']);
// [
//     ['id' => 1, 'name' => '张无忌', 'age' => 23],
//     ['id' => 8, 'name' => '韦一笑', 'age' => 49],
//     ['id' => 6, 'name' => '向问天', 'age' => 51],
// ]

Arrays::multisort($roles, ['age' => 'SORT_ASC', 'id' => 'SORT_DESC']);
// [
//     ['id' => 8, 'name' => '韦一笑', 'age' => 49],
//     ['id' => 6, 'name' => '向问天', 'age' => 51],
//     ['id' => 1, 'name' => '张无忌', 'age' => 23],
// ]
```
