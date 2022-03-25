<?php
// Web Routes
Route::group([
	'middleware' => ['web', 'storefront'],
	'namespace' => '\Incevio\Package\Wallet\Http\Controllers',
	// 'prefix' => 'wallet'
], function(){

	// Markerplace Admin only routes
	Route::group(['middleware' => ['admin'], 'namespace' => 'Admin', 'as' => 'admin.', 'prefix' => 'admin'], function()
	{
		Route::get('setting/wallet', 'WalletSettingsController@index')->name(config('wallet.routes.settings'));
		Route::get('payout/requests', 'WithdrawalRequestController@index')->name('wallet.payout.requests');
        Route::get('payout/{transaction}/approval', 'WithdrawalRequestController@show_form')->name('payout.approval');
        Route::post('payout/{transaction}/approve', 'WithdrawalRequestController@approve')->name('wallet.payout.approve');
        Route::post('payout/{transaction}/decline', 'WithdrawalRequestController@decline')->name('wallet.payout.decline');

        //Payout
        Route::get('account/wallet/payouts', 'PayoutController@index')->name('wallet.payouts');
        Route::get('account/wallet/payout/form', 'PayoutController@show_form')->name('wallet.payout.form');
        Route::post('account/wallet/payout', 'PayoutController@payout')->name('wallet.payout');

        //Reports:
        Route::get('report/wallet/payout/report', 'PayoutReportController@report')->name('wallet.payout.report');
        Route::get('account/wallet/payout/report/getMore', 'PayoutReportController@reportGetMore')->name('wallet.payout.report.getMore');
        Route::get('account/wallet/payout/report/getMoreChart', 'PayoutReportController@reportGetMoreForChart')->name('wallet.payout.report.getMoreChart');
    });

	// Merchant only routes
	Route::group(['middleware' => ['merchant'], 'as' => 'merchant.account.', 'prefix' => 'merchant'], function()
	{
		/*Route::get('account/wallet', 'WalletController@index')->name('wallet');
        //Deposit
		Route::get('account/wallet/deposit', 'DepositController@show_form')->name('wallet.deposit.form');
		Route::post('account/wallet/deposit', 'DepositController@deposit')->name('wallet.deposit');*/
        //Withdraw
		Route::get('account/wallet/withdraw', 'WithdrawalController@show_form')->name('wallet.withdrawal.form');
		Route::post('account/wallet/withdraw', 'WithdrawalController@withdraw')->name('wallet.withdrawal.request');
	});

    /**
     * Start Customer and merchant Wallet
     */
    Route::get('account/wallet', 'WalletController@index')->name('account.wallet');

    //Deposit
    Route::get('account/wallet/deposit/form', 'DepositController@show_form')->name('account.wallet.deposit.form');
    Route::post('account/wallet/deposit', 'DepositController@deposit')->name('account.wallet.deposit');

    //Transfer
    Route::get('account/wallet/transfer/form', 'TransferController@show_form')->name('account.wallet.transfer.form');
    Route::post('account/wallet/transfer', 'TransferController@transfer')->name('account.wallet.transfer');

    //Withdrawals
    Route::get('account/wallet/withdraw', 'WithdrawalController@show_form')->name('account.wallet.withdrawal.form');

    /**
     * End Customer Wallet
     */
    // Paypal and Paystack redirect routes
    Route::get('wallet/depositFailed', 'DepositController@paymentFailed')->name('wallet.deposit.failed');
    Route::get('wallet/depositSuccessPaypal', 'DepositController@paypalPaymentSuccess')->name('wallet.deposit.paypal.success');
    Route::get('wallet/depositSuccessPaystack', 'DepositController@paystackPaymentSuccess')->name('wallet.deposit.paystack.success');

	// Common routes
	Route::get('wallet/{transaction}/invoice', 'WalletController@invoice')->name('wallet.transaction.invoice');

});

// Customer only routes for storefront
Route::group([
	'middleware' => ['web', 'auth:customer', 'storefront'],
	'namespace' => '\Incevio\Package\Wallet\Http\Controllers\Storefront'
], function(){

	/*Route::get('account/wallet', 'WalletController@index')->name('my.wallet');
	Route::get('account/wallet/deposit/form', 'WalletController@show_deposit_form')->name('my.wallet.deposit.form');
	Route::post('account/wallet/deposit', 'WalletController@deposit')->name('my.wallet.deposit');

	//Deposit vaia Paypal and Paystack
    Route::get('account/depositSuccessPaystack', 'WalletController@paystackPaymentSuccess')->name('account.deposit.paystack.success');
    Route::get('account/depositSuccessPaypal', 'WalletController@paypalPaymentSuccess')->name('account.deposit.paypal.success');

    Route::get('account/depositFailed', 'WalletController@paymentFailed')->name('account.deposit.failed');

    //Transfer
    Route::get('account/wallet/transfer/form', 'WalletController@show_transfer_form')->name('my.wallet.transfer.form');
    Route::post('account/wallet/transfer', 'WalletController@transfer')->name('my.wallet.transfer');

    //Invoice
	Route::get('account/wallet/{transaction}/invoice', 'WalletController@invoice')->name('my.wallet.invoice');*/

});

// API Routes
Route::group([
	'middleware' => ['api','auth:api'],
	'namespace' => '\Incevio\Package\Wallet\Http\Controllers\Api'
], function(){

	//

});

