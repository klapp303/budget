<h3>収入の登録</h3>

  <?php echo $this->Form->create('Income', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => '収入名')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額')); ?>円<br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $income_genres)); ?>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '確定'))); ?><br>
  
  <?php echo $this->Form->submit('登録する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>収入一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list">
    <tr><th>日付</th><th>収入名</th><th class="tbl-num">金額</th><th class="tbl-ico">種類</th><th class="tbl-ico">状態</th><th>action</th></tr>
    <?php for($i = 0; $i < $income_counts; $i++){ ?>
    <tr><td><?php echo $income_lists[$i]['Income']['date']; ?></td>
        <td><?php echo $income_lists[$i]['Income']['title']; ?></td>
        <td class="tbl-num"><?php echo $income_lists[$i]['Income']['amount']; ?>円</td>
        <td class="tbl-ico"><span class="icon-genre col-i_<?php echo $income_lists[$i]['Income']['genre_id']; ?>"><?php echo $income_lists[$i]['IncomesGenre']['title']; ?></span></td>
        <td class="tbl-ico"><?php if ($income_lists[$i]['Income']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                              elseif ($income_lists[$i]['Income']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td>
        <td><?php echo $this->Form->postLink('修正', array('action' => 'edit', $income_lists[$i]['Income']['id'])); ?>
            <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $income_lists[$i]['Income']['id'])); ?></td></tr>
    <?php } ?>
  </table>