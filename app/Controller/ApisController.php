<?php

App::uses('AppController', 'Controller');

/**
 * Apis Controller
 */
class ApisController extends AppController {

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;

    function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow(['login','checkLoggedIn']);
		$this->Auth->allow(['login','checkLoggedIn','cmsData','getVehicles','register']);
    }

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index']);
    }

    public function login() {
        if ($this->request->is(['post', 'patch'])) {
            $this->request->data['User'] = $this->request->data ; 
            if ($this->Auth->login()) {
                if (AuthComponent::User('status') == 0) {
                    $this->Auth->logout();
                    $this->success = false;
                    $this->message = __("Your account is not activated by admin yet..!");
                } else {
                    $this->success = true;
                    $this->message = __("Login successfully.");
                }
            } else {
                $this->success = false;
                $this->message = __("Invalid Username or Password !");
            }
        }
        $data['success'] = $this->success;
        $data['message'] =  $this->message;
        echo json_encode($data, JSON_HEX_APOS); die;
    }
    
    public function checkLoggedIn(){
        if ($this->Auth->loggedIn()) {
            $this->success = true;
            $this->message = __("Already logged in");
        } else {
            $this->success = false;
            $this->message = __("you are not logged in");
        }
        
        $data['success'] = $this->success;
        $data['message'] =  $this->message;
        echo json_encode($data, JSON_HEX_APOS); die;
    }
    
    public function logout(){
        if ($this->Auth->loggedIn()) {
            $this->Auth->logout();
            $this->success = true;
            $this->message = __("Logout Successfully");
        } else {
            $this->success = false;
            $this->message = __("Logout failed. Please try again");
        }
        
        $data['success'] = $this->success;
        $data['message'] =  $this->message;
        echo json_encode($data, JSON_HEX_APOS); die;
    }


    /*....Get CMS Page Data...
        Develop By : Parthiv Shah
        Date : 30-01-2017
        Purpose: GET CMS PAGE DATA 
        slug : Page URL 
        language : EN
    */
    public function cmsData(){

        if($this->request->data['slug'] && $this->request->data['language'] ){
          
                $page = $this->CmsPage->find('first', 
                                array('conditions' => 
                                    array('page_url' => $this->request->data['slug'],
                                          'language'=> $this->request->data['language'] ), 'fields' => array('title', 'description'),'recursive' => -1)); 
               
              $data['success'] = true;
              $data['data'] =  array('title'=>$page['CmsPage']['title'],
                                     'description' => $page['CmsPage']['description']
                                );
              echo json_encode($data, JSON_HEX_APOS); die;
        
        }else{

            $this->success = false;
            $this->message = __("Please pass required parameters.");
            echo json_encode($data, JSON_HEX_APOS); die;
        }

    }


    /*    
      Develop By :  Parthiv Shah
      Data : 30-01-2017     
      purpose: for sign up on site 
   */

    public function register() {         
        $this->loadModel('Company');
		$this->loadModel('User');
		
        if ($this->request->data) {        

            $email_already_exist = $this->User->find('count', array('conditions' => array('User.email' => $this->request->data['email'])));
        
            $usrname_already_exist = $this->User->find('count', array('conditions' => array('User.username' => $this->request->data['username'])));
         
            if ($email_already_exist != 0 || $usrname_already_exist != 0) {

                if ($email_already_exist != 0 && $usrname_already_exist != 0) {
                   
                    $this->success = false;
                    $this->message = __("Username & email already exist. Try with different username & email.");
					 
                
                } elseif ($email_already_exist != 0) {

                    $this->success = false;
                    $this->message = __('Email already exist. Try with different email id.');                    
					 
                } else {
                    
                    $this->success = false;
                    $this->message = __('Username already exist. Try with different username.');  
					         
                } 
                 
            }
            else
            { 
				$this->request->data['role_id'] = 2;
                $this->request->data['phone'] = $this->request->data['phone_code'] . "+" . $this->request->data['phone'];
                $this->request->data['mobile'] = $this->request->data['mobile_code'] . "+" . $this->request->data['mobile'];
                $this->request->data['name'] = $this->request->data['fname'];
			    $this->request->data['created'] = $this->request->data['modified'] = date('Y-m-d h:i:s');			 

                if ($this->User->save($this->request->data)) {

                    $this->request->data['user_id'] = $this->User->getLastInsertId();
					
                    if ($this->Company->save($this->request->data)) {
					
                        $this->loadModel('EmailTemplate');
                        $template = $this->EmailTemplate->findByAlias('reg-notification');
                        $emailContent = utf8_decode($template['EmailTemplate']['description']); //pr($emailContent);die;
                        $user_name = "<b>" . $this->request->data['fname'] . " " . $this->request->data['lname'] . " (" . $this->request->data['email'] . ")</b>";
                        $email_data = str_replace(array('{USER_NAME}'), array($user_name), $emailContent);
                        $this->set('mailData', $email_data);
                        $admin_detail = $this->User->findByRole_id(1, 'User.email');
                        $this->Email->subject = $template['EmailTemplate']['subject'];
                        $this->Email->sendAs = 'both';
                        $this->Email->to = $admin_detail['User']['email'];
                        $this->Email->from = 'support@carlsiting.com';
                        $this->Email->template = 'template';
                        if ($this->Email->send()) {                             
							$this->success = true;
		                    $this->message = __('Your profile application has been sent to admin for approval. We will respond you within 1-2 working days.');  							
							
							$datas = $this->User->find('first', 
								array('conditions' => 
											array('users.id =' => $this->request->data['user_id']),'recursive' => -1									
							)); 
							
                        } else {
                         	$this->success = false;
		                    $this->message = __('SomeThing went wrong, Please try again later.');  
                        } 						
                    }
                }
            }
			
			$data['success'] = $this->success;
			$data['message'] = $this->message;
			$data['data'] = $datas;			
			echo json_encode($data, JSON_HEX_APOS); die;
        }
    }


    public function getVehicles(){ 
	
		$this->loadModel('Vehicle');		
		if($this->request->is('post')){	
			 $shortBy = $this->request->data['short-by'];
			 $shortType = $this->request->data['short-type'];			 
			 $orderByString = '';
	
			 if(!$shortType)
			 	$shortType = ' asc'; 
				
			  if($shortBy)
			  {
			  		if($shortBy == "price"){
						$orderByString = 'Vehicle.min_auction_price '.$shortType;		
					}
					if($shortBy == "year"){
						//$orderByString = 'Vehicle.min_auction_price '.$shortType;		
					}
					if($shortBy == "odometer"){
						//$orderByString = 'Vehicle.min_auction_price '.$shortType;		
					}										
			  }
			  else 
			  {
			  	$orderByString = 'Vehicle.id DESC';
			  }
			
			 $querys = $this->Vehicle->find('all', 
								array('conditions' => 
											array('auction_ovr_tym >=' => date('Y-m-d h:i:s'),time()),'recursive' => 1,
									  'order' =>
									 	    array("$orderByString"),
									  'group' => 
									  		array('Vehicle.id'), 									
							)); 
			  $datas = '';  		  
			  foreach($querys as $keyQuery=>$valQuery){
					$valQuery['VehicleImage']['file_name'] = BASE_URL."/img/vehicle/thumb/".$valQuery['VehicleImage']['file_name'];   
					$datas[]= $valQuery;			
			  }
			  $data['success'] = true;
			  $data['data'] =  $datas; 
			  echo json_encode($data, JSON_HEX_APOS); die;
			  
		  }else{
		  	  $data['success'] = false;
			  $data['message'] =  "Please select post method"; 
			  echo json_encode($data, JSON_HEX_APOS); die;
		  }

    }

}
