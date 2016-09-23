<?php echo $this->Html->css('users', array('inline' => false)); ?>
<h3>ユーザ情報</h3>

  <table class="UserAddForm">
    <tr>
      <td><label>ログイン名</label></td>
      <td><?php echo $user_data['User']['username']; ?></td>
    </tr>
    <tr>
      <td><label>ハンドルネーム</label></td>
      <td><?php echo $user_data['User']['handlename']; ?></td>
    </tr>
    <tr>
      <td><label>給与日<span class="txt-min">（次回給与日までの支出予定を算出します）</span></label></td>
      <td><?php echo $user_data['User']['payday'] . '日'; ?></td>
    </tr>
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ ユーザ情報の変更はこちら', '/users/edit/'); ?></span>
  <span class="link-page"><?php echo $this->Html->link('⇨ パスワードの変更はこちら', '/users/password/'); ?></span>
</div>