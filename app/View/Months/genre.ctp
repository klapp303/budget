<?php echo $this->Html->css('sub_pop', array('inline' => FALSE)); ?>
<h3><?php echo $genres_e_lists[$genre_id]; ?>の支出一覧</h3>

  <table class="pop-list">
  <tr><th>日付</th><th>支出名</th><th class="tbl-num">金額</th><th class="tbl-ico">状態</th></tr>
  <?php foreach($expenditure_lists AS $expenditure_list){ ?>
  <tr><td><?php echo $expenditure_list['Expenditure']['date']; ?></td>
      <td><?php echo $expenditure_list['Expenditure']['title']; ?></td>
      <td class="tbl-num"><?php echo $expenditure_list['Expenditure']['amount']; ?>円</td>
      <td class="tbl-ico"><?php if ($expenditure_list['Expenditure']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                            elseif ($expenditure_list['Expenditure']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td></tr>
  <?php } ?>
  </table>