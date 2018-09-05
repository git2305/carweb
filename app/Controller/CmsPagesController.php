<?php

App::uses('AppController', 'Controller');

class CmsPagesController extends AppController {

    public $uses = array();
	var $components = array('Paginator');
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('pages', 'getCmsSlugById','getCmsPages');
    }

    /**
     * Sumit
     * aboutUs
     * purpose: to dispaly content on about us page
     */
    public function pages() {
        $this->layout = 'front';
        if ($this->request->params['slug']) {
            
            $lang = Configure::read('Config.language');
            $cmsLang = 'EN';
            if( $lang == 'eng' ) {
                $cmsLang = 'EN';
            } else if( $lang == 'deu' ){
                $cmsLang = 'DE';
            } else if( $lang == 'it' ){
                $cmsLang = 'IT';
            } else if( $lang == 'fra' ){
                $cmsLang = 'FR';
            }
            
            $page = $this->CmsPage->find('first', array('conditions' => array('page_url' => $this->request->params['slug'], 'language'=> $cmsLang ), 'fields' => array('title', 'description'), 'recursive' => -1));
            $this->set('data', $page);
            
            if( isset($page['CmsPage']['title']) && $page['CmsPage']['title'] != '' ){
                $this->set('PAGE_TITLE', $page['CmsPage']['title']);
            }
        }
    }

    /**
     * Sumit
     * admin_allPages
     * purpose: listing of cmspaghes at admin side
     */
    public function admin_allPages() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: CMS Pages');
        /*$pages = $this->CmsPage->find('all', array('fields' => array('CmsPage.id', 'CmsPage.title', 'CmsPage.status', 'CmsPage.created'))); //pr($pages);die;
        $this->set('pages_data', $pages);*/
        
        $this->Paginator->settings = array('limit' => 10,'fields' => array('CmsPage.id', 'CmsPage.title', 'CmsPage.status', 'CmsPage.created'),'conditions' => array('CmsPage.language' => 'EN'));
		$pages = $this->Paginator->paginate('CmsPage');
		$this->set('pages_data', $pages);
        
    }
    
    /**
     * Aniruddh
     * admin_allPagesbylang
     * purpose: listing of cmspaghes at admin side by language
     */
     
    public function admin_allPagesbylang() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: CMS Pages');
        /*$pages = $this->CmsPage->find('all', array('fields' => array('CmsPage.id', 'CmsPage.title', 'CmsPage.status', 'CmsPage.created'))); //pr($pages);die;
        $this->set('pages_data', $pages);*/
        $conditions = array();
        $language = $_POST['language'];
        if($language == 'de'){
			$conditions = array('CmsPage.language' => 'DE');
		}else if($language == 'fr'){
			$conditions = array('CmsPage.language' => 'FR');
		}else if($language == 'it'){
			$conditions = array('CmsPage.language' => 'IT');
		}else{
			$conditions = array('CmsPage.language' => 'EN');
		}
        
        $this->Paginator->settings = array('limit' => 10,'fields' => array('CmsPage.id', 'CmsPage.title', 'CmsPage.status', 'CmsPage.created') , 'conditions' => $conditions);
		$pages = $this->Paginator->paginate('CmsPage');
		$this->set('pages_data', $pages);
        
    }

    /**
     * Sumit
     * admin_update
     * purpose: to update cms pages content
     */
    public function admin_update($id = NULL) {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Page Content');
        if ($this->data) {
            @$this->data['CmsPage']['page_url'] = $this->createSlug($this->data['CmsPage']['page_url']);
            if ($this->CmsPage->save($this->data)) {
                $this->Session->setFlash("Modification saved successfully..! ", 'default', array('class' => 'green'));
            } else {
                $this->Session->setFlash("Unable to save modifications..! ", 'default', array('class' => 'red'));
            }
            $this->redirect("/admin/CmsPages/allPages");
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->CmsPage->findById($id);
        }
    }

    public function getCmsSlugById($id = "") {

        $cmsData = $this->CmsPage->find('first', array('conditions' => array('id' => $id), 'fields' => array('page_url'), 'recursive' => -1));
        
        if (isset($cmsData) && !empty($cmsData)) {
            return $cmsData['CmsPage']['page_url'];
        } else {
            return '';
        }
    }

    function createSlug($string) {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return strtolower($slug);
    }
}
