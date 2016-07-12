<?php
/*
The MIT License (MIT)

Copyright (c) 2016 microting

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
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

function button__clear($IN){
	button_set(array('Send'=>'','Post'=>'','Check'=>'',
		'Fetch'=>'','DeleteResponse'=>'','Test'=>''));
}


function button__generate_elements($IN){
	include_once '../elements/test.php';
	$xml = test_generate();
	button_set(array('Send'=>$xml));
}

function button__send($IN){
	$http = new HTTP(@$IN['ServerAddress'],@$IN['ServerToken'],
		@$IN['SiteUuid'],@$IN['ElementId']);
	$response = $http->post(@$_POST['Send']);
	button_set(array(
		'ElementId'=>$response->Value,
		'Post'=>print_r($response,1)
	));
}


function button__check($IN){
	$http = new HTTP(@$IN['ServerAddress'],@$IN['ServerToken'],
		@$IN['SiteUuid'],@$IN['ElementId']);
	$response = print_r($http->status(),1);
	button_set(array( 'Check'=>$response ));
}

function button__fetch($IN){
	$http = new HTTP(@$IN['ServerAddress'],@$IN['ServerToken'],
		@$IN['SiteUuid'],@$IN['ElementId']);
	$response = print_r($http->retrieve(),1);
	button_set(array( 'Fetch'=>$response ));
}


function button__delete($IN){
	$http = new HTTP(@$IN['ServerAddress'],@$IN['ServerToken'],
		@$IN['SiteUuid'],@$IN['ElementId']);
	$response = print_r($http->delete(),1);
	button_set(array( 'DeleteResponse'=>$response ));
}


function button__test_code($IN){
	$fetch = 'It is the test.';
	button_set(array('Test'=>$fetch));
}

function button__save($IN){ 
ctrl_file_arr('config.cfg', $_POST);}

function button_load(){ 
return ctrl_file_arr('config.cfg');}

function button_set($IN){ 
ctrl_file_arr('config.cfg', $IN+ctrl_file_arr('config.cfg'));}


