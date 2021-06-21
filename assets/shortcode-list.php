<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

if( ! class_exists( 'CCC_Browsing_History_ShortCode_List' ) ) {

  add_shortcode('ccc_browsing_history_list_results', array('CCC_Browsing_History_ShortCode_List', 'results') );

  class CCC_Browsing_History_ShortCode_List {

    public static function results($atts) {
      $atts = shortcode_atts(array(
        "title" => '',
        "posts_per_page" => '',
        "class" => '',
        "style" => '',
        "post_type" => '',
        "post__not_in" => '',
      ),$atts);
      if( $atts['title'] ) {
        $title = $atts['title'];
      } else {
        $title = __('Browsing history', CCCBROWSINGHISTORY_TEXT_DOMAIN);
      }
      if( $atts['posts_per_page'] and ctype_digit($atts['posts_per_page']) ) {
        $posts_per_page = $atts['posts_per_page'];
      } else {
        $posts_per_page = 8;
      }
      if( $atts['class'] ) {
        $class = 'class="'.$atts['class'].'"';
      } else {
        $class = null;
      }
      if( $atts['style'] or $atts['style'] === 0 or $atts['style'] === '0' ) {
        $style = $atts['style'];
      } else {
        $style = 1;
      }
      if( $atts['post_type'] ) {
        $post_type = $atts['post_type'];
      } else {
        $post_type = 'any';
      }
      $data = '<div id="content-ccc_browsing_history">';
      $data .= '<p class="title-section">'.$title.'</p>';
      $data .= '<div id="ccc-browsing_history-list" data-ccc_browsing_history-list-style="'.$style.'" data-ccc_browsing_history-posts_per_page="'.$posts_per_page.'" data-ccc_browsing_history-post_type="'.$post_type.'" '.$class.'></div>'; //<!-- /#ccc-browsing_history-list -->
      $data .= '</div>'; //<!-- /#content-ccc_browsing_history -->
      return $data;
    } //endfunction

  } //endclass
} //endif
