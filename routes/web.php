<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ItemController::class, 'index'])->name('inventory.index');

Route::prefix('items')->name('items.')->group(function () {
    Route::post('/',             [ItemController::class, 'store'])->name('store');
    Route::put('/{item}',        [ItemController::class, 'update'])->name('update');
    Route::delete('/{item}',     [ItemController::class, 'destroy'])->name('destroy');
    Route::get('/stats',         [ItemController::class, 'stats'])->name('stats');
});
