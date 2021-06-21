<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
} //endif

if( ! class_exists( 'CCC_Browsing_History_List' ) ) {
  class CCC_Browsing_History_List {

    public static function action() {
      /*** お気に入りの投稿のデータ（カンマ連結の文字列）を取得 ***/
      if ( is_user_logged_in() === false ) {
        $post_ccc_browsing_history = sanitize_text_field( $_POST['ccc-browsing_history'] );
        $browsing_history_post_ids = explode(',', $post_ccc_browsing_history); // 指定した値で分割した文字列の配列を返す
      } else {
        /* MySQLのユーザーメタ（usermeta）からユーザーが選んだ投稿IDを取得 */
        $user_browsing_history_post_ids = get_user_meta( wp_get_current_user()->ID, CCC_Browsing_History::CCC_BROWSING_HISTORY_POST_IDS, true );
        //var_dump($favorite_post_user);
        $browsing_history_post_ids = explode(',', $user_browsing_history_post_ids);
      }
      $browsing_history_post_ids = array_map('htmlspecialchars', $browsing_history_post_ids); // 配列データを一括でサニタイズする

      /*** 表示数の定義（指定が無ければ管理画面の表示設定（表示する最大投稿数）の値を取得） ***/
      if( isset( $_POST['ccc-posts_per_page'] ) ) {
        $posts_per_page = absint( $_POST['ccc-posts_per_page'] ); //負ではない整数に変換
      } else {
        $posts_per_page = get_option('posts_per_page');
      }

      /*** ポストタイプの定義（指定が無ければ "any"） ***/
      if( isset( $_POST['ccc-post_type'] ) and $_POST['ccc-post_type'] !== 'any' ) {
        $post_type = explode(',', $_POST['ccc-post_type']); //文字列をカンマで区切って配列に変換
        $post_type = str_replace(array(" ", "　"), "", $post_type); //配列の各要素の中にある半角空白と全角空白を取り除く（""に置き換え）
        $post_type = array_map('sanitize_text_field', $post_type); // 配列データを一括でサニタイズする
      } else {
        $post_type = 'any'; // リビジョンと 'exclude_from_search' が true にセットされたものを除き、すべてのタイプを含める
      }
      //print_r($post_type);

      $args= array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'post__in' => $browsing_history_post_ids,
        'orderby' => 'post__in',
      );
      $the_query = new WP_Query($args);
?>

<?php if( $the_query->have_posts() ) { ?>
<div id="post-ccc_browsing_history">
  <?php
                                      $count = 0;
                                      while( $the_query->have_posts() ) {
                                        $the_query->the_post();
                                        $count++;
  ?>
  <div class="list-ccc_browsing_history clearfix">
    <div class="img-post">
      <a href="<?php the_permalink(); ?>">
        <?php
                                        if( has_post_thumbnail() ) {
                                          echo '<div class="img-post-thumbnail has_post_thumbnail"><img src="'.get_the_post_thumbnail_url($the_query->post->ID, 'medium').'" alt="'.$the_query->post->post_title.'" loading="lazy" /></div>';
                                        } else {
                                          echo '<div class="img-post-thumbnail has_post_thumbnail-no"><img src="'.CCC_Post_Thumbnail::get_first_image_url($the_query->post).'" alt="'.$the_query->post->post_title.'" loading="lazy" /></div>';
                                        }
        ?>
      </a>
    </div><!-- /.img-post -->
    <h3 class="title-post"><a href="<?php the_permalink(); ?>" class="dotted-line"><?php the_title(); ?></a></h3><!-- /.title-post -->
  </div><!-- /.list-series -->
  <?php } //endwhile ?>
</div><!-- /.post-series -->
<?php
                                      wp_reset_postdata(); /* オリジナルの投稿データを復元 */
                                     }//endif

    } //endfunction
  }//endclass
}//endif
