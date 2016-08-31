<?php

App::uses('Model', 'Model');

class AppModel extends Model {

  public function exists($id = null) { //SoftDelete用の記述
      if ($this->Behaviors->attached('SoftDelete')) {
        return $this->existsAndNotDeleted($id);
      } else {
        return parent::exists($id);
      }
  }

  public function delete($id = null, $cascade = true) { //SoftDelete用の記述
      $result = parent::delete($id, $cascade);
      if ($result === false && $this->Behaviors->enabled('SoftDelete')) {
        return $this->field('deleted', array('deleted' => 1));
      }
      return $result;
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
