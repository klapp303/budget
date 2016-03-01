<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

  public $components = array(
      'Session', //Paginateのため記述
      'Flash', //ここからログイン認証用
      'Auth' => array(
          'loginRedirect' => array(
              'controller' => 'top',
              'action' => 'index'
          ),
          'logoutRedirect' => array(
              'controller' => 'users',
              'action' => 'login'
          ),
          'authenticate' => array(
              'Form' => array('passwordHasher' => 'Blowfish')
          )
      ),
      'DebugKit.Toolbar' //ページ右上の開発用デバッグツール
  );

  public function beforeFilter() {
      //$this->Auth->allow('index'); //認証なしのページを設定
  
      $this->admin_id = 1;
  }
}