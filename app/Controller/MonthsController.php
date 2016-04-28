<?php

App::uses('AppController', 'Controller');

class MonthsController extends AppController {

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
  
      if (empty($this->request->params['year_id'])) { //パラメータがない場合は今月の値を取得
        $year_id = date('Y');
        $month_id = date('n');
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
      $this->set(compact('year_id', 'month_id', 'year_pre_id', 'month_pre_id', 'year_post_id', 'month_post_id'));
  
      //n月の確定収支
      $array_option = array(
          'conditions' => array(
              'Income.date >=' => date($year_id.'-'.$month_id.'-01'),
              'Income.date <=' => date($year_id.'-'.$month_id.'-31'),
              'Income.status' => 1,
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => ('Income.amount')
      );
      $this->set('income_month_lists', $this->Income->find('list', $array_option));
      $array_option = array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
              'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
              'Expenditure.status' => 1,
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => ('Expenditure.amount')
      );
      $this->set('expenditure_month_lists', $this->Expenditure->find('list', $array_option));
      /* 比較用に先月の収支を取得ここから */
      $array_option = array(
          'conditions' => array(
              'Income.date >=' => date($year_pre_id.'-'.$month_pre_id.'-01'),
              'Income.date <=' => date($year_pre_id.'-'.$month_pre_id.'-31'),
              'Income.status' => 1,
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => ('Income.amount')
      );
      $this->set('income_month_pre_lists', $this->Income->find('list', $array_option));
      $array_option = array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_pre_id.'-'.$month_pre_id.'-01'),
              'Expenditure.date <=' => date($year_pre_id.'-'.$month_pre_id.'-31'),
              'Expenditure.status' => 1,
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => ('Expenditure.amount')
       );
      $this->set('expenditure_month_pre_lists', $this->Expenditure->find('list', $array_option));
      /* 比較用に先月の収支を取得ここまで */
  
      //支出内訳
      $genres_e_lists = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
      $genres_e_counts = count($genres_e_lists);
      $this->set(compact('genres_e_lists', 'genres_e_counts'));
      for($i = 1; $i <= $genres_e_counts; $i++) {
        $array_option = array(
            'conditions' => array(
                'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
                'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
                'Expenditure.genre_id' => $i,
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
            ),
            'fields' => ('Expenditure.amount')
        );
        $expenditure_month_g_lists = $this->Expenditure->find('list', $array_option);
        ${'expenditure_month_g'.$i.'_sum'} = array_sum($expenditure_month_g_lists); //ジャンル毎の支出を加算
        $this->set('expenditure_month_g'.$i.'_sum', ${'expenditure_month_g'.$i.'_sum'});
      }
      /* 支出割合を出すために母数を取得ここから */
      $expenditure_month_all_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
              'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => ('Expenditure.amount')
      ));
      $this->set('expenditure_month_all_lists', $expenditure_month_all_lists);
      /* 支出割合を出すために母数を取得ここまで */
      /* 比較用に先月の支出内訳を取得ここから */
      for($i = 1; $i <= $genres_e_counts; $i++) {
        $array_option = array(
            'conditions' => array(
                'Expenditure.date >=' => date($year_pre_id.'-'.$month_pre_id.'-01'),
                'Expenditure.date <=' => date($year_pre_id.'-'.$month_pre_id.'-31'),
                'Expenditure.genre_id' => $i,
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
            ),
            'fields' => ('Expenditure.amount')
        );
        $expenditure_month_g_pre_lists = $this->Expenditure->find('list', $array_option);
        ${'expenditure_month_g'.$i.'_pre_sum'} = array_sum($expenditure_month_g_pre_lists); //ジャンル毎の支出を加算
        $this->set('expenditure_month_g'.$i.'_pre_sum', ${'expenditure_month_g'.$i.'_pre_sum'});
      }
      /* 比較用に先月の支出内訳を取得ここまで */
  
      //支出一覧
      $this->Paginator->settings = array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
              'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Expenditure.date' => 'asc', 'Expenditure.title' => 'asc')
      );
      $this->set('expenditure_lists', $this->Paginator->paginate('Expenditure'));
  }

  public function genre() {
      $this->layout = 'budget_sub_pop';
  
      //管理者画面のためにユーザID一覧を取得しておく
      $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
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
        $this->set(compact('year_id', 'month_id', 'genre_id'));
      }
  
      //ジャンル別の支出一覧
      $array_option = array(
          'conditions' => array(
              'Expenditure.date >=' => date($year_id.'-'.$month_id.'-01'),
              'Expenditure.date <=' => date($year_id.'-'.$month_id.'-31'),
              'Expenditure.genre_id' => $genre_id,
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Expenditure.date' => 'asc', 'Expenditure.title' => 'asc')
      );
      $this->set('expenditure_lists', $this->Expenditure->find('all', $array_option));
  }
}
