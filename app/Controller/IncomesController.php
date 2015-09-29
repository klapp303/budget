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
class IncomesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Income', 'IncomesGenre'); //使用するModel

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
//    $income_lists = $this->Income->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = $this->paginate;
    $income_lists = $this->Paginator->paginate('Income');
    $income_counts = count($income_lists);
    $income_genres = $this->IncomesGenre->find('list', array('fields' => array('id', 'title')));
    $this->set('income_lists', $income_lists);
    $this->set('income_counts', $income_counts);
    $this->set('income_genres', $income_genres);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->Income->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Income->validates()) { //validate成功の処理
        $this->Income->save($this->request->data); //validate成功でsave
        if ($this->Income->save($id)) {
          $this->Session->setFlash('登録しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('登録できませんでした。', 'flashMessage');
        }
      } else { //validate失敗の処理
        $this->render('index'); //validate失敗でindexを表示
      }
    }

    $this->redirect('/incomes/');
  }

  public function edit($id = null) {
//    $income_lists = $this->Income->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    $this->Paginator->settings = $this->paginate;
    $income_lists = $this->Paginator->paginate('Income');
    $income_counts = count($income_lists);
    $income_genres = $this->IncomesGenre->find('list', array('fields' => array('id', 'title')));
    $this->set('income_lists', $income_lists);
    $this->set('income_counts', $income_counts);
    $this->set('income_genres', $income_genres);

    if (empty($this->request->data)) {
      $this->request->data = $this->Income->findById($id); //postデータがなければ$idからデータを取得
      $this->set('id', $this->request->data['Income']['id']); //viewに渡すために$idをセット
    } else {
      $this->Income->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Income->validates()) { //validate成功の処理
        $this->Income->save($this->request->data); //validate成功でsave
        if ($this->Income->save($id)) {
          $this->Session->setFlash('修正しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('修正できませんでした。', 'flashMessage');
        }
        $this->redirect('/incomes/');
      } else { //validate失敗の処理
        $this->set('id', $this->request->data['Income']['id']); //viewに渡すために$idをセット
//        $this->render('index'); //validate失敗でindexを表示
      }
    }
  }

  public function deleted($id = null){
    if (empty($id)) {
      throw new NotFoundException(__('存在しないデータです。'));
    }
    
    if ($this->request->is('post')) {
      $this->Income->Behaviors->enable('SoftDelete');
      if ($this->Income->delete($id)) {
        $this->Session->setFlash('削除しました。', 'flashMessage');
      } else {
        $this->Session->setFlash('削除できませんでした。', 'flashMessage');
      }
      $this->redirect('/incomes/');
    }
  }

  public function fix() {
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
    $this->set('income_unfixed_lists', $income_unfixed_lists);
    $this->set('income_unfixed_counts', $income_unfixed_counts);
  }

  public function fix_edit($id = null) {
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
    $income_genres = $this->IncomesGenre->find('list', array('fields' => array('id', 'title')));
    $this->set('income_unfixed_lists', $income_unfixed_lists);
    $this->set('income_unfixed_counts', $income_unfixed_counts);
    $this->set('income_genres', $income_genres);

    if (empty($this->request->data)) {
      $this->request->data = $this->Income->findById($id); //postデータがなければ$idからデータを取得
      $this->set('id', $this->request->data['Income']['id']); //viewに渡すために$idをセット
    } else {
      $this->Income->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Income->validates()) { //validate成功の処理
        $this->Income->save($this->request->data); //validate成功でsave
        if ($this->Income->save($id)) {
          $this->Session->setFlash('修正しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('修正できませんでした。', 'flashMessage');
        }
        $this->redirect('/incomes/fix/');
      } else { //validate失敗の処理
        $this->set('id', $this->request->data['Income']['id']); //viewに渡すために$idをセット
//        $this->render('index'); //validate失敗でindexを表示
      }
    }
  }

  public function fix_deleted($id = null){
    if (empty($id)) {
      throw new NotFoundException(__('存在しないデータです。'));
    }
    
    if ($this->request->is('post')) {
      $this->Income->Behaviors->enable('SoftDelete');
      if ($this->Income->delete($id)) {
        $this->Session->setFlash('削除しました。', 'flashMessage');
      } else {
        $this->Session->setFlash('削除できませんでした。', 'flashMessage');
      }
      $this->redirect('/incomes/fix/');
    }
  }
}