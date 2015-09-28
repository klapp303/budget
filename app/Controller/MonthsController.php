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

    //今月の収支
    $income_month_lists = $this->Income->find('list', array(
        'conditions' => array(
            'Income.date >=' => date($year_id.'-'.$month_id.'-01'),
            'Income.date <=' => date($year_id.'-'.$month_id.'-31')),
        'fields' => ('Income.amount')
    ));
    $this->set('income_month_lists', $income_month_lists);
    $expenditure_month_lists = $this->Expenditure->find('list', array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
            'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31')),
        'fields' => ('Expenditure.amount')
    ));
    $this->set('expenditure_month_lists', $expenditure_month_lists);

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
    $expenditure_counts = count($expenditure_lists);
    $this->set('expenditure_lists', $expenditure_lists);
    $this->set('expenditure_counts', $expenditure_counts);
  }

  public function edit($id = null) {
    //支出一覧
//    $expenditure_lists = $this->Expenditure->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = array(
        'conditions' => array(
            'Expenditure.date >=' => date('Y-m-01'),
            'Expenditure.date <=' => date('Y-m-31')),
        'order' => array('date' => 'asc')
    );
    $expenditure_lists = $this->Paginator->paginate('Expenditure');
    $expenditure_counts = count($expenditure_lists);
    $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $this->set('expenditure_lists', $expenditure_lists);
    $this->set('expenditure_counts', $expenditure_counts);
    $this->set('expenditure_genres', $expenditure_genres);

    if (empty($this->request->data)) {
      $this->request->data = $this->Expenditure->findById($id); //postデータがなければ$idからデータを取得
      $this->set('id', $this->request->data['Expenditure']['id']); //viewに渡すために$idをセット
    } else {
      $this->Expenditure->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Expenditure->validates()) { //validate成功の処理
        $this->Expenditure->save($this->request->data); //validate成功でsave
        if ($this->Expenditure->save($id)) {
          $this->Session->setFlash('修正しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('修正できませんでした。', 'flashMessage');
        }
        $this->redirect('/months/');
      } else { //validate失敗の処理
        $this->set('id', $this->request->data['Expenditure']['id']); //viewに渡すために$idをセット
//        $this->render('index'); //validate失敗でindexを表示
      }
    }
  }

  public function deleted($id = null){
    if (empty($id)) {
      throw new NotFoundException(__('存在しないデータです。'));
    }
    
    if ($this->request->is('post')) {
      $this->Expenditure->Behaviors->enable('SoftDelete');
      if ($this->Expenditure->delete($id)) {
        $this->Session->setFlash('削除しました。', 'flashMessage');
      } else {
        $this->Session->setFlash('削除できませんでした。', 'flashMessage');
      }
      $this->redirect('/months/');
    }
  }
}