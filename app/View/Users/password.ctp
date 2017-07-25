<?php echo $this->Html->css('users', array('inline' => false)); ?>
<h3>パスワード変更</h3>

  <table class="UserAddForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $userData['id'])); ?>
    <tr>
      <td class="label">パスワード</td>
      <td><?php echo $this->Form->input('password', array('type' => 'password', 'label' => false, 'placeholder' => '新しいパスワードを入力')); ?><span class="txt-alt txt-b">*</span><span class="txt-min">（半角英数のみ）</span></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('変更', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ ユーザ情報の変更はこちら', '/users/edit/'); ?></span>
</div>