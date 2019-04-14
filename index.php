<?php
    include('mvc/funcs.php');
    include('mvc/view.php');
    $view = new VIEW;

    // プロフィール情報取得
    include('mvc/model.php');
    $model = new MODEL;
    $table = 'profiles';
    $column = '*';
    $where = '';
    // データがない場合はBoolean値(False）
    $info = $model->anySelect($column, $table, $where);

    // 初期登録か判定
    if(!$info){
        $views = $view->viewCreateProfile();
        $step = 0;
    }else{
        // プロフィール登録済みの場合
        // jsonEncode
        $info = json($info);

        // キーワード取得
        $table = 'keywords';
        $words = $model->anySelectAll($column, $table, $where);
        // キーワードがない場合
        if(count($words) == 0){
            $words[] = 'キーワードなし';   
        }
        $words = json($words);

        // ポートフォリオ取得
        $table = 'siteinfos';
        $column = '*';
        $where = '';
        // データがない場合はBoolean値(False）
        $siteInfo = $model->anySelectAll($column, $table, $where);
        if(!$siteInfo){
            $siteInfo[] = 'サイトなし';
        }else{
            $table = 'siteKeywords';
            $column = '`side`, `keyword`';
            $count = 0;
            foreach($siteInfo as $site){
                $where = 'WHERE `siteinfo_id` ='."'".$site['siteinfo_id']."'";
                $keywords = $model->anySelectAll($column, $table, $where);
                $siteInfo[$count]['keyword'] = $keywords;
                $count++;
            }
        }
        $siteInfo = json($siteInfo);

        // サイトキーワード


        $views = $view->viewIndex();
        $step =2;
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portfolio</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>
<body>
    <?php echo $views ?>
</body>
<script>
    let info = <?php echo $info ?>;
    let words = <?php echo $words ?>;
    let siteInfo = <?php echo $siteInfo ?>;
    let step = <?php echo $step ?>;
</script>
<script src="js/index.js"></script>
</html>