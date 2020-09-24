<?php

namespace App\Helpers;

use Psr\Container\ContainerInterface;
use \Firebase\JWT\JWT;
use App\Models\Merchant;
use App\Models\Account;
use App\Models\Teller;

class Validator{

    protected $container;
    protected $request;
    protected $response;

    public function __construct(ContainerInterface $container,$request, $response) {

        $this->container = $container;
        $this->request = $request;
        $this->response = $response;

    }

    private function getToken(){

        $tokenRaw = $this->request->getHeader('Auth-Token');
        $token = trim($tokenRaw[0]);
        return $token;
        
    }

    public function isAdmin(){
        try 
        {

            $tokenR = $this->getToken();
            if(!$tokenR == ""){

                $credentials = JWT::decode($tokenR, base64_decode((string) getenv('APP_KEY')), ['HS256']);
                $data = $credentials->data;

                $roleId = $data->role_id;
                //role id 1 = tellers
                //role id 2 = customers
                //role id 3 = admin
                if($roleId == 3){

                    $this->responseMessage = [
                        'status' => true,
                    ];

                }else{

                    $this->responseMessage = [
                        'status' => false,
                        'message' => 'Un-authorised to access this service',
                        'response_code' => 200
                    ];
                    
                }
            }else{
                
                $this->responseMessage = [
                    'status' => false,
                    'message' => 'Token not provided',
                    'response_code' => 401
                ];
            }
            

        } catch(ExpiredException $e) {
            
            $this->responseMessage = [
                'status' => false,
                'message' => 'Provided token is expired.',
                'response_code' => 200
            ];
            

        } catch(Exception $e) {

            $this->responseMessage = [
                'status' => false,
                'message' => 'An error while decoding token.',
                'response_code' => 401
            ];
            

        }

        return $this->responseMessage;
    }

    public function isUser(){
        try 
        {

            $tokenR = $this->getToken();
            if(!$tokenR == ""){

                $credentials = JWT::decode($tokenR, base64_decode((string) getenv('APP_KEY')), ['HS256']);
                $data = $credentials->data;

                $roleId = $data->role_id;
                //role id 1 = tellers
                //role id 2 = customers
                //role id 3 = admin
                if($roleId == 2){

                    $this->responseMessage = [
                        'status' => true,
                    ];

                }else{

                    $this->responseMessage = [
                        'status' => false,
                        'message' => 'Un-authorised to access this service',
                        'response_code' => 200
                    ];
                    
                }
            }else{
                
                $this->responseMessage = [
                    'status' => false,
                    'message' => 'Token not provided',
                    'response_code' => 401
                ];
            }
            

        } catch(ExpiredException $e) {
            
            $this->responseMessage = [
                'status' => false,
                'message' => 'Provided token is expired.',
                'response_code' => 200
            ];
            

        } catch(Exception $e) {

            $this->responseMessage = [
                'status' => false,
                'message' => 'An error while decoding token.',
                'response_code' => 401
            ];
            

        }

        return $this->responseMessage;
    }


    public function isTeller(){

        try 
        {

            $tokenR = $this->getToken();
            if(!$tokenR == ""){

                $credentials = JWT::decode($tokenR, base64_decode((string) getenv('APP_KEY')), ['HS256']);
                $data = $credentials->data;

                $roleId = $data->role_id;
                //role id 1 = tellers
                //role id 2 = customers
                //role id 3 = admin
                if($roleId == 1){

                    $this->responseMessage = [
                        'status' => true,
                    ];

                }else{

                    $this->responseMessage = [
                        'status' => false,
                        'message' => 'Un-authorised to access this service',
                        'response_code' => 200
                    ];
                    
                }
            }else{
                
                $this->responseMessage = [
                    'status' => false,
                    'message' => 'Token not provided',
                    'response_code' => 401
                ];
            }
            

        } catch(ExpiredException $e) {
            
            $this->responseMessage = [
                'status' => false,
                'message' => 'Provided token is expired.',
                'response_code' => 200
            ];
            

        } catch(Exception $e) {

            $this->responseMessage = [
                'status' => false,
                'message' => 'An error while decoding token.',
                'response_code' => 401
            ];
            

        }

        return $this->responseMessage;
    }

    public function isMerchant(){

        try 
        {

            $tokenR = $this->getToken();
            if(!$tokenR == ""){

                $credentials = JWT::decode($tokenR, base64_decode((string) getenv('APP_KEY')), ['HS256']);
                $data = $credentials->data;

                $roleId = $data->role_id;
                //role id 1 = tellers
                //role id 2 = customers
                //role id 3 = admin
                if($roleId == 4){

                    $this->responseMessage = [
                        'status' => true,
                    ];

                }else{

                    $this->responseMessage = [
                        'status' => false,
                        'message' => 'Un-authorised to access this service',
                        'response_code' => 200
                    ];
                    
                }
            }else{
                
                $this->responseMessage = [
                    'status' => false,
                    'message' => 'Token not provided',
                    'response_code' => 401
                ];
            }
            

        } catch(ExpiredException $e) {
            
            $this->responseMessage = [
                'status' => false,
                'message' => 'Provided token is expired.',
                'response_code' => 200
            ];
            

        } catch(Exception $e) {

            $this->responseMessage = [
                'status' => false,
                'message' => 'An error while decoding token.',
                'response_code' => 401
            ];
            

        }

        return $this->responseMessage;
    }


    public function getAttributes($string){

        $token = JWT::decode($this->getToken(), base64_decode((string) getenv('APP_KEY')), ['HS256']);
        $data = $token->data;

        switch($string){

            case 'id':
                return  $data->id;
            break;

            case 'role_id':
                return  $data->role_id;
            break;

            case 'name':
                return  $data->name;
            break;

            case 'teller_id':
                return  $data->teller_id;
            break;
        }
    }

    public function validateMerchant($card){
        $state = false;
        if($card == "" || $card == NULL){
            return $state;
        }

        $tellerId = $this->getAttributes('teller_id');
        $teller = Teller::find($tellerId)->first();
        if($teller){
            $tellerMerchantId = $teller->merchant_id;
            $account = Account::where('merchant_id',$tellerMerchantId)
                                ->where('card_number_id',$card->id)
                                ->first();
            if($account){
                $state = true;
            }
        }

        return $state;

    }

    public function validateSchema($card){

        $state = false;
        if($card == "" || $card == NULL){
            return $state;
        }

        $cardSchema = substr($card,0,6);

        $tellerId = $this->getAttributes('teller_id');
        $teller = Teller::find($tellerId)->first();
        if($teller){

            $tellerMerchantId = $teller->merchant_id;

            $merchant = Merchant::find($tellerMerchantId)->first();
            $schema = $merchant->card_schema;
            if($schema == $cardSchema){
                $state = true;
            }
        }
        return $state;
        //get the teller id and query teller. check the tellers store id and get the id
        //query the store and and the store based on id provided then get the store schema
        //check the retrieved schema against the provided card's schema
    }

    public function checkSubscription($merchantId){

        $state = 0;
        //1 denotes revenue true no subscription
        //2 denotes its not revenue but subscription is active
        //3 denotes its not revenue but subscription is due
        $merchant = Merchant::where('id',$merchantId)->first();

        if($merchant == null){
            return 404;
        }
        
        $isRevenue = $merchant->is_revenue;
        if(!$isRevenue){
            //then this means it is subscription based
            $status = $merchant->status;
            if($status){
                $state = 2;
            }else{
                $state = 3;
            }
        }else{
            $state = 1;
        }

        return $state;
    }

}