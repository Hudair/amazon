<?php

use App\Http\Controllers\Storefront\AccountController;
use App\Http\Controllers\Storefront\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('my/{tab?}', [
  AccountController::class, 'index'
])->name('account');

Route::get('wishlist/{item}', [
  WishlistController::class, 'add'
])->name('wishlist.add')->middleware(['ajax']);

Route::delete('wishlist/{wishlist}', [
  WishlistController::class, 'remove'
])->name('wishlist.remove');

Route::put('my/password/update', [
  AccountController::class, 'password_update'
])->name('my.password.update');

Route::put('my/account/update', [
  AccountController::class, 'update'
])->name('account.update');

// Avatar
Route::post('my/avatar/save', [
  AccountController::class, 'avatar'
])->name('my.avatar.save');

Route::delete('my/avatar/remove', [
  AccountController::class, 'delete_avatar'
])->name('my.avatar.remove');

// Address
Route::get('my/address/create', [
  AccountController::class, 'create_address'
])->name('my.address.create');

Route::post('my/address/save', [
  AccountController::class, 'save_address'
])->name('my.address.save');

Route::get('my/address/{address}', [
  AccountController::class, 'address_edit'
])->name('my.address.edit');

Route::put('my/address/{address}/update', [
  AccountController::class, 'address_update'
])->name('my.address.update');

Route::get('my/address/{address}/delete', [
  AccountController::class, 'address_delete'
])->name('my.address.delete');
