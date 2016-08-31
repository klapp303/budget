<?php

App::uses('AppModel', 'Model');

class Word extends AppModel {

  public $useTable = 'words';
//  public $actsAs = ['SoftDelete', 'Search.Searchable'];

//  public $filtetArgs = ['' => ['' => '', '' => '']];

  public function getIncomeWords($user_id = null) {
      $datas = $this->find('all', array(
          'conditions' => array(
              'Word.user_id' => $user_id,
              'Word.category' => 'income'
          )
      ));
      $word_lists = array();
      foreach ($datas AS $data) {
        $MM = $data['Word']['strtotime'] + 1;
        $word_lists += array(
            str_replace(
                    array('%M%', '%MM%'),
                    array(
                        date('n', strtotime($data['Word']['strtotime'].' month')),
                        date('n', strtotime($data['Word']['strtotime'].' month')).','.date('n', strtotime($MM.' month'))
                    ),
                    $data['Word']['data']
            ) => $data['Word']['title']);
      }
      $word_lists = array('' => ($word_lists)? '選択してください': '登録してください') + $word_lists;
  
      return $word_lists;
  }

  public function getExpenditureWords($user_id = null) {
      $datas = $this->find('all', array(
          'conditions' => array(
              'Word.user_id' => $user_id,
              'Word.category' => 'expenditure'
          )
      ));
      $word_lists = array();
      foreach ($datas AS $data) {
        $MM = $data['Word']['strtotime'] + 1;
        $word_lists += array(
            str_replace(
                    array('%M%', '%MM%'),
                    array(
                        date('n', strtotime($data['Word']['strtotime'].' month')),
                        date('n', strtotime($data['Word']['strtotime'].' month')).','.date('n', strtotime($MM.' month'))
                    ),
                    $data['Word']['data']
            ) => $data['Word']['title']);
      }
      $word_lists = array('' => ($word_lists)? '選択してください': '登録してください') + $word_lists;
  
      return $word_lists;
  }

  public function addMonth($date = false, $add_month = 1) {
      if (!$date) {
          $date = date('Y-m-d');
      }
      
      //年月
      $date_1st = date('Y-m-01', strtotime($date));
      $new_Ym = date('Y-m', strtotime($date_1st.' '.$add_month.' month'));
      
      //日
      $day = date('d', strtotime($date));
      $max_day = date('t', strtotime($new_Ym));
      //末日をオーバーする場合のみその月の末日を返す
      if ($day <= $max_day) {
          $new_d = $day;
      } else {
          $new_d = $max_day;
      }
      
      return $new_Ym.'-'.$new_d;
  }
}
