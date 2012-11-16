This is a PHP interface to the Cooper-Hewitt collection API 
(https://collection.cooperhewitt.org/api/) by me (http://twitter.com/tarasyoung).

It contains the following functions, all of which return an array. Use print_r to see the results.

```
ch_request(args)              Directly request info from the API
ch_get(args)                  Send a GET url-style request
ch_search(query)              Search for things
ch_object(info_type, object)  Request info on an object
ch_list(info_type)            Request a list of things of type info_type
ch_info(info_type, id)        Request info on a thing of info_type
```

You can find full examples for each function within the code.

Available info_types are:

```
            |   ch_object   ch_list   ch_info |
------------+---------------------------------+-------------
info        |      x                          | info
image       |      x                          | image
person      |      x                     x    | person
exhibition  |      x           x         x    | exhibition
department  |                  x         x    | department
period      |                  x         x    | period
role        |                  x         x    | role
type        |                  x         x    | type
object      |                            x    | object
------------+---------------------------------+-------------
```

*** License ***
This program is free software. It comes without any warranty, to
the extent permitted by applicable law. You can redistribute it
and/or modify it under the terms of the Do What The Fuck You Want
To Public License, Version 2, as published by Sam Hocevar. See
http://sam.zoy.org/wtfpl/COPYING for more details.
