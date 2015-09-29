<h3><?php echo $year_id.'年'.$month_id.'月'; ?>の収支</h3>

  <table>
    <tr><th></th><th>金額</th><th>前月比</th></tr>
    <tr><td>収入</td>
        <td><?php echo array_sum($income_month_lists); ?></td>
        <td><?php if (array_sum($income_month_pre_lists) == 0) {
              echo 'データなし';
            } else {
              $income_month_comparison = round(array_sum($income_month_lists)/array_sum($income_month_pre_lists), 3)*100;
              echo $income_month_comparison.'%';
            } ?></td></tr>
    <tr><td>支出</td>
        <td><?php echo array_sum($expenditure_month_lists); ?></td>
        <td><?php if (array_sum($expenditure_month_pre_lists) == 0) {
              echo 'データなし';
            } else {
              $expenditure_month_comparison = round(array_sum($expenditure_month_lists)/array_sum($expenditure_month_pre_lists), 3)*100;
              echo $expenditure_month_comparison.'%';
            } ?></td></tr>
  </table>

  <?php if ($year_id == 2015 and $month_id == 9) { ?>
    <?php echo $this->html->link('来月', '/months/'.$year_post_id.'/'.$month_post_id); ?>
  <?php } else { ?>
    <?php echo $this->html->link('先月', '/months/'.$year_pre_id.'/'.$month_pre_id); ?>
    <?php echo $this->html->link('来月', '/months/'.$year_post_id.'/'.$month_post_id); ?>
  <?php } ?>

<h3>支出内訳</h3>

  <table>
    <tr><th>種類</th><th>金額</th><th>割合</th><th>前月比</th></tr>
    <?php for($i = 1; $i <= $genres_e_counts; $i++) { ?>
    <tr><td><?php echo $genres_e_lists[$i]; ?></td>
        <td><?php echo ${'expenditure_month_g'.$i.'_sum'}; ?></td>
        <td><?php if (array_sum($expenditure_month_lists) == 0) {
              echo 'データなし';
            } else {
              $expenditure_g_percentage = round(${'expenditure_month_g'.$i.'_sum'}/array_sum($expenditure_month_lists), 3)*100;
              echo $expenditure_g_percentage.'%';
            } ?></td>
        <td><?php if (${'expenditure_month_g'.$i.'_pre_sum'} == 0) {
              echo 'データなし';
            } else {
              $expenditure_g_comparison = round(${'expenditure_month_g'.$i.'_sum'}/${'expenditure_month_g'.$i.'_pre_sum'}, 3)*100;
              echo $expenditure_g_comparison.'%';
            } ?></td></tr>
    <?php } ?>
  </table>

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