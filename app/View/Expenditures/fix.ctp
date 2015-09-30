<h3>確定待ち支出一覧</h3>

  <?php if ($expenditure_unfixed_counts == 0) { ?>
    <?php echo '確定待ちの支出はありません。'; ?>
  <?php } else { ?>
    <table>
      <tr><th>日付</th><th>支出名</th><th>金額</th><th>種類</th><th>状態</th><th>action</th></tr>
      <?php for($i = 0; $i < $expenditure_unfixed_counts; $i++){ ?>
      <tr><td><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['date']; ?></td>
          <td><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['title']; ?></td>
          <td><?php echo $expenditure_unfixed_lists[$i]['Expenditure']['amount']; ?>円</td>
          <td><?php echo $expenditure_unfixed_lists[$i]['ExpendituresGenre']['title']; ?></td>
          <td><?php if ($expenditure_unfixed_lists[$i]['Expenditure']['status'] == 0) {echo '未定';}
                elseif ($expenditure_unfixed_lists[$i]['Expenditure']['status'] == 1) {echo '確定';} ?></td>
          <td><?php echo $this->Form->postLink('修正', array('action' => 'fix_edit', $expenditure_unfixed_lists[$i]['Expenditure']['id'])); ?>
              <?php echo $this->Form->postLink('削除', array('action' => 'fix_deleted', $expenditure_unfixed_lists[$i]['Expenditure']['id'])); ?></td></tr>
      <?php } ?>
    </table>
  <?php } ?>