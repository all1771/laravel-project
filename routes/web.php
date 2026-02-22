<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
Route::post('/ticket', [TicketController::class, 'store'])->name('ticket.store');
Route::get('/ticket/{ticket}/success', [TicketController::class, 'success'])->name('ticket.success');

Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
