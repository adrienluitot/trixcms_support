<?php

use Illuminate\Support\Facades\Route;

// Https://Docs.TrixCMS.Eu

Route::group(['namespace' => 'Admin', 'prefix' => 'support'], function() {
    // Categories
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', ['as' => 'admin.support_alfiory.categories', 'middleware' => 'permissions:DASHBOARD_SUPPORT_VIEW_CATEGORIES|admin', 'uses' => 'CategoriesController@index']);
        Route::post('add', ['as' => 'admin.support_alfiory.add_category', 'middleware' => 'permissions:DASHBOARD_SUPPORT_ADD_CATEGORY|admin', 'uses' => 'CategoriesController@add_category']);
        Route::post('delete', ['as' => 'admin.support_alfiory.delete_category', 'middleware' => 'permissions:DASHBOARD_SUPPORT_DELETE_CATEGORY|admin', 'uses' => 'CategoriesController@delete_category']);
        Route::post('edit', ['as' => 'admin.support_alfiory.edit_category', 'middleware' => 'permissions:DASHBOARD_SUPPORT_EDIT_CATEGORY|admin', 'uses' => 'CategoriesController@edit_category']);
    });

    // Tickets
    Route::group(['prefix' => 'tickets'], function () {
        Route::get('/', ['as' => 'admin.support_alfiory.tickets', 'middleware' => 'permissions:DASHBOARD_SUPPORT_VIEW_TICKET|admin', 'uses' => 'TicketsController@index']);

        Route::get('/{id}', ['as' => 'admin.support_alfiory.view_ticket', 'middleware' => 'permissions:DASHBOARD_SUPPORT_VIEW_TICKET|admin', 'uses' => 'TicketsController@view_ticket']);
        Route::post('/{id}', ['middleware' => 'permissions:DASHBOARD_SUPPORT_ANSWER_TICKET|admin', 'uses' => 'TicketsController@answer_ticket']);

        Route::post('/{id}/add_tag', ['as' => 'admin.support_alfiory.add_tag', 'middleware' => 'permissions:DASHBOARD_SUPPORT_EDIT_TICKET|admin', 'uses' => 'TicketsController@add_tag']);
        Route::post('/{id}/delete_tag', ['as' => 'admin.support_alfiory.delete_tag', 'middleware' => 'permissions:DASHBOARD_SUPPORT_EDIT_TICKET|admin', 'uses' => 'TicketsController@delete_tag']);
        Route::get('/{id}/change_resolved', ['as' => 'admin.support_alfiory.change_resolved', 'middleware' => 'permissions:DASHBOARD_SUPPORT_ANSWER_TICKET|admin', 'uses' => 'TicketsController@change_resolved']);
        Route::get('/{id}/delete_ticket', ['as' => 'admin.support_alfiory.delete_ticket', 'middleware' => 'permissions:DASHBOARD_SUPPORT_DELETE_TICKET|admin', 'uses' => 'TicketsController@delete_ticket']);
    });
});