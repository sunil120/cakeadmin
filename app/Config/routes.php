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
	// Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/', array('controller' => 'users', 'action' => 'login'));
/**
 * ...and connect the rest of 'api' controller's URLs via plugins.
 *   remove plugins and set all content in app controller, if you want
 *    uncomment below code for route with plugin
 * @category API
 */
        Router::connect('/api/:controller/index',
        array('[method]'=>'GET', 'action'=>'index'));

        Router::connect('/api/:controller/view/*',
        array('[method]'=>'GET', 'action'=>'view'));

        Router::connect('/api/:controller/add',
        array('[method]'=>'POST', 'action'=>'add'));

        Router::connect('/api/:controller/edit/*',
        array('[method]'=>'PUT', 'action'=>'edit'));

        Router::connect('/api/:controller/edit/*',
        array('[method]'=>'POST', 'action'=>'edit'));

        Router::connect('/api/:controller/delete/*',
        array('[method]'=>'DELETE', 'action'=>'delete'));

        Router::connect('/api/:controller/extra/:action/*',
        array('[method]'=>'GET'));

        Router::connect('/api/:controller/extra/:action/*',
        array('[method]'=>'POST'));
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();
/**
 * Load Phone Restfull by route
 * mapResources() which specifies the controllers that should support REST
 * Cake to start parsing extensions. So CakePHP will now be able to respond to actions that have extensions
 * @category API
 */
	Router::parseExtensions();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
