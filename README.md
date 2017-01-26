# wp-xapi-lrs 
**Contributors:** Tunapanda  
**Donate link:** http://www.tunapanda.org/contribute  
**Tags:** xapi, admin, learning, integration, lms, learning management system  
**Requires at least:** 3.8.1  
**Tested up to:** 4.7.1  
**Stable tag:** trunk  
**License:** GPLv3  
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html  

Lets your WordPress site to act as an xAPI Learning Record Store.


## Description 
This WordPress plugin enables your WordPress site to act as an 
[xAPI](https://en.wikipedia.org/wiki/Experience_API_(Tin_Can_API)) enabled 
Learning Record Store. At the time of writing, the support is very basic. 
You can put statements in the database, and retreive them with some very basic 
filtering, but that's about it. It is possible to filter statements based on 
agent, verb, activity, statementId and related_activities, which is a subset 
from the complete list of filters found in the 
[xAPI standard](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Communication.md#213-get-statements).


### How to use 

After the plugin is installed, you will find a settings page called
_xAPI LRS_ in the _Settings_ section of the admin panel. On this page
you will find the endpoint as well as credentials that can be used to connect
to the LRS.