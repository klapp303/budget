<?php

App::uses('AppController', 'Controller');

class ExpendituresController extends AppController
{
    public $uses = array('Expenditure', 'ExpendituresGenre', 'User', 'Word'); //使用するModel
    
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
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'budget_fullwidth';
//        $this->Expenditure->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        //管理者画面のためにユーザID一覧を取得しておく
        $array_users = $this->User->find('list', array('fields' => 'User.id'));
        
        //テンプレワードの一覧を取得
        $this->set('word_lists', $this->Word->getWordLists($this->Auth->user('id'), 'expenditure'));
        
        $this->Paginator->settings = array(
            'conditions' => array(
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users : $this->Auth->user('id')
            ),
            'order' => array('Expenditure.date' => 'desc', 'Expenditure.title' => 'asc')
        );
        $expenditure_lists = $this->Paginator->paginate('Expenditure');
        $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
        $login_id = $this->Auth->user('id');
        $this->set(compact('expenditure_lists', 'expenditure_genres', 'login_id'));
    }
    
    public function add()
    {
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
    
    public function edit($id = null)
    {
        //管理者画面のためにユーザID一覧を取得しておく
        $array_users = $this->User->find('list', array('fields' => 'User.id'));
        
        //テンプレワードの一覧を取得
        $this->set('word_lists', $this->Word->getWordLists($this->Auth->user('id'), 'expenditure'));
        
        $this->Paginator->settings = array(
            'conditions' => array(
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users : $this->Auth->user('id')
            ),
            'order' => array('Expenditure.date' => 'desc', 'Expenditure.title' => 'asc')
        );
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
//                $this->render('index'); //validate失敗でindexを表示
            }
        }
        
        $this->render('index');
    }
    
    public function deleted($id = null)
    {
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
    
    public function fix()
    {
        //管理者画面のためにユーザID一覧を取得しておく
        $array_users = $this->User->find('list', array('fields' => 'User.id'));
        
        $this->Paginator->settings = array(
            'conditions' => array(
                'Expenditure.status' => 0,
                'Expenditure.date <' => date('Y-m-d'),
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users : $this->Auth->user('id')
            ),
            'order' => array('Expenditure.date' => 'asc', 'Expenditure.title' => 'asc')
        );
        $expenditure_unfixed_lists = $this->Paginator->paginate('Expenditure');
        $this->set(compact('expenditure_unfixed_lists'));
    }
    
    public function fix_edit($id = null)
    {
        //管理者画面のためにユーザID一覧を取得しておく
        $array_users = $this->User->find('list', array('fields' => 'User.id'));
        
        //テンプレワードの一覧を取得
        $this->set('word_lists', $this->Word->getWordLists($this->Auth->user('id'), 'expenditure'));
        
        $this->Paginator->settings = array(
            'conditions' => array(
                'Expenditure.status' => 0,
                'Expenditure.date <' => date('Y-m-d'),
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users : $this->Auth->user('id')
            ),
            'order' => array('Expenditure.date' => 'asc', 'Expenditure.title' => 'asc')
        );
        $expenditure_unfixed_lists = $this->Paginator->paginate('Expenditure');
        $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
        $login_id = $this->Auth->user('id');
        $this->set(compact('expenditure_unfixed_lists', 'expenditure_genres', 'login_id'));
        
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
//                $this->render('index'); //validate失敗でindexを表示
            }
        }
        
        $this->render('fix');
    }
    
    public function fix_deleted($id = null)
    {
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
    
    public function search()
    {
        //管理者画面のためにユーザID一覧を取得しておく
        $array_users = $this->User->find('list', array('fields' => 'User.id'));
        
        //テンプレワードの一覧を取得
        $this->set('word_lists', $this->Word->getWordLists($this->Auth->user('id'), 'expenditure'));
        
        $expenditure_genres = $this->ExpendituresGenre->find('list', array('fields' => array('id', 'title')));
        $login_id = $this->Auth->user('id');
        $this->set(compact('expenditure_genres', 'login_id'));
        
        /* search wordを整形ここから */
        $search_query = @$this->request->query['search'];
        $search_word = str_replace('　', ' ', $search_query); //and検索用
        $search_word = str_replace(' OR ', '|', $search_word); //or検索用
        $this->request->query['search'] = $search_word;
        /* search wordを整形ここまで */
        $this->Expenditure->recursive = 0;
        $this->Prg->commonProcess('Expenditure');
//        $this->Prg->parsedParams();
        $this->Paginator->settings = array(
            'limit' => 20,
            'conditions' => array(
                $this->Expenditure->parseCriteria($this->passedArgs),
                'Expenditure.user_id' => ($this->Auth->user('id') == $this->admin_id)? $array_users : $this->Auth->user('id')
            ),
            'order' => array('Expenditure.id' => 'desc', 'Expenditure.title' => 'asc')
        );
        $expenditure_lists = $this->Paginator->paginate('Expenditure');
        $this->request->query['search'] = $search_query; //search wordを戻しておく
        if (!empty($expenditure_lists)) { //データが存在する場合
            $this->set('expenditure_lists', $expenditure_lists);
            
        } else { //データが存在しない場合
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            $this->redirect('/expenditures/');
        }
        
        $this->render('index');
    }
}
