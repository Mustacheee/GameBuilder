<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname=' . getenv('MYSQL_TEST_DATABASE');
$db['username'] = getenv('MYSQL_USER');
$db['password'] = getenv('MYSQL_ROOT_PASSWORD');

return $db;
