/*
* Author: Takashi Matsuyama
* Author URI: https://profiles.wordpress.org/takashimatsuyama/
* Description: WordPressで投稿の閲覧履歴をローカルストレージとMySQLのユーザーメタ（wp_usermeta）に保存
* Version: 1.0.0 or later
*/

/* グローバルネームスペース */
/* MYAPP = CCC */
var CCC = CCC || {};

(function($){
  /* サブネームスペース */
  CCC.browsing_history = {

    /* 初期設定（グローバル変数を設定） */
    //data_elm : $('body'),
    //data_key : 'post_id-history',

    storage_key : function() {
      var key = 'ccc-browsing_history'; // 過去に閲覧した投稿を保存するストレージキーの名前を指定
      return key;
    }, // メンバのメソッドを定義

    action : function(history_key, history_value) {
      /*** 実行本体：過去に閲覧した投稿を保存する関数 ***/
      var post_id = CCC_BROWSING_HISTORY_UPDATE.post_id;
      //console.log(post_id);
      if( post_id ) {
        /* 過去に閲覧した投稿を配列に追加（または新規作成） */
        if (history_value === null) {
          var history_value_array = []; // 新たに配列を作成
          history_value_array.unshift(post_id); // 配列の最初に1つ以上の要素を追加し、新しい配列の長さを返す
        } else {
          var history_value_array = history_value.split(','); // カンマで分割して配列にする
          var value_index = history_value_array.indexOf(String(post_id)); // ストレージの値は文字列に変換しているので、indexOfの指定も数値を文字列に変換する必要がある
          if (value_index === -1) {
            history_value_array.unshift(post_id); // 配列の最初に1つ以上の要素を追加し、新しい配列の長さを返す
          } else {
            //console.log("重複している投稿ID："+ post_id);
            history_value_array.splice(value_index, 1); // インデックスn番目から、1つの要素を削除（重複してたら、それを削除）
            history_value_array.unshift(post_id); // 配列の最初に1つ以上の要素を追加し、新しい配列の長さを返す（重複してたら、既存を削除してから改めて先頭に追加）
          }
        }
        /* 配列から「null」や「undefined」、「"" 空文字」が入った要素を削除する */
        history_value_array = history_value_array.filter(function(v){
          return !(v === null || v === undefined || v === ""); // 0は除くためfilter(Boolean)は使わない
        });
        /* 保存する投稿の数を制限：配列の数がX個以上ある場合、Y個に減らす */
        if( history_value_array.length > 100 ){
          history_value_array = history_value_array.slice( 0, 100 ); /* 開始位置と終了位置を指定（開始位置は0から数えて終了位置の値は含まない） */
        }
        history_value_array_str = history_value_array.join(','); // 配列要素の連結・結合：配列を連結して1つの文字列に変換
        /* ログインユーザーでは無い場合 */
        if( CCC_BROWSING_HISTORY_UPDATE.user_logged_in == false ) {
          localStorage.setItem(history_key, history_value_array_str); // 指定したキーのローカルストレージに過去に閲覧した投稿の文字列データを保存
        } else {
          /* ログインユーザーの場合は過去に閲覧した投稿をMySQLのユーザーメタ（wp_usermeta）に保存 */
          $.ajax({
            url : CCC_BROWSING_HISTORY_UPDATE.api, // admin-ajax.phpのパスをローカライズ（wp_localize_script関数）
            type : 'POST',
            data : {
              action : CCC_BROWSING_HISTORY_UPDATE.action, // wp_ajax_フックのサフィックス
              nonce : CCC_BROWSING_HISTORY_UPDATE.nonce,
              post_ids : history_value_array_str
            },
          }).fail( function(){
            console.log('browsing_history_update : ajax error');
          }).done( function(response){
            //console.log(response)
          });
        } //endif
      } //endif
    } // メンバのメソッドを定義

  }; // サブネームスペース





  /*** 初回ロード：過去に閲覧した投稿を保存 ***/
  /* ログインユーザーでは無い場合 */
  if( CCC_BROWSING_HISTORY_UPDATE.post_id ) {
    if( CCC_BROWSING_HISTORY_UPDATE.user_logged_in == false ) {
      var history_key = CCC.browsing_history.storage_key(); // 過去に閲覧した投稿を保存するストレージキーの名前を変数に格納（CCC.browsing_historyのstorage_key関数を呼び出し）
      var history_value = localStorage.getItem(history_key); // ローカルストレージから指定したキーの値を取得
      CCC.browsing_history.action(history_key, history_value); // CCC.browsing_historyのaction関数を呼び出し
    } else {
      $.ajax({
        url : CCC_BROWSING_HISTORY_GET.api, // admin-ajax.phpのパスをローカライズ（wp_localize_script関数）
        type : 'POST',
        data : {
          action : CCC_BROWSING_HISTORY_GET.action, // wp_ajax_フックのサフィックス
          nonce : CCC_BROWSING_HISTORY_GET.nonce // wp_ajax_フックのnonce
        },
      }).fail( function(){
        console.log('browsing_history_get : ajax error');
      }).done( function(response){
        var history_key = ''; // ログインユーザーはローカルストレージを使用しないのでストレージキーは不要
        var history_value = response; // MySQLのユーザーメタ（wp_usermeta）から過去に閲覧した投稿の値を取得
        CCC.browsing_history.action(history_key, history_value); // CCC.browsing_historyのaction関数を呼び出し
      });
    }
  } //endif

})(jQuery);
