<?php

App::uses('AppController', 'Controller');

class IncomesController extends AppController {

	public $uses = array('Income', 'IncomesGenre', 'User', 'Word'); //使用するModel

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
      //$this->Income->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      //管理者画面のためにユーザID一覧を取得しておく
      $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
      //テンプレタイトルの一覧を取得
      $this->set('word_lists', $this->Word->getIncomeWords($this->Auth->user('id')));
  
      $this->Paginator->settings = array(
          'conditions' => array(
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Income.date' => 'desc')
      );
      $income_lists = $this->Paginator->paginate('Income');
      $income_genres = $this->IncomesGenre->find('list', array('fields' => array('id', 'title')));
      $login_id = $this->Auth->user('id');
      $this->set(compact('income_lists', 'income_genres', 'login_id'));
  }

  public function add() {
      if ($this->request->is('post')) {
        $this->Income->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Income->validates()) { //validate成功の処理
          $this->Income->save($this->request->data); //validate成功でsave
          if ($this->Income->save($this->request->data)) {
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
      //管理者画面のためにユーザID一覧を取得しておく
      $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
      //テンプレタイトルの一覧を取得
      $this->set('word_lists', $this->Word->getIncomeWords($this->Auth->user('id')));
  
      $this->Paginator->settings = array(
          'conditions' => array(
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Income.date' => 'desc')
      );
      $income_lists = $this->Paginator->paginate('Income');
      $income_genres = $this->IncomesGenre->find('list', array('fields' => array('id', 'title')));
      $login_id = $this->Auth->user('id');
      $this->set(compact('income_lists', 'income_genres', 'login_id'));
  
      if (empty($this->request->data)) {
        $this->request->data = $this->Income->findById($id); //postデータがなければ$idからデータを取得
        /* user_idによる処理ここから */
        if ($this->request->data['Income']['user_id'] != $this->Auth->user('id') && $this->Auth->user('id') != $this->admin_id) {
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
          $this->redirect('/incomes/');
        }
        /* user_idによる処理ここまで */
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
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  
      $this->render('index');
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
      //管理者画面のためにユーザID一覧を取得しておく
      $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
      $this->Paginator->settings = array(
          'conditions' => array(
            'Income.status' => 0,
            'Income.date <=' => date('Y-m-d'),
            'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Income.date' => 'asc')
      );
      $income_unfixed_lists = $this->Paginator->paginate('Income');
      $income_unfixed_counts = count($income_unfixed_lists);
      $this->set(compact('income_unfixed_lists', 'income_unfixed_counts'));
  }

  public function fix_edit($id = null) {
      //管理者画面のためにユーザID一覧を取得しておく
      $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
      $this->Paginator->settings = array(
          'conditions' => array(
            'Income.status' => 0,
            'Income.date <=' => date('Y-m-d'),
            'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Income.date' => 'asc')
      );
      $income_unfixed_lists = $this->Paginator->paginate('Income');
      $income_unfixed_counts = count($income_unfixed_lists);
      $income_genres = $this->IncomesGenre->find('list', array('fields' => array('id', 'title')));
      $login_id = $this->Auth->user('id');
      $this->set(compact('income_unfixed_lists', 'income_unfixed_counts', 'income_genres', 'login_id'));
  
      if (empty($this->request->data)) {
        $this->request->data = $this->Income->findById($id); //postデータがなければ$idからデータを取得
        /* user_idによる処理ここから */
        if ($this->request->data['Income']['user_id'] != $this->Auth->user('id') && $this->Auth->user('id') != $this->admin_id) {
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
          $this->redirect('/incomes/fix/');
        }
        /* user_idによる処理ここまで */
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
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  
      $this->render('fix');
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

  public function search() {
      //管理者画面のためにユーザID一覧を取得しておく
      $array_users = $this->User->find('list', array('fields' => 'User.id'));
  
      $income_genres = $this->IncomesGenre->find('list', array('fields' => array('id', 'title')));
      $login_id = $this->Auth->user('id');
      $this->set(compact('income_genres', 'login_id'));
      
      $this->Income->recursive = 0;
      $this->Prg->commonProcess('Income');
      //$this->Prg->parsedParams();
      $this->Paginator->settings = array(
          'limit' => 20,
          'conditions' => array(
              $this->Income->parseCriteria($this->passedArgs),
              'Income.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users: $this->Auth->user('id')
          ),
          'order' => array('Income.id' => 'desc')
      );
      $income_lists = $this->Paginator->paginate('Income');
      if (!empty($income_lists)) { //データが存在する場合
        $this->set('income_lists', $income_lists);
      } else { //データが存在しない場合
        $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        $this->redirect('/incomes/');
      }
  
      $this->render('index');  
    }
}