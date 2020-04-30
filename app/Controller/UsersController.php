<?php

App::uses('AppController', 'Controller');
App::uses('CakeText', 'Utility');
App::import('Vendor', 'tcpdf/tcpdf');

class UsersController extends AppController {

    public $uses = array();
    var $helpers = array('Html', 'Form');
    var $components = array('Email', 'Upload', 'Paginator', 'Redis', 'RequestHandler');

    function beforeFilter() {
        //$this->Auth->authorize = 'controller';
        parent::beforeFilter();
        $this->Auth->allow('index', 'register', 'admin_login', 'findVehicleImage', 'contactUs', 'getAuctionData', 'sellCarToUser', 'api_index', 'getquicksearchData', 'forgotPassword', 'reset', 'checkData', 'activate', 'service_login', 'service_signup', 'setEmailPreferences');
    }

    /*     * * Sumit
     * index()
     * purpose: for home page */

    public function index() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', 'Home');
        $this->loadModel('VehicleRegion');
        $vehicle_region = $this->VehicleRegion->find('list', array('fields' => array('VehicleRegion.region_code', 'VehicleRegion.region_name'))); //pr($vehicle_region);die;
        $this->set('region_data', $vehicle_region);
    }

    /*     * * Sumit
     * findVehicleImage()
     * purpose: for finding Vehicle Images */

    public function findVehicleImage($id = NULL) {
        $this->autoRender = FALSE;
        $this->loadModel('VehicleDoc');
        $id = base64_decode($id); //return $id;
        $images = $this->VehicleDoc->find('first', array('conditions' => array('VehicleDoc.vehicle_id' => $id, 'VehicleDoc.file_type' => 2)));

        $fileName = '';
        if (!empty($images)) {
            $fileName = $images['VehicleDoc']['file_name'];
        }
        return $fileName;
    }

    /*     * * Sumit
     * register()
     * purpose: for sign up on site */

    public function register() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Sign Up'));
        $this->loadModel('Company');
        if ($this->data) {
            $tmp = $this->data;
            
            $company_already_exist = $this->Company->find('count', array('conditions' => array(
                'Company.name' => $tmp['Company']['name'],
                'Company.street' => $tmp['Company']['street'],
                'Company.postcode' => $tmp['Company']['postcode'],
            )));
            $email_already_exist = $this->User->find('count', array('conditions' => array('User.email' => $tmp['User']['email'])));
            $usrname_already_exist = $this->User->find('count', array('conditions' => array('User.username' => $tmp['User']['username'])));
            
            
            if ($email_already_exist != 0 || $usrname_already_exist != 0 || $company_already_exist > 0) {
                if ($email_already_exist != 0 && $usrname_already_exist != 0) {
                    $this->Session->setFlash( __('Username & email already exist. Try with different username & email.') , 'default', array('class' => 'alert alert-danger alert-dismissible'));
                } elseif ($email_already_exist != 0) {
                    $this->Session->setFlash( __('Email already exist. Try with different email id.') , 'default', array('class' => 'alert alert-danger alert-dismissible'));
                } elseif( $company_already_exist > 0 ){
                    $this->Session->setFlash( __('Company already exist. Try with different company information.') , 'default', array('class' => 'alert alert-danger alert-dismissible'));
                } else {
                    $this->Session->setFlash( __('Username already exist. Try with different username.'), 'default', array('class' => 'alert alert-danger alert-dismissible'));
                }
                
                //$this->set(array('data' => $this->data));
                
                //$this->redirect($this->referer());
                $this->redirect(['action' => 'register']);
            }

            $tmp['User']['role_id'] = 2; //As user
            $tmp['User']['phone'] = $tmp['User']['phone'];
            $tmp['User']['mobile'] = $tmp['User']['mobile'];
            $tmp['User']['tokenhash'] = Security::hash(CakeText::uuid(), 'sha512', true); //Set token hash key
            $tmp['User']['token'] = Security::hash(CakeText::uuid(), 'sha512', true); //Set token hash key
            
            if ($this->User->save($tmp)) {
                $lastInsertId = $this->User->getLastInsertId();
                $tmp['Company']['user_id'] = $lastInsertId;
                $this->__sendActivationEmail($lastInsertId);
                unset($tmp['User']);
                
                if( $this->Company->save($tmp) ) {
                    $this->Session->setFlash('Your profile application has been sent to admin for approval. We will respond you within 1-2 working days.', 'default', array('class' => 'green'));
                    $this->redirect(['action' => 'login']);
                } else {
                    $this->Session->setFlash('SomeThing went wrong, Please try again later.', 'default', array('class' => 'red'));
                }
            }
        }
    }

    /*
     * Send activation email to user
     */

    function __sendActivationEmail($id) {
        $user = $this->User->find('first', [ 'conditions' => array('User.id' => $id), 'recursive' => -1]);

        if (!empty($user)) {
            $this->User->id = $id;
            $activationUrl = Router::url(['controller' => 'users', 'action' => 'activate/' . $id . '/' . $this->User->getActivationHash()], true);
            $activationUrl = '<a href="' . $activationUrl . '">' . __('Activation Link') . '</a>';
            $this->set('activate_url', $activationUrl);
            $this->set('username', $user['User']['username']);
            $userNname = $user['User']['fname'] . " " . $user['User']['lname'];
            $emailData = array('{USER_NAME}' => $userNname, '{ACTIVATION_URL}' => $activationUrl, '{U_EMAIL}' => $user['User']['email']);
            return $this->Email->sendEmail(1, $emailData);
        }
    }

    public function activate($userId = null, $inHash = null) {

        $this->User->id = $userId;


        if ($this->User->exists() && ($inHash == $this->User->getActivationHash())) {

            if (empty($this->data)) {

                $this->data = $this->User->read(null, $userId);
                // Update the active flag in the database
                $this->User->set('status', 1);
                $this->User->save();

                $this->Session->setFlash(__('Your account has been activated, please log in below.'), 'default', array('class' => 'green'));
            } else {
                $this->Session->setFlash(__('Unable to find user in the system. Please try again.'), 'default', array('class' => 'red'));
            }
        } else {
            $this->Session->setFlash(__('Invalid link. Please contact administrator to activate your accont'), 'default', array('class' => 'red'));
        }
        $this->redirect('login');
    }

    /*     * * Sumit
     * admin_login()
     * purpose: for signin on admin panel */

    public function admin_login() {
        $this->layout = FALSE;
        if ($this->data) { //pr($this->data);die;
            if ($this->Auth->login()) {
                if (AuthComponent::User('role_id') == 1) {
                    $this->Session->setFlash('<div class="green">Welcome ' . AuthComponent::User('email') . ' !!</div>');
                    $this->redirect(array("controller" => "Users", "action" => "dashboard"));
                } else {
                    $this->Auth->logout();
                    $this->Session->setFlash('Access by administrative authorities only !!');
                    $this->redirect($this->referer());
                }
            } else {
                $this->Session->setFlash('Invalid username or password !!');
                $this->redirect($this->referer());
            }
        }
    }

    /*     * * Sumit
     * admin_dashboard()
     * purpose: for admin dashboard */

    public function admin_dashboard() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Dashboard');
        $this->loadModel('Vehicle');
        $vehicleChart = $this->Vehicle->find('all', array('fields' => array('count(Vehicle.created) as count', 'DATE_FORMAT(Vehicle.created,"%m-%Y") as month'), 'group' => 'MONTH(Vehicle.created)', 'recursive' => -1)); //pr($auctions); die;

        $vehiclesellChart = $this->Vehicle->find('all', array(
            'joins' => array(
                array(
                    'table' => 'auction_bids',
                    'alias' => 'AuctionBid',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'AuctionBid.vehicle_id = Vehicle.id'
                    )
                )
            ),
            'fields' => array('DATE_FORMAT(Vehicle.created,"%m-%Y") as month', 'max(AuctionBid.biding_amount) as CHF'),
            'conditions' => array('Vehicle.is_sell' => '1'),
            'group' => 'MONTH(Vehicle.created)',
            'recursive' => -1
        ));

        $this->set(array('vehicleChart' => $vehicleChart, 'vehiclesellChart' => $vehiclesellChart));
    }

    /*     * * Sumit
     * admin_logout()
     * purpose: for sign out on admin panel */

    public function admin_logout() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->Session->setFlash('Logout Successful !!');
        $this->redirect($this->Auth->logout());
    }

    /*     * * Sumit
     * admin_profile()
     * purpose: to display and update admin profile */

    public function admin_profile() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Profile');
        if ($this->data) { //pr($this->data);die;
            if ($this->User->save($this->data)) {
                $this->Session->setFlash('Profile Updated !!');
            } else {
                $this->Session->setFlash('Unable to update profile !!');
            }
            $this->redirect($this->referer());
        }
        $this->request->data = $this->User->findByRole_id(1); //pr($this->request->data);die;
    }

    /*     * * Sumit
     * admin_listing()
     * purpose: to display listing of all users */

    public function admin_listing() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Users');
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('User.role_id !=' => 1));
        $all_users = $this->Paginator->paginate('User');
        $this->set('user_data', $all_users);
    }

    /*     * * Aniruddh
     * admin_search()
     * purpose: to display listing of all users */

    public function admin_usersearch() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: Users');
        /* $all_users = $this->User->find('all', array('conditions' => array('User.role_id !=' => 1))); //pr($all_users);die;
          $this->set('user_data', $all_users); */

        $searchBoxVal = $_POST['searchBoxVal'];
        $searchField = $_POST['searchField'];
        $conditions = array();
        if ($searchBoxVal == 'email') {
            $conditions['User.role_id'] = 2;
            $conditions['User.email'] = $searchField;
            //$condition = array('User.role_id !=' => 1,'User.email =' => $searchField);
        } else if ($searchBoxVal == 'name') {
            $conditions['User.role_id'] = 2;
            $conditions['OR'] = array(
                array('User.fname LIKE' => '%' . $searchField . '%'),
                array('User.lname LIKE' => '%' . $searchField . '%'),
            );
        } else {
            $conditions['User.role_id'] = 2;
        }
        //pr($conditions);die;
        $this->Paginator->settings = array('limit' => 10, 'conditions' => $conditions);


        $all_users = $this->Paginator->paginate('User');

        /* $log = $this->User->getDataSource()->getLog(false, false); 
          debug($log); */
        $this->set('user_data', $all_users);
    }

    /*     * * Sumit
     * changestatus()
     * purpose: ajax function to chnage status */

    public function changestatus() {
        $this->autoRender = false;
        $this->layout = false; //pr($_POST);die;
        $id = $_POST['id'];
        $model = $_POST['model'];
        $status = $_POST['status'];
        $this->loadModel($model);
        $data[$model]["id"] = $id;
        if ($status == 'Active') {
            $data[$model]["status"] = 0;
            $return = '<span class="label label-table label-danger">Inactive</span>';
        } else {
            $data[$model]["status"] = 1;
            $return = '<span class="label label-table label-success">Active</span>';
        }
        //pr($data);die;
        if ($this->$model->save($data)) {
            return $return;
        } else {
            return 0;
        }
    }

    /*     * * Sumit
     * login()
     * purpose: for user login */

    public function login() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', 'Sign in');
        if ($this->data) { //pr($this->data);die;
            if ($this->Auth->login()) {
                if (AuthComponent::User('status') == 0) {
                    $this->Auth->logout();
                    $this->Session->setFlash("Your account is not activated by admin yet..!", 'default', array('class' => 'red'));
                    $this->redirect($this->referer());
                } else {
                    $this->Session->setFlash('Welcome ' . AuthComponent::User('prefix_name') . ' ' . AuthComponent::User('fname') . ' ' . AuthComponent::User('lname'), 'default', array('class' => 'green'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'index'));
                }
            } else {
                $this->Session->setFlash('Invalid Username / Password !', 'default', array('class' => 'red'));
                $this->redirect($this->referer());
            }
        }
    }

    /*     * * Sumit
     * logout()
     * purpose: for user sign out */

    public function logout() {
        if ($this->Auth->logout()) {
            $this->Session->setFlash("Successfully logged out, Thanks for visiting us..", "default", array("class" => "green"));
            $this->redirect(BASE_URL);
        }
    }

    /*     * * Shadmani
     * myProfile()
     * purpose: to display and update user profile */

    public function profile() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', 'User: Profile');
        $this->loadModel('Company');
        if ($this->data) { //pr($this->data);die;
            $data = $this->data;
            $data['User']['mobile'] = $data['User']['mobile'];
            $data['User']['phone'] = $data['User']['phone']; //pr($data);die;
            $imgtmp = $data['User']['image'];
            //pr($imgtmp); die;
            if (isset($imgtmp['name']) && !empty($imgtmp['name'])) {
                $imgNameExt = pathinfo($imgtmp["name"]);
                //pr($imgNameExt); die;
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
                        unlink('../webroot/img/users/thumb/' . $data['User']['old_image']);
                        unlink('../webroot/img/users/large/' . $data['User']['old_image']);
                        unlink('../webroot/img/users/orignal/' . $data['User']['old_image']);
                    }
                }
            } else {
                unset($data['User']['image']);
            }

            if ($this->User->save($data)) {
                $this->Company->save($data);
                $this->Session->setFlash('Profile Updated !!', 'default', array('class' => 'green'));
            } else {
                $this->Session->setFlash('Unable to update profile !!', 'default', array('class' => 'red'));
            }
            $this->redirect($this->referer());
        }
        $usr_data = $this->User->findById(AuthComponent::User('id'));
        $this->request->data = $usr_data;
    }

    function admin_edit($id = NULL) {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'User: Edit');

        if ($this->data) {
            if ($this->User->save($this->data)) {
                $this->Session->setFlash('User information has been updated successfully !!');
            } else {
                $this->Session->setFlash('Unable to update user information !!');
            }
            $this->redirect($this->referer());
        }

        if ($id) {
            $id = base64_decode($id);
        }

        $this->request->data = $this->User->find('first', array('conditions' => array('id' => $id), 'recursive' => -1));
    }

    public function admin_delete($id = null) {
        $id = base64_decode($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->User->delete($id)) {
            $this->Session->setFlash('The user has been deleted successfully.', 'default', array('class' => 'green'));
        } else {
            $this->Session->setFlash('The user could not be deleted. Please, try again.', 'default', array('class' => 'red'));
        }
        return $this->redirect(['action' => 'listing']);
    }

    public function changePassword() {
        $this->layout = 'front';
        if (!empty($this->data)) {

            if ($this->User->checkCurrentPassword($this->data)) {
                if ($this->User->saveField('password', $this->request->data['User']['password'])) {
                    $this->Session->setFlash('Password has been changed.', 'default', array('class' => 'green'));
                    return $this->redirect(['action' => 'changePassword']);
                } else {
                    $this->Session->setFlash('Password could not be changed.', 'default', array('class' => 'red'));
                }
            } else {
                $this->Session->setFlash('Current password not matched', 'default', array('class' => 'red'));
            }
        } else {
            $this->data = $this->User->findById($this->Auth->user('id'));
        }
    }

    public function emailPreference() {
        $this->layout = 'front';
        if (!empty($this->data)) {
            $this->User->id = $this->Auth->user('id');
            if ($this->User->saveField('email_preference', $this->request->data['User']['email_preference'])) {
                $this->Session->setFlash('Email preference has been changed successfully.', 'default', array('class' => 'green'));
                return $this->redirect(['action' => 'emailPreference']);
            } else {
                $this->Session->setFlash('Email preference could not be changed.', 'default', array('class' => 'red'));
            }
        } else {
            $this->data = $this->User->find('first', array('fields' => array('email_preference'), 'recursive' => -1, 'conditions' => array('id' => $this->Auth->user('id'))));
        }
    }

    public function contactUs() {
        $this->layout = 'front';

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
                $this->Session->setFlash('Request has been send successfully.', 'default', array('class' => 'green'));
            } else {
                $this->Session->setFlash('We are unable to send your request. Please try again.', 'default', array('class' => 'green'));
            }
        }
    }

    public function getAuctionData() {
        $this->layout = 'ajax';
        $this->loadModel('Vehicle');
        $this->loadModel('AuctionBid');

        $orderBy = array();
        $conditions = array();

        $conditions['Vehicle.status'] = 1;
        $conditions['Vehicle.auction_ovr_tym >= '] = date('Y-m-d H:i:s'); //, strtotime('- 2 day')

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

        $this->Vehicle->unbindModel(array('hasMany' => array('VehicleDoc')), true);

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

        $this->Cookie->delete('__bt');
        $this->set('auction_data', $auctions);
        $this->set('regions', $regions);
        $this->set('bidArr', $bidArr);
    }

    public function getsortAuctionData() {
        $this->layout = 'ajax';
        $this->loadModel('Vehicle');
        $this->loadModel('AuctionBid');
        if ($this->Cookie->read('__bt') !== null) {
            //$this->Paginator->settings = array('limit' => 3,  'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s'),'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))),'order' => 'Vehicle.created ASC');
            //$auctions = $this->Paginator->paginate('Vehicle');
            if ($this->request->data['sortopt'] == 'LH') {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s'), 'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))), 'order' => 'Vehicle.min_auction_price ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            } else if ($this->request->data['sortopt'] == 'HL') {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s'), 'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))), 'order' => 'Vehicle.min_auction_price DESC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            } else {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s'), 'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))), 'order' => 'Vehicle.created ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            }
        } else {
            //$this->Paginator->settings = array('limit' => 3,  'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s')),'order' => 'Vehicle.created ASC');
            //$auctions = $this->Paginator->paginate('Vehicle');
            if ($this->request->data['sortopt'] == 'LH') {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')), 'order' => 'Vehicle.min_auction_price ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            } else if ($this->request->data['sortopt'] == 'HL') {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')), 'order' => 'Vehicle.min_auction_price DESC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            } else {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')), 'order' => 'Vehicle.created ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            }
        }
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
        $this->Cookie->delete('__bt');
        $this->set('auction_data', $auctions);
        $this->set('bidArr', $bidArr);
    }

    public function getmyAuctionData() {
        $this->layout = 'ajax';
        $this->loadModel('Vehicle');
        $this->loadModel('AuctionBid');
        if ($this->Cookie->read('__bt') !== null) {
            //$this->Paginator->settings = array('limit' => 3,  'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s'),'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))),'order' => 'Vehicle.created ASC');
            //$auctions = $this->Paginator->paginate('Vehicle');
            if ($this->request->data['sortopt'] == '1') {

                $auctions = $this->Vehicle->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'auction_bids',
                            'alias' => 'AuctionBid',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'AuctionBid.vehicle_id = Vehicle.id'
                            )
                        )
                    ),
                    'conditions' => array('AuctionBid.user_id' => AuthComponent::User('id'), 'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt')), 'Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')),
                    'recursive' => 1
                ));

                //$auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s'),'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))), 'order' => 'Vehicle.min_auction_price ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            } else {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s'), 'Vehicle.body_type' => base64_decode($this->Cookie->read('__bt'))), 'order' => 'Vehicle.created ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            }
        } else {
            //$this->Paginator->settings = array('limit' => 3,  'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s')),'order' => 'Vehicle.created ASC');
            //$auctions = $this->Paginator->paginate('Vehicle');
            if ($this->request->data['sortopt'] == '1') {
                //$auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')), 'order' => 'Vehicle.min_auction_price ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
                $auctions = $this->Vehicle->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'auction_bids',
                            'alias' => 'AuctionBid',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'AuctionBid.vehicle_id = Vehicle.id'
                            )
                        )
                    ),
                    'conditions' => array('AuctionBid.user_id' => AuthComponent::User('id'), 'Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')),
                    'recursive' => 1
                ));
            } else {
                $auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:i:s')), 'order' => 'Vehicle.created ASC', 'recursive' => 1, 'limit' => 10)); //pr($auctions); die;
            }
        }
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
        $this->Cookie->delete('__bt');
        $this->set('auction_data', $auctions);
        $this->set('bidArr', $bidArr);
    }

    public function addUpdateUserBid() {

        $this->layout = 'front';
        $this->loadModel('AuctionBid');
        $this->loadModel('Vehicle');
        $bidDataCount = $this->AuctionBid->find('count', array('conditions' => array('AuctionBid.user_id' => AuthComponent::User('id'), 'AuctionBid.vehicle_id' => $this->request->data['vehicle_id'])));
        //echo $bidDataCount; die;


        $vehicleData = $this->Vehicle->find('first', array('conditions' => array('Vehicle.status' => 1, 'Vehicle.id' => $this->request->data['vehicle_id']))); //pr($auctions); die;

        $start_date = new DateTime($vehicleData['Vehicle']['auction_ovr_tym']);
        $since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));

        $dateDiffSec = $seconds = $since_start->days * 86400 + $since_start->h * 3600 + $since_start->i * 60 + $since_start->s;
        ;

        /* echo $since_start->i.' minutes<br>';
          die; */

        if ($bidDataCount > 0) {
            $this->AuctionBid->updateAll(array("AuctionBid.biding_amount" => $this->request->data['biding_amount']), array("AuctionBid.user_id" => $this->request->data['user_id'], "AuctionBid.vehicle_id" => $this->request->data['vehicle_id']));
        } else {
            $this->AuctionBid->save($this->request->data);
        }



        if ($since_start->i <= 10) {
            //echo $dateDiffSec;die;
            $dateDiffSec = $dateDiffSec + 600;
            $this->Redis->setRedisKey('vehicle_' . $this->request->data['vehicle_id'], $dateDiffSec);
            $this->Redis->expireRedisKey('vehicle_' . $this->request->data['vehicle_id'], $dateDiffSec);

            $newTime = date("Y-m-d H:i:s", strtotime($vehicleData['Vehicle']['auction_ovr_tym'] . " +10 minutes"));
            $this->Vehicle->updateAll(array("Vehicle.auction_ovr_tym" => "'" . $newTime . "'"), array("Vehicle.id" => $this->request->data['vehicle_id']));
            echo 'timeupdated';
            die;
        }
        echo 'success';
        die;
    }

    public function sellCarToUser() {
        $this->layout = 'none';

        if (isset($this->request->data['vehicle_id']) && $this->request->data['vehicle_id'] != '') {
            $this->loadModel('AuctionBid');
            $this->loadModel('Vehicle');

            $this->AuctionBid->Vehicle->unbindModel([ 'hasOne' => ['VehicleImage'], 'hasMany' => ['AuctionBid', 'VehicleImage', 'VehicleDoc', 'VehicleFavourite', 'VehicleDamage']]);

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
										<td align="right"><strong>' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</strong></td>
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
							<th align="center">' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</th>
							<th align="center">' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</th>
						</tr>
						<tr>
							<td align="right" colspan="5"></td>
						</tr>
						<hr/>
						<tr>
							<td align="right" colspan="5"></td>
						</tr>
						<tr>
							<td align="right" colspan="5"><strong>Total : ' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</strong></td>
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
                
                if ($this->Email->sendEmail(5, $sellerEmailData)) {
                    $this->Vehicle->updateAll(array('Vehicle.invoice_name' => '"' . $invoiceFileName . '"', "Vehicle.status" => '0', "Vehicle.is_sell" => '1', "Vehicle.buyer_id" => $auctionData['AuctionBid']['user_id']), array("Vehicle.id" => $this->request->data['vehicle_id']));
                    echo 'emailsuccess';
                    die;
                } else {
                    $this->Vehicle->updateAll(array('Vehicle.invoice_name' => '"' . $invoiceFileName . '"', "Vehicle.status" => '0', "Vehicle.is_sell" => '1', "Vehicle.buyer_id" => $auctionData['AuctionBid']['user_id']), array("Vehicle.id" => $this->request->data['vehicle_id']));
                    echo 'fail';
                    die;
                }

                /* $to = $auctionData['User']['email'];

                  $subject = "Confirmation Of Buy Car";

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
                  <p style="margin-bottom: 15px; font-size: 16px; font-weight:bold;" class="callout">Congratulations for your new car.</p>
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
                  $headers = "From: " . strip_tags('developer@youmewebs.com') . "\r\n";
                  $headers .= "MIME-Version: 1.0\r\n";
                  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";



                  $headers .= "--" . $separator . $eol;
                  $headers .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
                  $headers .= "Content-Transfer-Encoding: base64" . $eol;
                  $headers .= "Content-Disposition: attachment" . $eol . $eol;
                  $headers .= $attachment . $eol . $eol;
                  $headers .= "--" . $separator . "--"; */


                /*                 * ****************Email To Seller************* */
                //$adminData = $this->User->find('first', array('fields' => array('email'), 'conditions' => array('User.id' => 19)));
                //$toseller = $auctionData['Vehicle']['User']['email'];
                //$seller = $adminData['User']['fname'] . " " . $adminData['User']['lname'];
                //$emailData = array('{USER_NAME}' => $userNname);
                //$this->Email->sendEmail(4, $emailData, WWW_ROOT.'/invoices/'.$filename.'.pdf');

                /* $subject2 = "Confirmation Of Sell Your Car";
                  $html = '<html>
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
                  <td align="right"><strong>' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</strong></td>
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
                  <th align="center">' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</th>
                  <th align="center">' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</th>
                  </tr>
                  <tr>
                  <td align="right" colspan="5"></td>
                  </tr>
                  <hr/>
                  <tr>
                  <td align="right" colspan="5"></td>
                  </tr>
                  <tr>
                  <td align="right" colspan="5"><strong>Total : ' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</strong></td>
                  </tr>
                  </table>
                  </div>
                  </td>
                  <td></td>
                  </tr>
                  </table>
                  </body>
                  </html>';
                  echo $html; die;
                  if (mail($toseller, $subject2, $html, $headers)) {
                  echo 'Mail Sent';
                  } else {
                  echo 'Mail sent failed';
                  }



                  if (mail($to, $subject, $mailHtml, $headers)) {
                  $this->Vehicle->updateAll(array("Vehicle.status" => '0', "Vehicle.is_sell" => '1', "Vehicle.buyer_id" => $auctionData['AuctionBid']['user_id']), array("Vehicle.id" => $this->request->data['vehicle_id']));
                  echo 'emailsuccess';
                  die;
                  } else {
                  $this->Vehicle->updateAll(array("Vehicle.status" => '0', "Vehicle.is_sell" => '1', "Vehicle.buyer_id" => $auctionData['AuctionBid']['user_id']), array("Vehicle.id" => $this->request->data['vehicle_id']));
                  echo 'fail';
                  die;
                  } */

                /*                 * *** Please comment this when live **** */
                /* $this->Vehicle->updateAll(array("Vehicle.status" => '0',"Vehicle.is_sell" => '1',"Vehicle.buyer_id" => $auctionData['AuctionBid']['user_id']), array("Vehicle.id" => $this->request->data['vehicle_id']));
                  echo 'emailsuccess';die; */
            } else {
                $this->Vehicle->updateAll(array("Vehicle.status" => '0'), array("Vehicle.id" => $this->request->data['vehicle_id']));
                echo json_encode(['success' => true, 'message' => 'Congratulations. You have buy new vehicle', 'data' => []]); die;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid vehicle. Please try again', 'data' => []]); die;
        }
        die;
    }

    public function getquicksearchData() {

        $this->layout = 'ajax';
        $this->loadModel('Vehicle');
        $auctions = $this->Vehicle->find('all', array(
            'conditions' => array(
                'Vehicle.status' => 1,
                'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s'),
                'Vehicle.model' => $this->request->data['vehicleModel'],
                'Vehicle.brand' => $this->request->data['vehicleMake'],
                'Vehicle.vehicle_regions' => $this->request->data['vehicle_regions'],
            /* 'YEAR(first_reg) >=' => $this->request->data['minyear'],
              'YEAR(first_reg) <=' => $this->request->data['maxyear'], */
            ),
            'order' => 'Vehicle.created ASC', 'recursive' => -1, 'limit' => 10));
        $this->set('auction_data', $auctions);
    }

    public function pdfcreate() {

        $this->loadModel('AuctionBid');
        $this->loadModel('Vehicle');
        $this->loadModel('Users');

        $auctionData = $this->AuctionBid->find('first', array('conditions' => array('AuctionBid.vehicle_id' => '6'), 'order' => 'AuctionBid.biding_amount desc')); //pr($auctions); die;

        $filename = 'invoice_' . strtotime(date('h:i:s'));
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

        //$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

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
				<td><strong>' . $auctionData['Vehicle']['User']['prefix_name'] . " " . $auctionData['Vehicle']['User']['fname'] . " " . $auctionData['Vehicle']['User']['lname'] . '</strong></td>
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
							<td align="right"><strong>' . $auctionData['Vehicle']['User']['id'] . '</strong></td>
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
							<td align="right"><strong>' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</strong></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<h3>Rechnung</h3>
		<h3>Rechnungs-Nr. Invoice_' . $auctionData['Vehicle']['id'] . ' / Kunden-Nr. ' . $auctionData['Vehicle']['User']['id'] . '</h3>
		
		
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
				<th align="center">' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</th>
				<th align="center">' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</th>
			</tr>
			<tr>
				<td align="right" colspan="5"></td>
			</tr>
			<hr/>
			<tr>
				<td align="right" colspan="5"></td>
			</tr>
			<tr>
				<td align="right" colspan="5"><strong>Total : ' . $auctionData['AuctionBid']['biding_amount'] . ' CHF</strong></td>
			</tr>
		</table>';

        $pdf->writeHTML($tbl, true, false, false, false, '');

        //Close and output PDF document
        echo $pdfdoc = $pdf->Output('invoice.pdf', 'I');
        die;
    }

    public function api_index() {
        $this->Paginator->settings = array(
            'limit' => 1
        );
        $users = $this->Paginator->paginate('User');

        $this->set(array(
            'users' => $users,
            '_serialize' => array('users')
        ));
    }

    public function forgotPassword() {
        $this->layout = "front";
        $this->User->recursive = -1;
        if (!empty($this->request->data)) {

            if (empty($this->request->data['User']['email'])) {
                $this->Session->setFlash('Please provide your email address that you used to register with us');
            } else {
                $email = $this->request->data['User']['email'];
                $user = $this->User->find('first', array('conditions' => array('User.email' => $email)));
                if ($user) {
                    if ($user['User']['status']) {
                        $key = Security::hash(CakeText::uuid(), 'sha512', true);
                        $hash = sha1($user['User']['username'] . rand(0, 100));
                        $resetUrl = Router::url(array('controller' => 'users', 'action' => 'reset'), true) . '/' . $key . '#' . $hash;
                        $resetUrl = '<a href="' . $resetUrl . '">' . __('Reset Password') . '</a>';

                        $user['User']['tokenhash'] = $key;
                        $this->User->id = $user['User']['id'];
                        if ($this->User->saveField('tokenhash', $user['User']['tokenhash'])) {

                            $userNname = $user['User']['fname'] . " " . $user['User']['lname'];

                            $emailData = array('{USER_NAME}' => $userNname, '{PASSWORD_RESET_URL}' => $resetUrl, '{U_EMAIL}' => $user['User']['email']);
                            $this->Email->sendEmail(2, $emailData);

                            $this->Session->setFlash(__('Check your email to reset your password', true), 'default', ['class' => 'green']);
                        } else {
                            $this->Session->setFlash("Error Generating Reset link", 'default', ['class' => 'red']);
                        }
                    } else {
                        $this->Session->setFlash('This Account is not Active yet.Check Your mail to activate it', 'default', ['class' => 'red']);
                    }
                } else {
                    $this->Session->setFlash('Email does Not Exist', 'default', ['class' => 'red']);
                }
            }
        }
    }

    public function reset($token = null) {
        $this->layout = "front";
        $this->User->recursive = -1;
        if (!empty($token)) {
            $user = $this->User->findBytokenhash($token);
            if ($user) {
                $this->User->id = $user['User']['id'];
                if (!empty($this->data)) {
                    $this->User->data = $this->data;
                    $this->User->data['User']['username'] = $user['User']['username'];
                    $new_hash = sha1($user['User']['username'] . rand(0, 100)); //created token
                    $this->User->data['User']['tokenhash'] = $new_hash;
                    if ($this->User->validates(array('fieldList' => array('password', 'repassword')))) {

                        if ($this->User->save($this->User->data)) {
                            $userNname = $user['User']['fname'] . " " . $user['User']['lname'];
                            $emailData = array('{USER_NAME}' => $userNname, '{U_EMAIL}' => $user['User']['email']);
                            $this->Email->sendEmail(3, $emailData);

                            $this->Session->setFlash('Password Has been Updated', 'default', ['class' => 'green']);
                            $this->redirect(array('controller' => 'Users', 'action' => 'login'));
                        }
                    } else {
                        $this->set('errors', $this->User->invalidFields());
                    }
                }
            } else {
                $this->Session->setFlash('Token Corrupted,Please Retry.the reset link work only for once.', array('class' => 'red'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function checkData() {

        $this->__sendActivationEmail(2);
        die;

        $emailData = array('{USER_NAME}' => 'Aniket Panchal');
        $this->Email->sendEmail(1, $emailData);
        die;
    }
    
    public function addSubUser($id) {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', 'Add Sub Users');
        $this->loadModel('Company');
        
        if ($id) {
            $id = base64_decode($id);
        }
        
        if ($this->data) {
            $tmp = $this->data;
            $email_already_exist = $this->User->find('count', array('conditions' => array('User.email' => $tmp['User']['email'], 'parent_id' => $id )));
            $usrname_already_exist = $this->User->find('count', array('conditions' => array('User.username' => $tmp['User']['username'], 'parent_id' => $id)));
            if ($email_already_exist != 0 || $usrname_already_exist != 0) {
                if ($email_already_exist != 0 && $usrname_already_exist != 0) {
                    $this->Session->setFlash('Username & email already exist. Try with different username & email.', 'default', array('class' => 'red'));
                } elseif ($email_already_exist != 0) {
                    $this->Session->setFlash('Email already exist. Try with different email id.', 'default', array('class' => 'red'));
                } else {
                    $this->Session->setFlash('Username already exist. Try with different username.', 'default', array('class' => 'red'));
                }
                $this->redirect($this->referer());
            }

            $tmp['User']['role_id'] = 2; //As user
            $tmp['User']['parent_id'] = $id;
            $tmp['User']['status'] = 1;
            $tmp['User']['terms'] = 1;
            if ($this->User->save($tmp)) {
                $lastInsertId = $this->User->getLastInsertId();
                $tmp['Company']['user_id'] = $lastInsertId;
                unset($tmp['User']);
                if ($this->Company->save($tmp)) {
                    $this->Session->setFlash('Your profile application has been sent to admin for approval. We will respond you within 1-2 working days.', 'default', array('class' => 'green'));
                } else {
                    $this->Session->setFlash('SomeThing went wrong, Please try again later.', 'default', array('class' => 'red'));
                }
            }
        }

        

        $parentUserData = $this->User->find('first', array('fields'=> ['fname','lname'],'conditions' => array('id' => $id), 'recursive' => -1));
        $this->set('parentUserData', $parentUserData);
    }
    
    public function subUsers($id) {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', 'Sub Users Listing');
        
        if ($id) { $id = base64_decode($id); }
        
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('User.role_id !=' => 1, 'User.parent_id' => $id ));
        $all_users = $this->Paginator->paginate('User');
        $this->set('user_data', $all_users);
    }
    
    function editSubUser($id = NULL) {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', 'Sub User: Edit');
        
        if ($id) { $id = base64_decode($id); }

        if ($this->data) {
            $this->User->id = $id;
            if ($this->User->save($this->data)) {
                $this->Session->setFlash('User information has been updated successfully !!');
            } else {
                $this->Session->setFlash('Unable to update user information !!');
            }
            $this->redirect($this->referer());
        }

        

        $this->request->data = $this->User->find('first', array('conditions' => array('id' => $id), 'recursive' => -1));
    }
    
    public function delete($id = null) {
        $id = base64_decode($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->User->delete($id)) {
            $this->Session->setFlash('The user has been deleted successfully.', 'default', array('class' => 'green'));
        } else {
            $this->Session->setFlash('The user could not be deleted. Please, try again.', 'default', array('class' => 'red'));
        }
        return $this->redirect(['action' => 'subUsers', base64_encode($id)]);
    }
    
    public function subUserVehicles( $id ){
        $this->layout = 'front';
        $this->loadModel('Vehicle');
        $this->set('PAGE_TITLE', 'Sub Users Vehicles');
        
        if ($id) { $id = base64_decode($id); }
        
        $this->Vehicle->unbindModel(
            ['hasMany' => ['AuctionBid','VehicleFavourite'],'hasOne' => ['VehicleImage'] ]
        );
        
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.seller_id' => $id), 'order' => 'Vehicle.created DESC', 'recursive' => 2);
        $subUserVehicles = $this->Paginator->paginate('Vehicle');
        
        $this->set('subUserVehicles', $subUserVehicles);
    }
    
    public function mySavedSearch(){
        $this->layout = 'front';
        $this->loadModel('SavedSearch');
        $this->set('PAGE_TITLE', __('My Saved Search'));
        
       
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('SavedSearch.user_id' => AuthComponent::User('id')), 'order' => 'SavedSearch.created DESC', 'recursive' => 2);
        $savedSearch = $this->Paginator->paginate('SavedSearch');
        
        $this->set('savedSearch', $savedSearch);
        
    }
    
    public function addSearchReminder() {
        $this->layout = 'none';
        $responseData = [];
        
        if( $this->request->is('post') ){
            if ( $this->request->data ) {
                
                $this->loadModel('SavedSearch');
                
                $this->request->data['status'] = 1;
                $this->request->data['user_id'] = AuthComponent::User('id');
                
                $countSavedSearch = $this->SavedSearch->find('count', array('conditions' => array(
                    'SavedSearch.make' => $this->request->data['make'],
                    'SavedSearch.model' => $this->request->data['model'],
                    'SavedSearch.min_year' => $this->request->data['min_year'],
                    'SavedSearch.max_year' => $this->request->data['max_year'],
                    'SavedSearch.vehicle_regions' => $this->request->data['vehicle_regions'],
                    'SavedSearch.user_id' => AuthComponent::User('id')
                )));
                
                if( $countSavedSearch > 0 ){
                    $responseData['success'] = false;
                    $responseData['message'] = __('Reminder for this search is already in your saved search list.');
                } else {
                    if ($this->SavedSearch->save( $this->request->data )) {
                        $responseData['success'] = true;
                        $responseData['message'] = __('Your search criteria has been saved successfully.');
                    } else {
                        $responseData['success'] = false;
                        $responseData['message'] = __('Unable to store your data. Please try again');
                    }
                }
            }
        } else {
            $responseData['success'] = false;
            $responseData['message'] = __('Invalid request. Please try again');
        }
        
        echo json_encode($responseData, true); die;
        
    }
    
    

}
