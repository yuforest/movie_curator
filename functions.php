<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), false);
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );

function my_scripts() {
  wp_enqueue_style( 'my-drawer-style', 'https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/css/drawer.min.css', array(), '3.2.1' );
  wp_enqueue_style( 'my-font-awesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );
  wp_enqueue_script( 'my-scroll-js', 'https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll.min.js', array( 'jquery' ), '5.1.3', true );
  wp_enqueue_script( 'my-drawer-js', 'https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/js/drawer.min.js', array( 'my-scroll-js' ), '3.2.1', true );
}
add_action( 'wp_enqueue_scripts', 'my_scripts' );

/* メニューの位置設定 */
function register_my_menu() {
  register_nav_menu( 'my-drawer', 'ドロワーメニュー' );
}
add_action( 'after_setup_theme', 'register_my_menu' );


//コメント文言を変更
function custom_comment_form($args) {
  $args['comment_notes_before'] = '';
  $args['comment_notes_after'] = '';
  $args['label_submit'] = '送信';
  return $args;
}
add_filter('comment_form_defaults', 'custom_comment_form');

// 「コメントを残す」の文言を変更する
add_filter( 'comment_form_defaults', 'my_title_reply');
  function my_title_reply($defaults){
  $defaults['title_reply'] = '';
    return $defaults;
}
function my_comment_form_remove($arg) {
  $arg['email'] = '';
  $arg['url'] = '';
  $arg['author'] = '';
  $arg['wp-comment-cookies-consent'] = '';
  return $arg;
}
add_filter('comment_form_default_fields', 'my_comment_form_remove');

function change_comment($args) {
  $args['comment_field'] = '<textarea class="form-control" id="comment" name="comment" aria-required="true" cols="45" rows="8" placeholder="コメントを入力"></textarea>';
  return $args;
}
add_filter('comment_form_defaults', 'change_comment');

function move_comment_field_to_bottom($arg) {
  $comment_field = $arg['comment'];
  unset( $arg['comment'] );
  $arg['comment'] = $comment_field;
  return $arg;
}
add_filter( 'comment_form_fields', 'move_comment_field_to_bottom' );
