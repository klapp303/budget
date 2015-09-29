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
	public $uses = array('Income', 'Expenditure'); //使用するModel

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
  }

  public function index() {
    //現在の残高
    $income_past_lists = $this->Income->find('list', array(
        'conditions' => array('Income.date <=' => date('Y-m-d'), 'status' => 1),
        'fields' => ('Income.amount')
    ));
    $this->set('income_past_lists', $income_past_lists);
    $expenditure_past_lists = $this->Expenditure->find('list', array(
        'conditions' => array('Expenditure.date <=' => date('Y-m-d'), 'status' => 1),
        'fields' => ('Expenditure.amount')
    ));
    $this->set('expenditure_past_lists', $expenditure_past_lists);

    //次回給料日までの出費
    if(date('d') < 25) { //1日～24日の場合
      $expenditure_recent_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-25')),
          'fields' => ('Expenditure.amount')
      ));
    } elseif(date('m') == 12) { //12月25日～31日の場合
      $expenditure_recent_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-1-25', strtotime('+1 year'))),
          'fields' => ('Expenditure.amount')
      ));
    } else { //25日～31日の場合(12月を除く)
      $expenditure_recent_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-25', strtotime('+1 month'))),
          'fields' => ('Expenditure.amount')
      ));
    }
    $this->set('expenditure_recent_lists', $expenditure_recent_lists);

    //確定待ちの収入
    $income_unfixed_lists = $this->Income->find('all', array(
        'conditions' => array(
          'status' => 0,
          'date <=' => date('Y-m-d')
        ),
        'order' => array('date' => 'asc')
    ));
//    $this->Paginator->settings = $this->paginate;
//    $income_unfixed_lists = $this->Paginator->paginate('Income');
    $income_unfixed_counts = count($income_unfixed_lists);
//    $this->set('income_unfixed_lists', $income_unfixed_lists);
    $this->set('income_unfixed_counts', $income_unfixed_counts);

    //確定待ちの支出
    $expenditure_unfixed_lists = $this->Expenditure->find('all', array(
        'conditions' => array(
          'status' => 0,
          'date <=' => date('Y-m-d')
        ),
        'order' => array('date' => 'asc')
    ));
//    $this->Paginator->settings = $this->paginate;
//    $expenditure_unfixed_lists = $this->Paginator->paginate('Expenditure');
    $expenditure_unfixed_counts = count($expenditure_unfixed_lists);
//    $this->set('expenditure_unfixed_lists', $expenditure_unfixed_lists);
    $this->set('expenditure_unfixed_counts', $expenditure_unfixed_counts);

    //本日の支出
    $expenditure_now_lists = $this->Expenditure->find('all', array(
        'conditions' => array(
            'Expenditure.date' => date('Y-m-d')),
        'order' => array('date' => 'asc')
    ));
    $expenditure_now_counts = count($expenditure_now_lists);
    $this->set('expenditure_now_lists', $expenditure_now_lists);
    $this->set('expenditure_now_counts', $expenditure_now_counts);

    //直近の支出
    $expenditure_month_lists = $this->Expenditure->find('all', array(
        'conditions' => array(
            'Expenditure.date >' => date('Y-m-d'),
            'Expenditure.date <=' => date('Y-m-d', strtotime('+1 month'))),
        'order' => array('date' => 'asc')
    ));
    $expenditure_month_counts = count($expenditure_month_lists);
    $this->set('expenditure_month_lists', $expenditure_month_lists);
    $this->set('expenditure_month_counts', $expenditure_month_counts);
  }
}