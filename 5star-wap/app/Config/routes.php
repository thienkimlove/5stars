<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
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
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'users'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
	Router::connect('/proxy/**', array('controller' => 'proxy', 'action' => 'index'));

/**
 * ...Rewrite friendly url
 */
	//users controller
	Router::connect('/thong-tin.html', array('controller' => 'users', 'action' => 'profile'));
	Router::connect('/thay-doi-thong-tin.html', array('controller' => 'users', 'action' => 'edit_info'));
	Router::connect('/thay-doi-mat-khau.html', array('controller' => 'users', 'action' => 'edit_password'));
	Router::connect('/faqs.html', array('controller' => 'users', 'action' => 'faqs'));
	Router::connect('/dieu-khoan-su-dung.html', array('controller' => 'users', 'action' => 'term'));
	Router::connect('/trang-chu.html', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/dang-ky.html',array('controller'=>'users','action'=>'register'));
	
	//index controller
	Router::connect('/index/thong-tin.html', array('controller' => 'index', 'action' => 'profile'));
	Router::connect('/index/thay-doi-thong-tin.html', array('controller' => 'index', 'action' => 'edit_info'));
	Router::connect('/index/thay-doi-mat-khau.html', array('controller' => 'index', 'action' => 'edit_password'));
	Router::connect('/index/faqs.html', array('controller' => 'index', 'action' => 'faqs'));
	Router::connect('/index/dieu-khoan-su-dung.html', array('controller' => 'index', 'action' => 'term'));
	Router::connect('/index/trang-chu.html', array('controller' => 'index', 'action' => 'login'));
	Router::connect('/index/dang-ky.html',array('controller'=>'index','action'=>'register'));
	Router::connect('/index/quen-mat-khau.html',array('controller'=>'index','action'=>'lost'));
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
