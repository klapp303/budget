<?php echo $this->Html->script('jquery-insert', array('inline' => false)); ?>
<?php if (preg_match('#/expenditures/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
  <h3>支出の修正</h3>
<?php } else { //登録用 ?>
  <h3>支出の登録</h3>
<?php } ?>

  <?php if (preg_match('#/expenditures/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->create('Expenditure', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php } else { //登録用 ?>
    <?php echo $this->Form->create('Expenditure', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
  <?php } ?><!-- form start -->
  
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $login_id)); ?>
  
  <table>
    <tr><td class="label">支出名</td>
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
        <td><?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => false, 'options' => $expenditure_genres, 'class' => 'js-insert_genre')); ?></td></tr>
    <tr><td class="label">状態</td>
        <td><?php echo $this->Form->input('status', array('type' => 'select', 'label' => false, 'options' => array(0 => '未定', 1 => '確定'))); ?></td></tr>
    
    <?php if (preg_match('#/expenditures/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <tr><td></td>
          <td class="label"><?php echo $this->Form->submit('修正する'); ?></td></tr>
    <?php } else { //登録用 ?>
      <tr><td></td>
          <td class="label"><?php echo $this->Form->submit('登録する'); ?></td></tr>
    <?php } ?>
  </table>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>支出一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>
  
  <table class="detail-list">
    <tr><th class="tbl-date">日付<?php echo $this->Paginator->sort('Expenditure.date', '▼'); ?></th>
        <th>支出名<?php echo $this->Paginator->sort('Expenditure.title', '▼'); ?></th>
        <th class="tbl-num">金額<?php echo $this->Paginator->sort('Expenditure.amount', '▼'); ?></th>
        <th class="tbl-genre">種類<?php echo $this->Paginator->sort('Expenditure.genre_id', '▼'); ?></th>
        <th class="tbl-ico">状態<?php echo $this->Paginator->sort('Expenditure.status', '▼'); ?></th>
        <th class="tbl-act">action</th></tr>
    
    <?php foreach($expenditure_lists as $expenditure_list){ ?>
      <tr><td class="tbl-date"><?php echo $expenditure_list['Expenditure']['date']; ?></td>
          <td><?php echo $expenditure_list['Expenditure']['title']; ?></td>
          <td class="tbl-num"><?php echo $expenditure_list['Expenditure']['amount']; ?>円</td>
          <td class="tbl-genre"><span class="icon-genre col-e_<?php echo $expenditure_list['Expenditure']['genre_id']; ?>"><?php echo $expenditure_list['ExpendituresGenre']['title']; ?></span></td>
          <td class="tbl-ico"><?php if ($expenditure_list['Expenditure']['status'] == 0) {
                                  echo '<span class="icon-false">未定</span>';
                              } elseif ($expenditure_list['Expenditure']['status'] == 1) {
                                  echo '<span class="icon-true">確定</span>';
                              } ?></td>
          <td class="tbl-act"><?php echo $this->Html->link('修正', array('action' => 'edit', $expenditure_list['Expenditure']['id'])); ?>
                              <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $expenditure_list['Expenditure']['id']), null, $expenditure_list['Expenditure']['title'] . 'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>

<div>
  <?php echo $this->Form->create('Expenditure', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'expenditures', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  <table>
    <tr>
      <td><?php echo $this->Form->input('search', array('type' => 'text', 'label' => '支出の検索')); ?></td>
      <td><?php echo $this->Form->submit('検索する'); ?></td>
    </tr>
  </table>
  <?php echo $this->Form->end(); ?>
</div>