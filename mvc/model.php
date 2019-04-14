<?php
    include('data.php');
    class MODEL{
        function __construct(){
            $this->data = new DATA;
        }

        /////////////////////////////////////////////////
        // INSERT部分
        /////////////////////////////////////////////////
        // profile登録
        public function profileInsert($array){
            $table = 'profiles';
            $column = '`name`';
            $values = "'".h($array['name'])."'";
            
            // データ有無のチェック
            if(($array['facebook']) != ''){
                $column .= ', `facebook`';
                $values .= ', "'.h($array['facebook']).'"';
            }
            if(($array['twitter']) != ''){
                $column .= ', `twitter`';
                $values .= ', "'.h($array['twitter']).'"';
            }
            if(($array['introduce']) != ''){
                $column .= ', `introduce`';
                $values .= ', "'.h($array['introduce']).'"';
            }

            // DB登録
            $res = $this->data->insert($table, $column, $values);
            return $res;
        }

        // キーワード登録
        public function keywordInsert($array){
            $table = 'keywords';
            $count = 1;
            foreach($array as $word){
                if($count == 1){
                    $values = "('".h($word)."')";
                }else{
                    $values .= ", ('".h($word)."')";
                }
                $count++;
            }
           
            $res = $this->data->insertMulti($table, $values);
            return $res;

        }
        
        
        
        // サイト登録処理
        public function siteRegister($column, $data){
            // データ展開
            $data = $data;
            $column = $column;
            $date = Date("Y/m/d");

            // DB登録情報
            $table = 'siteinfos';
            $column = '`'.$column.'`, `date`';
            $values = "'".$data."'".","."'".$date."'";
            $res = $this->data->insert($table, $column, $values);
            return $res;

        }

        // サイトキーワード処理
        public function siteKeyword($id, $flont, $server){
            $table = 'siteKeywords';
            // フロント
            if(count($flont) == 1){
                $keyword = h($flont[0]);
                $values = "('".$id."'".","."'flont' , "."'".$keyword."')";
                $res = $this->data->insertMulti($table, $values);
            }else{
                $count = 1;
                foreach($flont as $keyword){
                        $keyword = h($keyword);
                    if($count == 1){
                        $values = $values = "('".$id."'".","."'flont' , "."'".$keyword."')";
                    }else{
                        $values .= ", ('".$id."'".","."'flont' , "."'".$keyword."')";
                    }
                    $count++;
                }
                $res = $this->data->insertMulti($table, $values);
            }
            if(!$res){
                echo 'NG';
                exit();
            }

            // サーバサイド
            if(count($server) == 1){
                $keyword = h($server[0]);
                $values = "('".$id."'".","."'server' , "."'".$keyword."')";
                $res = $this->data->insertMulti($table, $values);
            }else{
                $count = 1;
                foreach($server as $keyword){
                    $keyword = h($keyword);
                    if($count == 1){
                        $values = $values = "('".$id."'".","."'server' , "."'".$keyword."')";
                    }else{
                        $values .= ", ('".$id."'".","."'server' , "."'".$keyword."')";
                    }
                    $count++;
                }
                $res = $this->data->insertMulti($table, $values);
            }
            if(!$res){
                echo 'NG';
                exit();
            }

        }

        /////////////////////////////////////////////////
        // SELECT部分
        /////////////////////////////////////////////////
        // なんでも（1件取得）
        public function anySelect($column, $table, $where){
            $table = $table;
            $column = $column; 
            $where = $where;
            $res = $this->data->select($column, $table, $where);
            return $res;
        }

        // 複数件
        public function anySelectAll($column, $table, $where){
            $table = $table;
            $column = $column; 
            $where = $where;
            $res = $this->data->selectAll($column, $table, $where);
            return $res;
        }

        // サイト重複確認処理
        public function siteCheck($columntitle, $data){
            // データ展開
            $data = $data;
            $columntitle = $columntitle;

            // DB接続情報
            $table = 'siteinfos';
            $column = '*';
            $where = "WHERE `".$columntitle."` = "."'".$data."'";
            $res = $this->data->select($column, $table, $where);
            return $res;
        }

        // データID取得
        public function searchId($columntitle, $data){
            $table = 'siteinfos';
            $column = '`siteinfo_id`';

            $data = $data;
            $columntitle = $columntitle;
            $where = "WHERE `".$columntitle."` = "."'".$data."'";
            $res = $this->data->select($column, $table, $where);
            return $res;
        }

        // 検索ワード→サイトID取得
        public function searchwords($array){
            $table = 'siteKeywords';
            $column = 'siteinfo_id';
            $count = count($array);

            if($count == 1){
                $word = h($array[0]);
                $where = 'WHERE `keyword` ='."'".$word."'";
                $res = $this->data->selectAll($column, $table, $where);
                $id = array();
                foreach($res as $recode){
                    array_push($id, $recode['siteinfo_id']);
                }
                return $id;
            }else if($count == 2){
                $word = h($array[0]);
                $where = 'WHERE `keyword` ='."'".$word."'";
                $res = $this->data->selectAll($column, $table, $where);

                $words = h($array[1]);
                $ids = array();
                foreach($res as $recode){
                    $where = 'WHERE `keyword` ='."'".$words."'".'AND `siteinfo_id` ='."'".$recode['siteinfo_id']."'";
                    $res = $this->data->select($column, $table, $where);
                    array_push($ids, $res['siteinfo_id']);
                }
                return $ids;
            }else{
                $word = h($array[0]);
                $where = 'WHERE `keyword` ='."'".$word."'";
                $res = $this->data->selectAll($column, $table, $where);

                $words = h($array[1]);
                $ids = array();
                foreach($res as $recode){
                    $where = 'WHERE `keyword` ='."'".$words."'".'AND `siteinfo_id` ='."'".$recode['siteinfo_id']."'";
                    $res = $this->data->select($column, $table, $where);
                    array_push($ids, $res['siteinfo_id']);
                }
                $word2 = h($array[2]);
                $idarray = array();
                foreach($ids as $id){
                    $where = 'WHERE `keyword` ='."'".$word2."'".'AND `siteinfo_id` ='."'".$id."'";
                    $res = $this->data->select($column, $table, $where);
                    array_push($idarray, $res['siteinfo_id']);
                }
                return $idarray;
            }        
        }

        // キーワード検索からのサイト情報
        public function searchwordSiteinfo($array){
            
            // $count = 1;
            // foreach($array as $recode){
            //     if($count == 1){
            //         $where = 'SELECT siteinfos.title, siteinfos.url, siteinfos.image, siteKeywords.keyword FROM `siteinfos` INNER JOIN `siteKeywords` ON siteinfos.siteinfo_id = siteKeywords.siteinfo_id WHERE siteinfos.siteinfo_id = '."'".$recode."'";
            //     }else{
            //         $where .= 'OR '."'".$recode."'";
            //     }
            //     $count++;
            // }
            
            // $res = $this->data->selectInnerJoin($where);
            $table = 'siteinfos';
            $column = '*';
            $count = 1;
            foreach($array as $recode){
                if($count == 1){
                    $where = 'WHERE `siteinfo_id` ='."'".$recode."'";
                }else{
                    $where .= 'OR `siteinfo_id` ='."'".$recode."'";
                }
                $count++;
            }
            $res = $this->data->selectAll($column, $table, $where);
            

            //キーワード追記
            $table = 'siteKeywords';
            $column = '`side`, `keyword`';
            for($i = 0;$i<count($res);$i++){
                $where = 'WHERE `siteinfo_id` ='."'".$res[$i]['siteinfo_id']."'";
                $result = $this->data->selectAll($column, $table, $where);
                $res[$i]['keyword'][$i] = $result;
            }
           
            return $res;
        }

        /////////////////////////////////////////////////
        // UPDATE部分
        /////////////////////////////////////////////////
        public function photo($path, $dataid){
            $table = 'siteinfos';
            $values = '`image` ='."'".$path."'";
            $where = 'WHERE `siteinfo_id` ='."'".$dataid."'";
            $res = $this->data->update($table, $values, $where);
            return $res;
        }

        // プロフィール写真
        public function facephoto($path){
            $table = 'profiles';
            $values = '`photo` ='."'".$path."'";
            $where = '';
            $res = $this->data->update($table, $values, $where);
            return $res;
        }

        public function siteinfoUpdate($columntitle, $data, $id){
            $table = 'siteinfos';
            $values = '`'.$columntitle.'` = '."'".$data."'";
            $where = 'WHERE `siteinfo_id` ='."'".$id."'";
            $res = $this->data->update($table, $values, $where);
            return $res;
        }

















    }