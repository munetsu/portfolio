///////////////////////////////////////////////////// 
// 変数一覧
/////////////////////////////////////////////////////
// プロフィールシート設定
let keyword = [];

// プロフィール
info;
console.log(info);
// キーワード
words;
console.log(words);
// サイト
siteInfo;
console.log(siteInfo.length);

// 登録状況
step;
console.log(step);
///////////////////////////////////////////////////// 
// 関数一覧
///////////////////////////////////////////////////// 
// サイトリスト処理
function siteList(siteInfo){
    let view = '';
    for(let i=0;i<siteInfo.length;i++){
        view += viewSiteCard(siteInfo, i);
    }
    return view;
}

///////////////////////////////////////////////////// 
// 読み込み時処理
/////////////////////////////////////////////////////
if(step == 2){
    $('.profile').append(viewProfile(info));
    $('.productarea').append(viewSitelist());
    $('.lists').append(siteList(siteInfo));
}



///////////////////////////////////////////////////// 
// クリック処理
/////////////////////////////////////////////////////

///////////////////////////////////
// プロフィール入力
//////////////////////////////////
// キーワード設定
$(document).on('click', '.settingBtn', function(){
    let word = $('input[name=keyword]').val();
    if(word == ''){
        alert('キーワードを入力してください');
        return;
    }
    // 配列に追加
    keyword.push(word);
    // インプット欄を空欄に
    $('input[name=keyword]').val('');
    // キーワード欄に追加
    $('.keywordarea').append(viewKeyword(word));
})

// キーワード削除
$(document).on('click', '.delete', function(){
    console.log(keyword);
    let word = $(this).attr('data-word');
    // 配列削除
    keyword.splice(word, 1);
    // スペースからも削除
    $('div[data-word='+word+']').remove();
    console.log(keyword);
})

// プロフィール登録
$(document).on('click', '.sendBtn', function(e){
    e.preventDefault();
    // 値取得
    // 名前
    let name = $('input[name=name]').val();
    if(name == ''){
        alert('ニックネーム（名前）の入力をお願いします');
        return;
    }
    // facebook
    let facebook = $('input[name=facebook]').val();
    if(facebook == ''){
        if(!confirm('Facebookは登録しませんか？')){
            return;
        }else{
            
        }
    }
    // twitter
    let twitter = $('input[name=twitter]').val();
    if(twitter == ''){
        if(!confirm('twitterは登録しませんか？')){
            return;
        }else{
            
        }
    }
    // 自己紹介
    let introduce = $('textarea[name=introduce]').val();
    if(introduce == ''){
        if(!confirm('自己紹介は登録しませんか？')){
            return;
        }else{
            
        }
    }

    // ajax処理
    $.ajax({
        url: 'mvc/controller.php',
        type:'POST',
        data:{
            action:'setProfile',
            name:name,
            facebook:facebook,
            twitter: twitter,
            introduce:introduce,
            keyword:keyword
        }
    })
    .done((data)=>{
        if(data == 'NG'){
            alert('データ登録に失敗しました\n既に同じ名前での登録がされています');
            return;
        }
        // データ登録完了
        alert('プロフィール登録が完了しました');
        window.location.reload();
    })
    .fail((data)=>{
        console.log('失敗'+data)
    })
})

///////////////////////////////////
// プロフィール変更
//////////////////////////////////
// 写真変更
$(document).on('click', '.changePhoto', function(){
    $('.change').empty();
    $('.change').append(viewChangePhoto());
})


///////////////////////////////////
// サイト登録
//////////////////////////////////
// 登録ボタンを押した時
$(document).on('click', '.registerBtn', function(e){
    e.preventDefault();
    $.ajax({
        url:'mvc/controller.php',
        type:'POST',
        data:{
            action:'register'
        }
    })
    .done((data)=>{
        $('.productarea').html(data);
    })
    .fail((data)=>{
        alert('通信環境をご確認ください');
        return;
    })
})

// 登録ボタンを押した時
$(document).on('click', '.btn', function(e){
    e.preventDefault();
    let data = $('input[name=site]').val()
    let step = $(this).attr('data-id');
    let title = '';
    if(step == 0){
        title = 'url';
    }else{
        title = 'title';
    }

    // ajax
    $.ajax({
        url:'mvc/controller.php',
        type:'POST',
        data:{
            action:'registerTitle',
            data:data,
            column:title,
            step:step
        }
    })
    .done((data)=>{
        if(data == 'error'){
            alert('データ登録でエラーが発生しました');
            return
        }else if(data == 'NG'){
            alert('既に登録されています');
            $('input[name=sitename]').val('');
            return;
        }else{
            $('.right').html(data);
        }
    })
    .fail((data)=>{
        alert('ネットワーク環境をご確認ください');
    })
})

// 写真登録ボタンを押した時
$(document).on('click', '.uploadBtn', function(e){
    e.preventDefault();
    photoUp.submit();
})



///////////////////////////////////////////////////// 
// VIEW処理
/////////////////////////////////////////////////////
// キーワード設定
function viewKeyword(word){
    let view = `
        <div class="words" data-word="`+word+`">
            <p class="word">`+word+`<span><img src="img/icon/batsu.svg" class="delete" data-word="`+word+`"></span></p>
        </div>
    `;
    return view;
}

// プロフィール部分
function viewProfile(info){
    let photourl = info['photo'];
    if(photourl == 'noimage.svg'){
        photourl = 'img/icon/noimage.svg'
    }else{
        photourl = 'upload/'+info['photo'];
    }

    let view = `
        <div class="picture">
            <img src="`+photourl+`" alt="face" class="face">
            <div class="change">
            <button class="changePhoto">写真変更</button>
            </div>
        </div>
        <div class="username">
            <p class="name">`+info['name']+`</p>
        </div>
        <div class="snsarea">
            <ul class="snsicon">
    `;
    if(info['facebook'] != null){
        view += `
            <li><a href="https://facebook.com/`+info['facebook']+`" target="_blank"><i class="fab fa-facebook"></i></a></li>
        `;
    }
    if(info['twitter'] != null){
        view += `
            <li><a href="https://twitter.com/`+info['twitter']+`" target="_blank"><i class="fab fa-twitter"></i></a></li>
        `;
    }
    view += `
            </ul>
        </div>
        <div class="introduce">
            <p class="introtitle">【自己紹介】</p>
            <pre>`+info['introduce']+`</pre>
        </div>
    `;
    return view;
}

// 写真変更
function viewChangePhoto(){
    let view = `
        <form action="mvc/controller.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <input type="hidden" name="key" value="face">
            <input type="file" name="upfile">
            <button class="sendfile">写真変更</button>
        </form>
    `;
    return view;
}

// サイト一覧部分
function viewSitelist(){
    let view = `
        <div class="searcharea">
            キーワード検索
            <input type="text" name="search"><i class="fas fa-search serachbtn"></i>
        </div>
        <div class="lists">
        </div>    
    `;
    return view;
}

// サイトカード部分
function viewSiteCard(siteInfo, i){
    let view = `
        <div class="card">
            <p class="sitetitle"><a href="`+siteInfo[i]['url']+`" target="_blank">`+siteInfo[i]['title']+`</a></p>
            <img src="upload/`+siteInfo[i]['image']+`" class="siteImg">
        </div>
    `;
    return view;
}
