<?php
    include('view.php');
    $view = new VIEW;

    if (isset($_FILES["upfile"] ) && $_FILES["upfile"]["error"] ==0 ) {
        $fileData = ($_FILES['upfile']);

        // width,height指定
        $keyScore = 400;

        // 加工するファイル指定
        $file = $fileData["tmp_name"];
        // 加工前の画像の情報を取得
        list($original_w, $original_h, $type) = getimagesize($file);

        // 縦長or横長の判定
        if($original_w > $original_h){
            $longLength = $original_w;
        }else{
            $longLength = $original_h;
        }
        
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


        // var_dump($original_w, $original_h, $type);
        // exit();
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
                
                // index.phpへ
                header('location: ../index.php');

            } else {
                $view->errorview();
            }
        }
    }else{
        // error用のview
        echo 'error';
        $view->errorview();
    }