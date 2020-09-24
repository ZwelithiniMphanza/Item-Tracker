<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as V;

use App\Models\Notification;
use App\Models\Settings;
use App\Models\Passenger;
use App\Models\Destination;
use App\Models\Delivered;
use App\Models\Dispatch;
use App\Models\Luggage;
use App\Models\QrCode;
use App\Models\User;

class LuggageController{

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    public function map($request, $response){

        $passport = trim(stripslashes($request->getParam('passport_number')));
        $luggage_number = trim(stripslashes($request->getParam('luggage_number')));
        $qrCode = trim(stripslashes($request->getParam('qr_code')));
        //$destination_id = trim(stripslashes($request->getParam('destination_id')));
        $destination = trim(stripslashes($request->getParam('city')));

        $passenger = Passenger::where('passport_number',$passport)->first();
        if($passenger){
            $code = QrCode::where('qr_code',$qrCode)->first();
            if($code){

                $destination = Destination::where('place',ucfirst($destination))->first();
                if(!$destination){

                    $responseMessage = new \StdClass();
                    $responseMessage->message = "Unsupported destination";
                    $responseMessage->state = false;

                    return $response->withStatus(200)->withJson($responseMessage);
                }

                if($code->used == 'Yes'){

                    $responseMessage = new \StdClass();
                    $responseMessage->message = 'Code has already being used by other passenger';
                    $responseMessage->state = false;

                    return $response->withStatus(200)->withJson($responseMessage);
                }

                //will revist this part
                $ref = $code->qr_code;

                $luggage = new Luggage();
                $luggage->luggage_number = $luggage_number;
                $luggage->qr_code = $code->qr_code;
                $luggage->ref_number = $ref;
                $luggage->passenger_id = $passenger->id;
                $luggage->dispatched = 'No';
                $luggage->destination_id = $destination->id;
                $luggage->save();

                $code->used = 'Yes';
                $code->save();


                //send notification to passenger
                $message = 'Your luggage with ref# '.$ref.'has been logged for tracking. Thank you for using this service';

                $sender_id = Settings::where('_key','sender_id')->first();
                $notification = new Notification();
                $notification->message = $message;
                $notification->recipient = $passenger->mobile;
                $notification->sender_id = $sender_id->value;
                $notification->status = 'Pending';
                $notification->save();

                $responseMessage = new \StdClass();
                $responseMessage->message = 'Luggage registered successfully';
                $responseMessage->state = true;

                return $response->withStatus(200)->withJson($responseMessage);
                

            }else{
                $responseMessage = new \StdClass();
                $responseMessage->message = 'Scanned code does not exist';
                $responseMessage->state = false;

                return $response->withStatus(200)->withJson($responseMessage);
            }


            $responseMessage = new \StdClass();
            $responseMessage->message = 'passenger already exists';
            $responseMessage->state = false;
            $responseMessage->passenger = $passenger;

            return $response->withStatus(200)->withJson($responseMessage);

        }else{

            $responseMessage = new \StdClass();
            $responseMessage->message = 'resource not found';
            $responseMessage->state = false;
            $responseMessage->passenger = null;

            return $response->withStatus(200)->withJson($responseMessage);
        }
    }

    public function createDispatch($request, $response){
        
        //can only be viewed by the users
        $manifest = trim(stripslashes($request->getParam('manifest')));
        $route_id = trim(stripslashes($request->getParam('route_id')));

        $dispatch = new Dispatch();
        $dispatch->route_id = $route_id;
        $dispatch->luggage_manifest = $manifest;
        $dispatch->status = 'Transit';

        //for loop for notification
        $manifests = json_decode($manifest);
        foreach ($manifests as $mfts){
            foreach($mfts as $mf){
                //echo $t->luggage_number."\n";
                $passenger = Passenger::where('id',$mf->passenger_id)->first();
                $destination = Destination::where('id',$mf->destination_id)->first();
                $message = 'Your luggage with luggage# '.$mf->luggage_number.'has been dispatched for '.$destination->place.'. Thank you for using this service';

                $sender_id = Settings::where('_key','sender_id')->first();
                $notification = new Notification();
                $notification->message = $message;
                $notification->recipient = $passenger->mobile;
                $notification->sender_id = $sender_id->value;
                $notification->status = 'Pending';
                $notification->save();

                $luggage = Luggage::where('ref_number',$mf->ref_number)->first();
                $luggage->dispatched = 'Yes';
                $luggage->save();
            }
        }

        $dispatch->save();

        $responseMessage = new \StdClass();
        $responseMessage->message = 'Dispatch created successfully';
        $responseMessage->state = true;
        return $response->withStatus(200)->withJson($responseMessage);

    }

    public function viewDispatch($request, $response){

        //can only be viewed by the users
        $dispatch = Dispatch::all();
        $responseMessage = new \StdClass();
        $responseMessage->message = 'Dispatch Data';
        $responseMessage->state = true;
        $responseMessage->dispatch = $dispatch;
        return $response->withStatus(200)->withJson($responseMessage);

    }

    public function logDelivery($request, $response){

        $location = trim(stripslashes($request->getParam('location')));
        $qrcode = trim(stripslashes($request->getParam('qrcode')));

        
        $luggage = Luggage::where('qr_code',$qrcode)->first();

        
        if($luggage){

            $place = Destination::where('id',$luggage->destination_id)->first();
            $city = $place->place;

            if(!(trim(strtolower($location)) == trim(strtolower($city)))){
                $responseMessage = new \StdClass();
                $responseMessage->message = 'Location/Destination mismatch';
                $responseMessage->state = false;
                //$responseMessage->dispatch = $dispatch;
                return $response->withStatus(200)->withJson($responseMessage);
            }

            $delivery = new Delivered();
            $delivery->luggage_number_id = $luggage->id;
            $delivery->save();

            $lg = Luggage::where('id',$luggage->id)->first();
            $lg->delivery_id = $delivery->id;
            $lg->save();


            $responseMessage = new \StdClass();
            $responseMessage->message = 'Delivery Update Created';
            $responseMessage->state = true;
            //$responseMessage->dispatch = $dispatch;
            return $response->withStatus(200)->withJson($responseMessage);


        }else{

            $responseMessage = new \StdClass();
            $responseMessage->message = 'Error: Unknown Luggage destination';
            $responseMessage->state = false;
            return $response->withStatus(200)->withJson($responseMessage);

        }
    }

    public function track($request, $response){

        //only logged in passenger
        $ref = trim(stripslashes($request->getParam('ref')));
        $luggage =  Luggage::where('ref_number',$ref)->first();
        if($luggage){
            if($luggage->dispatched == 'Yes'){
                //check the dispatch and route details
                if($luggage->delivery_id == null){
                    $responseMessage = new \StdClass();
                    $responseMessage->message = 'Luggage in transit, not yet delivered';
                    $responseMessage->state = true;

                    return $response->withStatus(200)->withJson($responseMessage);

                }else{

                    $responseMessage = new \StdClass();
                    $responseMessage->message = 'Luggage delivered';
                    $responseMessage->state = true;

                    return $response->withStatus(200)->withJson($responseMessage);
                }

            }else{

                $responseMessage = new \StdClass();
                $responseMessage->message = 'Luggage not yet dispatched';
                $responseMessage->state = true;

                return $response->withStatus(200)->withJson($responseMessage);

            }

        }else{

            $responseMessage = new \StdClass();
            $responseMessage->message = 'invalid ref number';
            $responseMessage->state = false;
            

            return $response->withStatus(200)->withJson($responseMessage);

        }
    }

    public function view_luggage_by_passenger($request, $response){
        $passenger_id = trim(stripslashes($request->getParam('passenger_id')));
        $luggages =  Luggage::where('passenger_id',$passenger_id)->get();
        $response_payload = []; 
        if(count($luggages) > 0){
            foreach($luggages as $luggage){
                $status = '';
                
                if($luggage->dispatched == 'Yes' && $luggage->delivery_id !== null){
                    $status = 'delivered';
                }else if($luggage->dispatched == 'Yes' && $luggage->delivery_id == null){
                    $status = 'dispatched for delivery';
                }else{
                    $status = 'pending dispatch';
                }
                $response_payload[] = [
                    'luggage_number' => $luggage->luggage_number,
                    'qr_code' => $luggage->qr_code,
                    'ref_number' => $luggage->ref_number,
                    'destination' => Destination::where('id',$luggage->destination_id)->first()->place,
                    'status' => $status
                ];
            }
        }

        return $response->withStatus(200)->withJson($response_payload);

    }

    public function destinations($request, $response){

        //can only be viewed by the users
        $destinations = Destination::all();
        $responseMessage = new \StdClass();
        $responseMessage->message = 'Destination Data';
        $responseMessage->state = true;
        $responseMessage->destinations = $destinations;
        return $response->withStatus(200)->withJson($responseMessage);

    }

}