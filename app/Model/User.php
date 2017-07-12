<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth'); //パスワードのハッシュ化のため

class User extends AppModel
{
    public $useTable = 'users';
    public $actsAs = ['SoftDelete'/*, 'Search.Searchable'*/];
    
    public $validate = array(
        'username' => array(
            'rule' => 'notBlank',
            'required' => 'true',
            'message' => 'ユーザ名を正しく入力してください。'
        ),
        'password' => array(
            'rule' => 'notBlank',
            'required' => 'true',
            'message' => 'パスワードを正しく入力してください。'
        )
    );
    
    public function beforeSave($options = [])
    {
        //パスワードのハッシュ化のため
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        
        return true;
    }
    
//    public $filtetArgs = ['' => ['' => '', '' => '']];
    
//    public function getDbConfig()
//    {
//        //DBバックアップ作成のため
//        return ConnectionManager::getDataSource($this->useDbConfig);
//    }
}
