<?php

App::uses('Model', 'Model');

class SavedSearch extends Model {

    public $belongsTo = array(
        'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
        )
    );
}
