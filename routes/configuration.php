<?php

use Illuminate\Support\Facades\Route;

// Settings
Route::group(['prefix' => '/settings'], function () {
    Route::get('/', 'SettingController@index')->name('configuration.settings.index');
    Route::post('/', 'SettingController@saveConfigurationSettings')->name('configuration.settings.save');
});
