<?php

namespace App\Jobs;

use App\Models\Destination;
use App\Models\Passenger;
use App\Models\Dispatch;
use App\Models\Luggage;

class CloseDispatch{

    public static function close(){
        
        $dispatched = Dispatch::where('status','Transit')->get();
        if(count($dispatched) > 0){

            foreach($dispatched as $dispatch){

                $hasPassed = true;
                $luggage_manifest = $dispatch->luggage_manifest;
                $manifests = json_decode($luggage_manifest);
                
                foreach ($manifests as $mfts){
                    foreach($mfts as $mf){
                        
                        $luggage = Luggage::where('ref_number',$mf->ref_number)->first();
                        if($luggage->dispatched == 'Yes'){
                            
                            $passenger = Passenger::where('id',$mf->passenger_id)->first();
                            $destination = Destination::where('id',$mf->destination_id)->first();
                            $delivery_id = $luggage->delivery_id;
                            
                            if(is_null($delivery_id)){
                                //get dispatch id and luggage number and notfy sys admins that products 
                                //has not been delivered to the designated destination
                                $hasPassed = false;
                                $message = 'Luggage # '.$mf->luggage_number.'has not been delivered for '.$destination->place.'. Kindly Investigate';
                            }

                        }else{
                            $hasPassed = false;
                            $message = 'Luggage # '.$mf->luggage_number.'has not been dispatched for '.$destination->place.'. Kindly Investigate';
                        }
                        
                        $sender_id = Settings::where('_key','sender_id')->first();
                        $notification = new Notification();
                        $notification->message = $message;
                        $notification->recipient = $passenger->mobile;
                        $notification->sender_id = $sender_id->value;
                        $notification->status = 'Pending';
                        $notification->save();
           
                    }
                }
                if($hasPassed){

                    $openDispatch = Dispatch::where('id',$dispatch->id)->first();
                    $openDispatch->status = 'Arrived';
                    $openDispatch->save();

                }
            }
        }else{
            //send notification that no dispatch is in transit
        }  
    }
}

CloseDispatch::close();