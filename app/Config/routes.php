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

	Router::connect('/', array('controller' => 'Top', 'action' => 'index'));

/**
 * 'Months' controller's URLs.
 */
	Router::connect('/months/:year_id/:month_id',
          array('controller' => 'Months', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+', 'month_id' => '[0-9]+')); //:idを数字のみに制約
  Router::connect('/months/index/:year_id/:month_id', //index/:idの場合
          array('controller' => 'Months', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+', 'month_id' => '[0-9]+')); //:idを数字のみに制約
  /* paginatorのための記述 */
  Router::connect('/months/:year_id/:month_id/*',
          array('controller' => 'Months', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+', 'month_id' => '[0-9]+')); //:idを数字のみに制約
  Router::connect('/months/index/:year_id/:month_id/*', //index/:idの場合
          array('controller' => 'Months', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+', 'month_id' => '[0-9]+')); //:idを数字のみに制約
  /* ジャンル別詳細のための記述 */
  Router::connect('/months/genre/:genre_id/:year_id/:month_id',
          array('controller' => 'Months', 'action' => 'genre', 'method' => 'GET'),
          array('genre_id' => '[0-9]+', 'year_id' => '[0-9]+', 'month_id' => '[0-9]+')); //:idを数字のみに制約

/**
 * 'Years' controller's URLs.
 */
  Router::connect('/years/:year_id',
          array('controller' => 'Years', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+')); //:idを数字のみに制約
  Router::connect('/years/index/:year_id', //index/:idの場合
          array('controller' => 'Years', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+')); //:idを数字のみに制約
  /* paginatorのための記述 */
  /*Router::connect('/years/:year_id/*',
          array('controller' => 'Years', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+')); //:idを数字のみに制約
  Router::connect('/years/index/:year_id/*', //index/:idの場合
          array('controller' => 'Years', 'action' => 'index', 'method' => 'GET'),
          array('year_id' => '[0-9]+')); //:idを数字のみに制約*/

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