<?php
//searchboxのデフォルト設定
if (@!$controller) {
    $controller = 'expenditures';
}
if ($controller == 'expenditures') {
    $placeholder = '支出名';
} elseif ($controller == 'incomes') {
    $placeholder = '収入名';
}
?>
<div>
  <?php echo $this->Form->create('Search', array( //使用するModel
      'type' => 'get', //デフォルトはpost送信
      'url' => array('controller' => $controller, 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  <table>
    <tr>
      <td><?php echo $this->Form->input('search_word', array('type' => 'text', 'label' => false, 'value' => @$search_word, 'placeholder' => @$placeholder)); ?></td>
      <td><?php echo $this->Form->submit('検索する'); ?></td>
    </tr>
  </table>
  <?php echo $this->Form->end(); ?>
</div>