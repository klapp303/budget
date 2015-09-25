<h3><?php echo date('Y年m月d日'); ?>現在の収支情報</h3>

  <?php $now_income = array_sum($income_now_lists);
        $now_expenditure = array_sum($expenditure_now_lists); ?>
  現在の残高 <?php echo $now_income - $now_expenditure; ?><br>
  次回給料日までの出費 <?php echo array_sum($expenditure_month_lists); ?>

<h3>直近の支出</h3>

  <?php echo '<table><tr><th>日付</th><th>支出名</th><th>金額</th><th>種類</th><th>状態</th></tr>'; ?>
  <?php for($i = 0; $i < $expenditure_recent_counts; $i++){ ?>
    <tr><td><?php echo $expenditure_recent_lists[$i]['Expenditure']['date']; ?></td>
        <td><?php echo $expenditure_recent_lists[$i]['Expenditure']['title']; ?></td>
        <td><?php echo $expenditure_recent_lists[$i]['Expenditure']['amount']; ?></td>
        <td><?php echo $expenditure_recent_lists[$i]['ExpendituresGenre']['title']; ?></td>
        <td><?php if($expenditure_recent_lists[$i]['Expenditure']['status'] == 0) {echo '未定';}
              elseif($expenditure_recent_lists[$i]['Expenditure']['status'] == 1) {echo '確定';} ?></td></tr>
  <?php } ?>
  <?php echo '</table>'; ?>