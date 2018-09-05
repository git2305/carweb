<?php

App::uses('AppController', 'Controller');

/**
 * EmailTemplates Controller
 *
 * @property EmailTemplate $EmailTemplate
 * @property PaginatorComponent $Paginator
 */
class EmailTemplatesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator','Flash');

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Email Templates');
        $this->EmailTemplate->recursive = 0;
        $this->set('emailTemplates', $this->Paginator->paginate());
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        if (!$this->EmailTemplate->exists($id)) {
            throw new NotFoundException(__('Invalid email template'));
        }
        $options = array('conditions' => array('EmailTemplate.' . $this->EmailTemplate->primaryKey => $id));
        $this->set('emailTemplate', $this->EmailTemplate->find('first', $options));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->EmailTemplate->create();
            if ($this->EmailTemplate->save($this->request->data)) {
                $this->Flash->success(__('The email template has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The email template could not be saved. Please, try again.'));
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
        
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Manage Email Templates');
        
        $id = base64_decode($id);
        if (!$this->EmailTemplate->exists($id)) {
            throw new NotFoundException(__('Invalid email template'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->EmailTemplate->save($this->request->data)) {
                $this->Flash->success(__('The email template has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The email template could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('EmailTemplate.' . $this->EmailTemplate->primaryKey => $id));
            $this->request->data = $this->EmailTemplate->find('first', $options);
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
        $this->EmailTemplate->id = $id;
        if (!$this->EmailTemplate->exists()) {
            throw new NotFoundException(__('Invalid email template'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->EmailTemplate->delete()) {
            $this->Flash->success(__('The email template has been deleted.'));
        } else {
            $this->Flash->error(__('The email template could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
