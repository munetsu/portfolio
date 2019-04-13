<?php

    class VIEW{
        function __construct(){
        }

        // index.php部分
        public function viewIndex(){
            $view = '
                <div>
                    <div class="header">
                        <div class="logoarea">
                            <a href=""><img src="img/logo.png" class="logo"></a>
                        </div>
                        <div class="sitename">
                            <p>ポートフォリオ</p>
                        </div>
                        <div class="registerarea">
                            <a href="" class="registerBtn">サイト登録</a>
                        </div>
                    </div>
                    <div class="body">
                        <div class="left profile">
                        </div>
                        <div class="right productarea">
                        </div>
                    </div>
                </div>
            ';
            return $view;
        }

        // 登録画面１（サイトタイトル入力）
        public function viewRegisterTitle($m){
            $view = '
                <div class="siteregisterarea">
                    <div class="inputarea">
                        サイト名：<br>
                        <input type="text" name="site">
                    </div>
                    <div class="btnarea">
                        <a href="" class="btn" data-id="'.$m.'">タイトル登録</a>
                    </div>                    
                </div>    
            ';
            return $view;
        }

        // 登録画面2（サイトイメージ入力）
        public function viewRegisterPhoto($id){
            $view = '
                <div class="siteregisterarea">
                    <div class="inputarea">
                        <form action="mvc/controller.php" method="POST" name="photoUp" enctype="multipart/form-data">
                        <input type="hidden" name="id" value='.$id.'>
                        <input type="hidden" name="action" value="upload">
                        サイトイメージ：<input type="file" name="upfile">
                        <div class="btnarea">
                            <a href="" class="uploadBtn">写真登録</a>
                        </div>                    
                        </form>
                        <div class="photoarea">
                        </div>
                    </div>
                </div>
            ';
            return $view;
        }

        // 登録画面3（サイトUrl入力）
        public function viewRegisterUrl(){
            $view = '
                <div class="siteregisterarea">
                    <div class="inputarea">
                        サイトUrl：<br>
                        <input type="text" name="site">
                    </div>
                    <div class="btnarea">
                        <a href="" class="btn" data-id=0>URL登録</a>
                    </div>                    
                </div>    
            ';
            return $view;
        }

        // error画面
        public function errorview(){
            $view = '
                <div>
                <p>エラーです。</p>
                </div>
            ';
            echo $view;
            exit();
        }

        // プロフィール登録画面
        public function viewCreateProfile(){
            $view = '
                <div class="formarea">
                    <h3>プロフィールシートの入力をお願いします</h3>
                    <p class="comment"><span>*</span>は必須</p>
                    <div class="block">
                        <p class="title">【ニックネーム（名前）】<span>*</span></p>
                        <input type="text" name="name" maxlength="32" placeholder="ニックネーム（名前）を入力">
                    </div>
                    <div class="block">
                        <p class="title">【Facebookアカウント】</p>
                        <div class="sns">https://www.facebook.com/
                        <input type="text" name="facebook">
                        </div>
                    </div>
                    <div class="block">
                        <p class="title">【Twitterアカウント】</p>
                        <div class="sns">https://www.twitter.com/
                        <input type="text" name="twitter">
                        </div>
                    </div>
                    <div class="block">
                        <p class="title">【自己紹介(400文字以内)】</p>
                        <textarea cols="40" rows="10" maxlength="400" name="introduce"></textarea>
                    </div>
                    <div class="block">
                        <p class="title">【キーワード設定（1つ20文字以下）】</p>
                        <input type="text" maxlength="20" name="keyword"><button class="settingBtn">設定</button>
                        <div class="keywordarea">
                        </div>
                    </div>
                    <div class="sendform">
                        <a href="" class="sendBtn">登録</a>
                    </div>
                </div>
            ';
            return $view;
        }

    }
