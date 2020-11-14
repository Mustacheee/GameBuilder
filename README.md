<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

Yii 2 Basic Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-basic.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-basic)

DIRECTORY STRUCTURE
-------------------

      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

* PHP 7.1
* [Docker](https://docs.docker.com/)
* [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)


INSTALLATION
------------
* Clone the Repo
* run `composer install`
* run `docker-machine start` if your docker machine is asleepin'
* run `eval $(docker-machine env)`
* run `docker-compose up -d`
* Check to make sure all containers are running
* run `chmod +x ./yii`
* run `chmod +x ./toolbox.sh`
* run `./toolbox.sh yii migrate`
* You should be able to make a POST request to `http://192.168.99.199/signup` (note: your base url may be different)
* Note: You may need to create the `./runtime/cache` directory if you get a punk ass exception error when making the request
* Once you have a user, you can make a POST request to `http://192.168.99.100/login` to login and retrieve a token
* Then... idk...
* adminer available at `http://192.168.99.100:8080`


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Test Setup

To execute acceptance tests do the following:  

1. Make sure you have the latest packages installed with `composer install`
2. `chmod +x tests/bin/yii` (First time only if needed)

3. Login as root and create the test database if it does not yet exist
* Option 1: CLI (TODO)
* Option 2: Adminer
    - Navigate to Adminer
    - Login with `root` and `password`
    - Click `SQL Command`
    - Enter `CREATE DATABASE growie_api_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
    - When that successfully runs, enter `GRANT ALL PRIVILEGES ON growie_api_test.* to 'growie_admin'@'%'`


### Running Tests
1. Make sure migrations are up to date with `./toolbox.sh yii-test migrate`
   The database configuration can be found at `config/test_db.php`.

2. Codeception can be used with the toolbox:  
    * `./toolbox.sh codecept run`
    * `./toolbox.sh codecept unit-tests`
    * `./toolbox.sh coverage` 
### Code coverage support

```
# shorthand
./toolbox.sh coverage

#collect coverage for all tests
./toolbox.sh codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
./toolbox.sh codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
./toolbox.sh codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
