<?php

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
