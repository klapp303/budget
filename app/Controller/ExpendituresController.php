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

  public $components = array(
      'Paginator',
      'Search.Prg' => array(
          'commonProcess' => array(
              'paramType' => 'querystring',
              'filterEmpty' => true
          )
      )
  );
  public $paginate = array(
      'limit' => 20,
      'order' => array('date' => 'desc')
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->layout = 'budget_fullwidth';
    //$this->Expenditure->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    $this->admin_id = 1;
  }

  public function index() {
//    $expenditure_lists = $this->Expenditure->find('all', array(
//        'order' => array('date' => 'desc')
//    ));
    if ($this->Auth->user('id') == $this->admin_id) { //管理者アカウントの場合
      $this->Paginator->settings = $this->paginate;
    } else {
      $this->Paginator->settings = array(
          'conditions' => array('Expenditure.user_id' => $this->Auth->user('id')),
          'order' => array('Expenditure.date' => 'desc')
      );
    }
    $expenditure_lists = $this->Paginator->paginate('Expenditure');
    $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $login_id = $this->Auth->user('id');
    $this->set(compact('expenditure_lists', 'expenditure_genres', 'login_id'));
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->Expenditure->set($this->request->data); //postデータがあればModelに渡してvalidate
      if ($this->Expenditure->validates()) { //validate成功の処理
        $this->Expenditure->save($this->request->data); //validate成功でsave
        if ($this->Expenditure->save($this->request->data)) {
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
    if ($this->Auth->user('id') == $this->admin_id) { //管理者アカウントの場合
      $this->Paginator->settings = $this->paginate;
    } else {
      $this->Paginator->settings = array(
          'conditions' => array('Expenditure.user_id' => $this->Auth->user('id')),
          'order' => array('Expenditure.date' => 'desc')
      );
    }
    $expenditure_lists = $this->Paginator->paginate('Expenditure');
    $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $login_id = $this->Auth->user('id');
    $this->set(compact('expenditure_lists', 'expenditure_genres', 'login_id'));

    if (empty($this->request->data)) {
      $this->request->data = $this->Expenditure->findById($id); //postデータがなければ$idからデータを取得
      /* user_idによる処理ここから */
      if ($this->request->data['Expenditure']['user_id'] != $this->Auth->user('id') && $this->Auth->user('id') != $this->admin_id) {
        $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        $this->redirect('/expenditures/');
      }
      /* user_idによる処理ここまで */
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
    
    $this->render('index');
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

  public function fix() {
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
//    $this->Paginator->settings = $this->paginate;
//    $expenditure_unfixed_lists = $this->Paginator->paginate('Expenditure');
    $expenditure_unfixed_counts = count($expenditure_unfixed_lists);
    $this->set(compact('expenditure_unfixed_lists', 'expenditure_unfixed_counts'));
  }

  public function fix_edit($id = null) {
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
//    $this->Paginator->settings = $this->paginate;
//    $expenditure_unfixed_lists = $this->Paginator->paginate('Expenditure');
    $expenditure_unfixed_counts = count($expenditure_unfixed_lists);
    $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $login_id = $this->Auth->user('id');
    $this->set(compact('expenditure_unfixed_lists', 'expenditure_unfixed_counts', 'expenditure_genres', 'login_id'));

    if (empty($this->request->data)) {
      $this->request->data = $this->Expenditure->findById($id); //postデータがなければ$idからデータを取得
      /* user_idによる処理ここから */
      if ($this->request->data['Expenditure']['user_id'] != $this->Auth->user('id') && $this->Auth->user('id') != $this->admin_id) {
        $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        $this->redirect('/expenditures/fix/');
      }
      /* user_idによる処理ここまで */
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
        $this->redirect('/expenditures/fix/');
      } else { //validate失敗の処理
        $this->set('id', $this->request->data['Expenditure']['id']); //viewに渡すために$idをセット
//        $this->render('index'); //validate失敗でindexを表示
      }
    }
    
    $this->render('fix');
  }

  public function fix_deleted($id = null){
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
      $this->redirect('/expenditures/fix/');
    }
  }

  public function search() {
    $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
    $login_id = $this->Auth->user('id');
    $this->set(compact('expenditure_genres', 'login_id'));
    
    $this->Expenditure->recursive = 0;
    $this->Prg->commonProcess('Expenditure');
    //$this->Prg->parsedParams();
    if ($this->Auth->user('id') == $this->admin_id) { //管理者アカウントの場合
      $this->Paginator->settings = array(
          'limit' => 20,
          'conditions' => array(
              $this->Expenditure->parseCriteria($this->passedArgs)
          ),
          'order' => array('Expenditure.id' => 'desc')
      );
    } else {
      $this->Paginator->settings = array(
          'limit' => 20,
          'conditions' => array(
              $this->Expenditure->parseCriteria($this->passedArgs),
              'Expenditure.user_id' => $this->Auth->user('id')
          ),
          'order' => array('Expenditure.id' => 'desc')
      );
    }
    $expenditure_lists = $this->Paginator->paginate('Expenditure');
    if (!empty($expenditure_lists)) { //データが存在する場合
      $this->set('expenditure_lists', $expenditure_lists);
    } else { //データが存在しない場合
      $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
      $this->redirect('/expenditures/');
    }
      
    $this->render('index');  
  }
}