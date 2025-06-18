<?php

use App\Http\Controllers\RFIDController;
use Illuminate\Support\Facades\Route;

Route::get('/rfid', [RFIDController::class, 'form'])->name('rfid.form');
Route::post('/rfid/scan', [RFIDController::class, 'scan'])->name('rfid.scan');
Route::get('/rfid/data', [RFIDController::class, 'getData'])->name('rfid.data');