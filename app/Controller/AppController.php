<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用

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
      $this->set('userData', $this->Auth->user());

      //paginatorのオプションを定義しておく
      $paginator_option = array(
          'modulus' => 4, //現在ページから左右あわせてインクルードする個数
          'separator' => ' | ', //デフォルト値のセパレーター
          'first' => '＜', //先頭ページへのリンク
          'last' => '＞' //最終ページへのリンク
      );
      $this->set('paginator_option', $paginator_option);
  }
}
