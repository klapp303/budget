<?php echo $this->Html->css('sub_pop', array('inline' => FALSE)); ?>
<h3><?php echo $genres_e_lists[$genre_id]; ?>の支出一覧</h3>

  <table class="pop-list">
  <tr><th class="tbl-date_subpop">日付</th><th>支出名</th><th class="tbl-num_subpop">金額</th><th class="tbl-ico_subpop">状態</th></tr>
  <?php foreach($expenditure_lists AS $expenditure_list){ ?>
  <tr><td class="tbl-date_subpop"><?php echo $expenditure_list['Expenditure']['date']; ?></td>
      <td><?php echo $expenditure_list['Expenditure']['title']; ?></td>
      <td class="tbl-num_subpop"><?php echo $expenditure_list['Expenditure']['amount']; ?>円</td>
      <td class="tbl-ico_subpop"><?php if ($expenditure_list['Expenditure']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                            elseif ($expenditure_list['Expenditure']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td></tr>
  <?php } ?>
  </table>