<h3>収入の登録</h3>

  <?php echo $this->Form->create('Income', array( //使用するModel
      'type' => 'post',
      'action' => 'add',
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => '収入名')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額')); ?><br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $income_genres)); ?><br>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '確定'))); ?><br>
  
  <?php echo $this->Form->submit('登録する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>最近の収入</h3>

  <?php echo '<table><tr><th>日付</th><th>収入名</th><th>金額</th><th>種類</th><th>状態</th><th>action</th></tr>'; ?>
  <?php for($i = 0; $i < $income_counts; $i++){
    echo '<tr><td>'.$income_lists[$i]['Income']['date'].'</td>
              <td>'.$income_lists[$i]['Income']['title'].'</td>
              <td>'.$income_lists[$i]['Income']['amount'].'</td>
              <td>'.$income_lists[$i]['Income']['genre_id'].'</td>
              <td>'.$income_lists[$i]['Income']['status'].'</td>
              <td>'.'削除'.'</td></tr>';
  } ?>
  <?php echo '</table>'; ?>