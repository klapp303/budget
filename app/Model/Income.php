<?php

App::uses('AppModel', 'Model');

/**
 * Income Model.
 */
class Income extends AppModel {
  public $useTable = 'Incomes';
  public $actAs = ['SoftDelete'/*, 'Search.Searchable'*/];

//  public $filtetArgs = ['' => ['' => '', '' => '']];
}