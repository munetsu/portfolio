<?php
/** 共通で使うものを別ファイルにしておきましょう。*/

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

/**
* XSS
* @Param:  $str(string) 表示する文字列
* @Return: (string)     サニタイジングした文字列
*/
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function chkSsid(){
  if(!isset($_SESSION["chk_ssid"]) ||
  $_SESSION["chk_ssid"] != session_id()
  ){exit("LOGIN ERROR");
  }else{
    session_regenerate_id(true);
    $_SESSION["chk_ssid"] = session_id();
  }
}

// JSON_ENCODE
function json($array){
  $array = JSON_ENCODE($array,JSON_UNESCAPED_UNICODE);
  return $array;
}


?>
