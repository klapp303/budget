<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public $uses = array('User'); //使用するModel

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'budget_fullwidth';
      //$this->Income->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
      // ユーザ自身による登録とログアウトを許可する
      $this->Auth->allow('add', 'logout');
  }

  public function login() {
      $this->layout = 'budget_login';
  
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
      if ($this->Auth->user()) {
        $user_data = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
        $this->set('user_data', $user_data);
      } else {
        $this->redirect('/users/login/');
      }
  }

  public function add() {
      if ($this->Auth->user('id') != $this->admin_id) {
        $this->redirect('/');
      }
  
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

  public function edit() {
      $id = $this->Auth->user('id');
      
      if (empty($this->request->data)) {
        $this->request->data = $this->User->findById($id); //postデータがなければ$idからデータを取得
      } else {
        $this->User->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->User->validates()) { //validate成功の処理
          $this->User->save($this->request->data); //validate成功でsave
          if ($this->User->save($id)) {
            $this->Session->setFlash('変更しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('変更できませんでした。', 'flashMessage');
          }
          $this->redirect('/users/');
        } else { //validate失敗の処理
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  }

  public function password() {
      $id = $this->Auth->user('id');
      
      if ($this->request->is('post')) {
        $this->User->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->User->validates()) { //validate成功の処理
          $this->User->save($this->request->data); //validate成功でsave
          if ($this->User->save($id)) {
            $this->Session->setFlash('変更しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('変更できませんでした。', 'flashMessage');
          }
          $this->redirect('/users/');
        } else { //validate失敗の処理
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  }

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