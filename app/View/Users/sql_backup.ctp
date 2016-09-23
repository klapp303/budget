<?php echo 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";'; ?>


<?php foreach ($array_model as $model) { ?>
<?php if (@${$model . '_datas'}) { ?>
INSERT INTO `<?php echo ${$model . '_tbl'}; ?>` (<?php
    $count_column = count(${$model . '_datas'}[0][$model]);
    foreach (${$model . '_datas'}[0][$model] as $column => $data) {
        if ($count_column > 1) {
            echo "'" . $column . "', ";
            $count_column--;
        } else {
            echo "'" . $column . "'";
            break;
        }
    }
?>) VALUES

<?php
$count_data = count(${$model . '_datas'});
foreach (${$model . '_datas'} as $data) {
    if ($count_data > 1) {
        echo '(';
        
        $count_column = count($data[$model]);
        foreach ($data[$model] as $column) {
            if ($count_column > 1) {
                echo "'" . $column . "', ";
                $count_column--;
            } else {
                echo "'" . $column . "'";
            }
        }
        
        echo '), ' . "\n";
        $count_data--;
    } else {
        echo '(';
        
        $count_column = count($data[$model]);
        foreach ($data[$model] as $column) {
            if ($count_column > 1) {
                echo "'" . $column . "', ";
                $count_column--;
            } else {
                echo "'" . $column . "'";
            }
        }
        
        echo ');';
        break;
    }
}
?>


<?php } ?>
<?php } ?>