<?php
/**
 * 変数の定義があれば記述
 */
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
      <?php echo '収支管理'; ?>
    </title>
    <?php
//    echo $this->Html->meta('icon');
    
    echo $this->Html->css(array(
        'common',
        'detail',
        'top',
        'months',
        'fix'
    ));
    
    echo $this->Html->script(array(
        'jquery-1.11.3.min',
        'jquery-migrate-1.2.1.min'
    ));
    
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
	  ?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <?php echo $this->element('budget_header'); ?>
      </div>
      
      <div id="menu_side">
        <?php echo $this->element('budget_menu'); ?>
      </div>
      
      <div id="content">
        <?php echo $this->Flash->render(); ?>
        
        <?php echo $this->fetch('content'); ?>
      </div>
      
      <div id="footer">
        <?php echo $this->element('budget_footer'); ?>
      </div>
    </div>
  </body>
</html>