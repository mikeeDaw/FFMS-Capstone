<?php

use App\Http\Controllers\DashCtrl;
use App\Http\Controllers\FirebaseTrial;
use App\Http\Controllers\HomeCtrl;
use App\Http\Controllers\LogCtrl;
use App\Http\Controllers\MailCtrl;
use App\Http\Controllers\OrderCtrl;
use App\Http\Controllers\QuoteCtrl;
use App\Http\Controllers\ReportCtrl;
use App\Http\Controllers\ShipmentCtrl;
use Google\Cloud\Firestore\Admin\V1\Index\IndexField\Order;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::get('/', [HomeCtrl::class, 'index'])->name('home');

Route::get('/profile', [HomeCtrl::class, 'profile']);

Route::get('/profile/orders/{shipID}', [HomeCtrl::class, 'showOrderInfo']);

Route::post('/profile/update', [HomeCtrl::class, 'pfUpd']);

Route::get('/profile/{email}/resetPassword', [HomeCtrl::class, 'passUpdate']);

Route::get('/sample', function () {
    return view('/components/another');
});


// Order Routes
Route::get('/order/step1', [OrderCtrl::class, 'index']);

Route::post('/order', [OrderCtrl::class, 'S1store']);

Route::get('/step2', [OrderCtrl::class, 'ordStep2']);

Route::post('/step2/order', [OrderCtrl::class, 'S2store']);

Route::get('/step3', [OrderCtrl::class, 'ordStep3']);

Route::post('/step3/order', [OrderCtrl::class, 'S3store']);

Route::get('/dash/orders', [OrderCtrl::class, 'showOrders']);

Route::get('/dash/orders/{orderID}', [OrderCtrl::class, 'orderDetails']);

Route::post('/cancelOrder/{shipID}', [OrderCtrl::class, 'cancelOrder']);

Route::get('/order/edit/{shipID}', [OrderCtrl::class, 'editPackage']);

Route::post('/order/save/{shipID}', [OrderCtrl::class, 'saveOrderUpd']);

Route::get('/order/dismiss/{shipID}',[OrderCtrl::class, 'dismissOrder']);


// Payment Routes

Route::post('/dash/payment/{orderID}', [OrderCtrl::class, 'uploadPayment']);

Route::post('/verifyPayment', [OrderCtrl::class, 'verifyPay']);

Route::get('/paymentSuccess', [HomeCtrl::class, 'paymentSuccess']);

// Route::get('dash/paymentConfirm/{orderID}/{bool}', [OrderCtrl::class, 'confirmPayment'] );



Route::get('/firebaseTrial', [FirebaseTrial::class, 'trial']);


// LogForms Routes
Route::get('/register/create', [LogCtrl::class, 'createRegistration']);

Route::post('/register', [LogCtrl::class, 'storeRegistration']);

Route::get('/login/attempt', [LogCtrl::class, 'showLogin']);

Route::post('/login', [LogCtrl::class, 'verifyLogin']);

Route::get('/logout', [LogCtrl::class, 'logout']);

Route::get('/verifyAccount', [LogCtrl::class, 'verifyAcc']);

Route::get('/forgotPassword', [LogCtrl::class, 'showForgot']);

Route::post('/forgotPassword/send', [LogCtrl::class, 'sendForgot']);


// Quotation Routes
Route::get('/quote', [QuoteCtrl::class, 'index']);

Route::post('/quote/calc', [QuoteCtrl::class, 'calcQuote'])->name('quoteSubmit');

Route::get('sampling', function(){
    return view('logforms.sample');
})->name('sample');

// Dashboard Routes
Route::get('/dashboard', [DashCtrl::class, 'index']);

/* Account Dashboard */
Route::get('/dashboard/admin', [DashCtrl::class, 'accAdmin']);

Route::post('/dashboard/admins/create', [DashCtrl::class, 'adminStore']);

Route::get('/dashboard/users', [DashCtrl::class, 'accUsers']);

Route::post('/dashboard/users/create', [DashCtrl::class, 'userStore']);

Route::get('/dashboard/drivers', [DashCtrl::class, 'accDrivers']);

Route::post('/dashboard/drivers/create', [DashCtrl::class, 'driverStore']);

Route::post('/dashboard/drivers/availability', [DashCtrl::class, 'driverAvailUpd']);

Route::get('/dashboard/staffs', [DashCtrl::class, 'accStaffs']);

Route::post('/dashboard/staffs/create', [DashCtrl::class, 'staffStore']);

Route::get('/dashboard/{ulevel}/{uid}', [DashCtrl::class, 'dashProfile']);

Route::post('/dashboard/{ulev}/{uid}/update', [DashCtrl::class, 'userUpdate']);

Route::post('dashboard/drivers/{uid}/vehicles',[DashCtrl::class, 'driverVehi']);

Route::get('/dashboard/{ulev}/disable/{uid}',[DashCtrl::class, 'disableUser']);

Route::get('/dashboard/{ulev}/enable/{uid}',[DashCtrl::class, 'enableUser']);



/* Prices Dashboard */

Route::get('/dash/prices', [DashCtrl::class, 'quotePrices']);

Route::get('/dash/prices/{type}', [DashCtrl::class, 'showUpdPrices']);

Route::post('/dash/prices/edit/{type}', [DashCtrl::class, 'savePrice']);

Route::post('/dash/addService', [DashCtrl::class, 'addServType']);

Route::post('/dash/addPackage', [DashCtrl::class, 'addPackType']);

/* Shipment Routes */

Route::get('/shipments', [ShipmentCtrl::class, 'showShipments']);

Route::post('/shipments/updateStatus/{progress}', [ShipmentCtrl::class, 'updateStatus']);

Route::get('/shipments/allocate', [ShipmentCtrl::class, 'shipmentAllocate']);

Route::post('/shipments/allocate/save', [ShipmentCtrl::class, 'saveAllocation']);

Route::get('/shipments/cancels', [ShipmentCtrl::class, 'showCancels']);

Route::post('/shipments/cancels/update', [ShipmentCtrl::class, 'updateCancel']);

/* Vehicles Routes */

Route::get('/vehicles', [ReportCtrl::class, 'showVehicles']);

Route::post('/vehicles/create', [ReportCtrl::class, 'addVehicle']);

Route::post('/vehicles/updAvail/{vehiType}', [ReportCtrl::class, 'updateAvail']);

/* Mail Routes */

Route::get('/sendMail', [MailCtrl::class, 'index']);

/* Reports Routes */

Route::get('/reports/sales', [ReportCtrl::class, 'showSales']);

Route::get('/reports/ratings', [ReportCtrl::class, 'showDriverRating']);



/* Testing Route */

Route::get('/dummySite', [ReportCtrl::class, 'dummyFunc']);