<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3>ユーザ登録</h3>

  <table class="UserAddForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'action' => 'add', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <tr>
      <td class="label">ログイン名</td>
      <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'placeholder' => 'ログイン時に使用します')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td class="label">パスワード</td>
      <td><?php echo $this->Form->input('password', array('type' => 'text', 'label' => false, 'placeholder' => 'ログイン時に使用します')); ?><span class="txt-alt txt-b">*</span><span class="txt-min">（半角英数のみ）</span></td>
    </tr>
  
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('登録', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ ログインはこちら', '/users/login/'); ?></span>
</div>