<?php

App::uses('AppModel', 'Model');

/**
 * Income Model.
 */
class Income extends AppModel {
  public $useTable = 'Incomes';
  public $actAs = ['SoftDelete'/*, 'Search.Searchable'*/];

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'true'
      ),
      'amount' => array(
          'rule' => 'numeric',
          'required' => 'true',
          'message' => '金額を正しく入力してください。'
      )
  );

//  public $filtetArgs = ['' => ['' => '', '' => '']];
}