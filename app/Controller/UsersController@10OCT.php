<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public $uses = array();
	var $helpers = array('Html', 'Form');
	var $components = array('Email');
	
	function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('index', 'register', 'admin_login', 'findVehicleImage');
    }

	
/*** Sumit
 * index()
 * purpose: for home page */
	public function index() {
		$this->layout = 'front';
		$this->set('PAGE_TITLE', 'Home');
		$this->loadModel('Vehicle');
		$auctions = $this->Vehicle->find('all', array('conditions'=>array('Vehicle.status'=>1, 'Vehicle.auction_ovr_tym >='=>date('Y-m-d H:m:s')), 'order'=>'Vehicle.created ASC', 'recursive'=>-1, 'limit'=>10)); //pr($auctions); die;
		$this->set('auction_data', $auctions);
	}
	
	
/*** Sumit
 * findVehicleImage()
 * purpose: for finding Vehicle Images */
	public function findVehicleImage($id=NULL) { 
		$this->autoRender = FALSE;
		$this->loadModel('VehicleDoc');
		$id = base64_decode($id); //return $id;
		$images = $this->VehicleDoc->find('first', array('conditions'=>array('VehicleDoc.vehicle_id'=>$id, 'VehicleDoc.file_type'=>2)));
		//pr($images); die;
		return $images['VehicleDoc']['file_name'];
	}
	
	
/*** Sumit
 * register()
 * purpose: for sign up on site */
	public function register() {
		$this->layout = 'front';
		$this->set('PAGE_TITLE', 'Sign Up');
		$this->loadModel('Company');
		if($this->data){ //pr($this->data);die;
			$tmp = $this->data;
			$tmp['User']['role_id'] = 2; //As user
			$tmp['User']['phone'] = $tmp['User']['phone_code']."+".$tmp['User']['phone'];
			$tmp['User']['mobile'] = $tmp['User']['mobile_code']."+".$tmp['User']['mobile'];
			if($this->User->save($tmp)){
				$tmp['Company']['user_id'] = $this->User->getLastInsertId();
				if($this->Company->save($tmp)){
					$this->loadModel('EmailTemplate');
					$template = $this->EmailTemplate->findByAlias('reg-notification');
					$emailContent = utf8_decode($template['EmailTemplate']['description']); //pr($emailContent);die;
					$user_name = "<b>".$tmp['User']['fname']." ".$tmp['User']['lname']." (".$tmp['User']['email'].")</b>";
					$email_data = str_replace(array('{USER_NAME}'), array($user_name), $emailContent);
					//pr($email_data); die;
                    $this->set('mailData', $email_data);
					$admin_detail = $this->User->findByRole_id(1, 'User.email');
                    $this->Email->subject = $template['EmailTemplate']['subject'];
                    $this->Email->sendAs = 'both';
                    $this->Email->to = $admin_detail['User']['email'];
                    $this->Email->from = 'support@carlsiting.com';
                    $this->Email->template = 'template';
                    if($this->Email->send()){
						$this->Session->setFlash('Your profile application has been sent to admin for approval. We will respond you within 1-2 working days.', 'default', array('class'=>'green'));
					}else{
						$this->Session->setFlash('SomeThing went wrong, Please try again later.', 'default', array('class' => 'red'));
					}
					$this->redirect(array("controller" => "Users", "action" => "index"));
				}
			}
		}
	}
	

/*** Sumit
 * admin_login()
 * purpose: for signin on admin panel */
	public function admin_login() {
		$this->layout = FALSE;
		if($this->data){ //pr($this->data);die;
			if ($this->Auth->login()) {
				if(AuthComponent::User('role_id') == 1){
					$this->Session->setFlash('<div class="green">Welcome ' . AuthComponent::User('email') . ' !!</div>');
					$this->redirect(array("controller" => "Users", "action" => "dashboard"));
				}
				else{
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
	
	
/*** Sumit
 * admin_dashboard()
 * purpose: for admin dashboard */
	public function admin_dashboard() {
		$this->layout = 'admin';
		$this->set('PAGE_TITLE', 'Admin: Dashboard');
	}
	
	
/*** Sumit
 * admin_logout()
 * purpose: for sign out on admin panel */
	public function admin_logout() {
		$this->layout = FALSE;
		$this->autoRender = FALSE;
		$this->Session->setFlash('Logout Successful !!');
        $this->redirect($this->Auth->logout());
	}
	
	
/*** Sumit
 * admin_profile()
 * purpose: to display and update admin profile */
	public function admin_profile() {
		$this->layout = 'admin';
		$this->set('PAGE_TITLE', 'Admin: Profile');
		if($this->data){ //pr($this->data);die;
			if($this->User->save($this->data)){
				$this->Session->setFlash('Profile Updated !!');
			}else{
				$this->Session->setFlash('Unable to update profile !!');
			}
			$this->redirect($this->referer());
		}
		$this->request->data = $this->User->findByRole_id(1); //pr($this->request->data);die;
	}
	
	
/*** Sumit
 * admin_listing()
 * purpose: to display listing of all users */
	public function admin_listing() {
		$this->layout = 'admin';
		$this->set('PAGE_TITLE', 'Admin: Users');
		$all_users = $this->User->find('all', array('conditions'=>array('User.role_id !='=> 1))); //pr($all_users);die;
		$this->set('user_data', $all_users);
	}
	
	

	
/*** Sumit
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


/*** Sumit
 * login()
 * purpose: for user login */
	public function login() {
		$this->layout = 'front';
        $this->set('PAGE_TITLE', 'Sign in');
        if ($this->data) { //pr($this->data);die;
            if ($this->Auth->login()) {
				if(AuthComponent::User('status') == 0){
					$this->Auth->logout();
					$this->Session->setFlash("Your account is not activated by admin yet..!", 'default', array('class' => 'red'));
					$this->redirect($this->referer());
				}else{
					$this->Session->setFlash('Welcome '.AuthComponent::User('prefix_name').' '.AuthComponent::User('fname').' '.AuthComponent::User('lname'), 'default', array('class' => 'green'));
					$this->redirect(array('controller' => 'Users', 'action' => 'index'));
				}
            } else {
                $this->Session->setFlash('Invalid Username / Password !', 'default', array('class' => 'red'));
                $this->redirect($this->referer());
            }
        }
    }
	
	
/*** Sumit
 * logout()
 * purpose: for user sign out */
	public function logout() {	
		if($this->Auth->logout()){
			$this->Session->setFlash("Successfully logged out, Thanks for visiting us..", "default", array("class" => "green"));
			$this->redirect(BASE_URL);
		}
	}
	
	/*** Shadmani
 * myProfile()
 * purpose: to display and update user profile */
	public function myProfile() {
		$this->layout = 'front';
		$this->set('PAGE_TITLE', 'User: Profile');
		$this->loadModel('Company');		
		if($this->data){
//pr($this->data);die;
			$data = $this->data;
			$imgtmp = $data['User']['image'];
				if(isset($imgtmp['name']) && !empty($imgtmp['name'])){
					$imgNameExt = pathinfo($img["name"]);
					$ext = $imgNameExt['extension'];
					
					$ext = strtolower($ext);
					if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
						$newImgName = "Adv_".$img['size']."_".time(); //echo $newImgName; die;
						$newImgName = str_replace(array('#', '.', '"', ' '),"",$newImgName);
						$tempFile = $img['tmp_name'];
						$destThumb = realpath('../webroot/img/vehicle/thumb/') . '/';
						$destOriginal = realpath('../webroot/img/vehicle/orignal/') . '/';
						$destLarge = realpath('../webroot/img/vehicle/large/') . '/';
						$file = $img;

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
					
					$pic['VehicleDoc']['id'] = "";
					$pic['VehicleDoc']['vehicle_id'] = $data['VehicleDoc']['vehicle_id'];
					$pic['VehicleDoc']['file_name'] = $name;
					$pic['VehicleDoc']['file_type'] = 2;
					$this->VehicleDoc->save($pic);
                }
				}



		
			if($this->Company->save($this->data)){
				$this->User->save($this->data);
				$this->Session->setFlash('Profile Updated !!');
			}else{
				$this->Session->setFlash('Unable to update profile !!');
			}
			$this->redirect($this->referer());
		}
		$this->request->data = $this->User->findByRole_id(AuthComponent::User('id')); //pr($this->request->data);die;
	}
	
}