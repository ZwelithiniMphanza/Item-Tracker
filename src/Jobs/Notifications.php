<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\QrCode;

class Notifications{

    public static function send(){

        $notifications = Notification::where('status','Pending')->get();
        if(count($notifications) > 0){
            
            foreach($notifications as $notification){

                $clphone = str_replace(" ", "", $notification->recipient); #Remove any whitespace
                $sender_id = $notification->sender_id;
                $message = $notification->message;

                $ch = curl_init();
                //will revist based on api parameters
                $gateway_url = "";
                $queryString = "?apiKey=".(string) getenv('SMS_GATEWAY_API_KEY')."&to=".$clphone."&content=".$message;
                curl_setopt($ch, CURLOPT_URL, (string) getenv('SMS_GATEWAY_URL') . $queryString);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    //$get_sms_status = curl_error($ch);
                    $notification->status = 'Failed';
                    $notification->save();
                }

                curl_close($ch);

                $get_result = json_decode($response, true);
                
                /*
                if (is_array($get_result)) {
                    if ($get_result['messages']['0']['error'] == '') {
                        $get_sms_status = 'Success|' . $get_result['messages']['0']['apiMessageId'];
                    } else {
                        $get_sms_status = $get_result['messages']['0']['error'];
                    }
                } else {
                    $get_sms_status = 'Unknown error';
                }
                */
                
                $notification->status = 'Delivered';
                $notification->save();

            }
        }
    }
}

Notification::send();