<?php
App::uses('AppController', 'Controller');

class ApiController extends AppController {

    var $components = array('Email', 'Upload', 'Paginator', 'Cookie','Redis','RequestHandler');
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
    
    public function uploadImage(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        
        if ($this->request->is('post')) {
            
            $img = $_FILES['file'];
           
            $imgNameExt = pathinfo($img["name"]);
            $ext = $imgNameExt['extension'];
            $ext = strtolower($ext);
            
            $newImgName = "Aniket_panchal"; //echo $newImgName; die;
            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
            $tempFile = $img['tmp_name'];
            $destLarge = realpath('../webroot/img/') . '/';
            $file = $img;
            
            $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
            $name = $newImgName . ".jpeg";
            return json_encode(['success' => true,'message' => 'Vehicle images uploaded successfully','data'=> []]);
        }
        
        return json_encode(['success' => false,'message' => 'Invalid request','data'=> [] ]);
        
        
    }
    
    public function step1() {
        
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        
        App::uses('CakeText', 'Utility');
        
        $this->loadModel('Vehicle');
        $this->loadModel('VehicleRegion');
        $this->loadModel('VehicleDamage');
        if ($this->request->is('post')) {
            
            $requestData = $this->data;
            pr($requestData);
            pr($_FILES); die;
            
            
            
            $requestData['Vehicle']['temp_id'] = CakeText::uuid();
            
            if( isset($requestData['Vehicle']['gen_condition']) && !empty($requestData['Vehicle']['gen_condition']) ){
                $requestData['Vehicle']['gen_condition'] = implode(',', $requestData['Vehicle']['gen_condition']); //pr($tmp);die;
            }
            
            $requestData['Vehicle']['seller_id'] = '';
            
            $leftsidename = '';
            $topsidename = '';
            $rightsidename = '';
            $bottomsidename = '';
            $backsidename = '';
            
            $rimgtmp = isset($requestData['VehicleDamage']['rightside']) ? $requestData['VehicleDamage']['rightside'] : null;
            $limgtmp = isset($requestData['VehicleDamage']['leftside']) ? $requestData['VehicleDamage']['leftside'] : null;
            $timgtmp = isset($requestData['VehicleDamage']['topside']) ? $requestData['VehicleDamage']['topside'] : null;
            $bimgtmp = isset($requestData['VehicleDamage']['bottomside']) ? $requestData['VehicleDamage']['bottomside']: null;
            $bkimgtmp = isset($requestData['VehicleDamage']['backside']) ? $requestData['VehicleDamage']['backside'] : null;
            
            if (!empty($rimgtmp)) {
				
                //foreach($rimgtmp as $img) { //pr($img);die;
                    if(isset($rimgtmp['name']) && !empty($rimgtmp['name'])){
                        $imgNameExt = pathinfo($rimgtmp["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "rightside_" . $rimgtmp['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $rimgtmp['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $rimgtmp;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                //}
            }
            
            if (!empty($limgtmp)) {
				
                foreach($limgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "leftside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if (!empty($timgtmp)) {
				
                foreach($timgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "topside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if (!empty($bimgtmp)) {
				
                foreach($bimgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "bottomside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if (!empty($bkimgtmp)) {
				
                foreach($bkimgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "backside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            
            
            if ($this->Vehicle->save($requestData)) {
               
                $vehicleId = 0;
                if (isset($requestData['Vehicle']['id']) && !empty($requestData['Vehicle']['id'])) {
                    
                    $vehicleId = $requestData['Vehicle']['id'];
                } else {
                    $vehicleId = $this->Vehicle->getLastInsertId();
                }
                
                if( $leftsidename != '' || $rightsidename != '' || $topsidename != '' || $bottomsidename != '' || $backsidename != '' ) {
                    $pic['VehicleDamage']['id'] = "";
                    $pic['VehicleDamage']['vehicle_id'] = $vehicleId;
                    $pic['VehicleDamage']['left_file_name'] = $leftsidename;
                    $pic['VehicleDamage']['right_file_name'] = $rightsidename;
                    $pic['VehicleDamage']['top_file_name'] = $topsidename;
                    $pic['VehicleDamage']['bottom_file_name'] = $bottomsidename;
                    $pic['VehicleDamage']['back_file_name'] = $backsidename;
                    //$pic['VehicleDamage']['vehicle_damage'] = isset($tmp['VehicleDamage']['vehicle_damage']) ? implode(',', $tmp['VehicleDamage']['vehicle_damage']) : '';
                    $pic['VehicleDamage']['file_type'] = 2;
                    $this->VehicleDamage->save($pic);
                }
                
                return json_encode(['success' => true, 'message' => 'Congratulations. You have added new vehicle', 'data' => [] ]);
            } else {
                pr($this->Vehicle->validationErrors); die;
                return json_encode(['success' => false, 'message' => 'We are unable to add vehicle. Please try again', 'data' => [] ]);
            }
            
           
        }

//        $ltnId = '';
//        if (isset($this->request->query['ltn']) && $this->request->query['ltn'] != '') {
//            $ltnId = $this->request->query['ltn'];
//        }
//        $vehicle_region = $this->VehicleRegion->find('list', array('fields' => array('VehicleRegion.region_code', 'VehicleRegion.region_name'))); //pr($vehicle_region);die;
//        $this->set('region_data', $vehicle_region);
//        $this->set('ltnId', $ltnId);
//        $this->set('vehicleData', $this->Session->read('vehicleData.data'));

//        if (isset($_SESSION['ADVERTISEMENT_ID'])) { //pr($this->request->data);die;
//            $this->request->data = $this->Vehicle->findById($_SESSION['ADVERTISEMENT_ID']);
//        }
        
        return json_encode(['success' => false, 'message' => 'Invalid Request', 'data' => []]);
    }
    
    public function step2(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        $this->loadModel('Vehicle');
        if ($this->request->is('post')) {
            
//            $data = $this->data;
//            $imgtmp = $data['image'];
            $img = $_FILES['file'];
            if (!empty($imgtmp)) {
                foreach ($imgtmp as $img) { pr($img);die;
                    if (isset($img['name']) && !empty($img['name'])) {
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "Adv_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicle/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicle/orignal/') . '/';
                            $destLarge = realpath('../webroot/img/vehicle/large/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".jpg";
                            }

                            $pic['VehicleDoc']['id'] = "";
                            $pic['VehicleDoc']['vehicle_id'] = $data['VehicleDoc']['vehicle_id'];
                            $pic['VehicleDoc']['file_name'] = $name;
                            $pic['VehicleDoc']['file_type'] = 2;
                            $this->VehicleDoc->save($pic);
                        }
                    }
                }
            }

//            $doctmp = $data['doc'];
            $doctmp = $_FILES['doc'];
            if (!empty($doctmp)) {
                foreach ($doctmp as $doc) { //pr($img);die;
                    if (isset($doc['name']) && !empty($doc['name'])) {
                        $target_dir = realpath('../webroot/files/doc');
                        $name = time(strtotime(date('Y-m-d H:i'))) . '_' . $doc['name'];
                        str_replace(array('#', '.', '"', ' '), "", $name);
                        $target_file = $target_dir . '/' . $name;
                        if (move_uploaded_file($doc["tmp_name"], $target_file)) {
                            $document['VehicleDoc']['id'] = "";
                            $document['VehicleDoc']['vehicle_id'] = $data['VehicleDoc']['vehicle_id'];
                            $document['VehicleDoc']['file_name'] = $name;
                            $document['VehicleDoc']['file_type'] = 1;
                            $this->VehicleDoc->save($document);
                        }
                    }
                }
            }
            
            return json_encode(['success' => true,'message' => 'Vehicle images uploaded successfully','data'=> $query]);
        }
        return json_encode(['success' => false,'message'=> 'Invalid Rquest','data' =>[]]);
    }
    
    public function getCmsPage(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        
        if ($this->request->is('get')) {            
            $language = $this->request->query['language'];
            $pageUrl = $this->request->query['page_url'];
            if ($language && $pageUrl) {
                $this->loadModel('CmsPage');
                $cmsSlugs = $this->CmsPage->find('first', array( 'conditions'=> array( 'page_url' => $pageUrl,'language' => $language ), 'recursive' => -1)); //'conditions'=> array('language'=> $cmsLang )
                return json_encode(['success' => true,'message' => '','data'=> [ 'page_content' => $cmsSlugs['CmsPage']['description'] ]]);
            } else {
                return json_encode(['success' => false,'message'=> 'Please Provide language and page url','data' => []]);
            }
        } else {
            return json_encode(['success' => false,'message'=> 'Invalid request. Please try again','data' => []]);
        }
    }
    
    public function contactUs(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
       
        if($this->request->is('post')) {
            
            $to = "panchal.aniket@gmail.com";
            $subject = "Request from " . trim($this->request->data['first_name'] . ' ' . $this->request->data['last_name']);

            $mailHtml = '<html>
                            <head>
                                <meta name="viewport" content="width=device-width" />
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            </head>
                            <body style="font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; -webkit-font-smoothing:antialiased;  -webkit-text-size-adjust:none;">
                                <table style="width: 100%;" class="body-wrap">
                                    <tr>
                                        <td></td>
                                        <td class="container" bgcolor="#FFFFFF">

                                            <div class="content">
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td>
                                                            <h3>Hello,</h3>
                                                            <p style="margin-bottom: 15px; font-size: 16px; font-weight:bold;" class="callout">Below is the request Detail:</p>
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Customer Type :</b> <span class="booking-id">' . (($this->request->data["user_type"] == 'car_dealer') ? __('Car Dealer') : __('Private User')) . '</span> </p>
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Marketplace :</b> ' . $this->request->data["marketplace"] . ' </p>
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Request Type :</b> ' . $this->request->data["type"] . '</p>
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Topic :</b> ' . $this->request->data["topic"] . '</p>
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Request Description :</b> ' . $this->request->data["my_request"] . ' </p>
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Name :</b> ' . trim($this->request->data["prefix_name"] . " " . $this->request->data["first_name"] . " " . $this->request->data["last_name"]) . ' </p>
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Email :</b> ' . $this->request->data["email"] . '</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                            </body>
                        </html>';


            $option = array();
            $option = array(
                'body' => $mailHtml,
                'from' => 'developer@ef24.ch',
                'fromname' => $this->request->data['first_name'],
                'subject' => 'Contact us email'
            );

            if ($this->Email->contactEmail($option)) {
                return json_encode(['success' => true, 'message' => 'Request has been send successfully.', 'data' => [] ]);
            } else {
                return json_encode(['success' => false, 'message' => 'We are unable to send your request. Please try again.', 'data' => []]);
            }    
        }
        return json_encode(['success' => false, 'message' => 'Invalid Request', 'data' => []]);
    }
    
}

?>