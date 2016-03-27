<?php echo $this->Html->script('sub_pop', array('inline' => FALSE)); ?>
<h3><?php echo $year_id.'年'.$month_id.'月'; ?>の確定収支</h3>

  <table>
    <tr><th></th><th class="tbl-num">金額</th><th class="tbl-num">前月比</th></tr>
    <tr><td class="txt-b">収入</td>
      <td class="tbl-num"><?php echo array_sum($income_month_lists); ?>円</td>
      <td class="tbl-num"><?php if (array_sum($income_month_pre_lists) == 0) {
              echo 'データなし';
            } else {
              $income_month_comparison = round(array_sum($income_month_lists)/array_sum($income_month_pre_lists), 3)*100;
              echo $income_month_comparison.'%';
            } ?></td></tr>
    <tr><td class="txt-b">支出</td>
      <td class="tbl-num"><?php echo array_sum($expenditure_month_lists); ?>円</td>
      <td class="tbl-num"><?php if (array_sum($expenditure_month_pre_lists) == 0) {
              echo 'データなし';
            } else {
              $expenditure_month_comparison = round(array_sum($expenditure_month_lists)/array_sum($expenditure_month_pre_lists), 3)*100;
              echo $expenditure_month_comparison.'%';
            } ?></td></tr>
  </table>

  <?php if ($year_id == 2015 and $month_id == 9) { ?>
    <div class="pager_months_start">
      <?php echo $this->Html->link('来月', '/months/'.$year_post_id.'/'.$month_post_id); ?>
    </div>
  <?php } else { ?>
    <div class="pager_months">
      <?php echo $this->Html->link('先月', '/months/'.$year_pre_id.'/'.$month_pre_id); ?>
      <?php echo $this->Html->link('来月', '/months/'.$year_post_id.'/'.$month_post_id); ?>
    </div>
  <?php } ?>

<h3>支出内訳</h3>

  <table>
    <tr><th class="tbl-genre">種類</th>
        <th class="tbl-num">金額</th>
        <th class="tbl-num">割合</th>
        <th class="tbl-num">前月比</th>
        <th class="tbl-ico">action</th></tr>
    <?php for($i = 1; $i <= $genres_e_counts; $i++) { ?>
    <tr><td class="tbl-genre"><span class="icon-genre col-e_<?php echo $i; ?>"><?php echo $genres_e_lists[$i]; ?></span></td>
        <td class="tbl-num"><?php echo ${'expenditure_month_g'.$i.'_sum'}; ?>円</td>
        <td class="tbl-num"><?php if (array_sum($expenditure_month_lists) == 0) {
              echo 'データなし';
            } else {
              $expenditure_g_percentage = round(${'expenditure_month_g'.$i.'_sum'}/array_sum($expenditure_month_all_lists), 3)*100;
              echo $expenditure_g_percentage.'%';
            } ?></td>
        <td class="tbl-num"><?php if (${'expenditure_month_g'.$i.'_pre_sum'} == 0) {
              echo 'データなし';
            } else {
              $expenditure_g_comparison = round(${'expenditure_month_g'.$i.'_sum'}/${'expenditure_month_g'.$i.'_pre_sum'}, 3)*100;
              echo $expenditure_g_comparison.'%';
            } ?></td>
        <td class="tbl-ico"><span class="icon-button"><?php echo $this->Html->link('詳細', '/months/genre/'.$i.'/'.$year_id.'/'.$month_id, array('target' => 'sub_pop', 'onClick' => 'disp("/months/genre/${i}/${year_id}/${month_id}")')); ?></span></td></tr>
    <?php } ?>
  </table>

<h3>支出一覧</h3>
  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'months', 'action' => 'index')
  )); ?>
  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞', //最終ページへのリンク
      'url' => array('controller' => 'months', $year_id, $month_id)
  )); ?>

  <table class="detail-list">
  <tr><th class="tbl-date">日付<?php echo $this->Paginator->sort('Expenditure.date', '▼', array('url' => array($year_id, $month_id))); ?></th>
      <th>支出名<?php echo $this->Paginator->sort('Expenditure.title', '▼', array('url' => array($year_id, $month_id))); ?></th>
      <th class="tbl-num">金額<?php echo $this->Paginator->sort('Expenditure.amount', '▼', array('url' => array($year_id, $month_id))); ?></th>
      <th class="tbl-genre">種類<?php echo $this->Paginator->sort('Expenditure.genre_id', '▼', array('url' => array($year_id, $month_id))); ?></th>
      <th class="tbl-ico">状態<?php echo $this->Paginator->sort('Expenditure.status', '▼', array('url' => array($year_id, $month_id))); ?></th></tr>
  <?php foreach($expenditure_lists AS $expenditure_list){ ?>
  <tr><td class="tbl-date"><?php echo $expenditure_list['Expenditure']['date']; ?></td>
      <td><?php echo $expenditure_list['Expenditure']['title']; ?></td>
      <td class="tbl-num"><?php echo $expenditure_list['Expenditure']['amount']; ?>円</td>
      <td class="tbl-genre"><span class="icon-genre col-e_<?php echo $expenditure_list['Expenditure']['genre_id']; ?>"><?php echo $expenditure_list['ExpendituresGenre']['title']; ?></span></td>
      <td class="tbl-ico"><?php if ($expenditure_list['Expenditure']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                            elseif ($expenditure_list['Expenditure']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td></tr>
  <?php } ?>
  </table>