<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

if( ! class_exists( 'CCC_Browsing_History_Uninstall' ) ) {
  class CCC_Browsing_History_Uninstall {
    public static function delete_usermeta() {
      $meta_key = 'ccc_browsing_history_post_ids';
      delete_metadata( 'user', null, $meta_key, '', true );
    } //endfunction
  } //endclass
} //endif
