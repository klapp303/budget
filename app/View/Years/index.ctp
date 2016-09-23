<?php echo $this->Html->css('years', array('inline' => false)); ?>
<?php echo $this->Html->script('flotr2.min', array('inline' => false)); ?>
<h3><?php echo $year_id.'年'; ?>の確定収支</h3>

  <table>
    <tr><th></th><th class="tbl-num">金額</th><th class="tbl-num">前年比</th></tr>
    
    <tr><td class="txt-b">収入</td>
        <td class="tbl-num"><?php echo array_sum($income_year_lists); ?>円</td>
        <td class="tbl-num"><?php if (array_sum($income_year_pre_lists) == 0) {
                                echo 'データなし';
                            } else {
                                $income_year_comparison = round(array_sum($income_year_lists)/array_sum($income_year_pre_lists), 3) *100;
                                echo $income_year_comparison . '%';
                            } ?></td></tr>
    <tr><td class="txt-b">支出</td>
        <td class="tbl-num"><?php echo array_sum($expenditure_year_lists); ?>円</td>
        <td class="tbl-num"><?php if (array_sum($expenditure_year_pre_lists) == 0) {
                                echo 'データなし';
                            } else {
                                $expenditure_year_comparison = round(array_sum($expenditure_year_lists)/array_sum($expenditure_year_pre_lists), 3) *100;
                                echo $expenditure_year_comparison . '%';
                            } ?></td></tr>
  </table>

  <?php if ($year_id == 2015) { ?>
    <div class="pager_months_start">
      <?php echo $this->Html->link('翌年', '/years/' . $year_post_id); ?>
    </div>
  <?php } else { ?>
    <div class="pager_months">
      <?php echo $this->Html->link('昨年', '/years/' . $year_pre_id); ?>
      <?php echo $this->Html->link('翌年', '/years/' . $year_post_id); ?>
    </div>
  <?php } ?>

<h3>年間収支グラフ<span class="txt-n txt-min">（確定のみ）</span></h3>

<div id="year_graph" class="graph"></div>
<script>
    (function basic(container) {
        var d1 = [
            <?php foreach ($income_year_data as $key => $value) {
                if ($year_id < 2015) {
                    continue;
                } elseif ($year_id == 2015) {
                    if ($key > 9) {
                        echo '[' . $key . ', ' . $value . '],';
                    }
                } elseif ($year_id < date('Y')) {
                    echo '[' . $key . ', ' . $value . '],';
                } elseif ($year_id == date('Y')) {
                    if ($key <= date('m')) {
                        echo '[' . $key . ', ' . $value . '],';
                    }
                }
            } ?>
        ];
        var d2 = [
            <?php foreach ($expenditure_year_data as $key => $value) {
                if ($year_id < 2015) {
                    continue;
                } elseif ($year_id == 2015) {
                    if ($key > 9) {
                        echo '[' . $key . ', ' . $value . '],';
                    }
                } elseif ($year_id < date('Y')) {
                    echo '[' . $key . ', ' . $value . '],';
                } elseif ($year_id == date('Y')) {
                    if ($key <= date('m')) {
                        echo '[' . $key . ', ' . $value . '],';
                    }
                }
            } ?>
        ];
        var data = [
            {
                data: d1,
                label: '<?php echo $year_id; ?>収入',
                color: '#0080ff'
            },
            {
                data: d2,
                label: '<?php echo $year_id; ?>支出',
                color: '#fa5858'
            }
        ];
        
        function labelFn(label) {
            return label;
        }
        graph = Flotr.draw(container, data, {
            legend: {
                position: 'nw',
                labelFormatter: labelFn,
                backgroundColor: '#d2e8ff'
            },
            xaxis: {
                ticks: [[1, '12月'], [2,'1月'], [3,'2月'], [4,'3月'], [5,'4月'], [6,'5月'], [7,'6月'], [8,'7月'], [9,'8月'], [10,'9月'], [11,'10月'], [12,'11月'], [13,'12月']],
                min: 1,
                max: 13
            },
            yaxis: {
                min: 0,
                max: 400000
            },
            HtmlText: false
        });
    })(document.getElementById('year_graph'));
</script>

<h3>支出内訳グラフ<span class="txt-n txt-min">（確定のみ）</span></h3>

<div id="genre_graph" class="graph"></div>
<script>
    (function basic(container) {
        <?php foreach ($expenditure_genres as $genre_id => $genre_title) { ?>
            var d<?php echo $genre_id; ?> = [
                <?php foreach (${'expenditure_genre_data_'.$genre_id} as $key => $value) {
                    if ($year_id < 2015) {
                        continue;
                    } elseif ($year_id == 2015) {
                        if ($key > 9) {
                            echo '[' . $key . ', ' . $value . '],';
                        }
                    } elseif ($year_id < date('Y')) {
                        echo '[' . $key . ', ' . $value . '],';
                    } elseif ($year_id == date('Y')) {
                        if ($key <= date('m')) {
                            echo '[' . $key . ', ' . $value . '],';
                        }
                    }
                } ?>
            ];
        <?php } ?>
        <?php //ジャンル毎の色を予め定義しておく
        $array_color = array(
            1 => '#fe2e64',
            2 => '#fe2e9a',
            3 => '#f5a9e1',
            4 => '#faac58',
            5 => '#ffff00',
            6 => '#819ff7',
            7 => '#58acfa',
            8 => '#58d3f7',
            9 => '#d8d8d8'
        );
        ?>
        var data = [
            <?php foreach ($expenditure_genres as $genre_id => $genre_title) { ?>
                {
                    data: d<?php echo $genre_id; ?>,
                    label: '<?php echo $genre_title; ?>',
                    <?php  echo 'color: "' . $array_color[$genre_id] . '"'; ?>
                },
            <?php } ?>
        ];
        
        function labelFn(label) {
            return label;
        }
        graph = Flotr.draw(container, data, {
            legend: {
                position: 'nw',
                labelFormatter: labelFn,
                backgroundColor: '#d2e8ff'
            },
            xaxis: {
                ticks: [[1, '12月'], [2,'1月'], [3,'2月'], [4,'3月'], [5,'4月'], [6,'5月'], [7,'6月'], [8,'7月'], [9,'8月'], [10,'9月'], [11,'10月'], [12,'11月'], [13,'12月']],
                min: 1,
                max: 13
            },
            yaxis: {
                min: 0,
                max: 150000
            },
            HtmlText: false
        });
    })(document.getElementById('genre_graph'));
</script>