<?php

App::uses('AppController', 'Controller');

/**
 * VehicleMakes Controller
 *
 * @property VehicleMake $VehicleMake
 * @property PaginatorComponent $Paginator
 */
class VehicleMakesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('getAllMakes');
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->VehicleMake->recursive = 0;
        $this->set('vehicleMakes', $this->Paginator->paginate());
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        if (!$this->VehicleMake->exists($id)) {
            throw new NotFoundException(__('Invalid vehicle make'));
        }
        $options = array('conditions' => array('VehicleMake.' . $this->VehicleMake->primaryKey => $id));
        $this->set('vehicleMake', $this->VehicleMake->find('first', $options));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->VehicleMake->create();
            if ($this->VehicleMake->save($this->request->data)) {
                $this->Flash->success(__('The vehicle make has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The vehicle make could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        if (!$this->VehicleMake->exists($id)) {
            throw new NotFoundException(__('Invalid vehicle make'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->VehicleMake->save($this->request->data)) {
                $this->Flash->success(__('The vehicle make has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The vehicle make could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('VehicleMake.' . $this->VehicleMake->primaryKey => $id));
            $this->request->data = $this->VehicleMake->find('first', $options);
        }
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $this->VehicleMake->id = $id;
        if (!$this->VehicleMake->exists()) {
            throw new NotFoundException(__('Invalid vehicle make'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->VehicleMake->delete()) {
            $this->Flash->success(__('The vehicle make has been deleted.'));
        } else {
            $this->Flash->error(__('The vehicle make could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
    
    public function getAllMakes(){
        
        $responseData = array();
        $vehicleData = $this->VehicleMake->find('all', array( 'conditions'=> array("name LIKE '".$this->request->query['term']."%' ")  ,'limit'=>10) );
        
        if ( !empty($vehicleData) ){
            foreach($vehicleData as $key=>$val){
                $responseData[$key]['id'] = $val['VehicleMake']['id'];
                $responseData[$key]['text'] = $val['VehicleMake']['name'];
            }
        }
        
        echo json_encode($responseData, JSON_HEX_APOS); die;
    }

}
