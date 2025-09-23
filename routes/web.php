<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Appointment;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Appointment@index');


Route::post('slots', 'Appointment@pullSlotFinder');
Route::get('settings', 'SettingController@index');
Route::post('save', 'Appointment@save');
Route::get('cancellation', 'Appointment@indexCancel');
Route::get('qrverify', 'Appointment@indexdverify');
Route::get('slotviewer', 'Appointment@indexslotview');

Route::post('search-info', 'Appointment@appointInfo');
Route::post('cancel', 'Appointment@cancel');
Route::post('/accept', [Appointment::class, 'acceptappt'])->name('accept');
Route::get('attachment/{id}', 'Appointment@viewQRCode')->name('appointment.attachment');
Route::get('verification/{refno}', 'Appointment@verification')->name('appointment.verification');

// Walk-In Appointment
Route::group(['prefix' => '/30c234cc35caba164c8dbd3837a0c55a'], function () {
    Route::get('/', 'AppointmentWalkInController@index')->name('appointment.walk-in.index');
    Route::post('/', 'AppointmentWalkInController@create')->name('appointment.walk-in.create');
    Route::get('/{refno}', 'AppointmentWalkInController@viewAppointment')->name('appointment.walk-in.view');
    Route::post('/pull-slots', 'AppointmentWalkInController@pullAvailableSlots')->name('appointment.walk-in.pull-slots');
});

/* HEALTH DECLARATION ROUTE */
Route::get('health', 'Healthdec@index');
Route::post('health/save', 'Healthdec@save');

// Dropdowns
Route::group(['prefix' => '/dropdowns'], function () {
    Route::get('/transaction-types', 'DropdownController@transactionTypes')->name('dropdowns.transation-types');
});

// business search
Route::post('biz_search', 'Appointment@bizSearch');