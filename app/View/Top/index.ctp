<h3><?php echo date('Y年m月d日'); ?>現在の収支情報</h3>

  <?php echo '現在の貯金'; ?><br>
  <?php echo '次回給料日までの出費'; ?>

<h3>直近の支出</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <?php echo '<table><tr><th>日付</th><th>支出名</th><th>金額</th><th>種類</th><th>状態</th></tr>'; ?>
  <?php for($i = 0; $i < $expenditure_counts; $i++){ ?>
    <tr><td><?php echo $expenditure_lists[$i]['Expenditure']['date']; ?></td>
        <td><?php echo $expenditure_lists[$i]['Expenditure']['title']; ?></td>
        <td><?php echo $expenditure_lists[$i]['Expenditure']['amount']; ?></td>
        <td><?php echo $expenditure_lists[$i]['ExpendituresGenre']['title']; ?></td>
        <td><?php if($expenditure_lists[$i]['Expenditure']['status'] == 0) {echo '未定';}
              elseif($expenditure_lists[$i]['Expenditure']['status'] == 1) {echo '確定';} ?></td></tr>
  <?php } ?>
  <?php echo '</table>'; ?>