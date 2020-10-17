<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=' . getenv('DATABASE_HOST') . ';dbname=' . getenv('DATABASE_TEST_NAME');
$db['username'] = getenv('DATABASE_USER');
$db['password'] = getenv('DATABASE_PASSWORD');

return $db;
