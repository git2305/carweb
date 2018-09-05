<?php
App::uses('AppController', 'Controller');
/**
 * VehicleMakeYears Controller
 *
 * @property VehicleMakeYear $VehicleMakeYear
 * @property PaginatorComponent $Paginator
 */
class VehicleMakeYearsController extends AppController {

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
		$this->VehicleMakeYear->recursive = 0;
		$this->set('vehicleMakeYears', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->VehicleMakeYear->exists($id)) {
			throw new NotFoundException(__('Invalid vehicle make year'));
		}
		$options = array('conditions' => array('VehicleMakeYear.' . $this->VehicleMakeYear->primaryKey => $id));
		$this->set('vehicleMakeYear', $this->VehicleMakeYear->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->VehicleMakeYear->create();
			if ($this->VehicleMakeYear->save($this->request->data)) {
				$this->Flash->success(__('The vehicle make year has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The vehicle make year could not be saved. Please, try again.'));
			}
		}
		$makes = $this->VehicleMakeYear->Make->find('list');
		$this->set(compact('makes'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->VehicleMakeYear->exists($id)) {
			throw new NotFoundException(__('Invalid vehicle make year'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->VehicleMakeYear->save($this->request->data)) {
				$this->Flash->success(__('The vehicle make year has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The vehicle make year could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('VehicleMakeYear.' . $this->VehicleMakeYear->primaryKey => $id));
			$this->request->data = $this->VehicleMakeYear->find('first', $options);
		}
		$makes = $this->VehicleMakeYear->Make->find('list');
		$this->set(compact('makes'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->VehicleMakeYear->id = $id;
		if (!$this->VehicleMakeYear->exists()) {
			throw new NotFoundException(__('Invalid vehicle make year'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->VehicleMakeYear->delete()) {
			$this->Flash->success(__('The vehicle make year has been deleted.'));
		} else {
			$this->Flash->error(__('The vehicle make year could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
