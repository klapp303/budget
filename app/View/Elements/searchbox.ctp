<div>
  <?php echo $this->Form->create('Sample', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'samples', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
  
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => 'タイトル検索')); ?>
  
  <?php echo $this->Form->submit('検索する'); ?>
  <?php echo $this->Form->end(); ?>
</div>