<?php
App::uses('AppModel', 'Model');
/**
 * VehicleModel Model
 *
 * @property Makeyear $Makeyear
 */
class VehicleFavourite extends AppModel {

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
    var $belongsTo = array(
        'Vehicle' => array(
            'className' => 'Vehicle',
            'foreignKey' => 'vehicle_id',
        ),
        
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
    );

    //Function to add vehicle to Favourite
    public function addToFavourite($userId, $vehicleId) {
    	$this->save(array(
                'user_id' => $userId,
                'vehicle_id' => $vehicleId,
                'is_favourite' => '1'
            ));

    	return true;
    }
}
