<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class MonthsController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Income', 'Expenditure', 'ExpendituresGenre'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public $components = array('Paginator');
  public $paginate = array(
      'limit' => 20,
      'order' => array('date' => 'desc')
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->layout = 'budget_fullwidth';
  }

  public function index() {
    if (empty($this->request->params['year_id'])) { //パラメータがない場合は今月の値を取得
      $year_id = date('Y');
      $month_id = date('m');
    } elseif ($this->request->params['year_id'] < 2015) { //2014年までのパラメータの場合
      $this->Session->setFlash('存在しないデータです。', 'flashMessage');
      $this->redirect('/months/');
    } elseif ($this->request->params['year_id'] == 2015 and $this->request->params['month_id'] < 9) { //2015年8月までのパラメータの場合
      $this->Session->setFlash('存在しないデータです。', 'flashMessage');
      $this->redirect('/months/');
    } elseif ($this->request->params['year_id'] and $this->request->params['month_id']) {
      $year_id = $this->request->params['year_id'];
      $month_id = $this->request->params['month_id'];
    }
    
    if ($month_id == 1) { //先月と来月の日時情報を定義しておく
    $year_pre_id = $year_id - 1; $month_pre_id = 12;
    $year_post_id = $year_id; $month_post_id = 2;
    } elseif ($month_id == 12) {
    $year_pre_id = $year_id; $month_pre_id = 11;
    $year_post_id = $year_id + 1; $month_post_id = 1;
   } else {
    $year_pre_id = $year_id; $month_pre_id = $month_id - 1;
    $year_post_id = $year_id; $month_post_id = $month_id + 1;
    }
    
    $this->set('year_id', $year_id); //パラメータをviewにも渡しておく
    $this->set('month_id', $month_id);
    $this->set('year_pre_id', $year_pre_id);
    $this->set('month_pre_id', $month_pre_id);
    $this->set('year_post_id', $year_post_id);
    $this->set('month_post_id', $month_post_id);

    //n月の確定収支
    $income_month_lists = $this->Income->find('list', array(
        'conditions' => array(
            'Income.date >=' => date($year_id.'-'.$month_id.'-01'),
            'Income.date <=' => date($year_id.'-'.$month_id.'-31'),
            'Income.status' => 1),
        'fields' => ('Income.amount')
    ));
    $this->set('income_month_lists', $income_month_lists);
    $expenditure_month_lists = $this->Expenditure->find('list', array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
            'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
            'Expenditure.status' => 1),
        'fields' => ('Expenditure.amount')
    ));
    $this->set('expenditure_month_lists', $expenditure_month_lists);
    $expenditure_month_all_lists = $this->Expenditure->find('list', array( //支出割合を出すために取得する
        'conditions' => array(
            'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
            'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),),
        'fields' => ('Expenditure.amount')
    ));
    $this->set('expenditure_month_all_lists', $expenditure_month_all_lists);
    
      $income_month_pre_lists = $this->Income->find('list', array( //先月の収支を比較のために取得する
          'conditions' => array(
              'Income.date >=' => date($year_pre_id.'-'.$month_pre_id.'-01'),
              'Income.date <=' => date($year_pre_id.'-'.$month_pre_id.'-31'),
              'Income.status' => 1),
          'fields' => ('Income.amount')
      ));
      $this->set('income_month_pre_lists', $income_month_pre_lists);
      $expenditure_month_pre_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_pre_id.'-'.$month_pre_id.'-01'),
              'Expenditure.date <=' => date($year_pre_id.'-'.$month_pre_id.'-31'),
              'Expenditure.status' => 1),
          'fields' => ('Expenditure.amount')
      ));
      $this->set('expenditure_month_pre_lists', $expenditure_month_pre_lists);

    //支出内訳
    $genres_e_lists = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $genres_e_counts = count($genres_e_lists);
    $this->set('genres_e_lists', $genres_e_lists); //ジャンル一覧をviewに渡しておく
    $this->set('genres_e_counts', $genres_e_counts); //ジャンル数をviewに渡しておく
    for($i = 1; $i <= $genres_e_counts; $i++) {
      $expenditure_month_g_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
              'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
              'genre_id' => $i),
          'fields' => ('Expenditure.amount')
      ));
      ${'expenditure_month_g'.$i.'_sum'} = array_sum($expenditure_month_g_lists); //ジャンル毎の支出を加算
      $this->set('expenditure_month_g'.$i.'_sum', ${'expenditure_month_g'.$i.'_sum'});
    }
    
      for($i = 1; $i <= $genres_e_counts; $i++) { //先月の支出内訳を比較のために取得する
        $expenditure_month_g_pre_lists = $this->Expenditure->find('list', array(
            'conditions' => array(
                'Expenditure.date >=' => date($year_pre_id.'-'.$month_pre_id.'-01'),
                'Expenditure.date <=' => date($year_pre_id.'-'.$month_pre_id.'-31'),
                'genre_id' => $i),
            'fields' => ('Expenditure.amount')
        ));
        ${'expenditure_month_g'.$i.'_pre_sum'} = array_sum($expenditure_month_g_pre_lists); //ジャンル毎の支出を加算
        $this->set('expenditure_month_g'.$i.'_pre_sum', ${'expenditure_month_g'.$i.'_pre_sum'});
      }

    //支出一覧
//    $expenditure_lists = $this->Expenditure->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
            'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31')),
        'order' => array('date' => 'asc')
    );
    $expenditure_lists = $this->Paginator->paginate('Expenditure');
    $this->set('expenditure_lists', $expenditure_lists);
  }

  public function genre() {
    $this->layout = 'budget_sub_pop';
    
    $genres_e_lists = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $genres_e_counts = count($genres_e_lists);
    $this->set('genres_e_lists', $genres_e_lists); //ジャンル一覧をviewに渡しておく
    
    if (empty($this->request->params['year_id'])) { //パラメータがない場合は今月の値を取得
      $this->Session->setFlash('存在しないデータです。', 'flashMessage');
    } elseif ($this->request->params['year_id'] < 2015) { //2014年までのパラメータの場合
      $this->Session->setFlash('存在しないデータです。', 'flashMessage');
    } elseif ($this->request->params['year_id'] == 2015 and $this->request->params['month_id'] < 9) { //2015年8月までのパラメータの場合
      $this->Session->setFlash('存在しないデータです。', 'flashMessage');
    } elseif ($this->request->params['genre_id'] > $genres_e_counts) {
      $this->Session->setFlash('存在しないデータです。', 'flashMessage');
    } elseif ($this->request->params['year_id'] and $this->request->params['month_id']) {
      $year_id = $this->request->params['year_id'];
      $month_id = $this->request->params['month_id'];
      $genre_id = $this->request->params['genre_id'];
      $this->set('year_id', $year_id); //パラメータをviewにも渡しておく
      $this->set('month_id', $month_id);
      $this->set('genre_id', $genre_id);
    }

    //支出一覧
    $expenditure_lists = $this->Expenditure->find('all', array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
            'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
            'Expenditure.genre_id' => $genre_id),
        'order' => array('date' => 'asc')
    ));
    $this->set('expenditure_lists', $expenditure_lists);
  }
}