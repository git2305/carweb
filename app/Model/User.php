<?php

App::uses('Model', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends Model {
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }
    
    var $hasOne = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'user_id'
        )
    );
    
    var $hasMany = array(
        'SavedSearch' => array(
            'className' => 'SavedSearch',
            'foreignKey' => 'user_id'
        )
    );
    
    public function checkCurrentPassword($data) {
        $this->id = AuthComponent::user('id');
        $password = $this->field('password');
        return(AuthComponent::password($data['User']['current_password']) == $password);
    }
    
    function getActivationHash() {
            if (!isset($this->id)) {
                    return false;
            }
            return substr(Security::hash(Configure::read('Security.salt') . $this->field('created') . date('Ymd')), 0, 8);
    }

    /*Function to add user*/
    public function saveUser($data) {
        if($this->save($data)) {
            return $this->getLastInsertID();
        }
        return false;
    }

}
