<h3>ユーザ登録</h3>

  <?php echo $this->Form->create('User', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('username', array('type' => 'text', 'label' => 'ユーザ名')); ?><br>
  <?php echo $this->Form->input('password', array('type' => 'text', 'label' => 'パスワード')); ?>
  
  <?php echo $this->Form->submit('登録'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<p><a href="/users/login/">ログインはこちら</a></p>