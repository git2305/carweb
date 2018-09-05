<?php

App::uses('AppController', 'Controller');

/**
 * VehicleModels Controller
 *
 * @property VehicleModel $VehicleModel
 * @property PaginatorComponent $Paginator
 */
class VehicleModelsController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('getModelByMake');
    }
    
    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->VehicleModel->recursive = 0;
        $this->set('vehicleModels', $this->Paginator->paginate());
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        if (!$this->VehicleModel->exists($id)) {
            throw new NotFoundException(__('Invalid vehicle model'));
        }
        $options = array('conditions' => array('VehicleModel.' . $this->VehicleModel->primaryKey => $id));
        $this->set('vehicleModel', $this->VehicleModel->find('first', $options));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->VehicleModel->create();
            if ($this->VehicleModel->save($this->request->data)) {
                $this->Flash->success(__('The vehicle model has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The vehicle model could not be saved. Please, try again.'));
            }
        }
        $makeyears = $this->VehicleModel->Makeyear->find('list');
        $this->set(compact('makeyears'));
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        if (!$this->VehicleModel->exists($id)) {
            throw new NotFoundException(__('Invalid vehicle model'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->VehicleModel->save($this->request->data)) {
                $this->Flash->success(__('The vehicle model has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The vehicle model could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('VehicleModel.' . $this->VehicleModel->primaryKey => $id));
            $this->request->data = $this->VehicleModel->find('first', $options);
        }
        $makeyears = $this->VehicleModel->Makeyear->find('list');
        $this->set(compact('makeyears'));
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $this->VehicleModel->id = $id;
        if (!$this->VehicleModel->exists()) {
            throw new NotFoundException(__('Invalid vehicle model'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->VehicleModel->delete()) {
            $this->Flash->success(__('The vehicle model has been deleted.'));
        } else {
            $this->Flash->error(__('The vehicle model could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
    
    public function getModelByMake( ){
        $responseData = array();
        $vehicleData = $this->VehicleModel->find('all', array( 'contain'=> ['VehicleMakeYear'] ,'conditions'=> array('VehicleMakeYear.make_id'=> $this->request->query['make_id'] ,"VehicleModel.name LIKE '".$this->request->query['term']."%' ")  ,'limit'=>10) );
        
        if ( !empty($vehicleData) ){
            foreach($vehicleData as $key=>$val){
                $responseData[$key]['id'] = $val['VehicleModel']['id'];
                $responseData[$key]['text'] = $val['VehicleModel']['name']. '('. $val['VehicleMakeYear']['year'].')';
            }
        }
        
        echo json_encode($responseData, JSON_HEX_APOS); die;
    }

}
