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
        'fields' => 'Income.amount'
    );
    $this->set('income_year_lists', $this->Income->find('list', $array_option));
    $array_option = array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_id.'-01-01'),
            'Expenditure.date <=' => date($year_id.'-12-31'),
            'Expenditure.status' => 1,
            'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
        ),
        'fields' => 'Expenditure.amount'
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
        'fields' => 'Income.amount'
    );
    $this->set('income_year_pre_lists', $this->Income->find('list', $array_option));
    $array_option = array(
        'conditions' => array(
            'Expenditure.date >=' => date($year_pre_id.'-01-01'),
            'Expenditure.date <=' => date($year_pre_id.'-12-31'),
            'Expenditure.status' => 1,
            'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
        ),
        'fields' => 'Expenditure.amount'
    );
    $this->set('expenditure_year_pre_lists', $this->Expenditure->find('list', $array_option));
    /* 比較用に昨年の収支を取得ここまで */
  
    //年間収支グラフ
    $income_year_data = array();
    for ($i = 1; $i <= 12; $i++) {
      $income_month_lists = $this->Income->find('list', array(
          'conditions' => array(
              'Income.date >=' => date($year_id.'-'.$i.'-01'),
              'Income.date <=' => date($year_id.'-'.$i.'-31'),
              'Income.status' => 1,
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => 'Income.amount'
      ));
      if (!$income_month_lists) {
        continue;
      }
      $income_year_data += [$i => array_sum($income_month_lists)];
    }
    $expenditure_year_data = array();
    for ($i = 1; $i <= 12; $i++) {
      $expenditure_month_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_id.'-'.$i.'-01'),
              'Expenditure.date <=' => date($year_id.'-'.$i.'-31'),
              'Expenditure.status' => 1,
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => 'Expenditure.amount'
      ));
      if (!$expenditure_month_lists) {
        continue;
      }
      $expenditure_year_data += [$i => array_sum($expenditure_month_lists)];
    }
    $this->set(compact('income_year_data', 'expenditure_year_data'));
  
    //支出内訳グラフ
    $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    foreach ($expenditure_genres AS $genre_id => $genre_title) {
      ${'expenditure_genre_data_'.$genre_id} = array();
      for ($i = 1; $i <= 12; $i++) {
        $expenditure_month_lists = $this->Expenditure->find('list', array(
            'conditions' => array(
                'Expenditure.date >=' => date($year_id.'-'.$i.'-01'),
                'Expenditure.date <=' => date($year_id.'-'.$i.'-31'),
                'Expenditure.status' => 1,
                'Expenditure.genre_id' => $genre_id,
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
            ),
            'fields' => 'Expenditure.amount'
        ));
        if (!$expenditure_month_lists) {
          continue;
        }
        ${'expenditure_genre_data_'.$genre_id} += [$i => array_sum($expenditure_month_lists)];
      }
      $this->set('expenditure_genre_data_'.$genre_id, ${'expenditure_genre_data_'.$genre_id});
    }
    $this->set('expenditure_genres', $expenditure_genres);
  }
}