SAFE CREATIVE API WRAPPER FOR PHP
---------------------------------

This is a simple wrapper for calling Safe Creative API methods from PHP.

CONTENTS
--------
- README.TXT
  This file, you already knew that
  
- LEEME.TXT
  Spanish version of this file

- LICENSE.TXT
  GPL 3.0 license text

- SafeCreativeAPI.inc.php
  The wrapper proper.
  This file must be included in your PHP code before using any of its functions

- SafeCreativeAPI.config.arena.php
  Config values for using "arena" testing API endpoint
  You should include this file before including SafeCreativeAPI.inc.php

- SafeCreativeAPI.config.production.php
  Config values for using production API endpoint
  You should include this file before including SafeCreativeAPI.inc.php

- simple.register.php
  Sample code for registering a work using this wrapper
  
- simple.search.php
  Sample code for searching for a work

- author.search.php
  Sample code for searching photographs from an author
  
- work.list.php
  Sample code for listing your Safe Creative registered works
	
	
USAGE
-----
The wrapper includes three main methods for calling a Safe Creative API component:

call($params, $apiUrl)
  General purpose method for calling API components
   $params: name-value map or URL-encoded string with API call parameters 
   $apiUrl: API endpoint URL. Defaults to the API_URL constant as defined in SafeCreativeAPI.config.*.php

callSigned($params, $ztime, $nonceKey, $privateKey, $apiUrl)
  For calling API components which require a signed request (signature parameter)
   $params: name-value map with API call parameters
   $ztime: if true, the API server ztime will be included in the call. Defaults to false.
   $nonceKey: if true, a nonceKey will be retrieved from the server 
              and included in the call. Defaults to false.
   $privateKey: private key to use for signing the call
   $apiUrl: API endpoint URL. Defaults to the API_URL constant as defined in SafeCreativeAPI.config.*.php
 
search($params, $apiUrl)
  Synonym of call, but $apiUrl defaults to the search endpoint, instead of the API main endpoint.

All these methods return the API response deserialized as an object.
In general the $params parameter should be constructed as follows:
$params = array(
		"component" => "component.name",
		"paramName1" => paramValue1
		...
	);
As shown, the component to call is included as a parameter in the $params array.
The ztime, nonceKey and signature parameters don't need to be included in the $params array,
as these parameters are automatically generated when using callSigned()


Other methods included in the wrapper:

getManageAuthkeyUrl($authKey,$privateKey,$authKeyLevel = ACCESS_LEVEL_MANAGE)
 Returns the URL where a user must be redirected to authorize an authKey.
  $authKey: authKey to be authorized
  $privateKey: private key associated to the authKey
  $authKeyLevel: trust level requested for the authKey 
                 (must be one of ACCESS_LEVEL_GET, ACCESS_LEVEL_ADD or ACCESS_LEVEL_MANAGE)
                 
isAuthorized($authKey)
 Returns whether the specified $authKey has been authorized by a user
 
getZTime()
 Returns the ztime value from the API server.

getAuthKeyState($authKey)
 Calls authkey.state for the specified $authKey

getNonceKey($authKey)
 Requests a nonceKey associated to the $authKey

signParams($params, $privateKey)
 Calculates the call signature for the specified parameters 
 and returns the full request string to use in the API call



SAMPLES
-------

For running the samples you just need to copy them in a web server with a PHP interpreter.

Before using the samples you should create an API key by visiting:
http://arena.safecreative.org/new-api-key

Copy the shared key and private key values in SafeCreativeAPI.config.arena.php:
 define(PRIVATE_KEY,"your-private-key");
 define(SHARED_KEY,"your-shared-key");

We recommend to run the samples in the following order:

*.search.php
 This is the most simple operation. 
 For search operations, you don't even need to have a sharedKey, so it works "out of the box"
 
simple.register.php
 Once you have defined a sharedKey in the config files you can run this sample.
 The first time you run it, it will request and show you a new authKey with its privateKey.
 You must copy this values in SafeCreativeAPI.config.arena.php:
  define(AUTH_PRIVATE_KEY,"your-auth-private-key");
  define(AUTH_KEY,"your-auth-key"); 
 You should also visit the generated link to authorize your application to use your 
 Safe Creative (arena) account.
 
 After you have done all this, you can run the sample again.
 This time it will register a work titled "El Quijote" with the text "En un lugar de la mancha.."
 
 In a real application, you should store your authkeys and their private keys in a database or similar.
 You should not use the config constants, as that would allow only one person to use your application.
 
work.list.php
 For this sample to work, you need to have an authkey and its privatekey defined in you config file.
 It will show a list with your registered works in your Safe Creative (arena) account.


All these samples are prepared to work with Safe Creative test environment, 
which is accesible at http://arena.safecreative.org

It is strongly discouraged to run samples and application tests through the production servers,
as they may generate fictitious work registrations.
