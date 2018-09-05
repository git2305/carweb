<?php
App::uses('AppModel', 'Model');
/**
 * VehicleModel Model
 *
 * @property Makeyear $Makeyear
 */
class VehicleModel extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'VehicleMakeYear' => array(
			'className' => 'VehicleMakeYear',
			'foreignKey' => 'makeyear_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
