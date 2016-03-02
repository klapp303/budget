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

<h3>年間収支グラフ</h3>

<div id="year_graph" class="graph"></div>
<script>
  (function basic(container) {
      var d1 = [
          <?php foreach ($income_year_data AS $key => $value) {
            echo '['.$key.', '.$value.'],';
          } ?>
      ],
          d2 = [
          <?php foreach ($expenditure_year_data AS $key => $value) {
            echo '['.$key.', '.$value.'],';
          } ?>
      ],
      data = [{
          data: d1,
          label: '<?php echo $year_id; ?>収入',
          color: '#0080FF'
      }, {
          data: d2,
          label: '<?php echo $year_id; ?>支出',
          color: '#FA5858'
      }] ;
      
      function labelFn(label) {
          return label;
      }
      graph = Flotr.draw(container, data, {
          legend: {
              position: 'ne',
              labelFormatter: labelFn,
              backgroundColor: '#D2E8FF'
          },
          xaxis: {
              ticks: [[1,'1月'], [2,'2月'], [3,'3月'], [4,'4月'], [5,'5月'], [6,'6月'], [7,'7月'], [8,'8月'], [9,'9月'], [10,'10月'], [11,'11月'], [12,'12月']],
              min: 1,
              max: 12
          },
          yaxis: {
              min: 0,
              max: 300000
          },
          HtmlText: false
      });
  })(document.getElementById('year_graph'));
</script>

<h3>支出内訳グラフ</h3>

<div id="genre_graph" class="graph"></div>
<script>
  (function basic(container) {
      var 
      <?php foreach ($expenditure_genres AS $genre_id => $genre_title) { ?>
          d<?php echo $genre_id; ?> = [
          <?php foreach (${'expenditure_genre_data_'.$genre_id} AS $key => $value) {
            echo '['.$key.', '.$value.'],';
          } ?>
      ],
      <?php } ?>
      data = [
      <?php foreach ($expenditure_genres AS $genre_id => $genre_title) { ?>
          {
          data: d<?php echo $genre_id; ?>,
          label: '<?php echo $genre_title; ?>',
          <?php if ($genre_id==1) {echo 'color: "#FE2E64"';}
            elseif ($genre_id==2) {echo 'color: "#FE2E9A"';}
            elseif ($genre_id==3) {echo 'color: "#F5A9E1"';}
            elseif ($genre_id==4) {echo 'color: "#FAAC58"';}
            elseif ($genre_id==5) {echo 'color: "#FFFF00"';}
            elseif ($genre_id==6) {echo 'color: "#819FF7"';}
            elseif ($genre_id==7) {echo 'color: "#58ACFA"';}
            elseif ($genre_id==8) {echo 'color: "#58D3F7"';}
            elseif ($genre_id==9) {echo 'color: "#D8D8D8"';} ?>
      },
      <?php } ?>
      ] ;
      
      function labelFn(label) {
          return label;
      }
      graph = Flotr.draw(container, data, {
          legend: {
              position: 'ne',
              labelFormatter: labelFn,
              backgroundColor: '#D2E8FF'
          },
          xaxis: {
              ticks: [[1,'1月'], [2,'2月'], [3,'3月'], [4,'4月'], [5,'5月'], [6,'6月'], [7,'7月'], [8,'8月'], [9,'9月'], [10,'10月'], [11,'11月'], [12,'12月']],
              min: 1,
              max: 12
          },
          yaxis: {
              min: 0,
              max: 100000
          },
          HtmlText: false
      });
  })(document.getElementById('genre_graph'));
</script>