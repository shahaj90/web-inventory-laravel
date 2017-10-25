<?php

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
Auth::routes();
Route::get('/', 'DashboardController@index')->name('dashboard');
Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@checkLogin')->name('checkLogin');
Route::get('/logout', 'DashboardController@logout')->name('logout');
//Dashboard
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
//User
Route::get('/user', 'UserController@index')->name('user');
Route::get('/user/checkUsers', 'UserController@isAdmin')->name('checkUsers');
Route::get('/user/read', 'UserController@readUsers')->name('readUsers');
Route::post('/user/save', 'UserController@saveUser')->name('saveUser');
Route::post('/user/edit', 'UserController@editUser')->name('editUser');
Route::post('/user/update', 'UserController@updateUser')->name('updateUser');
Route::post('/user/delete', 'UserController@deleteUser')->name('deleteUser');
//category
Route::get('/category', 'CategoryController@index')->name('category');
Route::get('/category/read', 'CategoryController@readCategories')->name('readCategories');
Route::post('/category/save', 'CategoryController@saveCategory')->name('saveCategory');
Route::post('/category/edit', 'CategoryController@editCategory')->name('editCategory');
Route::post('/category/update', 'CategoryController@updateCategory')->name('updateCategory');
Route::post('/category/delete', 'CategoryController@deleteCategory')->name('deleteCategory');
//Product
Route::get('/product', 'ProductController@index')->name('product');
Route::get('/product/read', 'ProductController@readProducts')->name('readProducts');
Route::post('/product/edit', 'ProductController@editProduct')->name('editProduct');
Route::post('/product/save', 'ProductController@saveProduct')->name('saveProduct');
Route::post('/product/update', 'ProductController@updateProduct')->name('updateProduct');
Route::post('/product/delete', 'ProductController@deleteProduct')->name('deleteProduct');
//Purchase
Route::get('/product/readPurchases', 'ProductController@readPurchases')->name('readPurchasse');
Route::post('/product/getProductCat', 'ProductController@getProductCat')->name('getProductCat');
Route::post('/product/savePurchase', 'ProductController@savePurchase')->name('savePurchase');
Route::post('/product/editPurchase', 'ProductController@editPurchase')->name('editPurchase');
Route::post('/product/updatePurchase', 'ProductController@updatePurchase')->name('updatePurchase');
//Invoice
Route::get('/invoice', 'InvoiceController@index')->name('invoice');
Route::get('/newInvoice', 'InvoiceController@newInvoice')->name('newInvoice');
Route::get('/invoice/getCustomer', 'InvoiceController@getCustomer')->name('getCustomer');
Route::get('/invoice/checkStock', 'InvoiceController@checkStock')->name('checkStock');
Route::post('/createInvoice', 'InvoiceController@createInvoice')->name('createInvoice');
Route::get('/editInvoice/{id}', 'InvoiceController@editInvoice')->name('editInvoice');
Route::post('/updateInvoice', 'InvoiceController@updateInvoice')->name('updateInvoice');
Route::get('/printInvoice/{id}', 'InvoiceController@printInvoice')->name('printInvoice');
//Setting
Route::get('/setting', 'SettingController@index')->name('setting');
Route::post('/setting/confiqSave', 'SettingController@confiqSave')->name('confiqSave');

