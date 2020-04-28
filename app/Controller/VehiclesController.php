<?php

App::uses('AppController', 'Controller');

class VehiclesController extends AppController {

    public $uses = array();
    var $components = array('Email', 'Upload', 'Paginator', 'Cookie','Redis','RequestHandler');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('detail', 'sellVehicles', 'vehicleDetail','getAllMakes','getModelByMake','service_sellVehicles','addAdvertisement','getAuctionList','getAllRegions','getRecentOffers');
    }

    /* Sumits
     * detail()
     * purpose: for dispalying car detail
     */

    public function detail() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Vehicle Detail'));
    }

    /*     * * Sumit
     * addAdvertisement()
     * purpose: start process to add new advertisement and save vehicle category */

    public function addAdvertisement() {
        $this->layout = 'front';
        
        $user = $this->Auth->user();
        if(!$user) {
            $this->redirect(['action' => 'login', 'controller' => 'Users' ]);
        }
        $this->set('PAGE_TITLE', __('Add New Advertisement'));
        $this->loadModel('VehicleCategory');
        $this->loadModel('VehicleRegion');
        
        if ($this->data) {
            $tmp = $this->data;
            $tmp['Vehicle']['seller_id'] = AuthComponent::User('id');
            if ($this->Vehicle->save($tmp)) {
//                if (isset($tmp['Vehicle']['id']) && !empty($tmp['Vehicle']['id'])) {
//                    $_SESSION['ADVERTISEMENT_ID'] = $tmp['Vehicle']['id'];
//                } else {
//                    $_SESSION['ADVERTISEMENT_ID'] = $this->Vehicle->getLastInsertId();
//                }
                $this->redirect('/Vehicles/step1');
            }
        }
//        $vehicle_category = $this->VehicleCategory->find('list', array('fields' => array('VehicleCategory.id', 'VehicleCategory.name'))); //pr($vehicle_category);die;
//        $this->set('category_data', $vehicle_category);

        $vehicle_region = $this->VehicleRegion->find('list', array('fields' => array('VehicleRegion.region_code', 'VehicleRegion.region_name'))); //pr($vehicle_region);die;
        $this->set('region_data', $vehicle_region);

        if (isset($_SESSION['ADVERTISEMENT_ID']) && !empty($_SESSION['ADVERTISEMENT_ID'])) {
            $this->request->data = $this->Vehicle->findById($_SESSION['ADVERTISEMENT_ID']); //pr($this->request->data);die;
        }
        return json_encode(['success' => true, 'message' => 'Vehicle added successfully!!', 'data' => []]);
    }

    /*     * * Sumit
     * step1()
     * purpose: 1st step to add new advertisement (Add vehicle data) */

    function step1() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Advertisement: Step1'));
        $this->loadModel('VehicleRegion');
        $this->loadModel('VehicleDamage');
        if ($this->data) {
            $tmp = $this->data;
 
            if( isset($tmp['Vehicle']['gen_condition']) && !empty($tmp['Vehicle']['gen_condition']) ){
                $tmp['Vehicle']['gen_condition'] = implode(',', $tmp['Vehicle']['gen_condition']); //pr($tmp);die;
            }
            $tmp['Vehicle']['seller_id'] = AuthComponent::User('id');
            
            $leftsidename = '';
            $topsidename = '';
            $rightsidename = '';
            $bottomsidename = '';
            $backsidename = '';
            
            $rimgtmp = $tmp['VehicleDamage']['rightside'];
            $limgtmp = $tmp['VehicleDamage']['leftside'];
            $timgtmp = $tmp['VehicleDamage']['topside'];
            $bimgtmp = $tmp['VehicleDamage']['bottomside'];
            $bkimgtmp = $tmp['VehicleDamage']['backside'];
            
            if (!empty($rimgtmp)) {
				
                foreach($rimgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "rightside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $rightsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $rightsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if (!empty($limgtmp)) {
				
                foreach($limgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "leftside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $leftsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $leftsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if (!empty($timgtmp)) {
				
                foreach($timgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "topside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $topsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $topsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if (!empty($bimgtmp)) {
				
                foreach($bimgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "bottomside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $bottomsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $bottomsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if (!empty($bkimgtmp)) {
				
                foreach($bkimgtmp as $img) { //pr($img);die;
                    if(isset($img['name']) && !empty($img['name'])){
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "backside_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicledamage/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicledamage/orignal/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".gif";

                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".png";

                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".jpeg";

                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $backsidename = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg" , array('type' => 'resizemin', 'size' => array('750', '500')));
                                $backsidename = $newImgName . ".jpg";

                            }
                        }
                    }
                }
            }
            
            if ($this->Vehicle->save($tmp)) {
                $vehicleId = 0;
                if (isset($tmp['Vehicle']['id']) && !empty($tmp['Vehicle']['id'])) {
                    $_SESSION['ADVERTISEMENT_ID'] = $tmp['Vehicle']['id'];
                    $vehicleId = $tmp['Vehicle']['id'];
                } else {
                    $_SESSION['ADVERTISEMENT_ID'] = $this->Vehicle->getLastInsertId();
                    $vehicleId = $this->Vehicle->getLastInsertId();
                }
                
                if( $leftsidename != '' || $rightsidename != '' || $topsidename != '' || $bottomsidename != '' || $backsidename != '' ) {
                    $pic['VehicleDamage']['id'] = "";
                    $pic['VehicleDamage']['vehicle_id'] = $vehicleId;
                    $pic['VehicleDamage']['left_file_name'] = $leftsidename;
                    $pic['VehicleDamage']['right_file_name'] = $rightsidename;
                    $pic['VehicleDamage']['top_file_name'] = $topsidename;
                    $pic['VehicleDamage']['bottom_file_name'] = $bottomsidename;
                    $pic['VehicleDamage']['back_file_name'] = $backsidename;
                    //$pic['VehicleDamage']['vehicle_damage'] = isset($tmp['VehicleDamage']['vehicle_damage']) ? implode(',', $tmp['VehicleDamage']['vehicle_damage']) : '';
                    $pic['VehicleDamage']['file_type'] = 2;
                    $this->VehicleDamage->save($pic);
                }
                
                $this->redirect('/Vehicles/step2');
            }
        }

        $ltnId = '';
        if (isset($this->request->query['ltn']) && $this->request->query['ltn'] != '') {
            $ltnId = $this->request->query['ltn'];
        }
        $vehicle_region = $this->VehicleRegion->find('list', array('fields' => array('VehicleRegion.region_code', 'VehicleRegion.region_name'))); //pr($vehicle_region);die;
        $this->set('region_data', $vehicle_region);
        $this->set('ltnId', $ltnId);
        $this->set('vehicleData', $this->Session->read('vehicleData.data'));

        if (isset($_SESSION['ADVERTISEMENT_ID'])) { //pr($this->request->data);die;
            $this->request->data = $this->Vehicle->findById($_SESSION['ADVERTISEMENT_ID']);
        }
    }

    /*     * * Sumit
     * step2()
     * purpose: 2nd step to add advertisement (Add Conditions) */


    /*     * * Sumit
     * step3()
     * purpose: 3rd step to add advertisement (Add doc & pictures) */

    function step2() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Advertisement: Step2'));
        $this->loadModel('VehicleDoc');
        if ($this->data) { //pr($this->data);die;
            $data = $this->data;
            $imgtmp = $data['VehicleDoc']['image'];
            //pr($imgtmp);die;
            if (!empty($imgtmp)) {
                foreach ($imgtmp as $img) { //pr($img);die;
                    if (isset($img['name']) && !empty($img['name'])) {
                        $imgNameExt = pathinfo($img["name"]);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "Adv_" . $img['size'] . "_" . time(); //echo $newImgName; die;
                            $newImgName = str_replace(array('#', '.', '"', ' '), "", $newImgName);
                            $tempFile = $img['tmp_name'];
                            $destThumb = realpath('../webroot/img/vehicle/thumb/') . '/';
                            $destOriginal = realpath('../webroot/img/vehicle/orignal/') . '/';
                            $destLarge = realpath('../webroot/img/vehicle/large/') . '/';
                            $file = $img;

                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".gif";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".png";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".jpeg";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                                $name = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('750', '500')));
                                $name = $newImgName . ".jpg";

                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('800', '800')));
                                $name = $newImgName . ".jpg";
                            }

                            $pic['VehicleDoc']['id'] = "";
                            $pic['VehicleDoc']['vehicle_id'] = $data['VehicleDoc']['vehicle_id'];
                            $pic['VehicleDoc']['file_name'] = $name;
                            $pic['VehicleDoc']['file_type'] = 2;
                            $this->VehicleDoc->save($pic);
                        }
                    }
                }
            }

            $doctmp = $data['VehicleDoc']['doc'];
            if (!empty($doctmp)) {
                foreach ($doctmp as $doc) { //pr($img);die;
                    if (isset($doc['name']) && !empty($doc['name'])) {
                        $target_dir = realpath('../webroot/files/doc');
                        $name = time(strtotime(date('Y-m-d H:i'))) . '_' . $doc['name'];
                        str_replace(array('#', '.', '"', ' '), "", $name);
                        $target_file = $target_dir . '/' . $name;
                        if (move_uploaded_file($doc["tmp_name"], $target_file)) {
                            $document['VehicleDoc']['id'] = "";
                            $document['VehicleDoc']['vehicle_id'] = $data['VehicleDoc']['vehicle_id'];
                            $document['VehicleDoc']['file_name'] = $name;
                            $document['VehicleDoc']['file_type'] = 1;
                            $this->VehicleDoc->save($document);
                        }
                    }
                }
            }
             $this->redirect('/Vehicles/step3');
        }
        if (isset($_SESSION['ADVERTISEMENT_ID'])) { //pr($this->request->data);die;
            $this->request->data = $this->Vehicle->findById($_SESSION['ADVERTISEMENT_ID']);
        }
    }

    /*     * * Sumit
     * step4()
     * purpose: 4th step to add advertisement (Add Auction) */

    function step3() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Advertisement: Step3'));
        if ($this->data) {
            $tmp = $this->data;
            if ($tmp['Vehicle']['auction_duration'] == '2') {
                $timeAdd = '2 days';
            } else if ($tmp['Vehicle']['auction_duration'] == '3') {
                $timeAdd = '3 days';
            } else {
                $timeAdd = '24 hours';
            }
            $tmp['Vehicle']['auction_ovr_tym'] = date('Y-m-d H:m:s', strtotime('+' . $timeAdd)); //pr($tmp);die;
            $tmp['Vehicle']['status'] = 1;
            
            if ($this->Vehicle->save($tmp)) {
                $vehicleId = $this->Vehicle->getLastInsertID();
                $auctionTime = date('s', strtotime($tmp['Vehicle']['auction_ovr_tym']) );
                $this->Redis->setRedisKey('vehicle_'.$vehicleId, $auctionTime);
                $this->Redis->expireRedisKey('vehicle_'.$vehicleId, $auctionTime);
                
                unset($_SESSION['ADVERTISEMENT_ID']);
                $this->Session->setFlash('Your advertisement has been live now for 24 hours..', 'default', array('class' => 'green'));
                $this->redirect(BASE_URL);
            }
        }
        if (isset($_SESSION['ADVERTISEMENT_ID'])) { //pr($this->request->data);die;
            $this->request->data = $this->Vehicle->findById($_SESSION['ADVERTISEMENT_ID']);
        }
    }

    /*     * * Shadmani
     * admin_vehicleCategory()
     * purpose: to display listing of all Vehicles Category */

    public function admin_vehicleCategory() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Vehicles Category');
        $this->loadModel('VehicleCategory');
        /* $vcategory = $this->VehicleCategory->find('all'); //pr($vcategory);die;
          $this->set('vcategory', $vcategory); */

        $this->Paginator->settings = array('limit' => 10);
        $vehicles = $this->Paginator->paginate('VehicleCategory');
        $this->set('vcategory', $vehicles);
    }

    /*     * * Aniruddh
     * admin_vehiclecategorysearch()
     * purpose: to display listing of all Vehicles Category */

    public function admin_vehiclecategorysearch() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: Vehicles Category');
        $searchBoxVal = $_POST['searchBoxVal'];
        $searchField = $_POST['searchField'];
        $this->loadModel('VehicleCategory');
        $conditions = array();
        if ($searchBoxVal == 'catname') {
            $conditions = array('VehicleCategory.name Like ' => '%' . $searchField . '%');
        } else {
            $conditions = array();
        }
        //pr($conditions);die;
        $this->Paginator->settings = array('limit' => 10, 'conditions' => $conditions);
        $vehiclesCat = $this->Paginator->paginate('VehicleCategory');

        /* $log = $this->VehicleCategory->getDataSource()->getLog(false, false); 
          debug($log); */
        $this->set('vcategory', $vehiclesCat);
    }

    /**
     * Shadmani
     * admin_updateCategory
     * purpose: to update Vehicle Category 
     */
    public function admin_updateCategory($id = NULL) {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Update Category');
        $this->loadModel('VehicleCategory');
        if ($this->data) { //pr($this->data);die;
            if ($this->VehicleCategory->save($this->data)) {
                $this->Session->setFlash("Modification saved successfully..! ", 'default', array('class' => 'green'));
            } else {
                $this->Session->setFlash("Unable to save modifications..! ", 'default', array('class' => 'red'));
            }
            $this->redirect("/admin/Vehicles/vehicleCategory");
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->VehicleCategory->findById($id); //pr($this->request->data);die;
        }
    }

    public function admin_listing() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', 'Admin: Vehicles');
        /* $vehicles = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 1)));
          $this->set('vehicles', $vehicles); */
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 1));
        $vehicles = $this->Paginator->paginate('Vehicle');
        $this->set('vehicles', $vehicles);
    }

    /*     * * Aniruddh
     * admin_vehiclesearch()
     * purpose: to display listing of all Vehicles */

    public function admin_vehiclesearch() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: Vehicles');
        $searchBoxVal = $_POST['searchBoxVal'];
        $searchField = $_POST['searchField'];
        $conditions = array();
        if ($searchBoxVal == 'body') {
            $conditions['Vehicle.status'] = 1;
            $conditions['Vehicle.body_type Like '] = '%' . $searchField . '%';
        } else if ($searchBoxVal == 'model') {
            $conditions['Vehicle.status'] = 1;
            $conditions['OR'] = array(
                array('Vehicle.model LIKE' => '%' . $searchField . '%'),
                array('Vehicle.brand LIKE' => '%' . $searchField . '%'),
                array('Vehicle.type LIKE' => '%' . $searchField . '%'),
            );
        } else {
            $conditions['Vehicle.status'] = 1;
        }

        $this->Paginator->settings = array('limit' => 10, 'conditions' => $conditions);
        $vehicles = $this->Paginator->paginate('Vehicle');
        $this->set('vehicles', $vehicles);
    }

    public function sellVehicles() {
        $this->layout = 'front';
        $this->loadModel('VehicleRegion');
        if (isset($this->params['url']['bt'])) {
            $this->Cookie->write('__bt', $this->params['url']['bt'], $encrypt = false, $expires = null);
        }
        
        $vehicle_region = $this->VehicleRegion->find('list', array('fields' => array('VehicleRegion.region_code', 'VehicleRegion.region_name'))); 
        $this->set('region_data', $vehicle_region);

        
        $this->set('PAGE_TITLE', __('Sell Vehicles'));
    }

    public function auctionVehicles() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Vehicles in Auktion'));

        $this->Vehicle->unbindModel(
            ['hasMany' => ['AuctionBid','VehicleFavourite'],'hasOne' => ['VehicleImage'] ]
        );
        
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s'), 'Vehicle.seller_id' => AuthComponent::User('id')), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $auctions = $this->Paginator->paginate('Vehicle');
        $this->set('auction_data', $auctions);
    }

    public function favouriteVehicles() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('My Favourite Cars'));
        $this->loadModel('VehicleFavourite');
        
        $this->Vehicle->unbindModel(
            ['hasMany' => ['AuctionBid','VehicleFavourite'],'hasOne' => ['VehicleImage'] ]
        );
        
        $VehicleFavourite = $this->VehicleFavourite->find('list', array('fields' => array('VehicleFavourite.vehicle_id'),'conditions' => array('VehicleFavourite.is_favourite' => 1,'VehicleFavourite.user_id' => AuthComponent::User('id'))));
        $this->Paginator->settings = array('limit' => 10,  'conditions' => array('Vehicle.id' => $VehicleFavourite), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $auctions = $this->Paginator->paginate('Vehicle');
        $this->set('auction_data', $auctions);
    }

    public function admin_auctionvehicles() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', __('Vehicles Auctions'));
        $this->loadModel('Vehicle');
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 1, 'Vehicle.auction_ovr_tym >=' => date('Y-m-d H:m:s')), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $auctions = $this->Paginator->paginate('Vehicle');
        $this->set('auction_data', $auctions);
    }

    public function admin_auctionvehiclesearch() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: Vehicles');
        $searchBoxVal = $_POST['searchBoxVal'];
        $searchField = $_POST['searchField'];
        $this->loadModel('Vehicle');
        $conditions = array();
        if ($searchBoxVal == 'body') {
            $conditions['Vehicle.status'] = 1;
            $conditions['Vehicle.auction_ovr_tym >= '] = date('Y-m-d H:m:s');
            $conditions['Vehicle.body_type Like '] = '%' . $searchField . '%';
        } else if ($searchBoxVal == 'model') {
            $conditions['Vehicle.status'] = 1;
            $conditions['Vehicle.auction_ovr_tym >= '] = date('Y-m-d H:m:s');
            $conditions['OR'] = array(
                array('Vehicle.model LIKE' => '%' . $searchField . '%'),
                array('Vehicle.brand LIKE' => '%' . $searchField . '%'),
                array('Vehicle.type LIKE' => '%' . $searchField . '%'),
            );
        } else {
            $conditions['Vehicle.status'] = 1;
            $conditions['Vehicle.auction_ovr_tym >= '] = date('Y-m-d H:m:s');
        }

        $this->Paginator->settings = array('limit' => 10, 'conditions' => $conditions);
        $auctions = $this->Paginator->paginate('Vehicle');
        $this->set('auction_data', $auctions);
    }

    public function soldVehicles() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Vehicles Sold'));
        
        $this->Vehicle->unbindModel(
            ['hasMany' => ['AuctionBid','VehicleFavourite'],'hasOne' => ['VehicleImage'] ]
        );
        
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 0, 'Vehicle.is_sell' => 1, 'Vehicle.seller_id' => AuthComponent::User('id')), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $auctions = $this->Paginator->paginate('Vehicle');
        
        $datas['SoldVehicles'] = $auctions;
        $this->set('auction_data', $datas);
    }

    public function admin_soldvehicles() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', __('Vehicles Sold'));
        $this->loadModel('Vehicle');
        $this->loadModel('Users');
        //$auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 0,'Vehicle.is_sell' => 1,'Vehicle.seller_id' => AuthComponent::User('id')), 'order' => 'Vehicle.created ASC', 'recursive' => -1, 'limit' => 10)); //pr($auctions); die;
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 0, 'Vehicle.is_sell' => 1), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $auctions = $this->Paginator->paginate('Vehicle');

        $buyers = $this->Users->find('all', array('conditions' => array('Users.status' => 1)));
        $buyerArr = array();
        $datas = array();
        if (!empty($buyers)) {
            foreach ($buyers as $key => $val) {
                $buyerArr[$val['Users']['id']] = $val['Users']['fname'] . ' ' . $val['Users']['lname'];
            }
        }
        $datas['SoldVehicles'] = $auctions;
        $datas['buyerArr'] = $buyerArr;
        $this->set('auction_data', $datas);
    }

    public function admin_soldvehiclesearch() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: Vehicles');
        $searchBoxVal = $_POST['searchBoxVal'];
        $searchField = $_POST['searchField'];
        $this->loadModel('Users');
        $conditions = array();
        if ($searchBoxVal == 'body') {
            $conditions['Vehicle.status'] = 0;
            $conditions['Vehicle.is_sell'] = 1;
            $conditions['Vehicle.body_type Like '] = '%' . $searchField . '%';
        } else if ($searchBoxVal == 'model') {
            $conditions['Vehicle.status'] = 0;
            $conditions['Vehicle.is_sell'] = 1;
            $conditions['OR'] = array(
                array('Vehicle.model LIKE' => '%' . $searchField . '%'),
                array('Vehicle.brand LIKE' => '%' . $searchField . '%'),
                array('Vehicle.type LIKE' => '%' . $searchField . '%'),
            );
        } else {
            $conditions['Vehicle.status'] = 0;
            $conditions['Vehicle.is_sell'] = 1;
        }

        $this->Paginator->settings = array('limit' => 10, 'conditions' => $conditions);
        $auctions = $this->Paginator->paginate('Vehicle');
        $buyers = $this->Users->find('all', array('conditions' => array('Users.status' => 1)));
        $buyerArr = array();
        $datas = array();
        if (!empty($buyers)) {
            foreach ($buyers as $key => $val) {
                $buyerArr[$val['Users']['id']] = $val['Users']['fname'] . ' ' . $val['Users']['lname'];
            }
        }
        $datas['SoldVehicles'] = $auctions;
        $datas['buyerArr'] = $buyerArr;
        $this->set('auction_data', $datas);
    }

    public function purchasedVehicles() {
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Vehicles Purchased'));
        
        $this->Vehicle->unbindModel(
            ['hasMany' => ['AuctionBid','VehicleFavourite'],'hasOne' => ['VehicleImage'] ]
        );
        
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 0, 'Vehicle.is_sell' => 1, 'Vehicle.buyer_id' => AuthComponent::User('id')), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $auctions = $this->Paginator->paginate('Vehicle');
        
        $datas = array();
        $datas['SoldVehicles'] = $auctions;
        $this->set('auction_data', $datas);
    }

    public function admin_purchasedvehicles() {
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', __('Vehicles Purchased'));
        $this->loadModel('Vehicle');
        $this->loadModel('Users');
        //$auctions = $this->Vehicle->find('all', array('conditions' => array('Vehicle.status' => 0,'Vehicle.is_sell' => 1,'Vehicle.seller_id' => AuthComponent::User('id')), 'order' => 'Vehicle.created ASC', 'recursive' => -1, 'limit' => 10)); //pr($auctions); die;
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 0, 'Vehicle.is_sell' => 1), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $auctions = $this->Paginator->paginate('Vehicle');

        $buyers = $this->Users->find('all', array('conditions' => array('Users.status' => 1)));
        $buyerArr = array();
        $datas = array();
        if (!empty($buyers)) {
            foreach ($buyers as $key => $val) {
                $buyerArr[$val['Users']['id']] = $val['Users']['fname'] . ' ' . $val['Users']['lname'];
            }
        }
        $datas['SoldVehicles'] = $auctions;
        $datas['buyerArr'] = $buyerArr;
        $this->set('auction_data', $datas);
    }

    public function admin_purchasedvehiclesearch() {
        $this->layout = false;
        $this->set('PAGE_TITLE', 'Admin: Vehicles');
        $searchBoxVal = $_POST['searchBoxVal'];
        $searchField = $_POST['searchField'];
        $this->loadModel('Users');
        $conditions = array();
        if ($searchBoxVal == 'body') {
            $conditions['Vehicle.status'] = 0;
            $conditions['Vehicle.is_sell'] = 1;
            $conditions['Vehicle.body_type Like '] = '%' . $searchField . '%';
        } else if ($searchBoxVal == 'model') {
            $conditions['Vehicle.status'] = 0;
            $conditions['Vehicle.is_sell'] = 1;
            $conditions['OR'] = array(
                array('Vehicle.model LIKE' => '%' . $searchField . '%'),
                array('Vehicle.brand LIKE' => '%' . $searchField . '%'),
                array('Vehicle.type LIKE' => '%' . $searchField . '%'),
            );
        } else {
            $conditions['Vehicle.status'] = 0;
            $conditions['Vehicle.is_sell'] = 1;
        }

        $this->Paginator->settings = array('limit' => 10, 'conditions' => $conditions);
        $auctions = $this->Paginator->paginate('Vehicle');
        $buyers = $this->Users->find('all', array('conditions' => array('Users.status' => 1)));
        $buyerArr = array();
        $datas = array();
        if (!empty($buyers)) {
            foreach ($buyers as $key => $val) {
                $buyerArr[$val['Users']['id']] = $val['Users']['fname'] . ' ' . $val['Users']['lname'];
            }
        }
        $datas['SoldVehicles'] = $auctions;
        $datas['buyerArr'] = $buyerArr;
        $this->set('auction_data', $datas);
    }
    
    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
      }
    
    public function vehicleDetail($id = null) {
        $id = base64_decode($id);
        $this->layout = 'front';
        
        $user = $this->Auth->user();
        if(!$user) {
            $this->redirect(['action' => 'login', 'controller' => 'Users' ]);
        }
        
        $this->set('PAGE_TITLE', __('Vehicle Detail'));
        $this->loadModel('Users');
        $vehicles = $this->Vehicle->find('first', array('recursive' => 1, 'conditions' => array('Vehicle.id' => $id))); //, 
        
        $buyers = $this->Users->find('all', array('conditions' => array('Users.status' => 1)));
        $buyerArr = array();
        $datas = array();
        if (!empty($buyers)) {
            foreach ($buyers as $key => $val) {
                $buyerArr[$val['Users']['id']] = $val['Users']['username'];
                //$buyerArr[$val['Users']['id']] = $val['Users']['fname'] . ' ' . $val['Users']['lname'];
            }
        }
     
        $minAuctionPrice = $vehicles['Vehicle']['min_auction_price'];
        $bidingPriceArray = [];
        if( !empty($vehicles['AuctionBid']) ){
            foreach($vehicles['AuctionBid'] as $key => $val){
                $bidingPriceArray[] = $val['biding_amount'];
            }
        }
        
        $this->array_sort_by_column($vehicles['AuctionBid'], 'biding_amount', SORT_DESC);
        
        if( !empty($bidingPriceArray) ){
            $minAuctionPrice = max($bidingPriceArray);
        }
        
        /*$bidDropdown = [];
        if( $minAuctionPrice > 0 ){
            for( $i = $minAuctionPrice + $vehicles['Vehicle']['increase_with']; $i <= $minAuctionPrice * 2; $i = $i + $vehicles['Vehicle']['increase_with'] ){ //increase_with
                $i = (int) $i;
                $bidDropdown[$i] = $i;
            }
        } else {
            for( $i = $vehicles['Vehicle']['min_auction_price']; $i <= $vehicles['Vehicle']['min_auction_price'] * 2; $i = $i + $vehicles['Vehicle']['increase_with'] ){
                $i = (int) $i;
                $bidDropdown[$i] = $i;
            }
        }
        
        $bidDropdown['custom'] = __('Custom Price');*/
        
        $datas['Vehicle'] = $vehicles;
        $datas['buyerArr'] = $buyerArr;
        $datas['minAuctionPrice'] = $minAuctionPrice;
        //$datas['bidDropDown'] = $bidDropdown;
        $this->set('vehicle', $datas);
    }

    public function vehicledetails($id = null) {
        $id = base64_decode($id);
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Vehicle Detail'));
        
        if( isset($this->params['pass']['1']) ){
            if(base64_decode($this->params['pass']['1']) == 'sold_vehicles'){
                $conditions = array('Vehicle.id' => $id,'is_sell'=>'1');
            }else if(base64_decode($this->params['pass']['1']) == 'purchased_vehicles'){
                $conditions = array('Vehicle.id' => $id,'Vehicle.is_sell' => 1,'Vehicle.buyer_id' => AuthComponent::User('id'));
            }else if(base64_decode($this->params['pass']['1']) == 'fav_vehicles'){
                $conditions = array('Vehicle.id' => $id);
            }else{
                $conditions = array('Vehicle.id' => $id);
            }
        } else {
            $conditions = array('Vehicle.id' => $id);
        }
        
        $vehicles = $this->Vehicle->find('first', array('recursive'=> 2, 'contain'=>['AuctionBid' ] , 'conditions' => $conditions));
        $this->set('vehicle', $vehicles);
    }

    public function admin_vehicledetailpage($id = null) {
        $id = base64_decode($id);
        $this->layout = 'admin';
        $this->set('PAGE_TITLE', __('Vehicle Detail'));
        if (base64_decode($this->params['pass']['1']) == 'sold_vehicles') {
            $conditions = array('Vehicle.id' => $id, 'Vehicle.is_sell' => '1');
        } else if (base64_decode($this->params['pass']['1']) == 'purchased_vehicles') {
            $conditions = array('Vehicle.id' => $id, 'Vehicle.is_sell' => 1);
        } else {
            $conditions = array('Vehicle.id' => $id);
        }
        $vehicles = $this->Vehicle->find('first', array('recursive' => 2, 'contain' => ['AuctionBid'], 'conditions' => $conditions));
        $this->set('vehicle', $vehicles);
    }

    public function getAllMakes() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        $responseData = array();
        $vehicleData = $this->Vehicle->find('all', array( 'recursive' => -1 , 'fields'=> ['DISTINCT brand'] ,'conditions' => array("Vehicle.status" => 1, "Vehicle.auction_ovr_tym >=" => date('Y-m-d H:m:s'), "Vehicle.brand LIKE '" . $this->request->query['term'] . "%'"), 'limit' => 10));
        
        if (!empty($vehicleData)) {
            foreach ($vehicleData as $key => $val) {
                $responseData[$key]['id'] = $val['Vehicle']['brand'];
                $responseData[$key]['text'] = $val['Vehicle']['brand'];
            }
        }
        
        return json_encode($responseData);
        //echo json_encode($responseData, JSON_HEX_APOS);
        //die;
    }

    public function getModelByMake() {
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        $responseData = array();
        $vehicleData = $this->Vehicle->find('all', array( 'recursive' => -1, 'fields'=> ['DISTINCT model'] , 'conditions' => array('Vehicle.brand' => $this->request->query['make_id'], "Vehicle.status" => 1, "Vehicle.auction_ovr_tym >=" => date('Y-m-d H:m:s'), "Vehicle.model LIKE '%" . $this->request->query['term'] . "%' "), 'limit' => 10));
        if (!empty($vehicleData)) {
            foreach ($vehicleData as $key => $val) {
                $responseData[$key]['id'] = $val['Vehicle']['model'];
                $responseData[$key]['text'] = $val['Vehicle']['model'];
            }
        }
        return json_encode($responseData);
        //echo json_encode($responseData, JSON_HEX_APOS);
        //die;
    }

    public function addtofavourite() {

        $this->layout = 'front';
        $this->loadModel('VehicleFavourite');

        $favData = $this->VehicleFavourite->find('first', array('fields'=>['is_favourite'],'conditions' => array('VehicleFavourite.user_id' => AuthComponent::User('id') , 'VehicleFavourite.vehicle_id' => $this->request->data['vehicle_id']))); //$this->request->data['user_id']
        
        
        if( !empty($favData) ){
            if( $favData['VehicleFavourite']['is_favourite'] == 1 ){
                $is_favourite = '0';
            } else {
                $is_favourite = '1';
            }
            
            $this->VehicleFavourite->updateAll(array("VehicleFavourite.is_favourite" => $is_favourite), array("VehicleFavourite.user_id" => $this->request->data['user_id'], "VehicleFavourite.vehicle_id" => $this->request->data['vehicle_id']));
            
            
            if ($is_favourite == '1') {
                echo 'fav';
            } else {
                echo 'unfav';
            }
           die;
        } else {
            $this->VehicleFavourite->save($this->request->data);
            echo 'success';
            die;
        }
        
        /*if ( $favData['VehicleFavourite']['is_favourite']  > 0) {
            if ($this->request->data['is_favourite'] == '1') {
                $is_favourite = '0';
            } else {
                $is_favourite = '1';
            }
            $this->VehicleFavourite->updateAll(array("VehicleFavourite.is_favourite" => $is_favourite), array("VehicleFavourite.user_id" => $this->request->data['user_id'], "VehicleFavourite.vehicle_id" => $this->request->data['vehicle_id']));
            if ($is_favourite == '1') {
                echo 'fav';
            } else {
                echo 'unfav';
            }
            die;
        } else {
            $this->VehicleFavourite->save($this->request->data);
            echo 'success';
            die;
        } */
	}
	
	public function admin_vehiclepaid(){
		$this->layout = 'admin';
        $this->loadModel('Vehicle');
        $this->Vehicle->updateAll(array("Vehicle.is_paid" => '1'), array("Vehicle.id" => $this->request->data['vehicle_id']));
        echo 'paid';die;
	}
    
    public function getAllRegions(){
        $this->response->header('Access-Control-Allow-Origin', '*');
        $this->response->header('Access-Control-Allow-Headers','token');
        $this->autoRender = false;
        $this->loadModel('VehicleRegion');
        $vehicleRegion = $this->VehicleRegion->find('list', array('fields' => array('VehicleRegion.region_code', 'VehicleRegion.region_name')));
        return json_encode($vehicleRegion);
    }
    
    public function invoices(){
        $this->layout = 'front';
        $this->set('PAGE_TITLE', __('Invoices'));
        
        $this->Vehicle->unbindModel(
            ['hasMany' => ['AuctionBid','VehicleFavourite'],'hasOne' => ['VehicleImage'] ]
        );
        
        $this->Paginator->settings = array('limit' => 10, 'conditions' => array('Vehicle.status' => 0, 'Vehicle.is_sell' => 1, 'Vehicle.buyer_id' => AuthComponent::User('id')), 'order' => 'Vehicle.created ASC', 'recursive' => 2);
        $invoices = $this->Paginator->paginate('Vehicle');
        $this->set('invoices', $invoices);
    }
    
    public function buyNow(){
        $this->Session->setFlash("Vehicle purchased successfully. Invoice has been sent to your registered email address. ", 'default', array('class' => 'green'));
        $this->redirect('/');
    }
    
    public function getRecentOffers(){
        $this->layout = 'ajax';
        $this->loadModel('Vehicle');
        $this->loadModel('Users');
        
        $vehicleId = $this->request->query('vehicle_id');
        $vehicles = $this->Vehicle->find('first', array('recursive' => 1, 'conditions' => array('Vehicle.id' => $vehicleId)));
        
        $buyers = $this->Users->find('all', array('conditions' => array('Users.status' => 1)));
        $buyerArr = array();
        $datas = array();
        if (!empty($buyers)) {
            foreach ($buyers as $key => $val) {
                $buyerArr[$val['Users']['id']] = $val['Users']['fname'] . ' ' . $val['Users']['lname'];
            }
        }
        
        $vehicles['buyerArr'] = $buyerArr; 
        
        
        $this->set('vehicle', $vehicles);
        
    }
    
}
