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

This error message is given because according to the xAPI standard we need to specify which resource we want to access. Currently, the only implemented resource is the [statements](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Communication.md#21-statement-resource) resource, so let's try to access that:

    curl "http://8260a014ad6016ba2af2ed0c0f7684e0:7078d3dc378947905994affa86c20d48@localhost/wordpress/wp-content/plugins/wp-xapi-lrs/endpoint.php/statements"
    {
        "statements": []
    }

We are getting an empty list of statements back from the LRS, so something is working! Let's try to put a statements there. We can take the [hang gliding](https://experienceapi.com/statements-101/) example from Statements 101. The statement looks like this:

    {
        "actor": {
            "name": "Sally Glider",
            "mbox": "mailto:sally@example.com"
        },
        "verb": {
            "id": "http://adlnet.gov/expapi/verbs/experienced",
            "display": { "en-US": "experienced" }
        },
        "object": {
            "id": "http://example.com/activities/solo-hang-gliding",
            "definition": {
                "name": { "en-US": "Solo Hang Gliding" }
            }
        }
    }

Put the statement in a file called statement.json. Insert the statement using the following curl command:

    curl -X POST --data-binary @statement.json  "http://8260a014ad6016ba2af2ed0c0f7684e0:7078d3dc378947905994affa86c20d48@localhost/wordpress/wp-content/plugins/wp-xapi-lrs/endpoint.php/statements"
    [
        "e1dc2120-ca22-4500-96a2-611b32edfb70"
    ]

It worked! The data we got back is the [UUID](https://en.wikipedia.org/wiki/Universally_unique_identifier) for the statement. Let's try to retreive it:

    curl "http://8260a014ad6016ba2af2ed0c0f7684e0:7078d3dc378947905994affa86c20d48@localhost/wordpress/wp-content/plugins/wp-xapi-lrs/endpoint.php/statements"
    {
        "statements": [
            {
                "id": "e1dc2120-ca22-4500-96a2-611b32edfb70",
                "stored": "2016-11-07T09:44:53.000+00:00",
                "actor": {
                    "objectType": "Agent",
                    "name": "Sally Glider",
                    "mbox": "mailto:sally@example.com"
                },
                "verb": {
                    "id": "http:\/\/adlnet.gov\/expapi\/verbs\/experienced",
                    "display": {
                        "en-US": "experienced"
                    }
                },
                "timestamp": "2016-11-07T09:44:53.000+00:00",
                "object": {
                    "objectType": "Activity",
                    "id": "http:\/\/example.com\/activities\/solo-hang-gliding",
                    "definition": {
                        "name": {
                            "en-US": "Solo Hang Gliding"
                        }
                    }
                }
            }
        ]
    }

Yep, it seems like the statement is there!
