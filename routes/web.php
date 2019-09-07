<?php

Route::post('/login', 'UserController@login');

Route::post('/register', 'UserController@register');

Route::match(
	['post','get','delete','put'],
	'/ToolGroup',
	'ToolGroupController@index'
)->middleware('admintoken');

Route::get('/ToolGroupGet', 'ToolGroupController@ToolGroupGet')->middleware('admintoken');
Route::get('/ToolGroupUser', 'ToolGroupController@ToolGroupUser')->middleware('admintoken');

Route::match(
	['post','get','delete','put'],
	'/Tools',
	'ToolController@index'
)->middleware('admintoken');

Route::match(
    ['post','get','delete','put'],
    '/UserMapping',
    'UserMappingController@index'
)->middleware('admintoken');

Route::get('/user_tools', 'UserMappingController@user_tools')->middleware('usertoken');

Route::get('/userprofile', 'UserController@userprofile')->middleware('usertoken');
Route::get('/adminprofile', 'UserController@userprofile')->middleware('admintoken');
?>
