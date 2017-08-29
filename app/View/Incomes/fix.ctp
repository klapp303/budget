<?php echo $this->Html->script('jquery-insert', array('inline' => false)); ?>
<?php if (preg_match('#/incomes/fix_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
  <h3>確定待ちの収入の修正</h3>
  
    <?php echo $this->Form->create('Income', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'fix_edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
    <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $login_id)); ?>
    
    <table>
      <tr><td class="label">収入名</td>
          <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 20, 'class' => 'js-insert_value')); ?></td>
          <td><button type="button" class="js-insert"><<</button>
              <select class="js-insert_data">
                <?php foreach ($word_lists as $word): ?>
                <option value="<?php echo $word['value'] . ':' . $word['amount'] . ':' . $word['genre_id']; ?>"><?php echo $word['title']; ?></option>
                <?php endforeach; ?>
              </select></td></tr>
      <tr><td class="label">日付</td>
          <td><?php echo $this->Form->input('date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?></td></tr>
      <tr><td class="label">金額</td>
          <td><?php echo $this->Form->input('amount', array('type' => 'text', 'label' => false, 'size' => 18, 'class' => 'js-insert_amount')); ?>円</td></tr>
      <tr><td class="label">種類</td>
          <td><?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => false, 'options' => $income_genres, 'class' => 'js-insert_genre')); ?></td></tr>
      <tr><td class="label">状態</td>
          <td><?php echo $this->Form->input('status', array('type' => 'select', 'label' => false, 'options' => array(0 => '未定', 1 => '確定'))); ?></td></tr>
      
      <tr><td></td>
          <td class="label"><?php echo $this->Form->submit('修正する'); ?></td></tr>
    </table>
    <?php echo $this->Form->end(); ?><!-- form end -->
<?php } ?>

<h3>確定待ちの収入一覧</h3>

  <?php if (count($income_unfixed_lists) == 0) { ?>
    <div class="msg_fix">
      <?php echo '確定待ちの収入はありません。'; ?>
    </div>
  <?php } else { ?>
    <table class="tbl_fix">
      <tr><th class="tbl-date">日付<?php echo $this->Paginator->sort('Income.date', '▼'); ?></th>
          <th>収入名<?php echo $this->Paginator->sort('Income.title', '▼'); ?></th>
          <th class="tbl-num">金額<?php echo $this->Paginator->sort('Income.amount', '▼'); ?></th>
          <th class="tbl-genre">種類<?php echo $this->Paginator->sort('Income.genre_id', '▼'); ?></th>
          <th class="tbl-act">action</th></tr>
      
      <?php foreach($income_unfixed_lists as $income_unfixed_list){ ?>
        <tr><td class="tbl-date"><?php echo $income_unfixed_list['Income']['date']; ?></td>
            <td><?php echo $income_unfixed_list['Income']['title']; ?></td>
            <td class="tbl-num"><?php echo $income_unfixed_list['Income']['amount']; ?>円</td>
            <td class="tbl-genre"><span class="icon-genre col-i_<?php echo $income_unfixed_list['Income']['genre_id']; ?>"><?php echo $income_unfixed_list['IncomesGenre']['title']; ?></span></td>
            <td class="tbl-act"><?php echo $this->Html->link('修正', array('action' => 'fix_edit', $income_unfixed_list['Income']['id'])); ?>
                                <?php echo $this->Form->postLink('削除', array('action' => 'fix_deleted', $income_unfixed_list['Income']['id']), null, $income_unfixed_list['Income']['title'] . 'を削除しますか'); ?></td></tr>
      <?php } ?>
    </table>
  <?php } ?>