# wp-xapi-lrs
Enables your WordPress site to act as an xAPI Learning Record Store.

* [Introduction](#introduction)
* [How to use](#how-to-use)

## Introduction

This WordPress plugin enables your WordPress site to act as an [xAPI](https://en.wikipedia.org/wiki/Experience_API_(Tin_Can_API)) enabled Learning Record Store. It relies on the [minixapi](https://github.com/limikael/minixapi/) library. At the time of writing, the support is very basic. You can put statements in the database, and retreive them with somce very basic filtering, but that's about it. It is possible to filter statements based on `agent`, `verb`, `activity`, `statementId` and `related_activities`, which is a subset from the complete list of filters found [here](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Communication.md#213-get-statements).
