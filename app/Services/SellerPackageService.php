<?php

namespace App\Services;

use App\Models\SellerPackage;
use App\Models\SellerPackagePayment;
use App\Models\User;
use Carbon\Carbon;
use Session;

class SellerPackageService {

    public static function purchase(int $package_id, User $user, array $payment_details, bool $online = true){
        $shop = $user->shop;
        $now = Carbon::now('America/Santo_Domingo');
        // Get the seller package to update de shop
        $seller_package = SellerPackage::find($package_id);

        // Create new seller package purchase register
        SellerPackagePayment::create([
            'user_id' => $user->id,
            'approval' => $online,
            'seller_package_id' => $seller_package->id,
            'payment_method' => 'Pago Azul',
            'payment_details' => json_encode($payment_details),
            'reciept' => null,
            'offline_payment' => !$online
        ]);

        // check if the current package is expired
        if($shop->package_invalid_at->isBefore($now)){
            /*
             * if the current package is expired add the package duration to the now time date and save it
             * */

            $newExpiryDate = $now->addDays($seller_package->duration);
            $shop->update([
                'seller_package_id' => $seller_package->id,
                'package_invalid_at' => $newExpiryDate
            ]);
        }else{
            /*
             * if the current package isn´t expired add the package duration to current package date expiration
             * and save it
             * */
            $newExpiryDate = $shop->package_invalid_at->addDays($seller_package->duration);
            $shop->update([
                'seller_package_id' => $seller_package->id,
                'package_invalid_at' => $newExpiryDate
            ]);
        }

    }

}
