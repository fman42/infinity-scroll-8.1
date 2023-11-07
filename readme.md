# Large Object Transmission Control Protocol

## Abstract

Large Object Transmission Control Protocol (LOTCP) is a language-independent, synchronized communication format between client and server.

This document establishes a common standard for interoperability between applications and is the primary guide to interoperability (Functional compatibility).

## Status of This Memo

This document defines the LOTCP tracking protocol.

The development of this document is subject to the development of the following standards:

* RFC 7159 (JSON)

Distribution of this memo is limited (Only for internal use).

## Copyright Notice

MIT License

Copyright (c) 2023 Aleksei Vlasov, Vlad Saliy.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## Table of Contents

1. [Introduction](#1-introduction)
   1.1 [Requirements](#1-1-requirements)
   1.2 [Terminology](#1-2-terminology)
   1.3 [Overall Operation](#1-3-overall-operation)
2. [Naming Convention and Type Naming Convention](#2-naming-convention-and-type-naming-convention)
   2.1 [Naming Convention](#2-1-naming-convention)
   2.2 [Type Naming Convention](#2-2-type-naming-convention)
3. [Protocol Parameters](#3-protocol-parameters)
   3.1 [Request](#3-1-request)
   3.2 [Response](#3-2-response)
   3.3 [Filter](#3-3-filter)
   3.4 [Page](#3-4-page)
   3.5 [Header](#3-5-header)
   3.6 [Meta](#3-6-meta)
   3.7 [Select](#3-7-select)
   3.8 [Synchronization](#3-8-synchronization)




### 1. Introduction
#### 1.1 Requirements

The key words **"MUST"**, **"MUST NOT"**, **"REQUIRED"**, **"SHALL"**, **"SHALL NOT"**, **"SHOULD"**, **"SHOULD NOT"**, **"RECOMMENDED"**, **"MAY"**, and **"OPTIONAL"** in this document are to be interpreted as described in **RFC 2119**.

An implementation is not compliant if it fails to satisfy one or more of the **MUST** or **REQUIRED** level requirements for the protocols it implements. An implementation that satisfies all the **MUST** or **REQUIRED** level and all the **SHOULD** level requirements for its protocols is said to be "unconditionally compliant"; one that satisfies all the MUST level requirements but not all the **SHOULD** level requirements for its protocols is said to be "conditionally compliant."

#### 1.2 Terminology

- Request - Attempt to get data from the server.
- Response - Get data from the server.
- Client - A program that establishes connections for the purpose of sending requests.
- Server - An application program that serves requests by sending responses. Any given program can be both a client and a server.


#### 1.3 Overall Operation

The LOTCP protocol is a request/response protocol. The client sends a request to the server in the form of JSON via any communication channel. The server responds back to the client as a JSON with a label for synchronization.

The request is built on JSON with the necessary parameters defined in [Request](#3-1-request).

Server **MAY** ignore request parameters. The server **REQUIRED** send new request parameters. The client is **REQUIRED** to change the request parameters as required by the server.

The response is built on JSON with **REQUIRED** parameters defined in [Response](#3-2-response).

### 2. Naming Convention and Type Naming Convention
#### 2.1 Naming Convention

- payload - Transferred data.
- header - Additional parameters for data; Properties/Description of transmitted data.
- meta - Communication data for client and server synchronization.
- countItems - Quantity of elements.
- identifyKey - Name of the identification key.
- totalItems - How many elements are present on the server. How big the object is.
- page - Information about the page or information about which page you need to get.
- hasNext - Information that the object is not fully received.
- nextIdentify - Information about client and server synchronization.
- totalPages - Quantity of existing pages.
- currentPage - Current page.
- nextPage - Next page.
- prevPage - Previous page.
- to - Required number of elements.
- filter - Information about filtering object elements.
- select - Object processing. Building a map of the object.
- name - Name of the operation/function/object.
- value - Depends on the context. Value or related data.

#### 2.2 Type Naming Convention

#### boolean - Boolean - bool
> true
> bytes: 01110100 01110010 01110101 01100101  
> hex: 74 72 75 65

#### string - STRING - String
> ABC 123
> bytes: 01000001 01000010 01000011 00100000 00110001 00110010 00110011
> hex: 41 42 43 20 31 32 33

#### number - Integer - int
> 1
> bytes: 00110001
> hex: 31

#### array - Array - Data collection.
> ```
>   int[2] = {1, 2, 3}; // C++ or Java
>   [1,2,3]; // JavaScript or PHP
> ```

#### JSON | XML | BASE 64
> JSON - RFC 8259. XML - RFC 5364. BASE 64 - RFC 4648.


### 3. Protocol Parameters
#### 3.1 Request

> If you need to get a configuration without data parameter [to = -1].

| Parameters Names |Descriptions|
|------------------|---|
| identifyKey      | Which field in the object should be used for identification. |
| to               | Required number of elements. [to >= -1] [0 == null] [max = 50]|
| filter           | Object of filter. [About of Filter](#3-3-filter)|
| page             | Number of page. [About of Page](#3-4-page) |
| nextIdentify     | Notifies about the next elements pool. |
| select           | Building a map of the object. [About of Select](#3-7-select) |


```json
// Request object
{
   "identifyKey": "uuid",
   "to": 25,
   "filter": [
      {
         "name": "SORT",
         "identifyKey": "date",
         "value": "ASC"
      },
      {
         "name": "BETWEEN",
         "identifyKey": "date",
         "value": "[\"1970-01-01\",\"1970-12-31\"]"
      }
   ],
   "page": 1,
   "nextIdentify": "Ub7AZUwxNu...",
   "select": ["uuid", "name", "date"]
}
```

#### 3.2 Response

| Parameters Names |Descriptions|
|------------------|---|
| payload          | Transferred data. |
| header           | Description of transmitted data. [About of Header](#3-5-header) |
| meta             | Communication data for client and server synchronization. [About of Meta](#3-6-meta) |

```json
// Response object
{
   "payload": [
      {"uuid": "Ub7AZUwxNu..."},
      {"uuid": "AZNuUb7AZU..."}
   ],
   "header": {
      "countItems": 2,
      "identifyKey": "uuid"
   },
   "meta": {
      "totalItems": 100,
      "page":{
         "totalPages": 4,
         "currentPage": 1,
         "nextPage": 2,
         "prevPage": 0
      },
      "hasNext": true,
      "nextIdentify": "NuUbUwxNu..."
   }
}
```

#### 3.3 Filter

The filter is designed to filter data (sort). The server may ignore the filter due to its absence or due to incorrect data passed to the filter.
The minimum list of filters. Their presence is mandatory.

| Name of filter | Description or example                                              |
|----------------|---------------------------------------------------------------------|
| SORT           | Sorting data [value = "ASC" or "DESC"]                              |
| LIKE           | To search for the specified template in the column. [value=%text%]  |
| FIND           | Get an object by identification key.                                |
| WHEREIN        | To search for the specified match values in a list. [value=[1]]     |
| WHERENOTIN     | To search for the specified match values not in a list. [value=[1]] |
| BETWEEN        | To search for a value between. [value = {"from": "", "to": ""}]     |
| LESS           | x < value                                                           |
| OVER           | x > value                                                           |
| EQUAL_OR_LESS  | x <= value                                                          |
| EQUAL_OR_OVER  | x >= value                                                          |

Basic parameters for describing an object.

| Parameters Names |Descriptions|
|------------------|---|
| name             | Name of the filter. |
| identifyKey      | Name of the identification key for the filter. |
| value            | Value or related data. |

Example JSON
```json
// Filter object
{
   "name": "SORT",
   "identifyKey": "date",
   "value": "ASC"
}
```

#### 3.4 Page

The page describes the logic of the behavior of pages (like a book page).

This object depends on the parameters of the [to, page] request [About of Request](#3-1-request). If these parameters are missing, then the current object is not in the server response.

Basic parameters for describing an object.

| Parameters Names |Descriptions|
|------------------|---|
| totalPages       | Quantity of existing pages. |
| currentPage      | Current page. |
| nextPage         | Next page. |
| prevPage         | Previous page. |

Example JSON
```json
// Page object
{
   "totalPages": 4,
   "currentPage": 1,
   "nextPage": 2,
   "prevPage": 0
}
```

#### 3.5 Header

The header describes the data that came in the payload.

Basic parameters for describing an object.

| Parameters Names |Descriptions|
|------------------|---|
| countItems       | Quantity of elements in payload. |
| identifyKey      | Name of the identification key. The client obeys this value. |

Example JSON
```json
// Header object
{
   "countItems": 2,
   "identifyKey": "uuid"
}
```

#### 3.6 Meta

The meta object is used for coordination or for communication between the client and the server. This object is necessary for the client to understand how to get another piece of information.

Basic parameters for describing an object.

| Parameters Names |Descriptions|
|------------------|---|
| totalItems       | How many elements are present on the server. |
| page             | [About of Page](#3-4-page) |
| hasNext          | Information that the object is not fully received. |
| nextIdentify     | Information about client and server synchronization. |


Example JSON
```json
// Meta object
{
   "totalItems": 100,
   "page":{
      "totalPages": 4,
      "currentPage": 1,
      "nextPage": 2,
      "prevPage": 0
   },
   "hasNext": true,
   "nextIdentify": "NuUbUwxNu..."
}
```

#### 3.7 Select

Select is used to process the object. With this object, you can specify which object the client expects.

Example JSON
```json
// Select object
["uuid", "name", "date"]
```

#### 3.8 Synchronization

This protocol supports synchronization between the client and the server. Synchronization occurs every clock cycle (request/response). For premature synchronization, the client can request a zero clock cycle. Using [to = 0] will not give a result. Since 0 will be perceived as a lack of configuration. To get the result, use [to = -1]

> If you need to get a configuration without data parameter [to = -1].

Example Request
```json
// Request object
{
   "identifyKey": "",
   "to": -1
}
```

Example Response
```json
// Response object
{
   "payload": [],
   "header": {
      "countItems": 0,
      "identifyKey": "uuid"
   },
   "meta": {
      "totalItems": 100,
      "hasNext": true,
      "nextIdentify": "NuUbUwxNu..."
   }
}
```