<?php

/**
 * @package Disable_Menuitems
 * @version 1.0
 */
/*
  Plugin Name: Disable Menuitems
  Plugin URI:
  Description: Remove some admin menuitems
  Author: Hannes Kirsman
  Version: 1.0
  Author URI:
 */

/**
 * Implements admin_menu()
 * @global object $menu
 * @global object $user_ID
 */
function remove_menus() {
  global $menu, $user_ID;
  $user = new WP_User($user_ID);

  if ($user->data->ID != 1) {
    $restricted = array(
      __('Dashboard'),
      //__('Media'),
      //__('Links'),
      __('Appearance'),
      __('Posts'),
      __('Tools'),
      __('Users'),
      __('Settings'),
      __('Comments'),
      __('Plugins')
    );
    end($menu);
    while (prev($menu)) {
      $value = explode(' ', $menu[key($menu)][0]);
      if (in_array($value[0] != NULL ? $value[0] : "", $restricted)) {
        unset($menu[key($menu)]);
      }
    }

    add_menu_page('Menus', 'Menus', 'manage_options', 'nav-menus.php');
  }
}
add_action('admin_menu', 'remove_menus');

/**
 * Implements wp_before_admin_bar_render()
 * @global object $wp_admin_bar
 */
function custom_admin_bar_render() {
  global $wp_admin_bar, $user_ID;
  $user = new WP_User($user_ID);

  if ($user->data->ID != 1) {
    $wp_admin_bar->remove_menu('new-post');
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('dashboard');

    $wp_admin_bar->add_menu(array(
      'parent' => 'site-name', // use 'false' for a root menu, or pass the ID of the parent menu
      'id' => 'list_pages', // link ID, defaults to a sanitized title value
      'title' => __('Pages'), // link title
      'href' => admin_url('edit.php?post_type=page'), // name of file
      'meta' => false // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
    ));
  }
}
add_action('wp_before_admin_bar_render', 'custom_admin_bar_render');


function media_plus_redirect() {
  $request = trim($_SERVER['REQUEST_URI'], '/');
  if ($request === 'wp-admin') {
    wp_safe_redirect('/wp-admin/edit.php?post_type=page');
    die();
  }
}
add_filter('setup_theme', 'media_plus_redirect');