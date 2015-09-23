<h3>収入の登録</h3>

  <?php echo $this->Form->create('Income', array( //使用するModel
      'type' => 'post',
      'action' => 'add',
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('title', array('type' => 'text', 'div' => false)); ?>
  <?php echo $this->Form->input('amount', array('type' => 'text', 'div' => false)); ?>
  <?php echo $this->Form->input('genre_id', array('type' => 'text', 'div' => false)); ?>
  <?php echo $this->Form->input('status', array('type' => 'text', 'div' => false)); ?>
  <?php echo $this->Form->input('month', array('type' => 'text', 'div' => false)); ?>
  
  <?php echo $this->Form->submit('登録する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>最近の収入</h3>

  <?php echo '<pre>';
  print_r($income_lists);
  echo '</pre>'; ?>