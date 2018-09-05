<?php
App::uses('AppController', 'Controller');
/**
 * Companies Controller
 */
class CompaniesController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;
    var $components = array('Paginator');
    public function admin_listing() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Companies');
        /*$companies = $this->Company->find('all', array('conditions' => array()));
        $this->set('companies', $companies);*/
        
        $this->Paginator->settings = array('limit' => 10,'conditions' => array());
		$companies = $this->Paginator->paginate('Company');
		$this->set('companies', $companies);
    }
    
    /* * * Aniruddh
     * admin_searchcompanies()
     * purpose: to display listing of searched Companies*/
     
    public function admin_searchcompanies() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: Companies');
        $searchBoxVal = $_POST['searchBoxVal'];
        $searchField = $_POST['searchField'];
        $conditions = array();
        if($searchBoxVal == 'compname'){
			$conditions = array('Company.name Like ' => '%'.$searchField.'%');
		}else{
        	$conditions = array();
		}
		//pr($conditions);die;
		$this->Paginator->settings = array('limit' => 10,  'conditions' => $conditions);
		$companies = $this->Paginator->paginate('Company');
		/*$log = $this->Company->getDataSource()->getLog(false, false); 
		debug($log);*/
		$this->set('companies', $companies);
    }
    
    public function admin_add() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Company: Add');
        
        if ($this->data) {
            if ($this->Company->save($this->data)) {
                $this->Session->setFlash('User information has been updated successfully !!', 'default', array('class' => 'green'));
            } else {
                $this->Session->setFlash('Unable to update user information !!', 'default', array('class' => 'red'));
            }
            $this->redirect($this->referer());
        }
    }
    
    public function admin_delete( $id = null ){
        $id = base64_decode($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Company->delete($id)) {
            $this->Session->setFlash('The company has been deleted successfully.', 'default', array('class' => 'green'));
        } else {
            $this->Session->setFlash('The company could not be deleted. Please, try again.', 'default', array('class' => 'red'));
        }
        return $this->redirect(['action' => 'listing']);
        
    }
    
    function admin_edit( $id = NULL ){
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Company: Edit');
        
        if ($this->data) {
            if ($this->Company->save($this->data)) {
                $this->Session->setFlash('Company information has been updated successfully !!');
            } else {
                $this->Session->setFlash('Unable to update company information !!');
            }
            $this->redirect($this->referer());
        }
        
        if($id){
            $id = base64_decode($id);
        }
        
        $this->request->data = $this->Company->find('first', array('conditions'=> array('id'=> $id ), 'recursive' => -1 ) );
    }

}
