<h3>収入の修正</h3>

  <?php echo $this->Form->create('Income', array( //使用するModel
      'type' => 'put', //変更はput
      'action' => 'fix_edit', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => '収入名')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額')); ?>円<br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $income_genres)); ?>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '確定'))); ?><br>
  
  <?php echo $this->Form->submit('修正する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>確定待ち収入一覧</h3>

  <?php if ($income_unfixed_counts == 0) { ?>
    <?php echo '確定待ちの収入はありません。'; ?>
  <?php } else { ?>
    <table>
      <tr><th>日付</th><th>収入名</th><th>金額</th><th>種類</th><th>状態</th><th>action</th></tr>
      <?php for($i = 0; $i < $income_unfixed_counts; $i++){ ?>
      <tr><td><?php echo $income_unfixed_lists[$i]['Income']['date']; ?></td>
          <td><?php echo $income_unfixed_lists[$i]['Income']['title']; ?></td>
          <td><?php echo $income_unfixed_lists[$i]['Income']['amount']; ?>円</td>
          <td><?php echo $income_unfixed_lists[$i]['IncomesGenre']['title']; ?></td>
          <td><?php if ($income_unfixed_lists[$i]['Income']['status'] == 0) {echo '未定';}
                elseif ($income_unfixed_lists[$i]['Income']['status'] == 1) {echo '確定';} ?></td>
          <td><?php echo $this->Form->postLink('修正', array('action' => 'fix_edit', $income_unfixed_lists[$i]['Income']['id'])); ?>
              <?php echo $this->Form->postLink('削除', array('action' => 'fix_deleted', $income_unfixed_lists[$i]['Income']['id'])); ?></td></tr>
      <?php } ?>
    </table>
  <?php } ?>