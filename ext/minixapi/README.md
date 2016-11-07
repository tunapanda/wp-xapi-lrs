# minixapi

An embeddable xAPI learning record store.

* [Introduction](#introduction)
* [Usage as a REST endpoint](#usage-as-a-rest-endpoint)
* [Usage as a library](#usage-as-a-library)

## Introduction

MiniXapi is an xAPI learning record store. It is common for xAPI record stores to store their data in a NoSQL database, such as MongoDB. MiniXapi is a bit different since it stores its data in a standard relational database, using the standard PDO class in PHP. It was designed this way in order to fit into the environment of traditional web applications, such as WordPress. It be used as a library, in order to be embedded into the web application. However, it can also expose an xAPI RESTful endpoint to be accessed according to the xAPI standard. It has no where near full xAPI compliance, it might get there some day, who knows. However, full compliance is not seen as a goal at the moment, it will grow on a need basis.

## Usage as a REST endpoint

This example shows how to use MiniXapi in the standard way as a RESTful endpoint. Create an `index.php` file somewhere, together with a `.htaccess` file that makes the `index.php` file catch all requests. You don't need to put MiniXapi inside this directory, put it somewhere where it can be included into the php file. Then put the following code in the `index.php` file:

```php
<?php

    require_once "<path to MiniXapi>/MiniXapi.php";

    // Create an instance of MiniXapi.
    $miniXapi=new MiniXapi();

    // Set the data service name to use.
    $miniXapi->setDsn("sqlite:mylrs.sqlite");

    // Make sure the database is set up.
    if (!$miniXapi->isInstalled())
        $miniXapi->install();

    // Serve incoming requests.
    $miniXapi->serve();
```

This is the basic idea. There are some more things we can do, such as setting up authentication. See the [reference documentation](https://limikael.github.io/minixapi/doc/) for details.

## Usage as a library

In order to embed MiniXapi into a web application, it is possible to use it as a library. There are two functions exposed for this purpose, `getStatements` and `putStatement`. The setup, e.g. specifying the database connection, is done in the same way as when MiniXapi is used as a RESTful endpoint. For more information, see the [reference documentation](https://limikael.github.io/minixapi/doc/).

The function `putStatement` is used to put a statement into the learning record store. The statement is represented by a PHP array. Even though the statement is an array, rather than JSON as is used by the xAPI standard, all fields used in the standard can be used in the array. 

```php
<?php

    require_once "<path to MiniXapi>/MiniXapi.php";

    // First we need to create an instance of MiniXapi and set the database
    // connection details.
    $miniXapi=new MiniXapi();
    $miniXapi->setDsn("sqlite:mylrs.sqlite");

    // Our statement.
    $statement=array(
        "actor"=>array("mbox"=>"mailto:sally@example.com"),
        "verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/experienced"),
        "object"=>array("id"=>"http://example.com/activities/solo-hang-gliding")
    );

    // Put the statement in the database.
    $statementId=$miniXapi->putStatement($statement);
```

The function `getStatements` is used to search for and retreive statements from the learning record store. This function accepts an array as input, and returns an array of matching statements. The fields of the array corresponds to the fields used when getting statements from an xAPI record store, see the [xAPI standard documentation](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Communication.md#213-get-statements) for details.

Please not that at the time of writing this functionality is very incomplete, and only the fields `agent`, `verb`, `activity`, `statementId` and `related_activities` work according to the standard.


```php
<?php

    require_once "<path to MiniXapi>/MiniXapi.php";

    // First we need to create an instance of MiniXapi and set the database
    // connection details.
    $miniXapi=new MiniXapi();
    $miniXapi->setDsn("sqlite:mylrs.sqlite");

    // Retreive all Sallys statements.
    $statements=$miniXapi->getStatements(array(
        "agent"=>array("mbox"=>"mailto:sally@example.com")
    ));

    // Retreive everything Sally has completed.
    $statements=$miniXapi->getStatements(array(
        "agent"=>array("mbox"=>"mailto:sally@example.com"),
        "verb"=>"http://adlnet.gov/expapi/verbs/completed"
    ));
```
