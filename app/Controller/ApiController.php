<?php
App::uses('AppController', 'Controller');

class ApiController extends AppController {


    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
        //$this->Auth->allow('getCmsPages','getVehicleAuctionDetailPage','getVehicleAuctionBidding','service_addVehicleToFavourite','getUserFavouriteVehicleList','getMyVehicleAuctionList','getMyFavouriteVehicleList','getMyPurchasedVehicleList','getInvoice','setEmailPreferences','getBidderList','getMySellVehicleList','getAuctionList','service_login');
    }

	/*
    * Chirag Api Work
    */
    /*
    * CMS page api
    */
    public function getCmsPages() { 
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        $this->loadModel('CmsPage');
        if ($this->request->is('get')) { 
            $language = $this->request->query['language'];
            $pageUrl = $this->request->query['page-url'];
            if (!is_null($language) && !is_null($pageUrl)) {
                $query = $this->CmsPage->find('all', array(
                    'conditions' => array('page_url' => $pageUrl,'language' => $language)
                ));
            return json_encode(['success' => true,'message' => 'cms pages','data'=> $query]);
            }
            return json_encode(['success' => false,'message'=> 'Please Provide language and page url','data' => []]);                
        }
        return json_encode(['success' => false,'message'=> 'Invalid Rquest','data' =>[]]);
    }

    /*
    * Get Vehicle auction detail page
    */
    public function getVehicleAuctionDetailPage() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $this->loadModel('Vehicle');
            $vehicleId = $this->request->query['vehicle-id'];
            if ($vehicleId) {
                $query = $this->Vehicle->find('all', array(
                    'conditions' => array('vehicle.id' => $vehicleId, 'buyer_id' => 0)
                ));
                if (count($query) > 0) {
                    return json_encode(['success' => true,'message' => 'Vehicle Auction Detail Page','data'=>$query]);
                }
                return json_encode(['success' => false,'message'=>'No record found','data' => []]);
            }
            return json_encode(['success' => false,'message'=> 'Please provide vehicle id','data' => []]);    
        }
        return json_encode(['success' => false,'message' => 'Invalid Rquest','data' => []]);   
    }

    /*
    * Get vehicle auction bidding
    */
    public function getVehicleAuctionBidding() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $this->loadModel('AuctionBid');
            $vehicleId = $this->request->query['vehicle-id'];
            if ($vehicleId) {
                $query = $this->AuctionBid->find('all', array(
                    'conditions' => array('vehicle_id' => $vehicleId)
                ));
                if (count($query) > 0) {
                    return json_encode(['sucess' => true,'message' => 'Vehicle Auctions bidding List','data'=>$query]);
                }
                return json_encode(['success' => false,'message'=>'No record found','data' => []]);
            }
            return json_encode(['success'=>false,'message'=> 'Please provide vehicle id','data'=>[]]);    
        }
        return json_encode(['success' => false,'message'=> 'Invalid Rquest','data'=>[]]);
    }

        /*Function to Add vehicle to Favourite*/
    public function service_addVehicleToFavourite() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if($this->request->is('post')){
            $user = $this->Auth->user();
            if(!$user){
                return json_encode(['success' => true, 'message' => 'Loggedin Required', 'data' => []]);
            }
            if (isset($this->request->data['vehicleId']) && !empty($this->request->data['vehicleId']))
            {
                //Code to add record in database
                $userId = $user['id'];
                $vehicleId = $this->request->data['vehicleId'];
                $this->loadModel('VehicleFavourite');
                $data = $this->VehicleFavourite->find('first', array(
                    'conditions' => array('user_id' => $userId, 'vehicle_id' => $vehicleId)
                    )
                );
                if($data) {
                    return json_encode(['success' => false, 'message' => 'Vehicle is alredy added as your favourite', 'data' => []]);
                }
                $this->VehicleFavourite->addToFavourite($userId, $vehicleId);
                return json_encode(['success' => true, 'message' => 'Vechile added to favourite successfully!!', 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => 'Required parameter missing', 'data' => []]);
            }
        }
        return json_encode(['success' => true, 'message' => 'Invalid Request', 'data' => []]);
    }

    /*
    * Get user favourite vehicle list
    */
    public function getUserFavouriteVehicleList() {
        $this->loadModel('Users');
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $token  = $this->request->header('token');
            if ($token) {
                $query = $this->Users->find('first', array(
                        'conditions' => array('token' => $token )
                    ));
                if ($query) {
                    $this->loadModel('VehicleFavourite');
                    $query = $this->VehicleFavourite->find('all', array(
                       'conditions' => array('user_id' => $query['Users']['id'])
                    ));
                return json_encode(['success' => true,'message' => 'User favourite vehicle list','data'=> $query]);
                }
            return json_encode(['success' => false,'message'=> 'Invalid Token','data' => []]);
            }          
        }
        return json_encode(['success' => false,'message'=> 'Invalid Rquest','data' => []]);
    }
    /*
    * Get my vehicle auction list
    */
    public function getMyVehicleAuctionList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $this->loadModel('Vehicle');
            $token  = $this->request->header('token');
            if ($token) {
                $userData = $this->Vehicle->User->find('first', array(
                        'fields' => array('id'),
                        'conditions' => array('token' => $token )
                    ));
                
                $this->Vehicle->unbindModel(
                    array('hasMany' => array('VehicleDoc','VehicleFavourite','AuctionBid','VehicleDamage')),
                    array('hasOne' => array('User'))
                );
                
                $data = $this->Vehicle->find('all', array(
                        'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s'), 'Vehicle.seller_id' => $userData['User']['id'])
                    ));
                
                return json_encode(['success' => true ,'message' => 'My Vehicle Auction Vehicle List', 'data'=>$data]);

            } else {
                return json_encode(['success' => false, 'message' => 'Login required', 'data' => []]);
            }
            
        }
        return json_encode(['success' => false,'message'=> 'Invalid Rquest','data' => []]);
    }


    /*
     * Function to get favourite vehicle list of loggedin user.
    */
    public function getMyFavouriteVehicleList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        
        $this->autoRender = false ;
        
        $this->loadModel('VehicleFavourite');
        $this->loadModel('User');
        
        $this->VehicleFavourite->Vehicle->unbindModel(
            array('hasMany' => array('VehicleDoc','VehicleFavourite','AuctionBid')),
            array('hasOne' => array('User'))
        );
        
        $token  = $this->request->header('token');
        if ($token) {
            $userData = $this->User->find('first', array(
                    'fields' => array('id'),
                    'conditions' => array('token' => $token )
                ));
            
            $data =  $this->VehicleFavourite->find('all', array(
                'recursive' => 2, 'conditions' => array('user_id' => $userData["User"]['id'], 'is_favourite' => 1)));
            
            return json_encode(['success' => true, 'message' => 'Vehicle listed successfully', 'data' => $data]);
            
        } else {
            return json_encode(['success' => false, 'message' => 'Login required', 'data' => []]);
        } 
       
    }

    /*
    *Get my purchase vehicle list
    */
    public function getMyPurchasedVehicleList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        
        if ($this->request->is('get')) {
            
            $token  = $this->request->header('token');
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token )
                ));
                
                if( !empty($userData) ) {
                    $this->loadModel('Vehicle');
                     
                    $this->Vehicle->unbindModel(
                        array('hasMany' => array('VehicleDoc','VehicleFavourite','AuctionBid','VehicleDamage')),
                        array('hasOne' => array('User'))
                    );
                    $vehicleData = $this->Vehicle->find('all', array(
                        'conditions' => array('buyer_id' => $userData['User']['id'], 'is_sell' => 1)
                    ));
                    
                    return json_encode(['success' => true, 'message' => 'My purchased vehicle list','data'=> $vehicleData]);
                } else {
                    return json_encode(['success' => false,'message' => 'User not found in the system.','data' => []]);
                }
                
            } else {
                return json_encode(['success' => false,'message' => 'Login Required','data' => []]);
            }
        }
        return json_encode(['success' => false ,'message' => 'Invalid Rquest','data' => []]);
    }

    /*
    * Get Invoice
    */
    public function getInvoice() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $this->loadModel('Vehicle');
            $invoiceName = $this->request->query['invoice-name'];
            if ($invoiceName) {
                $query = $this->Vehicle->find('all', array(
                    'conditions' => array('invoice_name' => $invoiceName)
                ));
                if (count($query) > 0) {
                    return json_encode([
                        'sucess' =>true,
                        'message' => 'Invoice listed successfully',
                        'data'=>$query,
                        'filepath'=>WWW_ROOT.'/invoices/'.$invoiceName.'.pdf'
                        ]);
                }
                return json_encode(['success' => false,'message'=>'No record found','data' => []]);
            }
            return json_encode(['success' => false,'message'=> 'Please provide invoice name','data'=>[]]);    
        }
        return json_encode(['success' => false,'message'=> 'Invalid Rquest','data'=>[]]);
    }

    /*
    * Chirag Work API
    */
    /*
    * Set email preference
    */
    public function setEmailPreferences()
    {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        
        $this->autoRender = false;
        if ($this->request->is(['put','post'])) {
            
            $this->loadModel('User');
            $token  = $this->request->header('token');

            if ($token) {
                $userData = $this->User->find('first', array(
                        'conditions' => array('token' => $token )
                    ));
                if ($userData) {
                    if ( $this->request->data['email_preference'] != ''  ) {
                        $this->User->id = $userData['User']['id'];
                        if( $this->User->saveField('email_preference', $this->request->data['email_preference']) ){
                            return json_encode(['success' => true,'message' => 'Email preference set successfully','data' => ['email_preference' => $this->request->data['email_preference'] ]]);
                        } else {
                            return json_encode(['success' => false,'message' => 'Unable to update data. Please try again','data' => []]);
                        }
                        
                    } else {
                        return json_encode(['success' => false,'message' => 'Email preference Required','data' => []]);
                    }
                } else {
                    return json_encode(['success' => false,'message' => 'User does not exist in the system.','data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => 'Login required', 'data' => []]);
            }
        }
        return json_encode(['success' => false,'message' => 'Invalid Request','data' => []]);
    }

    /*
    * Get bidder list
    */
    public function getBidderList(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        $this->loadModel('AuctionBid');
        if ($this->request->is('get')) {
            $vehicleId = $this->request->query['vehicle-id'];
            $query = $this->AuctionBid->find('all', array(
                    'conditions' => array('vehicle_id' => $vehicleId)
                ));
            return json_encode(['success' => true,'message' => 'Bidder List','data'=> $query]);
        }
        return json_encode(['success' => false,'message' => 'Invalid Rquest','data' => []]);     
    }

    /*
    * Get my sell vehicle list
    */
    public function getMySellVehicleList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $token  = $this->request->header('token');
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token )
                ));
                
                if( !empty($userData) ) {
                    $this->loadModel('Vehicle');
                     
                    $this->Vehicle->unbindModel(
                        array('hasMany' => array('VehicleDoc','VehicleFavourite','AuctionBid','VehicleDamage')),
                        array('hasOne' => array('User'))
                    );
                    $vehicleData = $this->Vehicle->find('all', array(
                        'conditions' => array('seller_id' => $userData['User']['id'], 'is_sell' => 1)
                    ));
                    
                    return json_encode(['success' => true, 'message' => 'My sell vehicle list','data'=> $vehicleData]);
                } else {
                    return json_encode(['success' => false,'message' => 'User not found in the system.','data' => []]);
                }
                
            } else {
                return json_encode(['success' => false,'message' => 'Login Required','data' => []]);
            }
        }
        return json_encode(['success' => false,'message'=> 'Invalid Rquest','data' =>[]]);
    }
    /*
    * Get Auction List
    */
    public function getAuctionList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        $this->loadModel('Vehicle');
        $this->loadModel('AuctionBid');
        $this->loadModel('User');
        $orderBy = array();
        $conditions = array();

        $conditions['Vehicle.status'] = 1;
        $conditions['Vehicle.auction_ovr_tym >= '] = date('Y-m-d H:i:s');

        if (isset($this->request->query['makeName']) && $this->request->query['makeName'] != '') {
            $conditions['Vehicle.brand'] = $this->request->query['makeName'];
        }

        if (isset($this->request->query['modelName']) && $this->request->query['modelName'] != '') {
            $conditions['Vehicle.model'] = $this->request->query['modelName'];
        }

        if (isset($this->request->query['regionCode']) && $this->request->query['regionCode'] != '') {
            $conditions['Vehicle.vehicle_regions'] = $this->request->query['regionCode'];
        }

        if (isset($this->request->query['minYear']) && $this->request->query['minYear'] != '') {
            $conditions['YEAR(first_reg) >= '] = $this->request->query['minYear'];
        }

        if (isset($this->request->query['maxYear']) && $this->request->query['maxYear'] != '') {
            $conditions['YEAR(first_reg) <= '] = $this->request->query['maxYear'];
        }

        $orderBy = array();

        $token  = $this->request->header('token');

        if ($token) {
            $query = $this->User->find('first', array(
                    'conditions' => array('token' => $token )
                ));
            if ($query) {

            $this->Vehicle->bindModel(
                    array(
                'hasOne' => array(
                    'MyFavourite' => array(
                        'className' => 'VehicleFavourite',
                        'foreignKey' => 'vehicle_id',
                        'conditions' => array('MyFavourite.user_id' => $query['User']['id'])
                    ),
                )
                    ), false
            );

            $orderBy['MyFavourite.is_favourite'] = 'DESC';

            if (isset($this->request->query['isFavourite']) && $this->request->query['isFavourite'] == 1) {
                $conditions['MyFavourite.is_favourite'] = 1;
            }

            if (isset($this->request->query['shortListed']) && $this->request->query['shortListed'] == 1) {
                $this->Vehicle->bindModel(
                        array(
                    'hasOne' => array(
                        'MyAuctions' => array(
                            'className' => 'AuctionBid',
                            'foreignKey' => 'vehicle_id',
                            'type' => 'INNER',
                            'conditions' => array('MyAuctions.user_id' => $query['User']['id']  )
                        ),
                    )
                        ), false
                );
            }
        
            }
        } else {
            return json_encode(['success' => false,'message'=> 'Please pass token','data' =>[]]);
        }

        if ( isset($this->request->query['body_type']) && $this->request->query['body_type'] != '') {
            $conditions['Vehicle.body_type'] = $this->request->query['body_type'];
        }

        if (isset($this->request->query['sortOn']) && $this->request->query['sortOn'] != '') {
            if ($this->request->query['sortOn'] == 'YLH') {
                $orderBy['Vehicle.created'] = 'ASC';
            } else if ($this->request->query['sortOn'] == 'YLH') {
                $orderBy['Vehicle.created'] = 'DESC';
            } else if ($this->request->query['sortOn'] == 'YHL') {
                $orderBy['Vehicle.created'] = 'DESC';
            } else if ($this->request->query['sortOn'] == 'PLH') {
                $orderBy['Vehicle.min_auction_price'] = 'ASC';
            } else if ($this->request->query['sortOn'] == 'PHL') {
                $orderBy['Vehicle.min_auction_price'] = 'DESC';
            } else if ($this->request->query['sortOn'] == 'KLH') {
                $orderBy['Vehicle.kilometers'] = 'DESC';
            } else if ($this->request->query['sortOn'] == 'KHL') {
                $orderBy['Vehicle.kilometers'] = 'DESC';
            }
        } else {
            $orderBy['Vehicle.created'] = 'DESC';
        }

        $this->Vehicle->unbindModel(array('hasMany' => array('VehicleDoc')), true);

        $auctions = $this->Vehicle->find('all', array('group' => 'Vehicle.id', 'conditions' => $conditions, 'recursive' => 1, 'order' => $orderBy));

        $bidData = $this->AuctionBid->find('all');
        if (!empty($bidData)) {
            $bidArr = array();
            foreach ($bidData as $key => $val) {
                $bidDataPrice = $this->AuctionBid->find('first', array('conditions' => array('AuctionBid.biding_amount > ' => $val['AuctionBid']['biding_amount'], 'AuctionBid.vehicle_id' => $val['AuctionBid']['vehicle_id'])));
                if (!empty($bidDataPrice)) {
                    $bidArr[$bidDataPrice['AuctionBid']['vehicle_id']] = $bidDataPrice['AuctionBid']['biding_amount'];
                }
            }
        }
        return json_encode(['success' => true, 'message' => 'Auction List', 
            'data' => [
                'auction_data'=> $auctions,
                'bidArr' => $bidArr]
            ]);
    }

    /*API for logged in users*/
    public function service_login() {
        
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false ;
        if($this->request->is('post')){
            if (isset($this->request->data['username']) && !empty($this->request->data['username']) && isset($this->request->data['password']) && !empty($this->request->data['password'])) {
                    $this->request->data['User'] = $this->request->data; 
                    if ($this->Auth->login()) {
                      //return json_encode(['success'=> true, 'message' => 'Loggedin successfully!!', 'data' => ['token'=> $this->Session->read('Auth.User.token')  ]]);
                      return json_encode(['success'=> true, 'message' => 'Loggedin successfully!!', 'data' => [$this->Auth->user()]]);
                    }
                return json_encode(['success' => false, 'message' => 'Invalid username-password', 'data' => []]);

            } else {
                return json_encode(['success'=> false, 'message' => 'Required parameter missing', 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => 'Invalid Request', 'data' => []  ]);
    }
    
    /*API for User Registration*/
    public function service_signup() {
        //$this->__sendActivationEmail(44);
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if($this->request->is('post')) {
            //if(isset($this->request->data['prefix_name']) && !empty($this->request->data['prefix_name']) && isset($this->request->data['fname']) && !empty($this->request->data['fname']) && isset($this->request->data['lname']) && !empty($this->request->data['lname']) && isset($this->request->data['username']) && !empty($this->request->data['username']) && isset($this->request->data['password']) && !empty($this->request->data['password']) && isset($this->request->data['phone']) && !empty($this->request->data['phone']) && isset($this->request->data['postcode']) && !empty($this->request->data['town']) && isset($this->request->data['town']) && !empty($this->request->data['town']) && isset($this->request->data['country']) && !empty($this->request->data['country'])){
                $email_already_exist = $this->User->find('count', array('conditions' => array('User.email' => $this->request->data['email'])));
                $usrname_already_exist = $this->User->find('count', array('conditions' => array('User.username' => $this->request->data['username'])));                
                if($email_already_exist){
                    return json_encode(['success' => false, 'message' => 'Email already exist in database', 'data' => []]);
                }
                if($usrname_already_exist) {
                    return json_encode(['success' => false, 'message' => 'Username already exist in database', 'data' => []]);
                }
                $this->request->data['role_id'] = 2; //As user
                $this->request->data['tokenhash'] = Security::hash(String::uuid(), 'sha512', true); //Set token hash key
                $this->request->data['token'] = Security::hash(String::uuid(), 'sha512', true); //Set token hash key
                if ( $lastInsertId = $this->User->saveUser($this->data)) {
                    $this->loadModel('Company');
                    $this->request->data['user_id'] = $this->User->id;
                    if ($this->Company->save($this->request->data)) {

                        //$this->__sendActivationEmail($lastInsertId);
                        $user = $this->User->findById($this->request
                            ->data['user_id']);
                        return json_encode(['success' => true, 'message' => 'User registration successfully', 'data' => [$user]]);
                    } else {
                        return json_encode(['success' => false, 'message' => 'Something went wrong, please try again', 'data' => []]);
                    }                 
                } else {
                    return json_encode(['success' => false, 'message' => 'Can not save user', 'data' => []]);               
                }
            //} else {
            //    return json_encode(['success' => false, 'message' => 'Required parameter missing', 'data' => []]);
            //}
        }
        return json_encode(['success' => false, 'message' => 'Invalid Request', 'data' => []]);
    }
    
    public function changePassword() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        
        $this->autoRender = false;
        $this->loadModel('User');
        if (  $this->request->is(['put','post']) ) {
            
            $token  = $this->request->header('token');
            
            if ($token) {
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token )
                ));
                
                //$userData['User']['current_password'] = $this->request->data['current_password'];
                
                //$this->data = $userData;
                if ( !empty($userData) ) {
                    
                    if (AuthComponent::password($this->request->data['current_password']) == $userData['User']['password']) {
                        
                        $saveData = array();
                        $this->User->id = $userData['User']['id'];
                        $saveData['User']['password'] = $this->request->data['password'];
                        
                        if ($this->User->save($saveData)) {
                            return json_encode(['success' => true, 'message' => 'Password has been changed.', 'data' => []]);
                        } else {
                            return json_encode(['success' => false,'message' => 'Password could not be changed. Please try again.','data' => []]);
                        }
                    } else {
                        return json_encode(['success' => false,'message' => 'Current password not matched','data' => []]);
                    }
                } else {
                    return json_encode(['success' => false,'message' => 'User not found in the system.','data' => []]);
                }
            } else {
                return json_encode(['success' => false,'message' => 'Login Required','data' => []]);
            }
            
            
        } else {
            $this->data = $this->User->findById($this->Auth->user('id'));
        }
    }
    
    public function buyNow() {
        
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
       
        if($this->request->is('post')) {
                return json_encode(['success' => true, 'message' => 'Congratulations. You have buy new vehicle', 'data' => [] ]);
        }
        return json_encode(['success' => false, 'message' => 'Invalid Request', 'data' => []]);
    }
    
}

?>