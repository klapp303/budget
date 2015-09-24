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
class ExpendituresController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Expenditure', 'ExpendituresGenre'); //使用するModel

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
//    $expenditure_lists = $this->Expenditure->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = $this->paginate;
    $expenditure_lists = $this->Paginator->paginate('Expenditure');
    $expenditure_counts = count($expenditure_lists);
    $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $this->set('expenditure_lists', $expenditure_lists);
    $this->set('expenditure_counts', $expenditure_counts);
    $this->set('expenditure_genres', $expenditure_genres);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->Expenditure->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Expenditure->validates()) { //validate成功の処理
        $this->Expenditure->save($this->request->data); //validate成功でsave
        if ($this->Expenditure->save($id)) {
          $this->Session->setFlash('登録しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('登録できませんでした。', 'flashMessage');
        }
      } else { //validate失敗の処理
        $this->render('index'); //validate失敗でindexを表示
      }
    }

    $this->redirect('/expenditures/');
  }

  public function edit($id = null) {
//    $expenditure_lists = $this->Expenditure->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = $this->paginate;
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
        $this->redirect('/expenditures/');
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
      $this->redirect('/expenditures/');
    }
  }
}