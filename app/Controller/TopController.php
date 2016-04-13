<?php

App::uses('AppController', 'Controller');

class TopController extends AppController {

  public $uses = array('Income', 'Expenditure', 'User'); //使用するModel

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'budget_fullwidth';
      $this->set('admin_id', $this->admin_id);
      $this->set('login_user', $this->Auth->user());
  }

  public function index() {
      //管理者画面のためにユーザID一覧を取得しておく
      $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
      //現在の残高
      $income_past_lists = $this->Income->find('list', array(
          'conditions' => array(
              'Income.date <=' => date('Y-m-d'),
              'Income.status' => 1,
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => 'Income.amount'
      ));
      $expenditure_past_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date <=' => date('Y-m-d'),
              'Expenditure.status' => 1,
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'fields' => 'Expenditure.amount'
      ));
      $this->set('past_income', array_sum($income_past_lists));
      $this->set('past_expenditure', array_sum($expenditure_past_lists));
  
      //次回給与日までの支出予定合計
      $user_data = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
      $pay_date = $user_data['User']['payday'];
      if(date('d') < $pay_date) { //今月の給与日がまだの場合
        $expenditure_recent_lists = $this->Expenditure->find('list', array(
            'conditions' => array(
                'Expenditure.date >' => date('Y-m-d'),
                'Expenditure.date <=' => date('Y-m-'.$pay_date),
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
            ),
            'fields' => 'Expenditure.amount'
        ));
      } else { //今月の給与日を過ぎた場合
        $expenditure_recent_lists = $this->Expenditure->find('list', array(
            'conditions' => array(
                'Expenditure.date >' => date('Y-m-d'),
                'Expenditure.date <=' => date('Y-m-'.$pay_date, strtotime('+1 month')),
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
            ),
            'fields' => 'Expenditure.amount'
        ));
      }
      $this->set('recent_expenditure', array_sum($expenditure_recent_lists));
  
      //確定待ちの収入と支出
      $income_unfixed_count = $this->Income->find('count', array(
          'conditions' => array(
              'Income.status' => 0,
              'Income.date <=' => date('Y-m-d'),
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Income.date' => 'asc')
      ));
      $expenditure_unfixed_count = $this->Expenditure->find('count', array(
          'conditions' => array(
              'Expenditure.status' => 0,
              'Expenditure.date <=' => date('Y-m-d'),
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Expenditure.date' => 'asc')
      ));
      $this->set(compact('income_unfixed_count', 'expenditure_unfixed_count'));
  
      //本日の支出
      $expenditure_now_lists = $this->Expenditure->find('all', array(
          'conditions' => array(
              'Expenditure.date' => date('Y-m-d'),
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Expenditure.date' => 'asc')
      ));
      $this->set('expenditure_now_lists', $expenditure_now_lists);
  
      //今後の支出予定
      $expenditure_month_lists = $this->Expenditure->find('all', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-d', strtotime('+1 month')),
              'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Expenditure.date' => 'asc')
      ));
      $this->set('expenditure_month_lists', $expenditure_month_lists);
  
      //ユーザ一覧
      $user_lists = $this->User->find('all', array(
          'order' => array('User.id' => 'asc')
      ));
      $this->set('user_lists', $user_lists);
  }
}
