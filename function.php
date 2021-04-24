<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
require( CCCBROWSINGHISTORY_PLUGIN_PATH .'/assets/list.php' );
require( CCCBROWSINGHISTORY_PLUGIN_PATH .'/addons/ccc-post_thumbnail/ccc-post_thumbnail.php' );


class CCC_Browsing_History {

  const CCC_BROWSING_HISTORY_POST_IDS = 'ccc_browsing_history_post_ids';

  /*** Initial execution ***/
  public function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'jquery_check' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'save_scripts' ) );
    add_action( 'wp_ajax_ccc_browsing_history-update-action', array( $this, 'usermeta_browsing_history_update') );
    add_action( 'wp_ajax_ccc_browsing_history-get-action', array( $this, 'usermeta_browsing_history_get') );

    add_action( 'wp_enqueue_scripts', array( $this, 'list_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'list_scripts' ) );
    add_action( 'wp_ajax_ccc_browsing_history-list-action', array( $this, 'list_posts_action' ) );
    add_action( 'wp_ajax_nopriv_ccc_browsing_history-list-action', array( $this, 'list_posts_action' ) );
  } //endfunction

  public function jquery_check() {
    wp_enqueue_script('jquery');
  } //endfunction

  public function save_scripts() {
    if( ! ( is_home() or is_front_page() ) and ( is_single() or is_page() ) ) {
      global $post;
      $post_id = $post->ID;
    }
    $handle = 'ccc_browsing_history-save-js';
    $file = 'save.js';
    wp_register_script( $handle, CCCBROWSINGHISTORY_PLUGIN_URL.'/assets/'.$file, array( 'jquery' ), CCCBROWSINGHISTORY_PLUGIN_VERSION, true );
    wp_enqueue_script( $handle );

    $action_update = 'ccc_browsing_history-update-action';
    wp_localize_script( $handle, 'CCC_BROWSING_HISTORY_UPDATE',
                       array(
                         'api'    => admin_url( 'admin-ajax.php' ),
                         'action' => $action_update,
                         'nonce'  => wp_create_nonce( $action_update ),
                         'post_id' => $post_id,
                         'user_logged_in' => is_user_logged_in()
                       )
                      );

    $action_get = 'ccc_browsing_history-get-action';
    wp_localize_script( $handle, 'CCC_BROWSING_HISTORY_GET',
                       array(
                         'api'    => admin_url( 'admin-ajax.php' ),
                         'action' => $action_get,
                         'nonce'  => wp_create_nonce( $action_get )
                       )
                      );
  } //endfunction

  /*** お気に入りの投稿をユーザーメタ（usermeta）に追加 ***/
  public function usermeta_browsing_history_update() {
    if( check_ajax_referer( $_POST['action'], 'nonce', false ) ) {
      /* 保存された値でメタデータを更新する（もしくはまだそのフィールドが存在しなければ新規作成する）ための関数 */
      update_user_meta( wp_get_current_user()->ID, self::CCC_BROWSING_HISTORY_POST_IDS, sanitize_text_field( $_POST['post_ids'] ) );
      $data = get_user_meta( wp_get_current_user()->ID, self::CCC_BROWSING_HISTORY_POST_IDS, true );
    } else {
      //status_header( '403' );
      $data = null;
    }
    echo $data;
    /* スクリプト終了時のメッセージを削除（注意：admin-ajax.phpの仕様でwp_die('0');があるためレスポンスの値に「0」が含まれる）*/
    die(); //メッセージは無しで現在のスクリプトを終了する（メッセージは空にする）
  } //endfunction

  /*** ユーザーメタに保存されたお気に入りの投稿を取得 ***/
  public function usermeta_browsing_history_get() {
    if( check_ajax_referer( $_POST['action'], 'nonce', false ) ) {
      $data = get_user_meta( wp_get_current_user()->ID, self::CCC_BROWSING_HISTORY_POST_IDS, true );
    } else {
      //status_header( '403' );
      $data = null;
    }
    echo $data;
    /* スクリプト終了時のメッセージを削除（注意：admin-ajax.phpの仕様でwp_die('0');があるためレスポンスの値に「0」が含まれる）*/
    die(); //メッセージは無しで現在のスクリプトを終了する（メッセージは空にする）
  } //endfunction




  public function list_styles() {
    wp_enqueue_style( 'ccc_browsing_history-list-css', CCCBROWSINGHISTORY_PLUGIN_URL.'/assets/list.css', array(), CCCBROWSINGHISTORY_PLUGIN_VERSION, 'all');
  } //endfunction

  public function list_scripts() {
    $handle = 'ccc_browsing_history-list-js';
    $file = 'list.js';
    wp_register_script( $handle, CCCBROWSINGHISTORY_PLUGIN_URL.'/assets/'.$file, array( 'jquery' ), CCCBROWSINGHISTORY_PLUGIN_VERSION, true );
    wp_enqueue_script( $handle );

    $action = 'ccc_browsing_history-list-action';
    wp_localize_script( $handle, 'CCC_BROWSING_HISTORY_LIST',
                       array(
                         'api'    => admin_url( 'admin-ajax.php' ),
                         'action' => $action,
                         'nonce'  => wp_create_nonce( $action )
                       )
                      );
  } //endfunction

  public function list_posts_action() {
    if( check_ajax_referer( $_POST['action'], 'nonce', false ) ) {
      $data = CCC_Browsing_History_List::action();
    } else {
      //status_header( '403' );
      $data = 'Forbidden';
    }
    echo $data;
    die();
  } //endfunction


} //endclass











