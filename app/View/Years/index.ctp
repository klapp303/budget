<?php echo $this->Html->css('years', array('inline' => FALSE)); ?>
<?php echo $this->Html->script('flotr2.min', array('inline' => FALSE)); ?>
<h3><?php echo $year_id.'年'; ?>の確定収支</h3>

  <table>
    <tr><th></th><th class="tbl-num">金額</th><th class="tbl-num">前年比</th></tr>
    <tr><td class="txt-b">収入</td>
      <td class="tbl-num"><?php echo array_sum($income_year_lists); ?>円</td>
      <td class="tbl-num"><?php if (array_sum($income_year_pre_lists) == 0) {
              echo 'データなし';
            } else {
              $income_year_comparison = round(array_sum($income_year_lists)/array_sum($income_year_pre_lists), 3)*100;
              echo $income_year_comparison.'%';
            } ?></td></tr>
    <tr><td class="txt-b">支出</td>
      <td class="tbl-num"><?php echo array_sum($expenditure_year_lists); ?>円</td>
      <td class="tbl-num"><?php if (array_sum($expenditure_year_pre_lists) == 0) {
              echo 'データなし';
            } else {
              $expenditure_year_comparison = round(array_sum($expenditure_year_lists)/array_sum($expenditure_year_pre_lists), 3)*100;
              echo $expenditure_year_comparison.'%';
            } ?></td></tr>
  </table>

  <?php if ($year_id == 2015) { ?>
    <div class="pager_months_start">
      <?php echo $this->Html->link('翌年', '/years/'.$year_post_id); ?>
    </div>
  <?php } else { ?>
    <div class="pager_months">
      <?php echo $this->Html->link('昨年', '/years/'.$year_pre_id); ?>
      <?php echo $this->Html->link('翌年', '/years/'.$year_post_id); ?>
    </div>
  <?php } ?>

<h3>グラフ</h3>

<div id="graph"></div>

<script>
  (function basic(container) {
      var d1 = [
          [1, 70],
          [2, 68],
          [3, 65],
          [4, 67],
          [5, 64],
          [6, 61],
          [7, 60],
          [8, 62],
          [9, 68],
          [10, 67],
          [11, 70],
          [12, 72]
      ],
          d2 = [
          [1, 70],
          [2, 69],
          [3, 70],
          [4, 71],
          [5, 69],
          [6, 70],
          [7, 69],
          [8, 68],
          [9, 69],
          [10, 70],
          [11, 73],
          [12, 75]
      ],
      data = [{
          data: d1,
          label: 'サンプル1'
      }, {
          data: d2,
          label: 'サンプル2'
      }] ;
      
      function labelFn(label) {
          return label;
      }
      graph = Flotr.draw(container, data, {
          legend: {
              position: 'se',
              labelFormatter: labelFn,
              backgroundColor: '#D2E8FF'
          },
          HtmlText: false
      });
  })(document.getElementById('graph'));
</script>