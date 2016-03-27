<?php echo $this->Html->script('jquery-insert', array('inline' => FALSE)); ?>
<?php if (preg_match('#/incomes/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
<h3>収入の修正</h3>
<?php } else { //登録用 ?>
<h3>収入の登録</h3>
<?php } ?>

  <?php if (preg_match('#/incomes/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <?php echo $this->Form->create('Income', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php } else { //登録用 ?>
    <?php echo $this->Form->create('Income', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?>
  <?php } ?><!-- form start -->
  
  <?php echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $login_id)); ?>
  
  <table>
    <tr><td class="label">収入名</td>
        <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 20, 'class' => 'js-insert_area')); ?></td>
        <td><button type="button" class="js-insert"><<</button>
            <?php echo $this->Form->input('word', array('type' => 'select', 'label' => false, 'options' => $word_lists, 'class' => 'js-insert_data')); ?></td></tr>
    <tr><td class="label">日付</td>
        <td><?php echo $this->Form->input('date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?></td></tr>
    <tr><td class="label">金額</td>
        <td><?php echo $this->Form->input('amount', array('type' => 'text', 'label' => false, 'size' => 18)); ?>円</td></tr>
    <tr><td class="label">種類</td>
        <td><?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => false, 'options' => $income_genres)); ?></td></tr>
    <tr><td class="label">状態</td>
        <td><?php echo $this->Form->input('status', array('type' => 'select', 'label' => false, 'options' => array(0 => '未定', 1 => '確定'))); ?></td></tr>
    
    <?php if (preg_match('#/incomes/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
    <tr><td></td>
        <td class="label"><?php echo $this->Form->submit('修正する'); ?></td></tr>
    <?php } else { //登録用 ?>
    <tr><td></td>
        <td class="label"><?php echo $this->Form->submit('登録する'); ?></td></tr>
    <?php } ?>
  </table>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>収入一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th class="tbl-date">日付<?php echo $this->Paginator->sort('Income.date', '▼'); ?></th>
        <th>収入名<?php echo $this->Paginator->sort('Income.title', '▼'); ?></th>
        <th class="tbl-num">金額<?php echo $this->Paginator->sort('Income.amount', '▼'); ?></th>
        <th class="tbl-genre">種類<?php echo $this->Paginator->sort('Income.genre_id', '▼'); ?></th>
        <th class="tbl-ico">状態<?php echo $this->Paginator->sort('Income.status', '▼'); ?></th>
        <th class="tbl-act">action</th></tr>
    <?php foreach($income_lists AS $income_list){ ?>
    <tr><td class="tbl-date"><?php echo $income_list['Income']['date']; ?></td>
        <td><?php echo $income_list['Income']['title']; ?></td>
        <td class="tbl-num"><?php echo $income_list['Income']['amount']; ?>円</td>
        <td class="tbl-genre"><span class="icon-genre col-i_<?php echo $income_list['Income']['genre_id']; ?>"><?php echo $income_list['IncomesGenre']['title']; ?></span></td>
        <td class="tbl-ico"><?php if ($income_list['Income']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                              elseif ($income_list['Income']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td>
        <td class="tbl-act"><?php echo $this->Html->link('修正', array('action' => 'edit', $income_list['Income']['id'])); ?>
                            <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $income_list['Income']['id']), null, $income_list['Income']['title'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>

<div>
  <?php echo $this->Form->create('Income', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'incomes', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
  <table>
    <tr>
      <td><?php echo $this->Form->input('search', array('type' => 'text', 'label' => '収入の検索')); ?></td>
      <td><?php echo $this->Form->submit('検索する'); ?></td>
    </tr>
  </table>
  <?php echo $this->Form->end(); ?>
</div>