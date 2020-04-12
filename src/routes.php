<?php

use Illuminate\Support\Facades\Route;

//MAPPING_AREA_FOR_CRUD_DO_NOT_REMOVE_OR_EDIT_THIS_LINE_USE_AREA//

Route::name('Wovosoft.')
    ->prefix('backend')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Wovosoft\SettingsManager\Http\Controllers\SettingsController::routes();
        //MAPPING_AREA_FOR_CRUD_DO_NOT_REMOVE_OR_EDIT_THIS_LINE//
    });
