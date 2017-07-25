<?php echo $this->Html->css('users', array('inline' => false)); ?>
<h3>ログイン</h3>

  <table class="UserLoginForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'login', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    <tr>
      <td class="label">ログイン名</td>
      <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td class="label">パスワード</td>
      <td><?php echo $this->Form->input('password', array('type' => 'password', 'label' => false)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('ログイン'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ 新規登録はこちら', '/users/add/'); ?></span>
</div>