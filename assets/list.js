/*
* Author: Takashi Matsuyama
* Author URI: https://profiles.wordpress.org/takashimatsuyama/
* Description: WordPressで投稿の閲覧履歴を一覧で表示
* Version: 1.3.0 or later
*/

/*
* /browsing-history/save.js：別途、読み込みが必要（サブネームスペースで共通の変数と共通の関数を定義しているため）
*/

/* グローバルネームスペース */
/* MYAPP = CCC */
var CCC = CCC || {};

(function($) {
  var content_area_elm = $('#content-ccc_browsing_history');
  var post_area_elm = $('#ccc-browsing_history-list');

  var history_key = CCC.browsing_history.storage_key(); // 過去に閲覧した投稿を保存するストレージキーの名前を変数に格納（CCC.favoriteのstorage_key関数を呼び出し）
  var history_value = localStorage.getItem(history_key); // ローカルストレージから指定したキーの値を取得
  var data_set = {}; // オブジェクトのキーに変数を使用：ES5までの書き方（IE11以下への対応のため）
  data_set['action'] = CCC_BROWSING_HISTORY_LIST.action;
  data_set['nonce'] = CCC_BROWSING_HISTORY_LIST.nonce;
  data_set[history_key] = history_value;
  data_set['ccc-posts_per_page'] = post_area_elm.data('ccc_browsing_history-posts_per_page');
  data_set['ccc-post_type'] = post_area_elm.data('ccc_browsing_history-post_type');

  $.ajax({
    type: 'POST',
    url: CCC_BROWSING_HISTORY_LIST.api,
    data: data_set
  }).fail( function(){
    alert('error');
  }).done( function(response){
    post_area_elm.html(response);
    if( post_area_elm.find('.list-ccc_browsing_history').length < 1 ) {
      content_area_elm.hide();
    }
  });
})(jQuery);
