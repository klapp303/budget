<h3><?php echo date('Y年m月d日'); ?>現在の収支情報</h3>

  <?php if ($login_user['id'] == $admin_id) { //管理者アカウントの場合 ?>
  <table class="fl cf">
    <tr><td>確定済みの収入</td><td class="tbl-num"><?php echo $past_income; ?>円</td></tr>
    <tr><td>確定済みの支出</td><td class="tbl-num"><?php echo $past_expenditure; ?>円</td></tr>
  </table>
  <?php } else { ?>
  <table class="fl cf">
    <tr><td>現在の残高</td><td class="tbl-num"><?php echo $past_income - $past_expenditure; ?>円</td></tr>
    <tr><td>次回給料日までの出費</td><td class="tbl-num"><?php echo $recent_expenditure; ?>円</td></tr>
  </table>
  <?php } ?>

  <table class="tbl-budget_top">
    <tr><td>確定待ちの収入</td><td><?php echo $this->Html->link($income_unfixed_counts.'件', '/incomes/fix/'); ?></td></tr>
    <tr><td>確定待ちの支出</td><td><?php echo $this->Html->link($expenditure_unfixed_counts.'件', '/expenditures/fix/'); ?></td></tr>
  </table>

<?php if ($login_user['id'] != $admin_id) { //管理者アカウント以外の場合 ?>
<h3 class="title-ex-now_top">本日の支出</h3>

  <?php if ($expenditure_now_counts > 0) { ?>
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
<?php } ?>

<h3>直近の支出予定</h3>

  <table class="detail-list">
    <tr><th>日付</th><th>支出名</th><th class="tbl-num">金額</th><th class="tbl-ico">種類</th><th class="tbl-ico">状態</th></tr>
    <?php foreach($expenditure_month_lists AS $expenditure_month_list){ ?>
    <tr><td><?php echo $expenditure_month_list['Expenditure']['date']; ?></td>
        <td><?php echo $expenditure_month_list['Expenditure']['title']; ?></td>
        <td class="tbl-num"><?php echo $expenditure_month_list['Expenditure']['amount']; ?>円</td>
        <td class="tbl-ico"><span class="icon-genre col-e_<?php echo $expenditure_month_list['Expenditure']['genre_id']; ?>"><?php echo $expenditure_month_list['ExpendituresGenre']['title']; ?></span></td>
        <td class="tbl-ico"><?php if ($expenditure_month_list['Expenditure']['status'] == 0) {echo '<span class="icon-false">未定</span>';}
                              elseif ($expenditure_month_list['Expenditure']['status'] == 1) {echo '<span class="icon-true">確定</span>';} ?></td></tr>
    <?php } ?>
  </table>

<?php if ($login_user['id'] == $admin_id) { //管理者アカウントの場合 ?>
<h3>ユーザ一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-num">ユーザID</th><th>ユーザ名</th><th>登録日時</th><th>権限</th></tr>
    <?php foreach($user_lists AS $user_list) { ?>
    <tr><td class="tbl-num"><?php echo $user_list['User']['id']; ?></td>
        <td><?php echo $user_list['User']['username']; ?></td>
        <td><?php echo $user_list['User']['created']; ?></td>
        <td><?php echo ($user_list['User']['id'] == $admin_id)? '管理者': 'ユーザ'; ?></td></tr>
    <?php } ?>
  </table>

<div class="link-page_top">
  <span class="link-page"><?php echo $this->Html->link('⇨ ユーザの新規登録はこちら', '/users/add/'); ?></span>
</div>
<?php } ?>