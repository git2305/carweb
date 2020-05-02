<?php

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array('Session', 'Cookie', 'Auth' => array(
        //'loginAction' => array('controller' => 'Users', 'action' => 'admin_index'),
        //'logoutRedirect' => array('controller' => 'Users', 'action' => 'admin_login')
        )
    );

    public function beforeFilter() {
        
        $this->Auth->allow('changeLang');
				
        $lang = $this->Cookie->read('lang');
        
        //$this->Auth->loginRedirect = array('controller' => 'Users', 'action' => 'index');
        //$this->Auth->logoutRedirect = array('controller' => 'Users', 'action' => 'index');
        $this->Auth->authenticate = array(
            'Form' => array(
                //'passwordHasher' => 'Blowfish',
                'fields' => array(
                    'username' => 'username',
                    'password' => 'password'
                ),
                'userModel' => 'User'
            )
        );
        
        if (empty($lang)) {
            $lang = 'deu';

        }
        
        Configure::write('Config.language', $lang);
        
        $this->set('language', $lang);
        //Configure::write('Config.language', $this->Session->read('Config.language'));
        
        if ($this->request->is('post') && $this->action == 'login') {
            $username = $this->request->data['User']['username'];
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $this->Auth->authenticate['Form']['fields']['username'] = 'email';
                $this->request->data['User']['email'] = $username;
                unset($this->request->data['User']['username']);
            }
        }
        
//        pr($this->Auth->authenticate); 
//        pr($this->request->data); die;
        
        
        $this->loadModel('CmsPage');
        $cmsSlugs = $this->CmsPage->find('list', array('fields' => array('id','page_url' ),  'recursive' => -1)); //'conditions'=> array('language'=> $cmsLang )
        //pr($cmsSlugs); die;
        $this->set('cmsSlugs', $cmsSlugs);
        
    }
    
    public function changeLang($lang = 'deu') {
        if ($this->request->is('post')) {
            $this->Cookie->write('lang', $this->request->data['language']);
            return $this->redirect($this->request->referer());
        }
    }
    
}

?>