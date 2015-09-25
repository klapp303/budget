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
    $income_now_lists = $this->Income->find('list', array(
        'conditions' => array('Income.date <=' => date('Y-m-d')),
        'fields' => ('Income.amount')
    ));
    $this->set('income_now_lists', $income_now_lists);
    $expenditure_now_lists = $this->Expenditure->find('list', array(
        'conditions' => array('Expenditure.date <=' => date('Y-m-d')),
        'fields' => ('Expenditure.amount')
    ));
    $this->set('expenditure_now_lists', $expenditure_now_lists);

    if(date('d') < 25) { //1日～24日の場合
      $expenditure_month_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-25')),
          'fields' => ('Expenditure.amount')
      ));
    } elseif(date('m') == 12) { //12月25日～31日の場合
      $expenditure_month_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-1-25', strtotime('+1 year'))),
          'fields' => ('Expenditure.amount')
      ));
    } else { //25日～31日の場合(12月を除く)
      $expenditure_month_lists = $this->Expenditure->find('list', array(
          'conditions' => array(
              'Expenditure.date >' => date('Y-m-d'),
              'Expenditure.date <=' => date('Y-m-25', strtotime('+1 month'))),
          'fields' => ('Expenditure.amount')
      ));
    }
    $this->set('expenditure_month_lists', $expenditure_month_lists);

    $expenditure_recent_lists = $this->Expenditure->find('all', array(
        'conditions' => array(
            'Expenditure.date >' => date('Y-m-d'),
            'Expenditure.date <=' => date('Y-m-d', strtotime('+1 month'))),
        'order' => array('date' => 'asc')
    ));
    $expenditure_recent_counts = count($expenditure_recent_lists);
    $this->set('expenditure_recent_lists', $expenditure_recent_lists);
    $this->set('expenditure_recent_counts', $expenditure_recent_counts);
  }
}