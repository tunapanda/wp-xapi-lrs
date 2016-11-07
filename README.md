# wp-xapi-lrs
Enables your WordPress site to act as an xAPI Learning Record Store.

* [Introduction](#introduction)
* [How to use](#how-to-use)

## Introduction

This WordPress plugin enables your WordPress site to act as an [xAPI](https://en.wikipedia.org/wiki/Experience_API_(Tin_Can_API)) enabled Learning Record Store. It relies on the [minixapi](https://github.com/limikael/minixapi/) library. At the time of writing, the support is very basic. You can put statements in the database, and retreive them with somce very basic filtering, but that's about it. It is possible to filter statements based on `agent`, `verb`, `activity`, `statementId` and `related_activities`, which is a subset from the complete list of filters found [here](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Communication.md#213-get-statements).

## How to use

Go to _Settings >> xAPI LRS_ to see the url for the xAPI endpoint, as well credentials for the newly created LRS on your system. The username and password will be randomly generated upon installation. We can try to access the endpoint using curl:

    curl "http://8260a014ad6016ba2af2ed0c0f7684e0:7078d3dc378947905994affa86c20d48@localhost/wordpress/wp-content/plugins/wp-xapi-lrs/endpoint.php/"
    
    {
        "error": true,
        "message": "Expected xAPI method, try appending \/statements to the url."
    }

This error message is given because according to the [xAPI standard](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Communication.md#21-statement-resource) we need to specify which resource we want to access. Currently, the only implemented resource is the _statements_ resource, so let's try to access that:

    curl "http://8260a014ad6016ba2af2ed0c0f7684e0:7078d3dc378947905994affa86c20d48@localhost/wordpress/wp-content/plugins/wp-xapi-lrs/endpoint.php/statements"
    
    {
        "statements": []
    }
