<h3>確定待ち支出一覧</h3>

  <?php if ($expenditure_unfixed_counts == 0) { ?>
    <div class="msg_fix">
      <?php echo '確定待ちの支出はありません。'; ?>
    </div>
  <?php } else { ?>
    <table class="tbl_fix">
      <tr><th>日付</th><th>支出名</th><th class="tbl-num">金額</th><th class="tbl-ico">種類</th><th>action</th></tr>
      <?php for($i = 0; $i < $expenditure_unfixed_counts; $i++){ ?>
      <tr><td><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['date']; ?></td>
          <td><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['title']; ?></td>
          <td class="tbl-num"><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['amount']; ?>円</td>
          <td class="tbl-ico"><span class="icon-genre col-e_<?php echo $expenditure_unfixed_lists[$i]['Expenditure']['genre_id']; ?>"><?php echo $expenditure_unfixed_lists[$i]['ExpendituresGenre']['title']; ?></span></td>
          <td><?php echo $this->Form->postLink('修正', array('action' => 'fix_edit', $expenditure_unfixed_lists[$i]['Expenditure']['id'])); ?>
              <?php echo $this->Form->postLink('削除', array('action' => 'fix_deleted', $expenditure_unfixed_lists[$i]['Expenditure']['id'])); ?></td></tr>
      <?php } ?>
    </table>
  <?php } ?>