<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed request origins
    |--------------------------------------------------------------------------
    |
    | Indicates which origins are allowed to perform requests.
    |
    */

    'allow_origins' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed HTTP headers
    |--------------------------------------------------------------------------
    |
    | Indicates which HTTP headers are allowed.
    |
    */

    'allow_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'Application'],

    /*
    |--------------------------------------------------------------------------
    | Allowed HTTP methods
    |--------------------------------------------------------------------------
    |
    | Indicates which HTTP methods are allowed.
    |
    */

    'allow_methods' => ['POST', 'GET', 'OPTIONS', 'PUT', 'DELETE'],

    /*
    |--------------------------------------------------------------------------
    | Whether or not the response can be exposed when credentials are present
    |--------------------------------------------------------------------------
    |
    | Indicates whether or not the response to the request can be exposed when the credentials flag is true.
    | When used as part of a response to a preflight request, this indicates whether or not the actual request
    | can be made using credentials.  Note that simple GET requests are not preflighted, and so if a request
    | is made for a resource with credentials, if this header is not returned with the resource, the response
    | is ignored by the browser and not returned to web content.
    |
    */

    'allow_credentials' => true,

    /*
    |--------------------------------------------------------------------------
    | Exposed headers
    |--------------------------------------------------------------------------
    |
    | Headers that are allowed to be exposed to the web server.
    |
    */

    'expose_headers' => ['Access-Control-Allow-Origin',
        'Access-Control-Allow-Methods',
        'Access-Control-Allow-Credentials',
        'Access-Control-Max-Age',
        'Access-Control-Allow-Headers'],

    /*
    |--------------------------------------------------------------------------
    | Max age
    |--------------------------------------------------------------------------
    |
    | Indicates how long the results of a preflight request can be cached.
    |
    */

    'max_age' => 0,
    ];
