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
if (@$search_date) {
    list($search_year, $search_month, $search_day) = explode('-', $search_date);
} else {
    list($search_year, $search_month, $search_day) = array(date('Y'), date('m'), date('d'));
}
?>
<div>
  <?php echo $this->Form->create('Search', array( //使用するModel
      'type' => 'get', //デフォルトはpost送信
      'url' => array('controller' => $controller, 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => ''),
      'class' => 'search_form'
  )); ?>
  <table>
    <tr>
      <td><?php echo $this->Form->input('search_word', array('type' => 'text', 'label' => false, 'value' => @$search_word, 'placeholder' => @$placeholder, 'class' => 'search_word')); ?></td>
      <td><?php echo $this->Form->input('search_date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015, 'value' => array('year' => $search_year, 'month' => $search_month, 'day' => $search_day), 'class' => 'search_date',
          'disabled' => (@$search_date)? '' : 'disabled')); ?></td>
      <td><?php echo $this->Form->input('search_date_null', array('type' => 'checkbox', 'label' => false, 'class' => 'search_date_null',
          'checked' => (@$search_date)? '' : 'checked')); ?></td>
      <td><?php echo $this->Form->submit('検索する'); ?></td>
    </tr>
  </table>
  <script>
      jQuery(function($) {
          $('.search_date_null').change(function() {
              if ($(this).is(':checked')) { //なしにチェックがある場合
                  $('.search_date').attr('disabled', 'disabled');
              } else { //なしにチェックがない場合
                  $('.search_date').removeAttr('disabled').focus();
              }
          });
          
          //wordもdateもなければ送信しない
          $('.search_form').submit(function() {
              var search_word = $('.search_word').val();
              var search_date_null = $('.search_date_null').prop('checked');
              if (search_word == '' && search_date_null == true) {
                  alert('検索ワードか日付を入力してください。');
                  return false;
              }
          });
      });
  </script>
  <?php echo $this->Form->end(); ?>
</div>