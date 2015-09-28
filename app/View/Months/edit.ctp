<h3>支出の修正</h3>

  <?php echo $this->Form->create('Expenditure', array( //使用するModel
      'type' => 'put', //変更はput
      'url' => array('controller' => 'months', 'action' => 'edit'), //Controller及びactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $id)); ?>
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => '支出名')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'label' => '金額')); ?><br>
  <?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => '種類', 'options' => $expenditure_genres)); ?><br>
  <?php echo $this->Form->input('status', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '未定', 1 => '確定'))); ?><br>
  
  <?php echo $this->Form->submit('修正する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3><?php echo date('Y年m月'); ?>の収支</h3>

  <table>
    <tr><td>収入</td><td><?php ?></td></tr>
    <tr><td>支出</td><td><?php ?></td></tr>
  </table>

<h3>支出内訳</h3>

<h3>支出一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table>
    <tr><th>日付</th><th>支出名</th><th>金額</th><th>種類</th><th>状態</th><th>action</th></tr>
    <?php for($i = 0; $i < $expenditure_counts; $i++){ ?>
    <tr><td><?php echo $expenditure_lists[$i]['Expenditure']['date']; ?></td>
        <td><?php echo $expenditure_lists[$i]['Expenditure']['title']; ?></td>
        <td><?php echo $expenditure_lists[$i]['Expenditure']['amount']; ?></td>
        <td><?php echo $expenditure_lists[$i]['ExpendituresGenre']['title']; ?></td>
        <td><?php if ($expenditure_lists[$i]['Expenditure']['status'] == 0) {echo '未定';}
              elseif ($expenditure_lists[$i]['Expenditure']['status'] == 1) {echo '確定';} ?></td>
        <td><?php echo $this->Form->postLink('修正', array('action' => 'edit', $expenditure_lists[$i]['Expenditure']['id'])); ?>
            <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $expenditure_lists[$i]['Expenditure']['id'])); ?></td></tr>
    <?php } ?>
  </table>