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
class TopController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Income', 'Expenditure', 'User'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public function beforeFilter() {
    parent::beforeFilter();
    $this->layout = 'budget_fullwidth';
    $this->admin_id = 1;
    $this->set('admin_id', $this->admin_id);
    $this->set('login_user', $this->Auth->user());
  }

  public function index() {
    //現在の残高
    if ($this->Auth->user('id') == $this->admin_id) { //管理者アカウントの場合
      $income_past_lists = $this->Income->find('list', array(
          'conditions' => array('Income.date <=' => date('Y-m-d'), 'Income.status' => 1),
          'fields' => ('Income.amount')
      ));
      $expenditure_past_lists = $this->Expenditure->find('list', array(
          'conditions' => array('Expenditure.date <=' => date('Y-m-d'), 'Expenditure.status' => 1),
          'fields' => ('Expenditure.amount')
      ));
    } else {
      $income_past_lists = $this->Income->find('list', array(
          'conditions' => array('Income.date <=' => date('Y-m-d'), 'Income.status' => 1, 'Income.user_id' => $this->Auth->user('id')),
          'fields' => ('Income.amount')
      ));
      $expenditure_past_lists = $this->Expenditure->find('list', array(
          'conditions' => array('Expenditure.date <=' => date('Y-m-d'), 'Expenditure.status' => 1, 'Expenditure.user_id' => $this->Auth->user('id')),
          'fields' => ('Expenditure.amount')
      ));
    }
    $this->set('past_income', array_sum($income_past_lists));
    $this->set('past_expenditure', array_sum($expenditure_past_lists));

    //次回給料日までの出費
    $pay_date = 25;
    if(date('d') < $pay_date) { //今月の給料日がまだの場合
      $expenditure_recent_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-'.$pay_date)),
          'fields' => ('Expenditure.amount')
      ));
    } else { //今月の給料日を過ぎた場合
      $expenditure_recent_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-'.$pay_date, strtotime('+1 month'))),
          'fields' => ('Expenditure.amount')
      ));
    }
    $this->set('recent_expenditure', array_sum($expenditure_recent_lists));

    //確定待ちの収入
    if ($this->Auth->user('id') == $this->admin_id) { //管理者アカウントの場合
      $income_unfixed_lists = $this->Income->find('all', array(
          'conditions' => array(
              'Income.status' => 0,
              'Income.date <=' => date('Y-m-d')
          ),
          'order' => array('Income.date' => 'asc')
      ));
    } else {
      $income_unfixed_lists = $this->Income->find('all', array(
          'conditions' => array(
              'Income.status' => 0,
              'Income.date <=' => date('Y-m-d'),
              'Income.user_id' => $this->Auth->user('id')
          ),
          'order' => array('Income.date' => 'asc')
      ));
    }
    $this->set('income_unfixed_counts', count($income_unfixed_lists));

    //確定待ちの支出
    if ($this->Auth->user('id') == $this->admin_id) { //管理者アカウントの場合
      $expenditure_unfixed_lists = $this->Expenditure->find('all', array(
          'conditions' => array(
              'Expenditure.status' => 0,
              'Expenditure.date <=' => date('Y-m-d')
          ),
          'order' => array('Expenditure.date' => 'asc')
      ));
    } else {
      $expenditure_unfixed_lists = $this->Expenditure->find('all', array(
          'conditions' => array(
              'Expenditure.status' => 0,
              'Expenditure.date <=' => date('Y-m-d'),
              'Expenditure.user_id' => $this->Auth->user('id')
          ),
          'order' => array('Expenditure.date' => 'asc')
      ));
    }
    $this->set('expenditure_unfixed_counts', count($expenditure_unfixed_lists));

    //本日の支出
    $expenditure_now_lists = $this->Expenditure->find('all', array(
        'conditions' => array(
            'Expenditure.date' => date('Y-m-d'),
            'Expenditure.user_id' => $this->Auth->user('id')
        ),
        'order' => array('Expenditure.date' => 'asc')
    ));
    $expenditure_now_counts = count($expenditure_now_lists);
    $this->set(compact('expenditure_now_lists', 'expenditure_now_counts'));

    //直近の支出
    if ($this->Auth->user('id') == $this->admin_id) { //管理者アカウントの場合
      $expenditure_month_lists = $this->Expenditure->find('all', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-d', strtotime('+1 month'))
          ),
          'order' => array('Expenditure.date' => 'asc')
      ));
    } else {
      $expenditure_month_lists = $this->Expenditure->find('all', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-d', strtotime('+1 month')),
              'Expenditure.user_id' => $this->Auth->user('id')
          ),
          'order' => array('Expenditure.date' => 'asc')
      ));
    }
    $this->set('expenditure_month_lists', $expenditure_month_lists);

    //ユーザ一覧
    $user_lists = $this->User->find('all', array(
        'order' => array('User.id' => 'asc')
    ));
    $this->set('user_lists', $user_lists);
  }
}