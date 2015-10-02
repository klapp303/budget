<h3>確定待ち収入一覧</h3>

  <?php if ($income_unfixed_counts == 0) { ?>
    <div class="msg_fix">
      <?php echo '確定待ちの収入はありません。'; ?>
    </div>
  <?php } else { ?>
    <table class="tbl_fix">
      <tr><th>日付</th><th>収入名</th><th class="tbl-num">金額</th><th>種類</th><th>action</th></tr>
      <?php for($i = 0; $i < $income_unfixed_counts; $i++){ ?>
      <tr><td><?php echo $income_unfixed_lists[$i]['Income']['date']; ?></td>
          <td><?php echo $income_unfixed_lists[$i]['Income']['title']; ?></td>
          <td class="tbl-num"><?php echo $income_unfixed_lists[$i]['Income']['amount']; ?>円</td>
          <td><?php echo $income_unfixed_lists[$i]['IncomesGenre']['title']; ?></td>
          <td><?php echo $this->Form->postLink('修正', array('action' => 'fix_edit', $income_unfixed_lists[$i]['Income']['id'])); ?>
              <?php echo $this->Form->postLink('削除', array('action' => 'fix_deleted', $income_unfixed_lists[$i]['Income']['id'])); ?></td></tr>
      <?php } ?>
    </table>
  <?php } ?>