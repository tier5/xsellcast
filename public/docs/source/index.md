---
title: XSellCast API Reference

language_tabs:
- bash
- javascript
- php

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>By Caffeine Interactive</a>
---
<!-- START_INFO -->
# Introduction

Welcome to XSellCast API documentation.
<!-- END_INFO -->

#Brand

Brand resource.
<!-- START_ac4403f8bf71ab01d05f2eca4cba5f68 -->
## All

Get a list of brands.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/brands" \
-H "Accept: application/json" \
    -d "access_token"="aut" \
    -d "page"="99751627" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/brands",
    "method": "GET",
    "data": {
        "access_token": "aut",
        "page": 99751627
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/brands`

`HEAD api/v1/brands`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    page | integer |  optional  | 

<!-- END_ac4403f8bf71ab01d05f2eca4cba5f68 -->
<!-- START_0669638b3a92accc17ca0ea8f473a929 -->
## Single

Get a brand by ID.
Return 404 if dealer doesn't exist.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/brand/{id}" \
-H "Accept: application/json" \
    -d "access_token"="error" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/brand/{id}",
    "method": "GET",
    "data": {
        "access_token": "error"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/brand/{id}`

`HEAD api/v1/brand/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_0669638b3a92accc17ca0ea8f473a929 -->
#Customer

Customer resource.
<!-- START_7dcb2e835d08b8605b7005d3fa447cf1 -->
## All

Get a list of customers.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customers" \
-H "Accept: application/json" \
    -d "access_token"="deserunt" \
    -d "page"="64069" \
    -d "limit"="64069" \
    -d "sort"="deserunt" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customers",
    "method": "GET",
    "data": {
        "access_token": "deserunt",
        "page": 64069,
        "limit": 64069,
        "sort": "deserunt"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/customers`

`HEAD api/v1/customers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    page | integer |  optional  | 
    limit | integer |  optional  | 
    sort | string |  optional  | 

<!-- END_7dcb2e835d08b8605b7005d3fa447cf1 -->
<!-- START_fa5ededd2926210969a3debb3471f61b -->
## Single

Get a customer by ID.

Return 404 if offer doesn't exist.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer/{id}" \
-H "Accept: application/json" \
    -d "access_token"="dolorem" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer/{id}",
    "method": "GET",
    "data": {
        "access_token": "dolorem"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/customer/{id}`

`HEAD api/v1/customer/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_fa5ededd2926210969a3debb3471f61b -->
<!-- START_d2453e4a18a3a2fb848370014939199a -->
## Brand Associates

Get a list of brand associates related to a customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer/{id}/brand-associates" \
-H "Accept: application/json" \
    -d "access_token"="eligendi" \
    -d "page"="3269477" \
    -d "limit"="3269477" \
    -d "filter_by"="rejected" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer/{id}/brand-associates",
    "method": "GET",
    "data": {
        "access_token": "eligendi",
        "page": 3269477,
        "limit": 3269477,
        "filter_by": "rejected"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/customer/{id}/brand-associates`

`HEAD api/v1/customer/{id}/brand-associates`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    page | integer |  optional  | 
    limit | integer |  optional  | 
    filter_by | string |  optional  | `approved` or `rejected`

<!-- END_d2453e4a18a3a2fb848370014939199a -->
<!-- START_380e75e17d64e5c54d8b4908e7334b30 -->
## Offers (lookbook)

Get a list of offers related to a customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer/{id}/offers" \
-H "Accept: application/json" \
    -d "access_token"="quae" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer/{id}/offers",
    "method": "GET",
    "data": {
        "access_token": "quae"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/customer/{id}/offers`

`HEAD api/v1/customer/{id}/offers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_380e75e17d64e5c54d8b4908e7334b30 -->
<!-- START_5c99dc8d1bba95d3ccddc791908c01ce -->
## Add Offer

Add an offer related to a customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer/offer" \
-H "Accept: application/json" \
    -d "access_token"="dolores" \
    -d "customer_id"="dolores" \
    -d "offer_id"="dolores" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer/offer",
    "method": "POST",
    "data": {
        "access_token": "dolores",
        "customer_id": "dolores",
        "offer_id": "dolores"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/customer/offer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    offer_id | string |  required  | 

<!-- END_5c99dc8d1bba95d3ccddc791908c01ce -->
<!-- START_d353f0c9b2a499f8dda5d44a23ce6adb -->
## Delete Offer

Delete an offer related to customer.
The @parameter $_method is required and value must set to <strong>DELETE</strong>.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer/offer" \
-H "Accept: application/json" \
    -d "access_token"="neque" \
    -d "customer_id"="13" \
    -d "offer_id"="13" \
    -d "_method"="DELETE" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer/offer",
    "method": "DELETE",
    "data": {
        "access_token": "neque",
        "customer_id": 13,
        "offer_id": 13,
        "_method": "DELETE"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/v1/customer/offer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | integer |  required  | 
    offer_id | integer |  required  | 
    _method | string |  required  | `DELETE`

<!-- END_d353f0c9b2a499f8dda5d44a23ce6adb -->
<!-- START_6f3777f6f7b214f3be250b794dec2a1a -->
## Create

Create a new customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer" \
-H "Accept: application/json" \
    -d "access_token"="sit" \
    -d "wp_userid"="sit" \
    -d "address1"="sit" \
    -d "address2"="sit" \
    -d "zip"="sit" \
    -d "city"="sit" \
    -d "state"="sit" \
    -d "geo_long"="sit" \
    -d "geo_lat"="sit" \
    -d "email"="sit" \
    -d "firstname"="sit" \
    -d "lastname"="sit" \
    -d "homephone"="sit" \
    -d "cellphone"="sit" \
    -d "officephone"="sit" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer",
    "method": "POST",
    "data": {
        "access_token": "sit",
        "wp_userid": "sit",
        "address1": "sit",
        "address2": "sit",
        "zip": "sit",
        "city": "sit",
        "state": "sit",
        "geo_long": "sit",
        "geo_lat": "sit",
        "email": "sit",
        "firstname": "sit",
        "lastname": "sit",
        "homephone": "sit",
        "cellphone": "sit",
        "officephone": "sit"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/customer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    wp_userid | string |  required  | 
    address1 | string |  required  | 
    address2 | string |  optional  | 
    zip | string |  required  | 
    city | string |  required  | 
    state | string |  required  | 
    geo_long | string |  optional  | 
    geo_lat | string |  optional  | 
    email | string |  required  | 
    firstname | string |  required  | 
    lastname | string |  required  | 
    homephone | string |  optional  | 
    cellphone | string |  optional  | 
    officephone | string |  optional  | 

<!-- END_6f3777f6f7b214f3be250b794dec2a1a -->
<!-- START_87f719923324af7d038cfc55afb9dc51 -->
## Update

Update an existing customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer" \
-H "Accept: application/json" \
    -d "access_token"="nemo" \
    -d "customer_id"="nemo" \
    -d "wp_userid"="nemo" \
    -d "address1"="nemo" \
    -d "address2"="nemo" \
    -d "zip"="nemo" \
    -d "city"="nemo" \
    -d "state"="nemo" \
    -d "geo_long"="nemo" \
    -d "geo_lat"="nemo" \
    -d "email"="nemo" \
    -d "firstname"="nemo" \
    -d "lastname"="nemo" \
    -d "cellphone"="nemo" \
    -d "officephone"="nemo" \
    -d "homephone"="nemo" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer",
    "method": "PUT",
    "data": {
        "access_token": "nemo",
        "customer_id": "nemo",
        "wp_userid": "nemo",
        "address1": "nemo",
        "address2": "nemo",
        "zip": "nemo",
        "city": "nemo",
        "state": "nemo",
        "geo_long": "nemo",
        "geo_lat": "nemo",
        "email": "nemo",
        "firstname": "nemo",
        "lastname": "nemo",
        "cellphone": "nemo",
        "officephone": "nemo",
        "homephone": "nemo"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/v1/customer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | Valid user_customer id
    wp_userid | string |  optional  | 
    address1 | string |  optional  | 
    address2 | string |  optional  | 
    zip | string |  optional  | 
    city | string |  optional  | 
    state | string |  optional  | 
    geo_long | string |  optional  | 
    geo_lat | string |  optional  | 
    email | string |  optional  | 
    firstname | string |  optional  | 
    lastname | string |  optional  | 
    cellphone | string |  optional  | 
    officephone | string |  optional  | 
    homephone | string |  optional  | 

<!-- END_87f719923324af7d038cfc55afb9dc51 -->
<!-- START_03e521f677383ad2d5d6a0315e2fa0a3 -->
## Delete

Delete an existing customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/customer" \
-H "Accept: application/json" \
    -d "access_token"="a" \
    -d "customer_id"="194585763" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/customer",
    "method": "DELETE",
    "data": {
        "access_token": "a",
        "customer_id": 194585763
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/v1/customer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | integer |  required  | 

<!-- END_03e521f677383ad2d5d6a0315e2fa0a3 -->
#Dealer

Dealer resource.
<!-- START_543a559c79b5e89127ea48a14d17ccbc -->
## All

Get a list of dealers.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/dealers" \
-H "Accept: application/json" \
    -d "access_token"="dolor" \
    -d "page"="60" \
    -d "zip"="dolor" \
    -d "limit"="60" \
    -d "category"="dolor" \
    -d "sort"="desc" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/dealers",
    "method": "GET",
    "data": {
        "access_token": "dolor",
        "page": 60,
        "zip": "dolor",
        "limit": 60,
        "category": "dolor",
        "sort": "desc"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/dealers`

`HEAD api/v1/dealers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    page | integer |  optional  | 
    zip | string |  optional  | 
    limit | integer |  optional  | 
    category | string |  optional  | 
    sort | string |  optional  | `desc` or `asc`

<!-- END_543a559c79b5e89127ea48a14d17ccbc -->
<!-- START_8c793e4127650186f15d88232265a0f9 -->
## Single

Get a dealer by ID.
Return 404 if dealer doesn't exist.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/dealer/{id}" \
-H "Accept: application/json" \
    -d "access_token"="optio" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/dealer/{id}",
    "method": "GET",
    "data": {
        "access_token": "optio"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/dealer/{id}`

`HEAD api/v1/dealer/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_8c793e4127650186f15d88232265a0f9 -->
<!-- START_c0ee645f33bf5bb6cafa4b1e8ee7427b -->
## Brands

Get brands related to a dealer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/dealer/{id}/brands" \
-H "Accept: application/json" \
    -d "access_token"="asperiores" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/dealer/{id}/brands",
    "method": "GET",
    "data": {
        "access_token": "asperiores"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/dealer/{id}/brands`

`HEAD api/v1/dealer/{id}/brands`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_c0ee645f33bf5bb6cafa4b1e8ee7427b -->
<!-- START_fa69673224d8a5249450c58b3054db28 -->
## Brand Associates

Get brand associates related to a dealer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/dealer/{id}/brand-associates" \
-H "Accept: application/json" \
    -d "access_token"="reprehenderit" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/dealer/{id}/brand-associates",
    "method": "GET",
    "data": {
        "access_token": "reprehenderit"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/dealer/{id}/brand-associates`

`HEAD api/v1/dealer/{id}/brand-associates`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_fa69673224d8a5249450c58b3054db28 -->
#Direct Message

Messages resource.
<!-- START_8c7a1bec0469ad06fea3896c7a53b10e -->
## Sent

Get list of direct messages sent by a customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message" \
-H "Accept: application/json" \
    -d "access_token"="facilis" \
    -d "customer_id"="facilis" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message",
    "method": "GET",
    "data": {
        "access_token": "facilis",
        "customer_id": "facilis"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/direct-message`

`HEAD api/v1/messages/direct-message`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 

<!-- END_8c7a1bec0469ad06fea3896c7a53b10e -->
<!-- START_fcdb2d44e6d3bf4021b13c4d53390874 -->
## Single

Get a message of a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message/show" \
-H "Accept: application/json" \
    -d "access_token"="illo" \
    -d "customer_id"="illo" \
    -d "message_id"="illo" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message/show",
    "method": "GET",
    "data": {
        "access_token": "illo",
        "customer_id": "illo",
        "message_id": "illo"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/direct-message/show`

`HEAD api/v1/messages/direct-message/show`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    message_id | string |  required  | 

<!-- END_fcdb2d44e6d3bf4021b13c4d53390874 -->
<!-- START_cce4c0c6aa8329f185366d0eee1bb30a -->
## Mark as Read

Mark a message as read.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message/mark-as-read" \
-H "Accept: application/json" \
    -d "access_token"="id" \
    -d "customer_id"="id" \
    -d "message_id"="id" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message/mark-as-read",
    "method": "POST",
    "data": {
        "access_token": "id",
        "customer_id": "id",
        "message_id": "id"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/messages/direct-message/mark-as-read`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    message_id | string |  required  | 

<!-- END_cce4c0c6aa8329f185366d0eee1bb30a -->
<!-- START_26fc9b3f94e169f3c1dc91f77aea9f29 -->
## Create

Create (send) a message to Sales Rep from a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message/store" \
-H "Accept: application/json" \
    -d "access_token"="voluptatum" \
    -d "customer_id"="voluptatum" \
    -d "salesrep_id"="voluptatum" \
    -d "body"="voluptatum" \
    -d "offer_id"="voluptatum" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/direct-message/store",
    "method": "POST",
    "data": {
        "access_token": "voluptatum",
        "customer_id": "voluptatum",
        "salesrep_id": "voluptatum",
        "body": "voluptatum",
        "offer_id": "voluptatum"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/messages/direct-message/store`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | Valid user_customer id
    salesrep_id | string |  required  | Valid user_salesrep id
    body | string |  required  | 
    offer_id | string |  optional  | Valid offer id

<!-- END_26fc9b3f94e169f3c1dc91f77aea9f29 -->
#Offer

Offer resource.
<!-- START_c233fc34839427dff7ef9ad7c3821ae3 -->
## All

Get a list of offers.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/offers" \
-H "Accept: application/json" \
    -d "access_token"="rerum" \
    -d "limit"="796437556" \
    -d "sort"="asc" \
    -d "page"="796437556" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/offers",
    "method": "GET",
    "data": {
        "access_token": "rerum",
        "limit": 796437556,
        "sort": "asc",
        "page": 796437556
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/offers`

`HEAD api/v1/offers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    limit | integer |  optional  | 
    sort | string |  optional  | `desc` or `asc`
    page | integer |  optional  | 

<!-- END_c233fc34839427dff7ef9ad7c3821ae3 -->
<!-- START_20f7dcc2be313226fef14601da7c335a -->
## Single

Get an offer by ID.
Return 404 if offer doesn't exist.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/offer/{id}" \
-H "Accept: application/json" \
    -d "access_token"="voluptas" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/offer/{id}",
    "method": "GET",
    "data": {
        "access_token": "voluptas"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/offer/{id}`

`HEAD api/v1/offer/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_20f7dcc2be313226fef14601da7c335a -->
<!-- START_2ca8d97a1326bf5f0ae6f768a7394ea5 -->
## Create

Create an offer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/offer" \
-H "Accept: application/json" \
    -d "access_token"="dolore" \
    -d "contents"="dolore" \
    -d "thumbnail_url"="http://www.morar.com/sed-laboriosam-et-non-unde" \
    -d "media"="dolore" \
    -d "status"="publish" \
    -d "title"="dolore" \
    -d "wpid"="dolore" \
    -d "brand_id"="dolore" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/offer",
    "method": "POST",
    "data": {
        "access_token": "dolore",
        "contents": "dolore",
        "thumbnail_url": "http:\/\/www.morar.com\/sed-laboriosam-et-non-unde",
        "media": "dolore",
        "status": "publish",
        "title": "dolore",
        "wpid": "dolore",
        "brand_id": "dolore"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/offer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    contents | string |  required  | 
    thumbnail_url | url |  optional  | 
    media | string |  optional  | 
    status | string |  required  | `publish`, `draft` or `pending`
    title | string |  required  | 
    wpid | string |  optional  | 
    brand_id | string |  required  | Valid brand id

<!-- END_2ca8d97a1326bf5f0ae6f768a7394ea5 -->
<!-- START_ae22be56456b84d4cad3d4fb056c6c4e -->
## Delete

Delete an existing offer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/offer" \
-H "Accept: application/json" \
    -d "access_token"="inventore" \
    -d "offer_id"="inventore" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/offer",
    "method": "DELETE",
    "data": {
        "access_token": "inventore",
        "offer_id": "inventore"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/v1/offer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    offer_id | string |  required  | Valid offer id

<!-- END_ae22be56456b84d4cad3d4fb056c6c4e -->
<!-- START_8a1eacfab996672031431329c01b589f -->
## Update

Update an existing offer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/offer" \
-H "Accept: application/json" \
    -d "access_token"="consectetur" \
    -d "contents"="consectetur" \
    -d "thumbnail_url"="http://kiehn.biz/" \
    -d "media"="consectetur" \
    -d "status"="publish" \
    -d "title"="consectetur" \
    -d "wpid"="consectetur" \
    -d "brand_id"="consectetur" \
    -d "offer_id"="consectetur" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/offer",
    "method": "PUT",
    "data": {
        "access_token": "consectetur",
        "contents": "consectetur",
        "thumbnail_url": "http:\/\/kiehn.biz\/",
        "media": "consectetur",
        "status": "publish",
        "title": "consectetur",
        "wpid": "consectetur",
        "brand_id": "consectetur",
        "offer_id": "consectetur"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/v1/offer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    contents | string |  optional  | 
    thumbnail_url | url |  optional  | 
    media | string |  optional  | 
    status | string |  optional  | `publish`, `draft` or `pending`
    title | string |  optional  | 
    wpid | string |  optional  | Required if the parameters `offer_id` are not present.
    brand_id | string |  optional  | Valid brand id
    offer_id | string |  optional  | Valid offer id

<!-- END_8a1eacfab996672031431329c01b589f -->
#Request Appointment

Request Appointment resource.
<!-- START_3fff70a2d18e768910f534d02e7ae861 -->
## All

Get a list of all appointment requests by a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/appt" \
-H "Accept: application/json" \
    -d "access_token"="officia" \
    -d "customer_id"="officia" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/appt",
    "method": "GET",
    "data": {
        "access_token": "officia",
        "customer_id": "officia"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/appt`

`HEAD api/v1/messages/request/appt`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 

<!-- END_3fff70a2d18e768910f534d02e7ae861 -->
<!-- START_fae4b9e88c3cb640fa513d3fdb8f1cc8 -->
## Single

Get an appointment request of a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/appt/{id}" \
-H "Accept: application/json" \
    -d "access_token"="quo" \
    -d "customer_id"="quo" \
    -d "message_id"="quo" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/appt/{id}",
    "method": "GET",
    "data": {
        "access_token": "quo",
        "customer_id": "quo",
        "message_id": "quo"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/appt/{id}`

`HEAD api/v1/messages/request/appt/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    message_id | string |  required  | 

<!-- END_fae4b9e88c3cb640fa513d3fdb8f1cc8 -->
<!-- START_37dad56141ee3a79bfe19c81f15ffb0f -->
## Create

Create (send) an appointment request to Sales Rep from a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/appt/store" \
-H "Accept: application/json" \
    -d "access_token"="ut" \
    -d "customer_id"="ut" \
    -d "offer_id"="ut" \
    -d "body"="ut" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/appt/store",
    "method": "POST",
    "data": {
        "access_token": "ut",
        "customer_id": "ut",
        "offer_id": "ut",
        "body": "ut"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/messages/request/appt/store`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    offer_id | string |  required  | 
    body | string |  required  | 

<!-- END_37dad56141ee3a79bfe19c81f15ffb0f -->
#Request Contact

Request Contact resource.
<!-- START_94db1f4f5d78840d6e695558802b8bbc -->
## All

Get a list of all contact requests by a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/contact_me" \
-H "Accept: application/json" \
    -d "access_token"="tempora" \
    -d "customer_id"="tempora" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/contact_me",
    "method": "GET",
    "data": {
        "access_token": "tempora",
        "customer_id": "tempora"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/contact_me`

`HEAD api/v1/messages/request/contact_me`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 

<!-- END_94db1f4f5d78840d6e695558802b8bbc -->
<!-- START_d741b7fd0edc85f0799dde9aebb2dd96 -->
## Single

Get an contact request of a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/contact_me/{id}" \
-H "Accept: application/json" \
    -d "access_token"="eum" \
    -d "customer_id"="eum" \
    -d "message_id"="eum" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/contact_me/{id}",
    "method": "GET",
    "data": {
        "access_token": "eum",
        "customer_id": "eum",
        "message_id": "eum"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/contact_me/{id}`

`HEAD api/v1/messages/request/contact_me/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    message_id | string |  required  | 

<!-- END_d741b7fd0edc85f0799dde9aebb2dd96 -->
<!-- START_74fe547aae058228400c9bba267412d9 -->
## Create

Create (send) an contact request to Sales Rep from a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/contact_me/store" \
-H "Accept: application/json" \
    -d "access_token"="vel" \
    -d "customer_id"="vel" \
    -d "phone_number"="vel" \
    -d "offer_id"="vel" \
    -d "body"="vel" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/contact_me/store",
    "method": "POST",
    "data": {
        "access_token": "vel",
        "customer_id": "vel",
        "phone_number": "vel",
        "offer_id": "vel",
        "body": "vel"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/messages/request/contact_me/store`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    phone_number | string |  required  | 
    offer_id | string |  required  | 
    body | string |  required  | 

<!-- END_74fe547aae058228400c9bba267412d9 -->
#Request Info

Request Info resource.
<!-- START_3d2daaa7d7e7ae32d313176113e693e1 -->
## All

Get a list of all info requests by a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/info" \
-H "Accept: application/json" \
    -d "access_token"="aut" \
    -d "customer_id"="aut" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/info",
    "method": "GET",
    "data": {
        "access_token": "aut",
        "customer_id": "aut"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/info`

`HEAD api/v1/messages/request/info`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 

<!-- END_3d2daaa7d7e7ae32d313176113e693e1 -->
<!-- START_d758341a0d2cf6e3fc403435c4f8f50d -->
## Single

Get an info request of a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/info/{id}" \
-H "Accept: application/json" \
    -d "access_token"="quidem" \
    -d "customer_id"="quidem" \
    -d "message_id"="quidem" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/info/{id}",
    "method": "GET",
    "data": {
        "access_token": "quidem",
        "customer_id": "quidem",
        "message_id": "quidem"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/info/{id}`

`HEAD api/v1/messages/request/info/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    message_id | string |  required  | 

<!-- END_d758341a0d2cf6e3fc403435c4f8f50d -->
<!-- START_6999b14b76974d07283f216bcaf8b77f -->
## Create

Create (send) an info request to Sales Rep from a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/info/store" \
-H "Accept: application/json" \
    -d "access_token"="autem" \
    -d "customer_id"="autem" \
    -d "offer_id"="autem" \
    -d "body"="autem" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/info/store",
    "method": "POST",
    "data": {
        "access_token": "autem",
        "customer_id": "autem",
        "offer_id": "autem",
        "body": "autem"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/messages/request/info/store`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    offer_id | string |  required  | 
    body | string |  required  | 

<!-- END_6999b14b76974d07283f216bcaf8b77f -->
#Request Price

Request Price resource.
<!-- START_531a09c6f3c5a9922bf11a567a6c0c82 -->
## All

Get a list of all price requests by a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/price" \
-H "Accept: application/json" \
    -d "access_token"="tempora" \
    -d "customer_id"="tempora" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/price",
    "method": "GET",
    "data": {
        "access_token": "tempora",
        "customer_id": "tempora"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/price`

`HEAD api/v1/messages/request/price`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 

<!-- END_531a09c6f3c5a9922bf11a567a6c0c82 -->
<!-- START_3e3011daacac96c6e4db52096c775f71 -->
## Single

Get a price request of a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/price/{id}" \
-H "Accept: application/json" \
    -d "access_token"="vero" \
    -d "customer_id"="vero" \
    -d "message_id"="vero" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/price/{id}",
    "method": "GET",
    "data": {
        "access_token": "vero",
        "customer_id": "vero",
        "message_id": "vero"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/messages/request/price/{id}`

`HEAD api/v1/messages/request/price/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    message_id | string |  required  | 

<!-- END_3e3011daacac96c6e4db52096c775f71 -->
<!-- START_c5c2d19c7720d08397f09ffc1b77fb1d -->
## Create

Create (send) a price request to Sales Rep from a specific customer.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/price/store" \
-H "Accept: application/json" \
    -d "access_token"="omnis" \
    -d "customer_id"="omnis" \
    -d "offer_id"="omnis" \
    -d "body"="omnis" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/messages/request/price/store",
    "method": "POST",
    "data": {
        "access_token": "omnis",
        "customer_id": "omnis",
        "offer_id": "omnis",
        "body": "omnis"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/messages/request/price/store`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    customer_id | string |  required  | 
    offer_id | string |  required  | 
    body | string |  required  | 

<!-- END_c5c2d19c7720d08397f09ffc1b77fb1d -->
#Sales Rep(BA)

Sales Rep resource.
<!-- START_db928a4bd903bf7c77f4075fffeb7c4f -->
## All

Get a list of sales reps.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/brand-associates" \
-H "Accept: application/json" \
    -d "access_token"="et" \
    -d "limit"="537109550" \
    -d "sort"="asc" \
    -d "page"="537109550" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/brand-associates",
    "method": "GET",
    "data": {
        "access_token": "et",
        "limit": 537109550,
        "sort": "asc",
        "page": 537109550
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/brand-associates`

`HEAD api/v1/brand-associates`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    limit | integer |  optional  | 
    sort | string |  optional  | `desc` or `asc`
    page | integer |  optional  | 

<!-- END_db928a4bd903bf7c77f4075fffeb7c4f -->
<!-- START_e4908c651ef5c15576a1cf183523960e -->
## Single

Get sales rep basic information.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/brand-associate/{id}" \
-H "Accept: application/json" \
    -d "access_token"="autem" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/brand-associate/{id}",
    "method": "GET",
    "data": {
        "access_token": "autem"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/brand-associate/{id}`

`HEAD api/v1/brand-associate/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_e4908c651ef5c15576a1cf183523960e -->
<!-- START_0a941ba143269ad38f2252af1728039d -->
## Customers

Get a list of customers related to BA.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/brand-associate/{id}/customers" \
-H "Accept: application/json" \
    -d "access_token"="non" \
    -d "limit"="603181798" \
    -d "sort"="asc" \
    -d "page"="603181798" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/brand-associate/{id}/customers",
    "method": "GET",
    "data": {
        "access_token": "non",
        "limit": 603181798,
        "sort": "asc",
        "page": 603181798
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "error": "invalid_request",
    "error_description": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the \"access token\" parameter."
}
```

### HTTP Request
`GET api/v1/brand-associate/{id}/customers`

`HEAD api/v1/brand-associate/{id}/customers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 
    limit | integer |  optional  | 
    sort | string |  optional  | `desc` or `asc`
    page | integer |  optional  | 

<!-- END_0a941ba143269ad38f2252af1728039d -->
#User

User resource.
<!-- START_080f3ecebb7bcc2f93284b8f5ae1ac3b -->
## All

Get a list of users.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/users" \
-H "Accept: application/json" \
    -d "access_token"="est" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/users",
    "method": "GET",
    "data": {
        "access_token": "est"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "access_token": [
        "The access token field is required."
    ]
}
```

### HTTP Request
`GET api/v1/users`

`HEAD api/v1/users`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    access_token | string |  required  | 

<!-- END_080f3ecebb7bcc2f93284b8f5ae1ac3b -->
<!-- START_eacf91ed954a61c54ef195ee35b6e4e1 -->
## Create

Create a new user.

> Example request:

```bash
curl "http://xsellcast.caffeineinteractive.net/api/v1/users/store" \
-H "Accept: application/json" \
    -d "firstname"="nam" \
    -d "lastname"="nam" \
    -d "access_token"="nam" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://xsellcast.caffeineinteractive.net/api/v1/users/store",
    "method": "POST",
    "data": {
        "firstname": "nam",
        "lastname": "nam",
        "access_token": "nam"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/users/store`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    firstname | string |  required  | Maximum: `255`
    lastname | string |  required  | Maximum: `255`
    access_token | string |  required  | 

<!-- END_eacf91ed954a61c54ef195ee35b6e4e1 -->
