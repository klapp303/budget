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
}