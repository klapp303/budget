<?php

App::uses('AppModel', 'Model');

class Word extends AppModel
{
    public $useTable = 'words';
//    public $actsAs = ['SoftDelete', 'Search.Searchable'];
    
//    public $filtetArgs = ['' => ['' => '', '' => '']];
    
    public function getWordLists($user_id = null, $category = null)
    {
        $datas = $this->find('all', array(
            'conditions' => array(
                'Word.user_id' => $user_id,
                'Word.category' => $category
            )
        ));
        $word_lists = array();
        $word_lists[] = array(
            'title' => '選択してください',
            'value' => '',
            'amount' => null,
            'genre_id' => null
        );
        foreach ($datas as $data) {
            //登録されているデータを取得
            $title = $data['Word']['title'];
            $value = $data['Word']['value'];
            $amount = $data['Word']['amount'];
            $genre_id = $data['Word']['genre_id'];
            $strtotime = $data['Word']['strtotime'];
            
            //データの整形
            $value_format = '';
            $value_array = explode('%', $value);
            //月表示がなければそのまま
            if ($value_array[0] == $value) {
                $value_format = $value;
                //月表示があれば整形
            } else {
                $month_count = substr_count($value_array[1], 'M');
                $month = date('n', strtotime(date('Y-m-01 ') . $strtotime . ' month'));
                //単月の場合
                if ($month_count == 1) {
                    $value_format = $value_array[0] . $month . $value_array[2];
                    //複数月の場合
                } elseif ($month_count > 1) {
                    if ($month + $month_count -1 <= 12) {
                        $month_period = $month + $month_count - 1;
                    } else {
                        $month_period = $month + $month_count -1 -12;
                    }
                    $value_format = $value_array[0] . $month . '～' . $month_period . $value_array[2];
                }
            }
            
            $word_lists[] = array(
                'title' => $title,
                'value' => $value_format,
                'amount' => $amount,
                'genre_id' => $genre_id
            );
        }
        
        return $word_lists;
    }
    
    public function addMonth($date = null, $add_month = 1)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        //年月
        $date_1st = date('Y-m-01', strtotime($date));
        $new_Ym = date('Y-m', strtotime($date_1st . ' ' . $add_month . ' month'));
        
        //日
        $day = date('d', strtotime($date));
        $max_day = date('t', strtotime($new_Ym));
        //末日をオーバーする場合のみその月の末日を返す
        if ($day <= $max_day) {
            $new_d = $day;
        } else {
            $new_d = $max_day;
        }
        
        return $new_Ym . '-' . $new_d;
    }
    
    public function searchWordTOConditions($search_word = null, $controller = null)
    {
        if ($controller) {
            $controller = $controller . '.';
        }
        
        //全角スペースは半角スペースに変換しておく
        $search_word = str_replace('　', ' ', $search_word);
        
        //and検索
        $array_word = explode(' ', $search_word);
        
        $search_conditions = [];
        foreach ($array_word as $val) {
            $search_conditions[] = array(
                $controller . 'title LIKE' => '%' . $val . '%'
            );
        }
        
        return $search_conditions;
    }
}
