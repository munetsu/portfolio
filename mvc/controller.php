<?php

    include('model.php');
    include('view.php');
    include('funcs.php');
    class CONTROLLER{
        function __construct(){
            $this->model = new MODEL;
            $this->view = new VIEW;
            $this->POST = $_POST['action'];
            $this->judge();
        }

        ///////////////////////////////////////////////////////// 
        //処理分岐
        /////////////////////////////////////////////////////////
        public function judge(){

            // profile登録
            if($this->POST == 'setProfile'){
                // バリデーション
                $array['name'] = h($_POST['name']);
                $array['facebook'] = h($_POST['facebook']);
                $array['twitter'] = h($_POST['twitter']);
                $array['introduce'] = h($_POST['introduce']);

                // profile登録
                $res = $this->model->profileInsert($array);
                if(!$res){
                    echo 'NG';
                    exit();
                }

                // キーワード登録
                if(!($_POST['keyword'])){
                    echo 'Nokeyword';
                    exit();
                }else{
                    // キーワード登録
                    $res = $this->model->keywordInsert($_POST['keyword']);
                    if(!$res){
                        echo 'NG';
                        exit();
                    }else{
                        // index.phpへ
                        exit();
                    }
                }
            }

            // 登録ボタン押した処理（ajax）
            if($this->POST == 'register'){
                $views = $this->view->viewRegisterUrl();
                echo $views;
            };

            // サイト名とサイトURL登録
            if($this->POST == 'registerTitle'){
                $data = h($_POST['data']);
                $columntitle = h($_POST['column']);
               
                // 条件分岐
                if(($_POST['step']) == 0){
                    // 重複データがあればデータを返す
                    $res = $this->model->siteCheck($columntitle, $data);
                    if($res != ''){
                        // JSONにEncode
                        echo 'NG';
                        return;
                    }else{
                        // データ登録
                        $res = $this->model->siteRegister($columntitle, $data);
                        // データ保存の確認
                        if(!$res){
                            echo 'error';
                            return;
                        }
                    }  
                }else{
                    $id = h($_POST['step']);
                    
                    // データ更新
                    $res = $this->model->siteinfoUpdate($columntitle, $data, $id);
                    // データ登録の確認
                    if($res != NULL){
                        echo 'error';
                        return;
                    }
                }

                

                if(($_POST['step']) == 0){
                    $res = $this->model->siteCheck($columntitle, $data);
                    $id = $res['siteinfo_id'];

                    // url登録へ
                    $views = $this->view->viewRegisterTitle($id);
                    echo $views;
                }else{
                    //キーワード登録へ
                    $view = $this->view->viewKeywords($id);
                    echo $view;
                }
            }

            // サイトキーワード
            if($this->POST == 'siteKeyword'){
                
                $id = h($_POST['id']);
                $flont = $_POST['flont'];
                $server = $_POST['server'];
                
                // modelへ
                $this->model->siteKeyword($id, $flont, $server);

                // 写真登録へ
                $view = $this->view->viewRegisterPhoto($id);
                echo $view;
            }

            // 写真追加
            if($this->POST == 'upload'){

                
                // 送信ファイル
                if (isset($_FILES["upfile"] ) && $_FILES["upfile"]["error"] ==0 ) {
                    $file = $_FILES['upfile'];
                    $path = $this->upload($file);
                }else{
                    $this->view->errorview();
                }

                // プロフィール写真の場合
                if(($_POST['key']) == 'face'){
                    $res = $this->model->facephoto($path);
                    if($res == "NULL"){
                        // error画面へ
                        $this->view->errorview();
                    }else{
                        header('location: ../index.php');
                        exit();
                    }
                }

                // データID
                $dataid = $_POST['id'];
                // DB登録
                $res = $this->model->photo($path, $dataid);
                
                if($res === "NULL"){
                    // error画面へ
                    $this->view->errorview();
                }else{
                    header('location: ../index.php');
                    exit();
                }
            }

            // サイト検索
            if($this->POST == 'searchWords'){
                // 検索ワード
                $words = $_POST['words'];
                
                $res = $this->model->searchwords($words);
                
            }
        }
        

        ///////////////////////////////////////////////////////
        // 関数一覧
        ///////////////////////////////////////////////////////
        private function upload($FILES){
            $fileData = $FILES;
    
            // width,height指定
            $keyScore = 400;
            
            // var_dump($fileData);

            // 加工するファイル指定
            $file = $fileData["tmp_name"];
            // 加工前の画像の情報を取得
            list($original_w, $original_h, $type) = getimagesize($file);
    
            // 縦長or横長の判定
            if($original_w > $original_h){
                $longLength = $original_w;
            }else if($original_w == $original_h){
                $longLength = $original_w;
            }else{
                $longLength = $original_h;
            }

            // var_dump($longLength);
            
            // 基準値を超えている場合
            if($longLength > $keyScore){
                $stand = $longLength / $keyScore;
                $w = $original_w / $stand;
                $h = $original_h / $stand;
            }else{
                // 基準値以下の場合
                $w = $original_w;
                $h = $original_h;
            }
    
            // 加工前のファイルをフォーマット別に読み出す（この他にも対応可能なフォーマット有り）
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $original_image = imagecreatefromjpeg($file);
                    break;
                case IMAGETYPE_PNG:
                    $original_image = imagecreatefrompng($file);
                    break;
                case IMAGETYPE_GIF:
                    $original_image = imagecreatefromgif($file);
                    break;
                default:
                    throw new RuntimeException('対応していないファイル形式です。: ', $type);
            }

            // var_dump($original_image);
    
            // 新しく描画するキャンバスを作成
            $canvas = imagecreatetruecolor($w, $h);
            // 画像の透過情報が消えてしまうのを防ぐ
            //ブレンドモードを無効にする
            imagealphablending($canvas, false);
            //完全なアルファチャネル情報を保存するフラグをonにする
            imagesavealpha($canvas, true);
            imagecopyresampled($canvas, $original_image, 0,0,0,0, $w, $h, $original_w, $original_h);
    
            $name = $fileData['name']; //ファイル名取得
            $extension = pathinfo($name, PATHINFO_EXTENSION); //拡張子取得(jpg, png, gif)
            $datetime = date("YmdHis"); //日付取得
            $uniq_name = $datetime."." . $extension;  //ユニークファイル名作成
            $file_dir_path = "../upload/";  //画像ファイル保管先
    
            // FileUpload [--Start--]
            if ( is_uploaded_file( $file ) ) {
                if ( move_uploaded_file( $file, $file_dir_path.$uniq_name ) ) {
                    chmod( $file_dir_path.$uniq_name, 0644 );
    
                    switch ($type) {
                        case IMAGETYPE_JPEG:
                            imagejpeg($canvas, $file_dir_path.$uniq_name);
                            break;
                        case IMAGETYPE_PNG:
                            imagepng($canvas, $file_dir_path.$uniq_name, 9);
                            break;
                        case IMAGETYPE_GIF:
                            imagegif($canvas, $file_dir_path.$uniq_name);
                            break;
                    }
    
                    // 読み出したファイルは消去
                    imagedestroy($original_image);
                    imagedestroy($canvas);
                    
                    return $uniq_name;
    
                } else {
                    $this->view->errorview();
                }
            }
        }
        

    }

    $controller = new CONTROLLER;