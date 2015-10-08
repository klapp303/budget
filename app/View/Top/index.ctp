<h3><?php echo date('Y年m月d日'); ?>現在の収支情報</h3>

  <?php $past_income = array_sum($income_past_lists);
        $past_expenditure = array_sum($expenditure_past_lists); ?>
<table class="fl cf">
    <tr><td>現在の残高</td><td class="tbl-num"><?php echo $past_income - $past_expenditure; ?>円</td></tr>
    <tr><td>次回給料日までの出費</td><td class="tbl-num"><?php echo array_sum($expenditure_recent_lists); ?>円</td></tr>
  </table>

  <table class="tbl-budget_top">
    <tr><td>確定待ちの収入</td><td><?php echo $this->Html->link($income_unfixed_counts.'件', '/incomes/fix/'); ?></td></tr>
    <tr><td>確定待ちの支出</td><td><?php echo $this->Html->link($expenditure_unfixed_counts.'件', '/expenditures/fix/'); ?></td></tr>
  </table>

<h3 class="title-ex-now_top">本日の支出</h3>

  <?php if($expenditure_now_counts > 0) { ?>
    <table class="detail-list">
      <tr><th>日付</th><th>支出名</th><th class="tbl-num">金額</th><th class="tbl-ico">種類</th><th class="tbl-ico">状態</th></tr>
      <?php for($i = 0; $i < $expenditure_now_counts; $i++){ ?>
      <tr><td><?php echo $expenditure_now_lists[$i]['Expenditure']['date']; ?></td>
          <td><?php echo $expenditure_now_lists[$i]['Expenditure']['title']; ?></td>
          <td class="tbl-num"><?php echo $expenditure_now_lists[$i]['Expenditure']['amount']; ?>円</td>
          <td class="tbl-ico"><span class="icon-genre col-e_<?php echo $expenditure_now_lists[$i]['Expenditure']['genre_id']; ?>"><?php echo $expenditure_now_lists[$i]['ExpendituresGenre']['title']; ?></span></td>
          <td class="tbl-ico"><?php if ($expenditure_now_lists[$i]['Expenditure']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                                elseif ($expenditure_now_lists[$i]['Expenditure']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td></tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <p>本日の支出はありません。</p>
  <?php } ?>

<h3>直近の支出</h3>

  <table class="detail-list">
    <tr><th>日付</th><th>支出名</th><th class="tbl-num">金額</th><th class="tbl-ico">種類</th><th class="tbl-ico">状態</th></tr>
    <?php for($i = 0; $i < $expenditure_month_counts; $i++){ ?>
    <tr><td><?php echo $expenditure_month_lists[$i]['Expenditure']['date']; ?></td>
        <td><?php echo $expenditure_month_lists[$i]['Expenditure']['title']; ?></td>
        <td class="tbl-num"><?php echo $expenditure_month_lists[$i]['Expenditure']['amount']; ?>円</td>
        <td class="tbl-ico"><span class="icon-genre col-e_<?php echo $expenditure_month_lists[$i]['Expenditure']['genre_id']; ?>"><?php echo $expenditure_month_lists[$i]['ExpendituresGenre']['title']; ?></span></td>
        <td class="tbl-ico"><?php if ($expenditure_month_lists[$i]['Expenditure']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                              elseif ($expenditure_month_lists[$i]['Expenditure']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td></tr>
    <?php } ?>
  </table>