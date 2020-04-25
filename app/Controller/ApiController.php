<?php

App::uses('AppController', 'Controller');
App::uses('String', 'Utility');
App::import('Vendor', 'tcpdf/tcpdf');
App::uses('L10n', 'I18n');

class ApiController extends AppController {

    var $components = array('Email', 'Upload', 'Paginator', 'Cookie', 'Redis', 'RequestHandler');
    public $language;
    
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
        
        
        //I18n::loadPo();
        //echo I18n::translate('Invalid Request. Please try again.' , null, null, 6, null, 'deu');
        
         $this->response->header('Access-Control-Allow-Origin', '*');
         $this->response->header('Access-Control-Allow-Headers', 'token, language');
        
        if( $this->request->header('language') && $this->request->header('language') != '' ){
            switch ($this->request->header('language')){
                case 'en':
                    $this->language = 'eng';
                    //Configure::write('Config.language', 'eng');
                    //$this->Session->write('Config.language', "eng");
                    break;
                case 'de':
                    $this->language = 'deu';
                    //Configure::write('Config.language', 'deu');
                    //$this->Session->write('Config.language', "deu");
                    break;
                case 'ita':
                    //Configure::write('Config.language', $this->Session->read('Config.language'));
                    break;
                case 'fra':
                    //Configure::write('Config.language', $this->Session->read('Config.language'));
                    break;
                default:
                    break;
            }
        }
        
        $this->response->vary('Accept-Language');
        
        //$this->Auth->allow('getCmsPages','getVehicleAuctionDetailPage','getVehicleAuctionBidding','service_addVehicleToFavourite','getUserFavouriteVehicleList','getMyVehicleAuctionList','getMyFavouriteVehicleList','getMyPurchasedVehicleList','getInvoice','setEmailPreferences','getBidderList','getMySellVehicleList','getAuctionList','service_login');
    }
    
    function translate( $text ){
        return I18n::translate($text , null, null, 6, null, $this->language);
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
                    'conditions' => array('page_url' => $pageUrl, 'language' => $language)
                ));
                return json_encode(['success' => true, 'message' => '', 'data' => $query]);
            }
            return json_encode(['success' => false, 'message' => $this->translate('Invalid language or page. Please try again.'), 'data' => []]);
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
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
                    return json_encode(['success' => true, 'message' => '', 'data' => $query]);
                }
                return json_encode(['success' => false, 'message' => $this->translate('Vehicle not found. Please try again.'), 'data' => []]);
            }
            return json_encode(['success' => false, 'message' => $this->translate('Invalid vehicle ID. Please try again.'), 'data' => []]);
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
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
                    return json_encode(['success' => true, 'message' => '', 'data' => $query]);
                }
                return json_encode(['success' => false, 'message' => $this->translate('No auctoin bidding data found. Please try again.'), 'data' => []]);
            }
            return json_encode(['success' => false, 'message' => $this->translate('Invalid vehicle ID. Please try again.'), 'data' => []]);
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /* Function to Add vehicle to Favourite */

    public function service_addVehicleToFavourite() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $user = $this->Auth->user();
            if (!$user) {
                return json_encode(['success' => true, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
            if (isset($this->request->data['vehicleId']) && !empty($this->request->data['vehicleId'])) {
                //Code to add record in database
                $userId = $user['id'];
                $vehicleId = $this->request->data['vehicleId'];
                $this->loadModel('VehicleFavourite');
                $data = $this->VehicleFavourite->find('first', array(
                    'conditions' => array('user_id' => $userId, 'vehicle_id' => $vehicleId)
                        )
                );
                if ($data) {
                    return json_encode(['success' => false, 'message' => $this->translate('Vehicle is alredy added as your favourite list.'), 'data' => []]);
                }
                $this->VehicleFavourite->addToFavourite($userId, $vehicleId);
                return json_encode(['success' => true, 'message' => $this->translate('Vehicle has been added to favourite successfully'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid vehicle ID. Please try again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => true, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Get user favourite vehicle list
     */

    public function getUserFavouriteVehicleList() {
        $this->loadModel('Users');
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $token = $this->request->header('token');
            if ($token) {
                $query = $this->Users->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                if ($query) {
                    $this->loadModel('VehicleFavourite');
                    $query = $this->VehicleFavourite->find('all', array(
                        'conditions' => array('user_id' => $query['Users']['id'])
                    ));
                    return json_encode(['success' => true, 'message' => '', 'data' => $query]);
                }
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Get my vehicle auction list
     */

    public function getMyVehicleAuctionList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $this->loadModel('Vehicle');
            $token = $this->request->header('token');
            if ($token) {
                $userData = $this->Vehicle->User->find('first', array(
                    'fields' => array('id'),
                    'conditions' => array('token' => $token)
                ));

                $this->Vehicle->unbindModel(
                        array('hasMany' => array('VehicleFavourite', 'AuctionBid', 'VehicleDamage')), array('hasOne' => array('User'))
                );

                $data = $this->Vehicle->find('all', array('group' => 'Vehicle.id',
                    'conditions' => array('Vehicle.status' => 1, 'is_active' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s'), 'Vehicle.seller_id' => $userData['User']['id'])
                ));

                return json_encode(['success' => true, 'message' => '', 'data' => $data]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Function to get favourite vehicle list of loggedin user.
     */

    public function getMyFavouriteVehicleList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');

        $this->autoRender = false;

        $this->loadModel('VehicleFavourite');
        $this->loadModel('User');

        $this->VehicleFavourite->Vehicle->unbindModel(
                array('hasMany' => array('VehicleFavourite', 'AuctionBid')), array('hasOne' => array('User'))
        );

        $token = $this->request->header('token');
        if ($token) {
            $userData = $this->User->find('first', array(
                'fields' => array('id'),
                'conditions' => array('token' => $token)
            ));

            $vehicleData = $this->VehicleFavourite->find('all', array(
                'recursive' => 2, 'conditions' => array('user_id' => $userData["User"]['id'], 'is_favourite' => 1)));

            $responseData = [];

            if (!empty($vehicleData)) {
                foreach ($vehicleData as $key => $val) {
                    $responseData[$key]['Vehicle'] = $val['Vehicle'];
                    $responseData[$key]['VehicleFavourite'] = $val['VehicleFavourite'];
                    $responseData[$key]['Buyer'] = $val['Vehicle']['Buyer'];
                    $responseData[$key]['User'] = $val['Vehicle']['User'];
                    $responseData[$key]['VehicleDamage'] = $val['Vehicle']['VehicleDamage'];
                    $responseData[$key]['VehicleImage'] = $val['Vehicle']['VehicleImage'];
                    $responseData[$key]['VehicleDoc'] = $val['Vehicle']['VehicleDoc'];

                    unset($responseData[$key]['Vehicle']['Buyer']);
                    unset($responseData[$key]['Vehicle']['User']);
                    unset($responseData[$key]['Vehicle']['VehicleDamage']);
                    unset($responseData[$key]['Vehicle']['VehicleImage']);
                    unset($responseData[$key]['Vehicle']['VehicleDoc']);
                }
            }

            return json_encode(['success' => true, 'message' => '', 'data' => $responseData]);
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
        }
    }

    /*
     * Get my purchase vehicle list
     */

    public function getMyPurchasedVehicleList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('get')) {

            $token = $this->request->header('token');
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));

                if (!empty($userData)) {
                    $this->loadModel('Vehicle');

                    $this->Vehicle->unbindModel(
                            array('hasMany' => array('VehicleDoc', 'VehicleFavourite', 'AuctionBid', 'VehicleDamage')), array('hasOne' => array('User'))
                    );
                    $vehicleData = $this->Vehicle->find('all', array('group' => 'Vehicle.id',
                        'conditions' => array('buyer_id' => $userData['User']['id'], 'is_sell' => 1)
                    ));

                    return json_encode(['success' => true, 'message' => '', 'data' => $vehicleData]);
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('User not exists in the system.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Get Invoice
     */

    public function getInvoices() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('get')) {

            $token = $this->request->header('token');

            if ($token) {
                $this->loadModel('Vehicle');
                $this->loadModel('User');

                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                if (!empty($userData)) {

                    $this->Vehicle->unbindModel(
                            ['hasMany' => ['AuctionBid', 'VehicleFavourite'], 'hasOne' => ['VehicleImage']]
                    );

                    $vehicles = $this->Vehicle->find('all', array(
                        'conditions' => array('Vehicle.status' => 0, 'Vehicle.is_sell' => 1, 'Vehicle.buyer_id' => $userData['User']['id']), 'order' => 'Vehicle.created ASC', 'recursive' => 2
                    ));


                    //$responseData = [];
                    if (!empty($vehicles)) {
                        foreach ($vehicles as $key => $val) {

                            if (file_exists(WWW_ROOT . '/invoices/' . $val['Vehicle']['invoice_name'])) {
                                $vehicles[$key]['Vehicle']['invoice_url'] = Router::url('/', true) . 'invoices/' . $val['Vehicle']['invoice_name'];
                            } else {
                                $vehicles[$key]['Vehicle']['invoice_url'] = null;
                            }
                        }
                    }

                    return json_encode([
                        'success' => true,
                        'message' => 'Invoice listed successfully.',
                        'data' => [
                            'invoiceData' => $vehicles
                        ],
                            //'filepath' => WWW_ROOT . '/invoices/' . $invoiceName . '.pdf'
                    ]);
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('User not exists in the system.'), 'data' => []]);
                }

                return json_encode(['success' => false, 'message' => $this->translate('Invalid invoice name. Please try again.'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Chirag Work API
     */
    /*
     * Set email preference
     */

    public function setEmailPreferences() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');

        $this->autoRender = false;
        if ($this->request->is(['put', 'post'])) {

            $this->loadModel('User');
            $token = $this->request->header('token');

            if ($token) {
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                if ($userData) {
                    if ($this->request->data['email_preference'] != '') {
                        $this->User->id = $userData['User']['id'];
                        if ($this->User->saveField('email_preference', $this->request->data['email_preference'])) {
                            return json_encode(['success' => true, 'message' => $this->translate('Email preference has been saved successfully.'), 'data' => ['email_preference' => $this->request->data['email_preference']]]);
                        } else {
                            return json_encode(['success' => false, 'message' => $this->translate('Unable to update data. Please try again.'), 'data' => []]);
                        }
                    } else {
                        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('User not exists in the system.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Get bidder list
     */

    public function getBidderList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        $this->loadModel('AuctionBid');
        if ($this->request->is('get')) {
            $vehicleId = $this->request->query['vehicle-id'];
            $query = $this->AuctionBid->find('all', array(
                'conditions' => array('vehicle_id' => $vehicleId)
            ));
            return json_encode(['success' => true, 'message' => 'Bidder List', 'data' => $query]);
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Get my sell vehicle list
     */

    public function getMySellVehicleList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            $token = $this->request->header('token');
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));

                if (!empty($userData)) {
                    $this->loadModel('Vehicle');

                    $this->Vehicle->unbindModel(
                            array('hasMany' => array('VehicleFavourite', 'AuctionBid', 'VehicleDamage')), array('hasOne' => array('User'))
                    );
                    $vehicleData = $this->Vehicle->find('all', array('group' => 'Vehicle.id',
                        'conditions' => array('seller_id' => $userData['User']['id'], 'is_sell' => 1)
                    ));

                    //pr($this->Vehicle->getDataSource()->getLog(false, false)); exit;


                    return json_encode(['success' => true, 'message' => '', 'data' => $vehicleData]);
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('User not exists in the system.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /*
     * Get Auction List
     */

    public function getAuctionList() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        $this->loadModel('Vehicle');
        $this->loadModel('AuctionBid');
        $this->loadModel('User');
        $orderBy = array();
        $conditions = array();

        $conditions['Vehicle.status'] = 1;
        $conditions['Vehicle.is_sell'] = 0;
        $conditions['Vehicle.is_active'] = 1;
        $conditions['Vehicle.auction_ovr_tym >= '] = date('Y-m-d H:i:s');

        if (isset($this->request->data['makeName']) && $this->request->data['makeName'] != '') {
            $conditions['Vehicle.brand'] = $this->request->data['makeName'];
        }

        if (isset($this->request->data['modelName']) && $this->request->data['modelName'] != '') {
            $conditions['Vehicle.model'] = $this->request->data['modelName'];
        }

        if (isset($this->request->data['regionCode']) && $this->request->data['regionCode'] != '') {
            $conditions['Vehicle.vehicle_regions'] = $this->request->data['regionCode'];
        }

        if (isset($this->request->data['minYear']) && $this->request->data['minYear'] != '') {
            $conditions['YEAR(first_reg) >= '] = $this->request->data['minYear'];
        }

        if (isset($this->request->data['maxYear']) && $this->request->data['maxYear'] != '') {
            $conditions['YEAR(first_reg) <= '] = $this->request->data['maxYear'];
        }

        $orderBy = array();

        if ($this->Session->read('Auth.User.id')) {
            $this->Vehicle->bindModel(
                    array(
                'hasOne' => array(
                    'MyFavourite' => array(
                        'className' => 'VehicleFavourite',
                        'foreignKey' => 'vehicle_id',
                        'conditions' => array('MyFavourite.user_id' => $this->Session->read('Auth.User.id'))
                    ),
                )
                    ), false
            );

            $orderBy['MyFavourite.is_favourite'] = 'DESC';

            if (isset($this->request->data['isFavourite']) && $this->request->data['isFavourite'] == 1) {
                $conditions['MyFavourite.is_favourite'] = 1;
            }

            if (isset($this->request->data['shortListed']) && $this->request->data['shortListed'] == 1) {
                //$this->Vehicle->unbindModel(array('hasMany' => array('AuctionBid')), true);
                $this->Vehicle->bindModel(
                        array(
                    'hasOne' => array(
                        'MyAuctions' => array(
                            'className' => 'AuctionBid',
                            'foreignKey' => 'vehicle_id',
                            'type' => 'INNER',
                            'conditions' => array('MyAuctions.user_id' => $this->Session->read('Auth.User.id'))
                        ),
                    )
                        ), false
                );

                //pr($this->Vehicle); die;
                //$conditions['AuctionBid.user_id'] = AuthComponent::User('id');
            }
        }

        if ($this->Cookie->read('__bt') !== null) {
            $conditions['Vehicle.body_type'] = base64_decode($this->Cookie->read('__bt'));
        }

        if (isset($this->request->data['sortOn']) && $this->request->data['sortOn'] != '') {
            if ($this->request->data['sortOn'] == 'YLH') {
                $orderBy['Vehicle.created'] = 'ASC';
            } else if ($this->request->data['sortOn'] == 'YLH') {
                $orderBy['Vehicle.created'] = 'DESC';
            } else if ($this->request->data['sortOn'] == 'YHL') {
                $orderBy['Vehicle.created'] = 'DESC';
            } else if ($this->request->data['sortOn'] == 'PLH') {
                $orderBy['Vehicle.min_auction_price'] = 'ASC';
            } else if ($this->request->data['sortOn'] == 'PHL') {
                $orderBy['Vehicle.min_auction_price'] = 'DESC';
            } else if ($this->request->data['sortOn'] == 'KLH') {
                $orderBy['Vehicle.kilometers'] = 'DESC';
            } else if ($this->request->data['sortOn'] == 'KHL') {
                $orderBy['Vehicle.kilometers'] = 'DESC';
            }
        } else {
            $orderBy['Vehicle.min_auction_price'] = 'DESC';
        }

        //$this->Vehicle->unbindModel(array('hasMany' => array('VehicleDoc')), true);

        $auctions = $this->Vehicle->find('all', array('group' => 'Vehicle.id', 'conditions' => $conditions, 'recursive' => 1, 'order' => $orderBy));

//        $log = $this->Vehicle->getDataSource()->getLog(false, false);
//        pr($log); die;
//        if ($this->Cookie->read('__bt') !== null) {
//                //$this->Paginator->settings = array('limit' => 3,  'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s'),'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))),'order' => 'Vehicle.created ASC');
//                //$auctions = $this->Paginator->paginate('Vehicle');
//                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s'),'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))), 'order' => 'Vehicle.created ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
//        }else{
//                //$this->Paginator->settings = array('limit' => 3,  'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s')),'order' => 'Vehicle.created ASC');
//                //$auctions = $this->Paginator->paginate('Vehicle');
//                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')), 'order' => 'Vehicle.created ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
//        }

        $bidData = $this->AuctionBid->find('all');

        $bidArr = array();
        if (!empty($bidData)) {
            foreach ($bidData as $key => $val) {
                $bidDataPrice = $this->AuctionBid->find('first', array('conditions' => array('AuctionBid.biding_amount > ' => $val['AuctionBid']['biding_amount'], 'AuctionBid.vehicle_id' => $val['AuctionBid']['vehicle_id'])));
                if (!empty($bidDataPrice)) {
                    $bidArr[$bidDataPrice['AuctionBid']['vehicle_id']] = $bidDataPrice['AuctionBid']['biding_amount'];
                }
            }
        }

        $this->loadModel('VehicleRegion');
        $regions = $this->VehicleRegion->find('list', ['fields' => ['region_code', 'region_name']]);

        return json_encode(['success' => true, 'message' => 'Auction List',
            'data' => [
                'auction_data' => $auctions,
                'bidArr' => $bidArr]
        ]);
    }

    /* API for logged in users */

    public function service_login() {

        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        if ($this->request->is('post')) {
            if (isset($this->request->data['username']) && !empty($this->request->data['username']) && isset($this->request->data['password']) && !empty($this->request->data['password'])) {
                $this->request->data['User'] = $this->request->data;
                if ($this->Auth->login()) {
                    //return json_encode(['success'=> true, 'message' => 'Loggedin successfully!!', 'data' => ['token'=> $this->Session->read('Auth.User.token')  ]]);
                    $userData = $this->Auth->user();

                    if ($userData['image'] != '' && file_exists(WWW_ROOT . 'img/users/thumb/' . $userData['image'])) {
                        $userData['image'] = Router::url('/', true) . 'img/users/thumb/' . $userData['image'];
                    } else {
                        $userData['image'] = null;
                    }

                    return json_encode(['success' => true, 'message' => $this->translate('Loggedin successfully.'), 'data' => ['userData' => $userData]]);
                }
                return json_encode(['success' => false, 'message' => $this->translate('Invalid username or password'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Required parameter missing. Please try again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    /* API for User Registration */

    public function service_signup() {
        //$this->__sendActivationEmail(44);
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        if ($this->request->is('post')) {

            $this->loadModel('User');
            CakeLog::write('error', json_encode($this->request->data));
            //if(isset($this->request->data['prefix_name']) && !empty($this->request->data['prefix_name']) && isset($this->request->data['fname']) && !empty($this->request->data['fname']) && isset($this->request->data['lname']) && !empty($this->request->data['lname']) && isset($this->request->data['username']) && !empty($this->request->data['username']) && isset($this->request->data['password']) && !empty($this->request->data['password']) && isset($this->request->data['phone']) && !empty($this->request->data['phone']) && isset($this->request->data['postcode']) && !empty($this->request->data['town']) && isset($this->request->data['town']) && !empty($this->request->data['town']) && isset($this->request->data['country']) && !empty($this->request->data['country'])){
            $email_already_exist = $this->User->find('count', array('conditions' => array('User.email' => $this->request->data['User']['email'])));
            $usrname_already_exist = $this->User->find('count', array('conditions' => array('User.username' => $this->request->data['User']['username'])));
            if ($email_already_exist) {
                return json_encode(['success' => false, 'message' => $this->translate('Email already exists. Please enter different email.'), 'data' => []]);
            }
            if ($usrname_already_exist) {
                return json_encode(['success' => false, 'message' => $this->translate('Username already exists.Please enter different username.'), 'data' => []]);
            }
            $this->request->data['User']['role_id'] = 2; //As user
            $this->request->data['User']['tokenhash'] = Security::hash(String::uuid(), 'sha512', true); //Set token hash key
            $this->request->data['User']['token'] = Security::hash(String::uuid(), 'sha512', true); //Set token hash key
            if ($lastInsertId = $this->User->saveUser($this->request->data)) {
                $this->loadModel('Company');
                $this->request->data['user_id'] = $this->User->id;
                if ($this->Company->save($this->request->data)) {

                    //$this->__sendActivationEmail($lastInsertId);
                    $user = $this->User->findById($this->request
                            ->data['user_id']);
                    return json_encode(['success' => true, 'message' => $this->translate('Your account has been registered successfully.'), 'data' => [$user]]);
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('Something went wrong. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Unable to create your account. Please try again.'), 'data' => []]);
            }
            //} else {
            //    return json_encode(['success' => false, 'message' => 'Required parameter missing', 'data' => []]);
            //}
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function changePassword() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');

        $this->autoRender = false;
        $this->loadModel('User');
        if ($this->request->is(['put', 'post'])) {

            $token = $this->request->header('token');

            if ($token) {
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));

                //$userData['User']['current_password'] = $this->request->data['current_password'];
                //$this->data = $userData;
                if (!empty($userData)) {

                    if (AuthComponent::password($this->request->data['current_password']) == $userData['User']['password']) {

                        $saveData = array();
                        $this->User->id = $userData['User']['id'];
                        $saveData['User']['password'] = $this->request->data['password'];

                        if ($this->User->save($saveData)) {
                            return json_encode(['success' => true, 'message' => $this->translate('Password has been changed successfully.'), 'data' => []]);
                        } else {
                            return json_encode(['success' => false, 'message' => $this->translate('Unable to change password. Please try again.'), 'data' => []]);
                        }
                    } else {
                        return json_encode(['success' => false, 'message' => $this->translate('Invalid current password. Please try again.'), 'data' => []]);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('User not exists in the system.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            $this->data = $this->User->findById($this->Auth->user('id'));
        }
    }

    public function buyNow() {

        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {

            $token = $this->request->header('token');

            if (isset($this->request->data['vehicle_id']) && $this->request->data['vehicle_id'] != '') {
                $this->loadModel('AuctionBid');
                $this->loadModel('Vehicle');
                $this->loadModel('User');

                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));

                $this->AuctionBid->Vehicle->unbindModel(['hasOne' => ['VehicleImage'], 'hasMany' => ['AuctionBid', 'VehicleImage', 'VehicleDoc', 'VehicleFavourite', 'VehicleDamage']]);

                $auctionData = $this->AuctionBid->find('first', array('recursive' => 3, 'conditions' => array('AuctionBid.vehicle_id' => $this->request->data['vehicle_id']), 'contain' => ['Vehicle' => ['User' => 'Company']], 'order' => 'AuctionBid.biding_amount desc'));

                if (!empty($auctionData)) {

                    $filename = 'invoice_' . $this->request->data['vehicle_id'] . '_' . strtotime(date('h:i:s'));
                    // create new PDF document
                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                    // set document information
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor('Aniruddh');
                    $pdf->SetTitle('Invoice');
                    $pdf->SetSubject('Invoice Of Purchased Car');

                    // set default header data
                    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' ' . $auctionData['Vehicle']['id'], PDF_HEADER_STRING);

                    // set header and footer fonts
                    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                    // set default monospaced font
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                    // set margins
                    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                    // set auto page breaks
                    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                    // set image scale factor
                    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                    // set some language-dependent strings (optional)
                    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                        require_once(dirname(__FILE__) . '/lang/eng.php');
                        $pdf->setLanguageArray($l);
                    }

                    // set font
                    $pdf->SetFont('helvetica', 'B', 20);

                    // add a page
                    $pdf->AddPage();

                    $pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);

                    $pdf->SetFont('helvetica', '', 8);

                    $tbl = '
					<table align="right">
						<tr>
							<td><strong>Eintauschfahrzeuge24.ch GmbH</strong></td>
						</tr>
						<tr>
							<td rowspan="2">Oberzelgstrasse 3<br/>8618 Oetwil am See ZH<br/>0 55 553 40 00 <br/>info@ef24.ch<br/>www.ef24.ch</td>
							<td>COL 3 - ROW 2</td>
						</tr>
						<tr>
						   <td>COL 3 - ROW 3</td>
						</tr>

					</table>
					<table align="left">
						<tr>
							<td><strong>' . $auctionData['User']['prefix_name'] . " " . $auctionData['User']['fname'] . " " . $auctionData['User']['lname'] . '</strong></td>
						</tr>
				
					</table>
					<table align="right">
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<table align="left">
									<tr>
										<td><strong>Rechnungs-Nr.</strong> : </td>
										<td align="right"><strong>Invoice_' . $auctionData['Vehicle']['id'] . '</strong></td>
									</tr>
									<tr>
										<td><strong>Kunden-Nr.</strong> : </td>
										<td align="right"><strong>' . $auctionData['User']['id'] . '</strong></td>
									</tr>
									<tr>
										<td><strong>Datum</strong> : </td>
										<td align="right"><strong>' . date('d-m-Y') . '</strong></td>
									</tr>
									<tr>
										<td><strong>Fällig am (Due on)</strong> : </td>
										<td align="right"><strong>' . date("d-m-Y", strtotime(date("d-m-Y") . "+ 10 Days")) . '</strong></td>
									</tr>
									<tr>
										<td><strong>Total</strong> : </td>
										<td align="right"><strong>' . $auctionData['Vehicle']['buy_price'] . ' CHF</strong></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					
					<h3>Rechnung</h3>
					<h3>Rechnungs-Nr. Invoice_' . $auctionData['Vehicle']['id'] . ' / Kunden-Nr. ' . $auctionData['User']['id'] . '</h3>
					
					
					<table cellspacing="0" cellpadding="1">
						<hr/>
						<tr>
							<td align="right" colspan="5"></td>
						</tr>
						<tr>
							<th align="center">Vehicle Nr</th>
							<th align="center">Produkt</th>
							<th align="center">Body Type</th>
							<th align="center">Preis (price)</th>
							<th align="center">Total</th>
						</tr>
						<tr>
							<td align="right" colspan="5"></td>
						</tr>
						<hr/>
						<tr>
							<td align="right" colspan="5"></td>
						</tr>
						<tr>
							<td align="center">' . $auctionData['Vehicle']['vehicle_no'] . '</td>
							<td align="center">' . $auctionData['Vehicle']['brand'] . " " . $auctionData['Vehicle']['model'] . '</td>
							<th align="center">' . $auctionData['Vehicle']['body_type'] . '</th>
							<th align="center">' . $auctionData['Vehicle']['buy_price'] . ' CHF</th>
							<th align="center">' . $auctionData['Vehicle']['buy_price'] . ' CHF</th>
						</tr>
						<tr>
							<td align="right" colspan="5"></td>
						</tr>
						<hr/>
						<tr>
							<td align="right" colspan="5"></td>
						</tr>
						<tr>
							<td align="right" colspan="5"><strong>Total : ' . $auctionData['Vehicle']['buy_price'] . ' CHF</strong></td>
						</tr>
					</table>';

                    $pdf->writeHTML($tbl, true, false, false, false, '');

                    $invoiceFile = WWW_ROOT . '/invoices/' . $filename . '.pdf';

                    //Close and output PDF document
                    $pdfdoc = $pdf->Output($invoiceFile, 'F');

                    //$attachment = chunk_split(base64_encode($pdfdoc));

                    $userNname = $auctionData['User']['fname'] . " " . $auctionData['User']['lname'];
                    $emailData = array(
                        '{USER_NAME}' => $userNname,
                        '{U_EMAIL}' => $auctionData['User']['email'],
                        '{COMPANY_NAME}' => $auctionData['Vehicle']['User']['Company']['name'],
                        '{SELLER_NAME}' => trim($auctionData['Vehicle']['User']['fname'] . ' ' . $auctionData['Vehicle']['User']['lname']),
                        '{SELLER_ADDRESS}' => $auctionData['Vehicle']['User']['Company']['street'],
                        '{SELLER_CITY}' => $auctionData['Vehicle']['User']['Company']['town'],
                        '{SELLER_ZIPCODE}' => $auctionData['Vehicle']['User']['Company']['postcode'],
                        '{SELLER_CONTACT_NUMBER}' => $auctionData['Vehicle']['User']['mobile'],
                    );
                    $this->Email->sendEmail(4, $emailData, WWW_ROOT . '/invoices/' . $filename . '.pdf');

                    $sellerUserName = $auctionData['Vehicle']['User']['fname'] . " " . $auctionData['Vehicle']['User']['lname'];
                    $sellerEmailData = array(
                        '{USER_NAME}' => $sellerUserName,
                        '{U_EMAIL}' => $auctionData['User']['email'],
                        '{COMPANY_NAME}' => $auctionData['User']['Company']['name'],
                        '{BUYER_NAME}' => trim($auctionData['User']['fname'] . ' ' . $auctionData['Vehicle']['User']['lname']),
                        '{BUYER_ADDRESS}' => $auctionData['User']['Company']['street'],
                        '{BUYER_CITY}' => $auctionData['User']['Company']['town'],
                        '{BUYER_ZIPCODE}' => $auctionData['User']['Company']['postcode'],
                        '{BUYER_CONTACT_NUMBER}' => $auctionData['User']['mobile'],
                    );


                    $invoiceFileName = $filename . '.pdf';

                    $this->Vehicle->updateAll(array('Vehicle.invoice_name' => '"' . $invoiceFileName . '"', "Vehicle.status" => '0', "Vehicle.is_sell" => '1', "Vehicle.buyer_id" => $userData['User']['id']), array("Vehicle.id" => $this->request->data['vehicle_id']));
                    $this->Email->sendEmail(5, $sellerEmailData);
                    return json_encode(['success' => true, 'message' => $this->translate('Congratulations. You have buy new vehicle.'), 'data' => []]);
                } else {
                    $this->Vehicle->updateAll(array("Vehicle.status" => '0'), array("Vehicle.id" => $this->request->data['vehicle_id']));
                    return json_encode(['success' => true, 'message' => $this->translate('Car has been sold successfully.'), 'data' => []]);
                }

                return json_encode(['success' => true, 'message' => $this->translate('Congratulations. You have buy new vehicle.'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid vehicle ID. Please try again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function uploadImage() {
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
            return json_encode(['success' => true, 'message' => $this->translate('Vehicle image has been uploaded successfully.'), 'data' => []]);
        }

        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function step1() {

        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        $this->loadModel('Vehicle');
        $this->loadModel('VehicleRegion');
        $this->loadModel('VehicleDamage');
        $this->loadModel('User');
        if ($this->request->is('post')) {

            $requestData = $this->data;

            $token = $this->request->header('token');

            $userData = $this->User->find('first', array(
                'conditions' => array('token' => $token)
            ));

            if (!empty($userData)) {
                $requestData['Vehicle']['seller_id'] = $userData['User']['id'];
            }

            if (isset($requestData['Vehicle']['gen_condition']) && !empty($requestData['Vehicle']['gen_condition'])) {
                $requestData['Vehicle']['gen_condition'] = implode(',', $requestData['Vehicle']['gen_condition']); //pr($tmp);die;
            }

            //$requestData['Vehicle']['is_damage'] = ( $requestData['Vehicle']['is_damage'] == '0' ) ? false : true;

            if ($this->Vehicle->save($requestData)) {

                $vehicleId = 0;
                if (isset($requestData['Vehicle']['id']) && !empty($requestData['Vehicle']['id'])) {
                    $vehicleId = $requestData['Vehicle']['id'];
                } else {
                    $vehicleId = $this->Vehicle->getLastInsertId();
                }

                return json_encode(['success' => true, 'message' => '', 'data' => ['vehicle_temp_id' => $vehicleId]]); //Congratulations. You have added new vehicle
            } else {
                // pr($this->Vehicle->validationErrors); die;
                return json_encode(['success' => false, 'message' => $this->translate('Unable to add vehicle. Please try again.'), 'data' => []]);
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

        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function step2() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;
        $this->loadModel('Vehicle');

        if ($this->request->is('post')) {
            $data = $this->data;
            $imgtmp = $data['VehicleDoc']['image'];

            if (!empty($imgtmp)) {
                $i = 0;
                for ($i = 0; $i < count($imgtmp); $i++) {

                    if (isset($imgtmp['name'][$i]) && !empty($imgtmp['name'][$i])) {

                        $imgNameExt = pathinfo($imgtmp["name"][$i]);

                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);

                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "Adv_" . $imgtmp['size'][$i] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $imgtmp['tmp_name'][$i];

                            $destThumb = realpath('../webroot/img/vehicle/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicle/orignal/') . '/';
                            $destLarge = realpath('../webroot/img/vehicle/large/') . '/';
                            $file = $imgtmp['tmp_name'][$i];

                            //$file[$i]['name'] = $imgNameExt['filename'] . '.' . $ext;

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

                            //$pic['VehicleDoc']['id'] = "";
//                            $pic['VehicleDoc']['vehicle_id'] = $data['VehicleDoc']['vehicle_id'];
//                            $pic['VehicleDoc']['file_name'] = $name;
//                            $pic['VehicleDoc']['file_type'] = 2;
//                            $this->VehicleDoc->save($pic);
                        }
                    }
                }
            }

//            $doctmp = $data['doc'];
//            $doctmp = $_FILES['doc'];
//            if (!empty($doctmp)) {
//                foreach ($doctmp as $doc) { //pr($img);die;
//                    if (isset($doc['name']) && !empty($doc['name'])) {
//                        $target_dir = realpath('../webroot/files/doc');
//                        $name = time(strtotime(date('Y-m-d H:i'))) . '_' . $doc['name'];
//                        str_replace(array('#', '.', '"', ' '), "", $name);
//                        $target_file = $target_dir . '/' . $name;
//                        if (move_uploaded_file($doc["tmp_name"], $target_file)) {
//                            ///$document['VehicleDoc']['id'] = "";
////                            $document['VehicleDoc']['vehicle_id'] = $data['VehicleDoc']['vehicle_id'];
////                            $document['VehicleDoc']['file_name'] = $name;
////                            $document['VehicleDoc']['file_type'] = 1;
////                            $this->VehicleDoc->save($document);
//                        }
//                    }
//                }
//            }

            return json_encode(['success' => true, 'message' => $this->translate('Vehicle image has been uploaded successfully.'), 'data' => []]);
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function getCmsPage() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('get')) {
            $language = $this->request->query['language'];
            $pageUrl = $this->request->query['page_url'];
            if ($language && $pageUrl) {
                $this->loadModel('CmsPage');
                $cmsSlugs = $this->CmsPage->find('first', array('conditions' => array('page_url' => $pageUrl, 'language' => $language), 'recursive' => -1)); //'conditions'=> array('language'=> $cmsLang )
                return json_encode(['success' => true, 'message' => '', 'data' => ['page_content' => $cmsSlugs['CmsPage']['description']]]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid language or page. Please try again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function contactUs() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {

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
                                                            <p style="margin-bottom: 10px; font-weight: normal; font-size:14px; line-height:1.6;"><b> Customer Type :</b> <span class="booking-id">' . (($this->request->data["user_type"] == 'car_dealer') ? $this->translate('Car Dealer') : $this->translate('Private User')) . '</span> </p>
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
                return json_encode(['success' => true, 'message' => $this->translate('Request has been send successfully. We will be get back to you soon.'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('We are unable to submit your request. Please try again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function getVehicleTempDetail() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('get')) {
            $this->loadModel('Vehicle');
            $vehicleTempId = $this->request->query['vehicle_temp_id'];
            if ($vehicleTempId != '') {
                $this->Vehicle->unbindModel(
                        array('hasMany' => array('VehicleFavourite', 'AuctionBid', 'Buyer')), array('hasOne' => array('User', 'Buyer'))
                );
                $vehicleData = $this->Vehicle->find('first', array('conditions' => array('Vehicle.id' => $vehicleTempId), 'recursive' => 1));
                //pr($vehicleData);
                $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                        
                if( !empty($vehicleData['VehicleDamage']) ){
                    foreach($vehicleData['VehicleDamage'] as $key=> $val ){
                        
                        $fileName = '';
                        if( $val['bottom_file_name'] ){
                            $fileName = $val['bottom_file_name'];
                        } else if( $val['left_file_name'] ){
                            $fileName = $val['left_file_name'];
                        } else if( $val['top_file_name'] ){
                            $fileName = $val['top_file_name'];
                        } else if( $val['right_file_name'] ){
                            $fileName = $val['right_file_name'];
                        } else if( $val['back_file_name'] ){
                            $fileName = $val['back_file_name'];
                        }
                        
                        if(file_exists($destOriginal.$fileName  ) ){
                            $vehicleData['VehicleDamage'][$key]['imageUrl'] =  BASE_URL . '/img/vehicledamage/orignal/'. $fileName;
                        }
                    }
                }
                
                return json_encode(['success' => true, 'message' => '', 'data' => ['vehicleData' => $vehicleData]]);
            } else {
                return json_encode(['success' => true, 'message' => '', 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function activateVehicle() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            //CakeLog::write('error', json_encode($this->request->data));

            if (isset($this->request->data['Vehicle']['auction_duration'])) {
                if ($this->request->data['Vehicle']['auction_duration'] == '2') {
                    $timeAdd = '2 days';
                } else if ($this->request->data['Vehicle']['auction_duration'] == '3') {
                    $timeAdd = '3 days';
                } else {
                    $timeAdd = '24 hours';
                }
                $this->request->data['Vehicle']['auction_ovr_tym'] = date('Y-m-d H:i:s', strtotime('+' . $timeAdd));
            }

            $this->request->data['Vehicle']['status'] = 1;

            $this->loadModel('Vehicle');
            if ($this->Vehicle->save($this->request->data)) {
                return json_encode(['success' => true, 'message' => $this->translate('Vehicle has been added successfully.'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Unable to add vehicle. Please try again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function uploadVehicleImages() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->loadModel('VehicleDoc');
            $data = $this->data;
            CakeLog::info(json_encode($data));
            $img = $data['VehicleDoc']['image'];

            if (!empty($img)) {
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
                        
                        $vehicleImageId = $this->request->data['vehicle_img_id'];
                        
                        if( $vehicleImageId != null ){
                            
                            $vehicleImageData = $this->VehicleDoc->find('first', array(
                                'conditions'=>array('id' => $vehicleImageId ),
                                'recursive' => -1
                            ));
                            
                            if( !empty($vehicleImageData) ){
                                $destThumb = realpath('../webroot/img/vehicle/thumb/') . '/';
                                $destOriginal = realpath('../webroot/img/vehicle/orignal/') . '/';
                                $destLarge = realpath('../webroot/img/vehicle/large/') . '/';

                                $fileName = $vehicleImageData['VehicleDoc']['file_name'];

                                if(file_exists($destOriginal.$fileName  ) ){
                                    @unlink($destOriginal.$fileName);
                                    @unlink($destThumb.$fileName);
                                    @unlink($destLarge.$fileName);
                                    $this->VehicleDoc->delete($vehicleImageId);
                                }
                            }
                        }
                        
                        $pic['VehicleDoc']['id'] = "";
                        $pic['VehicleDoc']['vehicle_id'] = $data['vehicle_id']; //$img['data']['id']
                        $pic['VehicleDoc']['file_name'] = $name;
                        $pic['VehicleDoc']['file_type'] = 2;
                        $this->VehicleDoc->save($pic);
                    } else {
                        return json_encode(['success' => false, 'message' => $this->translate('Invalid vehicle image format. Please upload valid image type.'), 'data' => []]);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('Please select vehicle image.'), 'data' => []]);
                }
            }

            $imageUrl = Router::url('/', true) . 'img/vehicle/thumb/' . $name;
            
            $vehicleImageId = $this->VehicleDoc->getLastInsertId();
            
            return json_encode(['success' => true, 'message' => '', 'data' => ['id' => $vehicleImageId,'imageUrl' => $imageUrl]]);
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function uploadVehicleDocs() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->loadModel('VehicleDoc');
            $data = $this->data;
            //CakeLog::info(json_encode($data));

            $doc = $data['VehicleDoc']['doc'];
            //if (!empty($doctmp)) {
                //foreach ($doctmp as $doc) {
                    if (isset($doc['name']) && !empty($doc['name'])) {
                        $target_dir = realpath('../webroot/files/doc');
                        $name = time(strtotime(date('Y-m-d H:i'))) . '_' . $doc['name'];
                        str_replace(array('#', '.', '"', ' '), "", $name);
                        $target_file = $target_dir . '/' . $name;
                        if (move_uploaded_file($doc["tmp_name"], $target_file)) {
                            
                            
                            $vehicleImageId = $this->request->data['vehicle_img_id'];
                        
                            if( $vehicleImageId != null ){

                                $vehicleImageData = $this->VehicleDoc->find('first', array(
                                    'conditions'=>array('id' => $vehicleImageId ),
                                    'recursive' => -1
                                ));

                                if( !empty($vehicleImageData) ){
                                    $destOriginal = realpath('../webroot/files/doc/') . '/';

                                    $fileName = $vehicleImageData['VehicleDoc']['file_name'];

                                    if(file_exists($destOriginal.$fileName  ) ){
                                        @unlink($destOriginal.$fileName);
                                        $this->VehicleDoc->delete($vehicleImageId);
                                    }
                                }
                            }
                            
                            $document['VehicleDoc']['id'] = "";
                            $document['VehicleDoc']['vehicle_id'] = $data['vehicle_id'];
                            $document['VehicleDoc']['file_name'] = $name;
                            $document['VehicleDoc']['file_type'] = 1;
                            $this->VehicleDoc->save($document);
                        }
                    }
                //}
            //}
            $vehicleDocId = $this->VehicleDoc->getLastInsertId();
            $imageUrl = Router::url('/', true) . 'files/doc/' . $name;
            return json_encode(['success' => true, 'message' => '', 'data' => [ 'id' => $vehicleDocId,'imageUrl' => $imageUrl]]);
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function uploadVehicleDamage() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->loadModel('VehicleDoc');
            $data = $this->data;
            CakeLog::info(json_encode($data));

            $doctmp = $data['VehicleDoc']['doc'];
            if (!empty($doctmp)) {
                foreach ($doctmp as $doc) {
                    if (isset($doc['name']) && !empty($doc['name'])) {
                        $target_dir = realpath('../webroot/files/doc');
                        $name = time(strtotime(date('Y-m-d H:i'))) . '_' . $doc['name'];
                        str_replace(array('#', '.', '"', ' '), "", $name);
                        $target_file = $target_dir . '/' . $name;
                        if (move_uploaded_file($doc["tmp_name"], $target_file)) {
                            $document['VehicleDoc']['id'] = "";
                            $document['VehicleDoc']['vehicle_id'] = $data['vehicle_id'];
                            $document['VehicleDoc']['file_name'] = $name;
                            $document['VehicleDoc']['file_type'] = 1;
                            $this->VehicleDoc->save($document);
                        }
                    }
                }
            }

            $imageUrl = Router::url('/', true) . 'files/doc/' . $name;
            return json_encode(['success' => true, 'message' => $this->translate('Vehicle damage image has been uploaded successfully.'), 'data' => ['imageUrl' => $imageUrl]]);
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    public function getVehicleBids() {

        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('get')) {

            $vehicleId = $this->request->query['vehicle_id'];

            $this->loadModel('Users');
            $this->loadModel('Vehicle');
            $vehicles = $this->Vehicle->find('first', array('recursive' => 1, 'conditions' => array('Vehicle.id' => $vehicleId)));

            $buyers = $this->Users->find('all', array('conditions' => array('Users.status' => 1)));
            $buyerArr = array();
            $datas = array();
            if (!empty($buyers)) {
                foreach ($buyers as $key => $val) {
                    $buyerArr[$val['Users']['id']] = $val['Users']['username'];
                }
            }

            $minAuctionPrice = 0;
            $bidingPriceArray = [];
            if (!empty($vehicles['AuctionBid'])) {
                foreach ($vehicles['AuctionBid'] as $key => $val) {
                    $bidingPriceArray[] = $val['biding_amount'];
                }
            }

            $this->array_sort_by_column($vehicles['AuctionBid'], 'biding_amount', SORT_DESC);

            if (!empty($bidingPriceArray)) {
                $minAuctionPrice = max($bidingPriceArray);
            }

            $bidDropdown = [];
            if ($minAuctionPrice > 0) {
                for ($i = $minAuctionPrice + $vehicles['Vehicle']['increase_with']; $i <= $minAuctionPrice * 2; $i = $i + $vehicles['Vehicle']['increase_with']) { //increase_with
                    $i = (int) $i;
                    $bidDropdown[$i] = $i;
                }
            } else {
                for ($i = $vehicles['Vehicle']['min_auction_price']; $i <= $vehicles['Vehicle']['min_auction_price'] * 2; $i = $i + $vehicles['Vehicle']['increase_with']) {
                    $i = (int) $i;
                    $bidDropdown[$i] = $i;
                }
            }

            return json_encode(['success' => true, 'message' => '', 'data' => ['buyerArr' => $buyerArr, 'bidDropDown' => $bidDropdown]]);
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function bidVehicle() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {

            $this->loadModel('AuctionBid');
            $this->loadModel('Vehicle');
            $this->loadModel('User');

            $token = $this->request->header('token');

            $userData = $this->User->find('first', array(
                'conditions' => array('token' => $token)
            ));

            if (!empty($userData)) {
                $this->request->data['AuctionBid']['user_id'] = $userData['User']['id'];
            }

            $this->request->data['AuctionBid']['biding_amount'] = $this->request->data['biding_amount'];
            $this->request->data['AuctionBid']['vehicle_id'] = $this->request->data['vehicle_id'];

            $bidDataCount = $this->AuctionBid->find('count', array('conditions' => array('AuctionBid.user_id' => $userData['User']['id'], 'AuctionBid.vehicle_id' => $this->request->data['vehicle_id'])));

            $vehicleData = $this->Vehicle->find('first', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.id' => $this->request->data['vehicle_id']))); //pr($auctions); die;

            $start_date = new DateTime($vehicleData['Vehicle']['auction_ovr_tym']);
            $since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));

            $dateDiffSec = $seconds = $since_start->days * 86400 + $since_start->h * 3600 + $since_start->i * 60 + $since_start->s;

            if ($bidDataCount > 0) {
                $this->AuctionBid->updateAll(array("AuctionBid.biding_amount" => $this->request->data['biding_amount']), array("AuctionBid.user_id" => $userData['User']['id'], "AuctionBid.vehicle_id" => $this->request->data['vehicle_id']));
            } else {
                $this->AuctionBid->save($this->request->data);
            }

            if ($since_start->i <= 10) {
                $dateDiffSec = $dateDiffSec + 600;
                $this->Redis->setRedisKey('vehicle_' . $this->request->data['vehicle_id'], $dateDiffSec);
                $this->Redis->expireRedisKey('vehicle_' . $this->request->data['vehicle_id'], $dateDiffSec);

                $newTime = date("Y-m-d H:i:s", strtotime($vehicleData['Vehicle']['auction_ovr_tym'] . " +10 minutes"));
                $this->Vehicle->updateAll(array("Vehicle.auction_ovr_tym" => "'" . $newTime . "'"), array("Vehicle.id" => $this->request->data['vehicle_id']));
            }
            return json_encode(['success' => true, 'message' => $this->translate('Your bid has been recorded successfully.'), 'data' => []]);
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function uploadDamageVehicle() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {

            $vehicleId = $this->data['vehicle_id'];
            $this->loadModel('VehicleDamage');
            $leftsidename = '';
            $topsidename = '';
            $rightsidename = '';
            $bottomsidename = '';
            $backsidename = '';

            $rimgtmp = isset($this->data['VehicleDamage']['rightside']) ? $this->data['VehicleDamage']['rightside'] : null;
            $limgtmp = isset($this->data['VehicleDamage']['leftside']) ? $this->data['VehicleDamage']['leftside'] : null;
            $timgtmp = isset($this->data['VehicleDamage']['topside']) ? $this->data['VehicleDamage']['topside'] : null;
            $bimgtmp = isset($this->data['VehicleDamage']['bottomside']) ? $this->data['VehicleDamage']['bottomside'] : null;
            $bkimgtmp = isset($this->data['VehicleDamage']['backside']) ? $this->data['VehicleDamage']['backside'] : null;

            $imageUrl = BASE_URL . '/img/vehicledamage/orignal/';
            
            if (!empty($rimgtmp)) {

                foreach ($rimgtmp as $img) {
                    if (isset($img['name']) && !empty($img['name'])) {
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "rightside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".jpg";
                            }
                        }
                    }
                }
            }

            if (!empty($limgtmp)) {

                foreach ($limgtmp as $img) { //pr($img);die;
                    if (isset($img['name']) && !empty($img['name'])) {
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

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".jpg";
                            }
                        }
                    }
                }
            }

            if (!empty($timgtmp)) {

                foreach ($timgtmp as $img) { //pr($img);die;
                    if (isset($img['name']) && !empty($img['name'])) {
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

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".jpg";
                            }
                        }
                    }
                }
            }

            if (!empty($bimgtmp)) {

                foreach ($bimgtmp as $img) { //pr($img);die;
                    if (isset($img['name']) && !empty($img['name'])) {
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

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".jpg";
                            }
                        }
                    }
                }
            }

            if (!empty($bkimgtmp)) {

                foreach ($bkimgtmp as $img) { //pr($img);die;
                    if (isset($img['name']) && !empty($img['name'])) {
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

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".jpg";
                            }
                        }
                    }
                }
            }
            $imageResponseUrl = '';
            if ($leftsidename != '') {
                $imageResponseUrl = $imageUrl . $leftsidename;
                $pic['VehicleDamage']['left_file_name'] = $leftsidename;
                $pic['VehicleDamage']['file_type'] = 2;
                $pic['VehicleDamage']['vehicle_id'] = $vehicleId;
                $this->VehicleDamage->save($pic);
            } else if ($rightsidename != '') {
                $imageResponseUrl = $imageUrl . $rightsidename;
                $pic['VehicleDamage']['right_file_name'] = $rightsidename;
                $pic['VehicleDamage']['file_type'] = 2;
                $pic['VehicleDamage']['vehicle_id'] = $vehicleId;
                $this->VehicleDamage->save($pic);
            } else if ($topsidename != '') {
                $imageResponseUrl = $imageUrl . $topsidename;
                $pic['VehicleDamage']['top_file_name'] = $topsidename;
                $pic['VehicleDamage']['file_type'] = 2;
                $pic['VehicleDamage']['vehicle_id'] = $vehicleId;
                $this->VehicleDamage->save($pic);
            } else if ($bottomsidename != '') {
                $imageResponseUrl = $imageUrl . $bottomsidename;
                $pic['VehicleDamage']['bottom_file_name'] = $bottomsidename;
                $pic['VehicleDamage']['file_type'] = 2;
                $pic['VehicleDamage']['vehicle_id'] = $vehicleId;
                $this->VehicleDamage->save($pic);
            } else if ($backsidename != '') {
                $imageResponseUrl = $imageUrl . $backsidename;
                $pic['VehicleDamage']['back_file_name'] = $backsidename;
                $pic['VehicleDamage']['file_type'] = 2;
                $pic['VehicleDamage']['vehicle_id'] = $vehicleId;
                $this->VehicleDamage->save($pic);
            }
            
            $vehicleDamageId = $this->VehicleDamage->getLastInsertID();

            return json_encode(['success' => true, 'message' => $this->translate('Vehicle damage image has been uploaded successfully.'), 'data' => [ 'imageUrl' => $imageResponseUrl, 'id' => $vehicleDamageId ]]);
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function uploadProfilePic() {


        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {

            $this->loadModel('User');

            $token = $this->request->header('token');
            $data = $this->data;

            $userData = $this->User->find('first', array(
                'conditions' => array('token' => $token)
            ));

            if (!empty($userData)) {
                $this->User->id = $userData['User']['id'];
                $data['User']['id'] = $userData['User']['id'];
                $data['User']['old_image'] = $userData['User']['image'];
            }

            $imgtmp = $data['User']['image'];

            if (isset($imgtmp['name']) && !empty($imgtmp['name'])) {
                $imgNameExt = pathinfo($imgtmp["name"]);

                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = "Usr_" . $imgtmp['size'] . "_" . time(); //echo $newImgName; die;
                    $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                    $tempFile = $imgtmp['tmp_name'];
                    $destThumb = realpath('../webroot/img/users/thumb/') . '/';
                    $destOriginal = realpath('../webroot/img/users/orignal/') . '/';
                    $destLarge = realpath('../webroot/img/users/large/') . '/';
                    $file = $imgtmp;

                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".gif";

                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif");
                        $name = $newImgName . ".gif";

                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('800', '800')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".png";

                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png");
                        $name = $newImgName . ".png";

                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('800', '800')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".jpeg";

                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg");
                        $name = $newImgName . ".jpeg";

                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('800', '800')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".jpg";

                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg");
                        $name = $newImgName . ".jpg";

                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('800', '800')));
                        $name = $newImgName . ".jpg";
                    }
                    $data['User']['image'] = $name;
                    if (isset($data['User']['old_image']) && !empty($data['User']['old_image'])) {
                        @unlink(realpath('../webroot/img/users/thumb/') . $data['User']['old_image']);
                        @unlink(realpath('../webroot/img/users/large/') . $data['User']['old_image']);
                        @unlink(realpath('../webroot/img/users/orignal/') . $data['User']['old_image']);
                    }
                }
            } else {
                unset($data['User']['image']);
                return json_encode(['success' => false, 'message' => 'Please upload file.', 'data' => []]);
            }

            if ($this->User->save($data)) {

                $imageUrl = Router::url('/', true) . '/img/users/thumb/' . $name;

                return json_encode(['success' => true, 'message' => $this->translate('Profile picture has been uploaded successfully.'), 'data' => ['imageUrl' => $imageUrl]]);
            } else {
                return json_encode(['success' => true, 'message' => $this->translate('Unable to upload profile picture. Please try again'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function getProfileData() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        $this->loadModel('User');
        if ($this->request->is('get')) {

            $token = $this->request->header('token');
            $data = $this->data;

            $userData = $this->User->find('first', array(
                'conditions' => array('token' => $token)
            ));

            if (!empty($userData)) {

                if ($userData['User']['image'] != '' && file_exists(WWW_ROOT . 'img/users/thumb/' . $userData['User']['image'])) {
                    $userData['User']['image'] = Router::url('/', true) . 'img/users/thumb/' . $userData['User']['image'];
                } else {
                    $userData['User']['image'] = null;
                }

                return json_encode(['success' => true, 'message' => '', 'data' => ['userData' => $userData]]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function updateProfileData() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {

            $this->loadModel('User');

            $token = $this->request->header('token');

            $data = $this->data;


            $userData = $this->User->find('first', array(
                'conditions' => array('token' => $token)
            ));

            if (!empty($userData)) {
                $data['User']['id'] = $userData['User']['id'];
                $data['Company']['id'] = $userData['Company']['id'];
            }

            if ($this->User->save($data)) {
                $this->User->Company->save($data);
                return json_encode(['success' => true, 'message' => $this->translate('User profile has been updated successfully.'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Unable to save profile information. Please try again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function makeFavourite() {

        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $token = $this->request->header('token');

            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));

                if (!empty($userData)) {
                    $this->loadModel('VehicleFavourite');

                    $vehicleFavData = $this->VehicleFavourite->find('first', [
                        'fields' => ['is_favourite'],
                        'conditions' => array('vehicle_id' => $this->request->data['vehicle_id'], 'user_id' => $userData['User']['id'])
                    ]);

                    if (!empty($vehicleFavData)) {
                        if ($vehicleFavData['VehicleFavourite']['is_favourite'] == 1) {
                            $is_favourite = '0';
                        } else {
                            $is_favourite = '1';
                        }

                        $this->VehicleFavourite->updateAll(array("VehicleFavourite.is_favourite" => $is_favourite), array("VehicleFavourite.user_id" => $userData['User']['id'], "VehicleFavourite.vehicle_id" => $this->request->data['vehicle_id']));

                        if ($is_favourite == '1') {
                            return json_encode(['success' => true, 'message' => $this->translate('Vehicle has been added to favourite successfully'), 'data' => ['isFavourite' => $is_favourite]]);
                        } else {
                            return json_encode(['success' => true, 'message' => $this->translate('Vehicle has been added to unfavourite successfully'), 'data' => ['isFavourite' => $is_favourite]]);
                        }
                    } else {
                        $favData = [];
                        $favData['VehicleFavourite']['user_id'] = $userData['User']['id'];
                        $favData['VehicleFavourite']['vehicle_id'] = $this->request->data['vehicle_id'];
                        $favData['VehicleFavourite']['is_favourite'] = 1;
                        $this->VehicleFavourite->save($favData);
                        return json_encode(['success' => true, 'message' => $this->translate('Vehicle has been added to favourite successfully'), 'data' => ['isFavourite' => 1]]);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function checkVehicleFavourite() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        $this->loadModel('User');
        if ($this->request->is('get')) {

            $token = $this->request->header('token');

            if ($token) {
                $vehicleId = $this->request->query['vehicle_id'];
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));

                $this->loadModel('VehicleFavourite');
                $vehicleFavData = $this->VehicleFavourite->find('first', [
                    'fields' => ['is_favourite'],
                    'conditions' => array('vehicle_id' => $vehicleId, 'user_id' => $userData['User']['id'])
                ]);

                if (!empty($vehicleFavData)) {
                    return json_encode(['success' => true, 'message' => '', 'data' => ['is_favourite' => $vehicleFavData['VehicleFavourite']['is_favourite']]]);
                } else {
                    return json_encode(['success' => true, 'message' => '', 'data' => ['is_favourite' => '0']]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function getVehicleId() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('get')) {

            $token = $this->request->header('token');

            if ($token) {
                $this->loadModel('Vehicle');

                //$vehicleData = $this->Vehicle->find('first', ['fields' => ['MAX(Vehicle.id) as max_vehicle_id'], 'recursive' => -1]);
                //$tempVehId = $vehicleData[0]['max_vehicle_id'] + 1;
                
                $this->Vehicle->save([]);
                $this->Vehicle->getLastInsertId();
                
                $tempVehId = $this->Vehicle->getLastInsertId();

                if (!empty($tempVehId)) {
                    return json_encode(['success' => true, 'message' => '', 'data' => ['id' => $tempVehId]]);
                } else {
                    return json_encode(['success' => true, 'message' => '', 'data' => ['id' => '0']]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        }
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }

    public function deleteVehicle() {

        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $token = $this->request->header('token');

            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));

                if (!empty($userData)) {
                    $this->loadModel('Vehicle');
                    $vehicleId = $this->request->data['vehicle_id'];

                    //$vehicle = $this->Vehicle->get($vehicleId);
                    //pr($vehicle); die;
                    $this->Vehicle->delete($vehicleId);

                    return json_encode(['success' => true, 'message' => $this->translate('Vehicle has been deleted successfully.'), 'data' => []]);
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

    public function checkUsername() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->autoRender = false;

        if ($this->request->is('post')) {

            $this->loadModel('User');
            $userExistCount = $this->User->find('count', array(
                'conditions' => array('username' => $this->request->data['username'])
            ));

            if( $userExistCount > 0 ){
                return json_encode(['success' => true, 'message' => $this->translate('Username already exists.Please enter different username.'), 'data' => []]);
            } else {
                return json_encode(['success' => false, 'message' => '', 'data' => []]);
            }
            
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }
    
    
    public function deleteDamagePhoto(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $token = $this->request->header('token');
            
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                
                if (!empty($userData)) {
                    $this->loadModel('VehicleDamage');
                    $vehicleDamageId = $this->request->data['vehicle_damge_id'];
                    
                    $vehicleDamageData = $this->VehicleDamage->find('first', array(
                        'conditions'=>array('id' => $vehicleDamageId ),
                        'recursive' => -1
                    ));
                    
                    if( !empty($vehicleDamageData) ){
                        
                        $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                        $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                        
                        $fileName = '';
                        if( $this->request->data['imageType'] == 'bottomside' ){
                            $fileName = $vehicleDamageData['VehicleDamage']['bottom_file_name'];
                        } else if( $this->request->data['imageType'] == 'leftside' ){
                            $fileName = $vehicleDamageData['VehicleDamage']['left_file_name'];
                        }  else if( $this->request->data['imageType'] == 'topside' ){
                            $fileName = $vehicleDamageData['VehicleDamage']['top_file_name'];
                        } else if( $this->request->data['imageType'] == 'rightside' ){
                            $fileName = $vehicleDamageData['VehicleDamage']['right_file_name'];
                        } else if( $this->request->data['imageType'] == 'backside' ){
                            $fileName = $vehicleDamageData['VehicleDamage']['back_file_name'];
                        }
                        
                        if(file_exists($destOriginal.$fileName  ) ){
                            @unlink($destOriginal.$fileName);
                            @unlink($destThumb.$fileName);
                            $this->VehicleDamage->delete($vehicleDamageId);
                            return json_encode(['success' => true, 'message' => $this->translate('Vehicle damage image has been deleted successfully.'), 'data' => []]);
                        } else {
                            return json_encode(['success' => false, 'message' => $this->translate('File does not exist. Please try again.'), 'data' => []]);
                        }
                    } else {
                        return json_encode(['success' => false, 'message' => $this->translate('No vehicle damage image found. Please try again.'), 'data' => []]);
                    }
                    
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }
    
    public function getVehicleImages(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('get')) {
            $this->loadModel('Vehicle');
            $vehicleTempId = $this->request->query['vehicle_temp_id'];
            if ($vehicleTempId != '') {
                $this->Vehicle->unbindModel(
                        array('hasMany' => array('VehicleFavourite', 'AuctionBid', 'Buyer','VehicleDamage', 'VehicleImage')), array('hasOne' => array('User', 'Buyer'))
                );
                $vehicleData = $this->Vehicle->find('first', array('conditions' => array('Vehicle.id' => $vehicleTempId), 'recursive' => 1));
                
                $destThumb = realpath('../webroot/img/vehicle/thumb/') . '/';
                $destOriginal = realpath('../webroot/img/vehicle/orignal/') . '/';
                
                $docPath = realpath('../webroot/files/doc/') . '/';
                
                
                $vehicleImages = [];
                $vehicleDocs = [];
                
                
                if( !empty($vehicleData['VehicleDoc']) ){
                    foreach($vehicleData['VehicleDoc'] as $key=> $val ){
                        $fileName = $val['file_name'];
                        
                        if( $val['file_type'] == '1' ){
                            if(file_exists($docPath.$fileName  ) ){
                                $vehicleDocs[] = ['id' => $val['id'], 'imageUrl' => BASE_URL . '/files/doc/'. $fileName ];
                            }
                        } else {
                            if(file_exists($destOriginal.$fileName  ) ){
                                $vehicleImages[] = ['id' => $val['id'], 'imageUrl' => BASE_URL . '/img/vehicle/orignal/'. $fileName ];
                            }
                        }
                    }
                }
                
                return json_encode(['success' => true, 'message' => '', 'data' => ['vehicleImages' => $vehicleImages, 'vehicleDocs' => $vehicleDocs, ]]);
            } else {
                return json_encode(['success' => true, 'message' => '', 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }
    
    public function deleteVehicleImage(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $token = $this->request->header('token');
            
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                
                if (!empty($userData)) {
                    $this->loadModel('VehicleDoc');
                    $vehicleImageId = $this->request->data['vehicle_img_id'];
                    
                    $vehicleImageData = $this->VehicleDoc->find('first', array(
                        'conditions'=>array('id' => $vehicleImageId ),
                        'recursive' => -1
                    ));
                    
                    if( !empty($vehicleImageData) ){
                        
                        $destThumb = realpath('../webroot/img/vehicle/thumb/') . '/';
                        $destOriginal = realpath('../webroot/img/vehicle/orignal/') . '/';
                        $destLarge = realpath('../webroot/img/vehicle/large/') . '/';
                        
                        $fileName = $vehicleImageData['VehicleDoc']['file_name'];
                        
                        if(file_exists($destOriginal.$fileName  ) ){
                            @unlink($destOriginal.$fileName);
                            @unlink($destThumb.$fileName);
                            @unlink($destLarge.$fileName);
                            $this->VehicleDoc->delete($vehicleImageId);
                            return json_encode(['success' => true, 'message' => $this->translate('Vehicle image has been deleted successfully.'), 'data' => []]);
                        } else {
                            return json_encode(['success' => false, 'message' => $this->translate('File does not exist. Please try again.'), 'data' => []]);
                        }
                    } else {
                        return json_encode(['success' => false, 'message' => $this->translate('No vehicle image found. Please try again.'), 'data' => []]);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }
    
    public function deleteVehicleDoc(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $token = $this->request->header('token');
            
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                
                if (!empty($userData)) {
                    $this->loadModel('VehicleDoc');
                    $vehicleImageId = $this->request->data['vehicle_img_id'];
                    
                    $vehicleImageData = $this->VehicleDoc->find('first', array(
                        'conditions'=>array('id' => $vehicleImageId ),
                        'recursive' => -1
                    ));
                    
                    if( !empty($vehicleImageData) ){
                        $docPath = realpath('../webroot/files/doc/') . '/';
                        
                        $fileName = $vehicleImageData['VehicleDoc']['file_name'];
                        
                        if(file_exists($docPath.$fileName  ) ){
                            @unlink($docPath.$fileName);
                            $this->VehicleDoc->delete($vehicleImageId);
                            return json_encode(['success' => true, 'message' => $this->translate('Vehicle document image has been deleted successfully.'), 'data' => []]);
                        } else {
                            return json_encode(['success' => false, 'message' => $this->translate('File does not exist. Please try again.'), 'data' => []]);
                        }
                    } else {
                        return json_encode(['success' => false, 'message' => $this->translate('No vehicle document image found. Please try again.'), 'data' => []]);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }
    
    
    /*
     * Get Vehicle auction detail page
     */

    public function getSubUsers() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        if ($this->request->is('get')) {
            
            $token = $this->request->header('token');
            
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                
                if (!empty($userData)) {
                    $subUsersData= $this->User->find('all', array(
                        'fields' => ['id', 'fname','lname'],
                        'conditions' => array('parent_id' => $userData['User']['id'])
                    ));
                    
                    return json_encode(['success' => true, 'message' => '', 'data' =>  $subUsersData ]);
                    
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
                
            } else{
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
            
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }
    
    public function deleteSubUser(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $token = $this->request->header('token');
            
            if ($token) {
                $this->loadModel('User');
                $userData = $this->User->find('first', array(
                    'conditions' => array('token' => $token)
                ));
                
                if (!empty($userData)) {
                    $subUserId = $this->request->data['subUserId'];
                    
                    $subUserData = $this->User->find('first', array(
                        'conditions'=>array('id' => $subUserId ),
                        'recursive' => -1
                    ));
                    
                    if( !empty($subUserData) && $this->User->delete($subUserData['User']['id']) ){
                        return json_encode(['success' => true, 'message' => $this->translate('Sub user account has been deleted successfully.'), 'data' => []]);
                    } else {
                        return json_encode(['success' => false, 'message' => $this->translate('No sub user account found. Please try again.'), 'data' => []]);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }
    
    
    public function getUserData( $userId ) {
        
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        $this->loadModel('User');
        if ($this->request->is('get')) {

            $token = $this->request->header('token');
            
            if( $token ){
                
                $userData = $this->User->find('first', array(
                    'conditions' => array('id' => $userId),
                    'recursive' => -1
                ));

                if (!empty($userData)) {

                    if ($userData['User']['image'] != '' && file_exists(WWW_ROOT . 'img/users/thumb/' . $userData['User']['image'])) {
                        $userData['User']['image'] = Router::url('/', true) . 'img/users/thumb/' . $userData['User']['image'];
                    } else {
                        $userData['User']['image'] = null;
                    }
                    
                    $userData['User']['password'] = '';

                    return json_encode(['success' => true, 'message' => '', 'data' => ['userData' => $userData]]);
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
                }
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('Invalid session. Please logged in again.'), 'data' => []]);
            }
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
        
        return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
    }
    
    
    public function addUpdateUser() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers', 'token, language');
        $this->autoRender = false;
        $isEdit = false;

        if ($this->request->is('post')) {

            $this->loadModel('User');

            $token = $this->request->header('token');

            $data = $this->request->data;
            

            $userData = $this->User->find('first', array(
                'conditions' => array('token' => $token)
            ));

            if (!empty($userData)) {
                
                if( isset($data['User']['id']) && $data['User']['id'] != '' ){
                    
                    $subUserData = $this->User->find('first', array(
                        'conditions' => array('id' => $data['User']['id']),
                        'recursive' => -1
                    ));
                    $data['User']['id'] = $subUserData['User']['id'];
                    
                    $isEdit = true;
                }
                
                $data['User']['id'] = $userData['User']['parent_id'];
                
                if( $isEdit == false ){
                    $data['User']['company_id'] = $userData['User']['company_id'];
                    $data['User']['role_id'] = 2;   
                    $data['User']['terms'] = $userData['User']['terms'];
                    $data['User']['tokenhash'] = Security::hash(CakeText::uuid(), 'sha512', true); 
                    $data['User']['token'] = Security::hash(CakeText::uuid(), 'sha512', true); 
                }
                        
                if ($this->User->save($data)) {
                    $this->User->Company->save($data);
                    
                    if($isEdit){
                        return json_encode(['success' => true, 'message' => $this->translate('User account has been updated successfully.'), 'data' => []]);
                    } else {
                        return json_encode(['success' => true, 'message' => $this->translate('User account has been created successfully.'), 'data' => []]);
                    }
                    
                } else {
                    return json_encode(['success' => false, 'message' => $this->translate('Unable to save profile information. Please try again.'), 'data' => []]);
                }
                
            } else {
                return json_encode(['success' => false, 'message' => $this->translate('No user detail found. Please try again.'), 'data' => []]);
            }

            
        } else {
            return json_encode(['success' => false, 'message' => $this->translate('Invalid Request. Please try again.'), 'data' => []]);
        }
    }

}

?>