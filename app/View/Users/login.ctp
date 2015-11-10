<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3>ログイン</h3>

  <table class="UserLoginForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'login', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <tr>
      <td><label>ユーザ名</label></td>
      <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td><label>パスワード</label></td>
      <td><?php echo $this->Form->input('password', array('type' => 'text', 'label' => false)); ?></td>
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