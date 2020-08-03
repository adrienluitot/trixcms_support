<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'support', 'middleware' => ['RIfUserIsLogout', 'permissions:admin|CAN__TX__PROFILE']], function () {
    Route::get('/', ['as' => 'support_alfiory.home', 'uses' => 'SupportController@index']);

    Route::get('/new', ['as' => 'support_alfiory.new_ticket', 'uses' => 'SupportController@new_ticket']);
    Route::post('/new', ['uses' => 'SupportController@create_ticket']);

    Route::post('/resolve_ticket', ['as' => 'support_alfiory.resolve_ticket', 'uses' => 'SupportController@resolve_ticket']);

    Route::get('/{id}', ['as' => 'support_alfiory.ticket', 'uses' => 'SupportController@ticket']);
    Route::post('/{id}', ['as' => 'support_alfiory.send_message', 'uses' => 'SupportController@send_message']);
});

