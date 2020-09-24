<?php

namespace App\Helpers;

use App\Models\Purchase;
use \Carbon\Carbon;

class Helpers{

    public static function sumMonthlyPurchases(Carbon $date){

        $year = $date->year;
        $month = $date->month;

        if ($month < 10) {
            $month = '0' . $month;
        }

        $search = $year . '-' . $month;
        //2019-07-12 
        $purchases = Purchase::where('created_at', 'like', $search .'%')->get();

        $sum = 0;
        foreach ($purchases as $purchase) {
            $sum += $purchase->net;
        }

        return $sum;
    }
}