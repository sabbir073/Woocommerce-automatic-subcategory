<?php
/**
* @package Automatic_Subcategories_To_Menu
*/
/*
Plugin Name:  Automatic Subcategories To Menu
Plugin URI:   https://github.com/sabbir073
Description:  This plugin will show all subcategories under the parent category in the nav menu. It works with Woocommerce product category only.
Version:      1.0.0
Author:       Amicritas IT Ltd.
Author URI:   https://amicritas.com
License:      GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  Automatic_Subcategories_To_Menu
*/


defined( 'ABSPATH' ) or die( 'Hey! You can not access to this' );

add_action( 'admin_menu', 'automaticoptionadmin' );
        
add_action('admin_enqueue_scripts','automatimenuadmin');

add_option( 'automatic_menu_id', '' );


//add option menu fuction
function automaticoptionadmin(){
    add_menu_page( 'Automatic Subcategories To Menu', 'Automatic Menu', 'manage_options', 'automaticmenu', 'automaticOptionPage', 'dashicons-align-center', 100 );


    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'automatic_menu_action_links' );
    
    function automatic_menu_action_links ( $actions ) {
    $automaticlinks = array(
        '<a href="' . admin_url( 'admin.php?page=automaticmenu' ) . '">Settings</a>',
        '<a href="https://www.paypal.com/paypalme/Shamimaaktherriya" target="_blank" style="color:red; font-weight:bold;">Donate</a>',
    );
    $actions = array_merge( $actions, $automaticlinks );
    return $actions;
    }
}

//All style and scripts
function automatimenuadmin($HOOK){
    if('toplevel_page_automaticmenu' !== $HOOK ){
        return;
    }
    wp_register_style( 'admin-styles',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
    wp_enqueue_style( 'admin-styles' );


}


//Plugin page view
function automaticOptionPage(){

    require_once(plugin_dir_path( __FILE__ ) . '/includes/dashboard.php');
}



function automaticnavmenuitem( $title, $url, $order, $parent = 0 ){
    $item = new stdClass();
    $item->ID = 1000000 + $order + $parent;
    $item->db_id = $item->ID;
    $item->title = $title;
    $item->url = $url;
    $item->menu_order = $order;
    $item->menu_item_parent = $parent;
    $item->type = '';
    $item->object = '';
    $item->object_id = '';
    $item->classes = array();
    $item->target = '';
    $item->attr_title = '';
    $item->description = '';
    $item->xfn = '';
    $item->status = '';
    return $item;
  }
  add_filter("wp_get_nav_menu_items", function ($items, $menu, $args) {
      $menu_id = intval(get_option('automatic_menu_id'));
      if( $menu->term_id != $menu_id ) return $items; 
      if (is_admin()) {
          return $items;
      }
      $ctr = ($items[sizeof($items)-1]->ID)+1;
      foreach ($items as $index => $i)
      {
          if ("product_cat" !== $i->object) {
              continue;
          }
          $menu_parent = $i->ID;
          $terms = get_terms( array('taxonomy' => 'product_cat', 'parent'  => $i->object_id ) );
          foreach ($terms as $term) {
              $new_item = automaticnavmenuitem( $term->name, get_term_link($term), $ctr, $menu_parent );
              $items[] = $new_item;
              $new_id = $new_item->ID;
              $ctr++;
              $terms_child = get_terms( array('taxonomy' => 'product_cat', 'parent'  => $term->term_id ) );
              if(!empty($terms_child))
              {
                  foreach ($terms_child as $term_child)
                  {
                      $new_child = automaticnavmenuitem( $term_child->name, get_term_link($term_child), $ctr, $new_id );
                      $items[] = $new_child;
                      $ctr++;
                  }
              }
          }
      }
  
      return $items;
  }, 10, 3);