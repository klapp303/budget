<h3><?php echo date('Y年m月d日'); ?>現在の収支情報</h3>

  <?php $past_income = array_sum($income_past_lists);
        $past_expenditure = array_sum($expenditure_past_lists); ?>
  <table>
    <tr><td>現在の残高</td><td class="tbl-num"><?php echo $past_income - $past_expenditure; ?></td></tr>
    <tr><td>次回給料日までの出費</td><td class="tbl-num"><?php echo array_sum($expenditure_recent_lists); ?></td></tr>
  </table>

  確定待ちの収入<?php echo $this->Html->link($income_unfixed_counts.'件', '/incomes/fix/'); ?>
  確定待ちの支出<?php echo $this->Html->link($expenditure_unfixed_counts.'件', '/expenditures/fix/'); ?>

<h3>本日の支出</h3>

  <?php if($expenditure_now_counts > 0) { ?>
    <table class="detail-list">
      <tr><th>日付</th><th>支出名</th><th class="tbl-num">金額</th><th>種類</th><th>状態</th></tr>
      <?php for($i = 0; $i < $expenditure_now_counts; $i++){ ?>
      <tr><td><?php echo $expenditure_now_lists[$i]['Expenditure']['date']; ?></td>
          <td><?php echo $expenditure_now_lists[$i]['Expenditure']['title']; ?></td>
          <td class="tbl-num"><?php echo $expenditure_now_lists[$i]['Expenditure']['amount']; ?></td>
          <td><?php echo $expenditure_now_lists[$i]['ExpendituresGenre']['title']; ?></td>
          <td><?php if ($expenditure_now_lists[$i]['Expenditure']['status'] == 0) {echo '未定';}
                elseif ($expenditure_now_lists[$i]['Expenditure']['status'] == 1) {echo '確定';} ?></td></tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <p>本日の支出はありません。</p>
  <?php } ?>

<h3>直近の支出</h3>

  <table class="detail-list">
    <tr><th>日付</th><th>支出名</th><th class="tbl-num">金額</th><th>種類</th><th>状態</th></tr>
    <?php for($i = 0; $i < $expenditure_month_counts; $i++){ ?>
    <tr><td><?php echo $expenditure_month_lists[$i]['Expenditure']['date']; ?></td>
        <td><?php echo $expenditure_month_lists[$i]['Expenditure']['title']; ?></td>
        <td class="tbl-num"><?php echo $expenditure_month_lists[$i]['Expenditure']['amount']; ?></td>
        <td><?php echo $expenditure_month_lists[$i]['ExpendituresGenre']['title']; ?></td>
        <td><?php if ($expenditure_month_lists[$i]['Expenditure']['status'] == 0) {echo '未定';}
              elseif ($expenditure_month_lists[$i]['Expenditure']['status'] == 1) {echo '確定';} ?></td></tr>
    <?php } ?>
  </table>