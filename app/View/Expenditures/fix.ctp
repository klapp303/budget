<?php if (preg_match('#/expenditures/fix_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
<h3>確定待ちの支出の修正</h3>

  <?php echo $this->Form->create('Expenditure', array( //使用するModel
      'type' => 'put', //変更はput
      'action' => 'fix_edit', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  
  <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $login_id)); ?>
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => '支出名')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額')); ?>円<br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $expenditure_genres)); ?>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '確定'))); ?><br>
  
  <?php echo $this->Form->submit('修正する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->
<?php } ?>

<h3>確定待ちの支出一覧</h3>

  <?php if ($expenditure_unfixed_counts == 0) { ?>
    <div class="msg_fix">
      <?php echo '確定待ちの支出はありません。'; ?>
    </div>
  <?php } else { ?>
    <table class="tbl_fix">
      <tr><th>日付<?php echo $this->Paginator->sort('Expenditure.date', '▼'); ?></th>
          <th>支出名<?php echo $this->Paginator->sort('Expenditure.title', '▼'); ?></th>
          <th class="tbl-num">金額<?php echo $this->Paginator->sort('Expenditure.amount', '▼'); ?></th>
          <th class="tbl-ico">種類<?php echo $this->Paginator->sort('Expenditure.genre_id', '▼'); ?></th>
          <th>action</th></tr>
      <?php for($i = 0; $i < $expenditure_unfixed_counts; $i++){ ?>
      <tr><td><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['date']; ?></td>
          <td><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['title']; ?></td>
          <td class="tbl-num"><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['amount']; ?>円</td>
          <td class="tbl-ico"><span class="icon-genre col-e_<?php echo $expenditure_unfixed_lists[$i]['Expenditure']['genre_id']; ?>"><?php echo $expenditure_unfixed_lists[$i]['ExpendituresGenre']['title']; ?></span></td>
          <td><?php echo $this->Html->link('修正', array('action' => 'fix_edit', $expenditure_unfixed_lists[$i]['Expenditure']['id'])); ?>
              <?php echo $this->Form->postLink('削除', array('action' => 'fix_deleted', $expenditure_unfixed_lists[$i]['Expenditure']['id']), null, $expenditure_unfixed_lists[$i]['Expenditure']['title'].'を削除しますか'); ?></td></tr>
      <?php } ?>
    </table>
  <?php } ?>