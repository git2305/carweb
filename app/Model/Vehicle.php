<?php

App::uses('Model', 'Model');

class Vehicle extends Model {

    var $hasMany = array(
        'VehicleDoc' => array(
            'className' => 'VehicleDoc',
            'foreignKey' => 'vehicle_id'
        ),
        'AuctionBid' => array(
            'className' => 'AuctionBid',
            'foreignKey' => 'vehicle_id'
        ),
        'VehicleFavourite' => array(
            'className' => 'VehicleFavourite',
            'foreignKey' => 'vehicle_id'
        ),
        'VehicleDamage' => array(
            'className' => 'VehicleDamage',
            'foreignKey' => 'vehicle_id'
        ),
        
    );
    
    var $hasOne = array(
        'VehicleImage' => array(
            'className' => 'VehicleDoc',
            'foreignKey' => 'vehicle_id',
            'limit' => 1,
//            'joinType' => 'INNER',
//            'strategy' => 'join',
            'conditions' => array(
                'file_type' => 2
            ),
        ),
    );
    
//    function afterFind($results, $primary=false) {
//        if (!empty($results)) {
//            foreach ($results as $i => $result) {
//                if (!empty($result[0])) { // If the [0] key exists in a result...
//                    foreach ($result[0] as $key => $value) { // ...cycle through all its fields...
//                        $results[$i][$this->alias][$key] = $value; // ...move them to the main result...
//                    }
//                    unset($results[$i][0]); // ...and finally remove the [0] array
//                }
//            }
//        }
//        pr($results); die;
//        return parent::afterFind($results, $primary=false); // Don't forget to call the parent::afterFind()
//    }
    
//    var $hasOne = array(
//        'MyFavourite' => array(
//            'className' => 'VehicleFavourite',
//            'foreignKey' => 'vehicle_id',
//            //'conditions'=> ['user_id' => 2 ]
//        ),
//    );
    
    public $belongsTo = array(
        'User' => array(
                'className' => 'User',
                'foreignKey' => 'seller_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
        ),
        'Buyer' => array(
                'className' => 'User',
                'foreignKey' => 'buyer_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
        ),
    );
}
