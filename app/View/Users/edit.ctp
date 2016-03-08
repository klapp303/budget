<?php echo $this->Html->css('users', array('inline' => FALSE)); ?>
<h3>ユーザ情報変更</h3>

  <table class="UserAddForm">
    <?php echo $this->Form->create('User', array( //使用するModel
        'type' => 'put', //変更はput
        'action' => 'edit', //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
        )
    ); ?><!-- form start -->
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $userData['id'])); ?>
    <tr>
      <td class="label">ログイン名</td>
      <td><?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'placeholder' => 'ログイン時に使用します')); ?><span class="txt-alt txt-b">*</span></td>
    </tr>
    <tr>
      <td class="label">ユーザ名</td>
      <td><?php echo $this->Form->input('handlename', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td class="label">給与日</td>
      <?php //日付選択肢用
        $day_options = array();
        for ($i = 1; $i <= 31; $i++) {
          $day_options += array($i => $i.'日');
        }
      ?>
      <td>毎月<?php echo $this->Form->input('payday', array('type' => 'select', 'label' => false, 'options' => $day_options)); ?><span class="txt-alt txt-b">*</span><span class="txt-min">（次回給与日までの支出予定を算出します）</span></td>
    </tr>
  
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('変更', array('div' => false, 'class' => 'submit')); ?>　　<span class="txt-alt txt-b">*</span><span class="txt-min">は必須項目</span></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<div class="link-page_users">
  <span class="link-page"><?php echo $this->Html->link('⇨ パスワードの変更はこちら', '/users/password/'); ?></span>
</div>