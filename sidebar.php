<?php
/*
Plugin Name: Add Multiple Sidebar
Plugin URI: #
Description: Add Multiple Sidebar
Version: 1.0
Author: Utsav Tilava
Author URI: https://profiles.wordpress.org/utsav72640
Text Domain: add-multiple-sidebar

Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
/**
 * @author    https://profiles.wordpress.org/utsav72640
 * @copyright Copyright (c) 2018, Clean to Shine
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @version   1.0
 */

//avoid direct calls to this file
if ( !defined( 'ABSPATH' ) ) { exit; }

class AMS_MAIN_CLASS
{
    
    function __construct() {
        add_action( 'plugins_loaded', array( $this,'ams_load_plugin_textdomain' ) );
        add_action( 'widgets_admin_page', array( $this,'ams_enqueue_scripts' ) );
        add_action( 'widgets_admin_page', array( $this,'ams_form_method' ) );
        add_action( 'widgets_init', array( $this,'ams_register_multiple_sidebar' ) );
        add_action( 'wp_ajax_ams_delete_sidebar', array( $this,'ams_delete_sidebar' ) );
        add_action( 'wp_ajax_nopriv_ams_delete_sidebar', array( $this,'ams_delete_sidebar' ) );
    }
    
    /*==============================================================
        Load Plugin Textdomain
    /*==============================================================*/
    public function ams_load_plugin_textdomain() {
        load_plugin_textdomain( 'add-multiple-sidebar', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /*==============================================================
        Enqueue Script And Css
    /*==============================================================*/
    public function ams_enqueue_scripts() {
        wp_register_style( 'sidebar-css', plugin_dir_url( __FILE__ ). '/css/sidebar.css', false, '1.0.0' );
        wp_enqueue_style( 'sidebar-css' );

        wp_register_script( 'sidebar-js', plugin_dir_url( __FILE__ ). '/js/sidebar.js', false, '1.0.0' );
        wp_enqueue_script( 'sidebar-js' );
    }

    /*==============================================================
       Add Input Field to Add Dyanmic Sidebar
    /*==============================================================*/
    public function ams_form_method() {
        $sidebar_form = '';
        $sidebar_form .= '<form method="post" action="">';
            $sidebar_form .= '<h3 class="sidebar-title">'.esc_html__('Add Multiple Sidebar' , 'add-multiple-sidebar').'</h3>';
            $sidebar_form .= '<div id="cs-options" class="csb cs-options" >';
                $sidebar_form .= '<input type="text" class="inwidget" name="sidebar1" placeholder="'.esc_html__( 'Sidebar name' , 'add-multiple-sidebar' ).'" >';    
                $sidebar_form .= '<button type="submit" class="button button-primary cs-action btn-create-sidebar" >';
                    $sidebar_form .= '<i class="dashicons dashicons-plus-alt"></i>';
                    $sidebar_form .= esc_html__('Add New Sidebar' , 'add-multiple-sidebar');
                $sidebar_form .= '</button>';
            $sidebar_form .= '</div>';
        $sidebar_form .= '</form>';
        printf( '%s', $sidebar_form );
    }

    /*==============================================================
       Add Sidebar Data Process
    /*==============================================================*/
    public function ams_register_multiple_sidebar() {
        $wp_cstm = get_option( 'add_new_multiple_sidebar' );

        if( empty($wp_cstm) ) {
            /* when full empty */
            if( !empty($_POST['sidebar1']) ) {
                $array=array();
                $firstside[] = sanitize_text_field($_POST['sidebar1']);
                array_push($array,$firstside);
                update_option( 'add_new_multiple_sidebar', $firstside );
            }
            elseif( (empty($wp_cstm)) && (!empty($_POST['sidebar1'])) ) {
                $firstside[] = sanitize_text_field($_POST['sidebar1']);
                add_option( 'add_new_multiple_sidebar', $firstside);
            }
        }
        elseif( !empty($_POST['sidebar1']) ) {
            $array = array();
            $newarry = array();
            $tet = 0;
            foreach ( $wp_cstm as $value ) {
                $newarry[] = $value;
                if( $value == $_POST['sidebar1'] ) {
                    $tet=1;
                    echo '<script>';
                        echo 'alert("Name Is Already Used!!!")';
                    echo '</script>';
                }
            }
            if( $tet == 0 ) {  
              $text = sanitize_text_field($_POST['sidebar1']);
              array_push($newarry,$text);
              update_option( 'add_new_multiple_sidebar', $newarry );
            }
        }

        $newupdte = get_option( 'add_new_multiple_sidebar' );
        if( !empty($newupdte) ) {
            // Register the script
            foreach ( $newupdte as $key => $value ) {
                register_sidebar( array(
                    'name' => $value,
                    'id' => str_replace(" ","_",$value),
                    'class'=>'multiple-sidebar-custom',
                    'before_widget' => '<div class="widcut">',
                    'after_widget' => '</div>',
                    'before_title' => '<h1 class="widcustom">',
                    'after_title' => '</h1>',
                ) );
            }
        }
    }

    /*==============================================================
       Ajax Using Delete Sidebar
    /*==============================================================*/
    public function ams_delete_sidebar() {
        $wp_cstm = get_option( 'add_new_multiple_sidebar' );
        $ret = 0;
        if( !empty( $_REQUEST['id'] ) ) {
            $id = sanitize_text_field($_REQUEST['id']);
            $idd = str_replace(" ","_",$id);
            $add = array();
            foreach( $wp_cstm as $value ) {
                $v = str_replace(" ","_",$value);
                if( $idd != $v ) {
                    $add[]=$value;
                    $ret=1;
                }
            }
            update_option( 'add_new_multiple_sidebar', $add );
        }
    }
}
new AMS_MAIN_CLASS();