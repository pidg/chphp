<?php

/*
		Horrific PHP interface to Cooper-Hewitt API for Dummies
		by taras				
		http://twitter.com/tarasyoung

		Gives you the following functions (all return an array, see
		individual functions for detailed instructions):

        ch_request (args)                Directly request info from the API
        ch_get (args)                    Send a GET url-style request
        ch_search (query)                Search for things
        ch_object (info_type, object)    Request info on an object
        ch_list (info_type)              Request a list of things of type info_type
        ch_info (info_type, id)          Request info on a thing of info_type

		Available info_types are:

                    |    ch_object   ch_list   ch_info     |
        ------------+--------------------------------------+-------------
        info        |        x                             |    info
        image       |        x                             |    image
        person      |        x                    x        |    person
        exhibition  |        x          x         x        |    exhibition
        department  |                   x         x        |    department
        period      |                   x         x        |    period
        role        |                   x         x        |    role
        type        |                   x         x        |    type
        object      |                             x        |    object
        ------------+--------------------------------------+-------------

    /!\ This program is free software. It comes without any warranty, to
        the extent permitted by applicable law. You can redistribute it
        and/or modify it under the terms of the Do What The Fuck You Want
        To Public License, Version 2, as published by Sam Hocevar. See
        http://sam.zoy.org/wtfpl/COPYING for more details.

*/


// Stick your access token in here:
$mytoken = "replacemewithatoken";	

// Define constants (such that it doesn't matter if you prefer singular)
define("department",0,1);    define("departments",0,1);
define("exhibition",1,1);    define("exhibitions",1,1);
define("person",2,1);        define("people",2,1);
define("period",3,1);        define("periods",3,1);
define("role",4,1);          define("roles",4,1);
define("type",5,1);          define("types",5,1);
define("object",6,1);        define("objects",5,1);
define("image",6,1);         define("images",5,1);
define("info",7,1);
// (I realise how awful this is, but bear with me)

function ch_request($args)
{

	/*	
		Asks Cooper-Hewitt API for stuff and spits it back as an array
		example:	$a = ch_request( array("method" => "cooperhewitt.search.collection", "query" => "sampler") );
	*/

	global $mytoken; $args["access_token"] = $mytoken;

	// Perform query:
	$query = curl_init();
	
		curl_setopt($query, CURLOPT_URL, "https://api.collection.cooperhewitt.org/rest/");
		curl_setopt($query, CURLOPT_POST, 1);
		curl_setopt($query, CURLOPT_POSTFIELDS, $args);
		curl_setopt($query, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($query);

	curl_close($query);

	return json_decode($response,1);
}

function ch_get($args)
{

	/*
		Lets you format your request like a GET request
		example:	$a = ch_get("method=cooperhewitt.search.collection&query=sampler");
	*/

	// Split arguments up and shove them in $data:

	$p = explode("&",$args);

	for ( $n=0; $n < count($p); $n++ )
	{
		$e = explode("=", $p[$n]);
		$data[$e[0]] = $e[1];
	}

	return ch_request($data);

}

function ch_search($query)
{

	/*
		Searches the collection
		example:	$a = ch_search("sampler");
	*/
	
	return ch_get("method=cooperhewitt.search.collection&query=$query");

}

function what_is_what($what)
{
	switch ($what)
	{
        case department:     $whatever = "departments"; break;
        case exhibition:     $whatever = "exhibitions"; break;
        case periods:        $whatever = "periods"; break;
        case person:         $whatever = "people"; break;
        case role:           $whatever = "roles"; break;
        case type:           $whatever = "types"; break;
        default:             $whatever = "departments";	
    }

	return $whatever;
}

function ch_object($type, $object)
{
	/*
		Returns a list of things related to an object
		example: 	$a = ch_object(images, 18766551)

		Possibilities:	info, images, people, exhibitions
	*/

	switch ( $type ) 
	{
        case info:          $what = "Info"; break;
        case people:        $what = "Participants"; break;
        case images:        $what = "Images"; break;
        case exhibitions:   $what = "Exhibitions"; break;
        default:            $what="Info";
	}

	return ch_get("method=cooperhewitt.objects.get$what&id=$object");
}

function ch_list($what)
{
	/* 
		Returns a list of things
		example: 	$a = ch_list(roles);

		Possibilities: departments, exhibitions, periods, roles, types

	*/

	$whatever = what_is_what($what);
	return ch_get("method=cooperhewitt.$whatever.getList");
}

function ch_info($what, $id)
{
	/* 
		Returns info about someone or something
		example: 	$a = ch_info(role, 35236657);

		Possibilities: 	object, department, exhibition, period, 
						person, role, type

	*/

	$whatever = what_is_what($what);
	return ch_get("method=cooperhewitt.$whatever.getInfo&id=$id");
}



