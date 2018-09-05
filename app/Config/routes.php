<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'Users', 'action' => 'index'));
	Router::connect('/admin', array('controller' => 'Users', 'action' => 'login','admin'=>true));
        Router::connect('/contact-us', array('controller' => 'Users', 'action' => 'contactUs'));
	Router::connect('/:slug', array('controller' => 'CmsPages', 'action' => 'pages'));
        
        
        Router::resourceMap( array(
            array( 'action' => 'api_index', 'method' => 'GET', 'id' => false ),
            array( 'action' => 'api_view', 'method' => 'GET', 'id' => true ),
            array( 'action' => 'api_add', 'method' => 'POST', 'id' => false),
            array( 'action' => 'api_edit', 'method' => 'PUT', 'id' => true ),
            array( 'action' => 'api_delete', 'method' => 'DELETE', 'id' => true ),
        ));
        
        Router::mapResources('users', array('prefix'=> 'api'));
        Router::parseExtensions( 'json' );
        
        //Router::mapResources( 'users', array( 'controller' => 'Users' ,'action'=>'index', 'prefix' => 'api' ) );
//        Router::connect('/:prefix/:controller/:action/*',
//            array(),
//            array(
//                'prefix' => 'api',
//            )
//        );

        
//        Router::resourceMap( array(
//            array( 'action' => 'api_list', 'method' => 'GET', 'id' => false ),
//            array( 'action' => 'api_view', 'method' => 'GET', 'id' => true ),
//            array( 'action' => 'api_create', 'method' => 'POST', 'id' => false),
//            array( 'action' => 'api_edit', 'method' => 'PUT', 'id' => true ),
//            array( 'action' => 'api_delete', 'method' => 'DELETE', 'id' => true ),
//        ));
//        
//        Router::mapResources( 'users' );
        //Router::parseExtensions( 'json' );


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';