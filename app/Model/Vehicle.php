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
            'conditions' => array(
                'file_type' => 2
            ),
        ),
    );
    
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
