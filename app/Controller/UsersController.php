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
class UsersController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('User'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public function beforeFilter() {
    parent::beforeFilter();
    $this->layout = 'budget_login';
    // ユーザ自身による登録とログアウトを許可する
    $this->Auth->allow(/*'add', */'logout');
  }

  public function login() {
      if ($this->request->is('post')) {
        if ($this->Auth->login()) {
          $this->redirect($this->Auth->redirect());
        } else {
          $this->Flash->error(__('ユーザ名かパスワードが間違っています。'));
        }
      }
  }

  public function logout() {
      $this->redirect($this->Auth->logout());
  }

  public function index() {
      $this->redirect('/users/login/');
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->User->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->User->validates()) { //validate成功の処理
        $this->User->save($this->request->data); //validate成功でsave
        if ($this->User->save($this->request->data)) {
          $this->Session->setFlash('登録しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('登録できませんでした。', 'flashMessage');
        }
      } else { //validate失敗の処理
        $this->render('index'); //validate失敗でindexを表示
      }
    }
  }

/*  public function edit($id = null) {
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
  }*/

/*  public function deleted($id = null){
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
  }*/
}