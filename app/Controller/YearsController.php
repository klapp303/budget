<?php

App::uses('AppController', 'Controller');

class YearsController extends AppController {

	public $uses = array('Income', 'Expenditure', 'ExpendituresGenre', 'User'); //使用するModel

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
    //管理者画面のためにユーザID一覧を取得しておく
    $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
    if (empty($this->request->params['year_id'])) { //パラメータがない場合は今年の値を取得
      $year_id = date('Y');
    } elseif ($this->request->params['year_id'] < 2015) { //2014年までのパラメータの場合
      $this->Session->setFlash('存在しないデータです。', 'flashMessage');
      $this->redirect('/years/');
    } else {
      $year_id = $this->request->params['year_id'];
    }
    
    //昨年と翌年の日時情報を定義しておく
    $year_pre_id = $year_id - 1;
    $year_post_id = $year_id + 1;
    $this->set(compact('year_id', 'year_pre_id', 'year_post_id'));
  
    //n年の確定収支
    $array_option = array(
        'conditions' => array(
            'Income.date >=' => date($year_id.'-01-01'),
            'Income.date <=' => date($year_id.'-12-31'),
            'Income.status' => 1,
            'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
        ),
        'fields' => ('Income.amount')
    );
    $this->set('income_year_lists', $this->Income->find('list', $array_option));
    $array_option = array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_id.'-01-01'),
            'Expenditure.date <=' => date($year_id.'-12-31'),
            'Expenditure.status' => 1,
            'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
        ),
        'fields' => ('Expenditure.amount')
    );
    $this->set('expenditure_year_lists', $this->Expenditure->find('list', $array_option));
    /* 比較用に昨年の収支を取得ここから */
    $array_option = array(
        'conditions' => array(
            'Income.date >=' => date($year_pre_id.'-01-01'),
            'Income.date <=' => date($year_pre_id.'-12-31'),
            'Income.status' => 1,
            'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
        ),
        'fields' => ('Income.amount')
    );
    $this->set('income_year_pre_lists', $this->Income->find('list', $array_option));
    $array_option = array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_pre_id.'-01-01'),
            'Expenditure.date <=' => date($year_pre_id.'-12-31'),
            'Expenditure.status' => 1,
            'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
        ),
        'fields' => ('Expenditure.amount')
    );
    $this->set('expenditure_year_pre_lists', $this->Expenditure->find('list', $array_option));
    /* 比較用に昨年の収支を取得ここまで */
  
    //グラフ
    $graph_hoge_data_lists = array(
        1 => 5000,
        2 => 3700,
        3 => 5600,
        4 => 6200,
        5 => 4700,
        6 => 5300
    );
    $graph_fuga_data_lists = array(
        1 => 4500,
        2 => 5600,
        3 => 5300,
        4 => 3900,
        5 => 5500,
        6 => 5700
    );
    $this->set(compact('graph_hoge_data_lists', 'graph_fuga_data_lists'));
  }
}