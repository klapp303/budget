<?php echo $this->Html->script('jquery-insert', array('inline' => FALSE)); ?>
<?php
  $word_lists = array(
      '' => '',
      'コンビニ(会社)' => 'コンビニ(会社)',
      '交通費(Suica)' => '交通費(Suica)',
      '家賃'.date('n', strtotime('+3 month')).'月分' => '家賃',
      '電気料金'.date('n').'月分' => '電気料金',
      'ガス料金'.date('n', strtotime('+1 month')).'月分' => 'ガス料金',
      '水道料金'.date('n').','.date('n', strtotime('+1 month')).'月分' => '水道料金',
      'ネット料金'.date('n', strtotime('+1 month')).'月分' => 'ネット料金',
      '電話料金'.date('n').'月分' => '電話料金',
      'スマホ料金'.date('n').'月分' => 'スマホ料金'
  );
?>
<?php if (preg_match('#/expenditures/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
<h3>支出の修正</h3>
<?php } else { //登録用 ?>
<h3>支出の登録</h3>
<?php } ?>

  <div class="word-insert">
    <?php echo $this->Form->input('word', array('type' => 'select', 'label' => false,'options' => $word_lists, 'class' => 'js-insert_data')); ?>
    <button class="js-insert">挿入する</button>
  </div>
  
  <?php if (preg_match('#/expenditures/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->create('Expenditure', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php } else { //登録用 ?>
    <?php echo $this->Form->create('Expenditure', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?>
  <?php } ?><!-- form start -->
  
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $login_id)); ?>
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => '支出名', 'class' => 'js-insert_area')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額')); ?>円<br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $expenditure_genres)); ?>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '確定'))); ?><br>
  
  <?php if (preg_match('#/expenditures/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->submit('修正する'); ?>
  <?php } else { //登録用 ?>
    <?php echo $this->Form->submit('登録する'); ?>
  <?php } ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>支出一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

<table class="detail-list">
  <tr><th>日付<?php echo $this->Paginator->sort('Expenditure.date', '▼'); ?></th>
      <th>支出名<?php echo $this->Paginator->sort('Expenditure.title', '▼'); ?></th>
      <th class="tbl-num">金額<?php echo $this->Paginator->sort('Expenditure.amount', '▼'); ?></th>
      <th class="tbl-ico">種類<?php echo $this->Paginator->sort('Expenditure.genre_id', '▼'); ?></th>
      <th class="tbl-ico">状態<?php echo $this->Paginator->sort('Expenditure.status', '▼'); ?></th>
      <th>action</th></tr>
    <?php foreach($expenditure_lists AS $expenditure_list){ ?>
    <tr><td><?php echo $expenditure_list['Expenditure']['date']; ?></td>
        <td><?php echo $expenditure_list['Expenditure']['title']; ?></td>
        <td class="tbl-num"><?php echo $expenditure_list['Expenditure']['amount']; ?>円</td>
        <td class="tbl-ico"><span class="icon-genre col-e_<?php echo $expenditure_list['Expenditure']['genre_id']; ?>"><?php echo $expenditure_list['ExpendituresGenre']['title']; ?></span></td>
        <td class="tbl-ico"><?php if ($expenditure_list['Expenditure']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                              elseif ($expenditure_list['Expenditure']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td>
        <td><?php echo $this->Html->link('修正', array('action' => 'edit', $expenditure_list['Expenditure']['id'])); ?>
            <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $expenditure_list['Expenditure']['id']), null, $expenditure_list['Expenditure']['title'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>

<div>
  <?php echo $this->Form->create('Expenditure', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'expenditures', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
  <table>
    <tr>
      <td><?php echo $this->Form->input('search', array('type' => 'text', 'label' => '支出の検索')); ?></td>
      <td><?php echo $this->Form->submit('検索する'); ?></td>
    </tr>
  </table>
  <?php echo $this->Form->end(); ?>
</div>