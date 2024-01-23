<?php
/*
Plugin Name: VI: Scroll Reveal
Plugin URI: http://neathawk.us
Description: A collection of Scroll Reveal Settings
Version: 0.1.221220
Author: Joseph Neathawk
Author URI: http://Neathawk.us
License: GNU General Public License v2 or later
*/
/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
# define default variables
# Generic Plugin Functions
# Generic PHP functions
# Shortcodes (are plugin territory)
--------------------------------------------------------------*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/*--------------------------------------------------------------
# define default variables
--------------------------------------------------------------*/
define( 'VI_SCROLLREVEAL', '0.1.221220' );
define( 'VI_SCROLLREVEAL_DB', '0.1.221220' );


class vi_scrollreveal {
/*--------------------------------------------------------------
# Generic Plugin Functions
--------------------------------------------------------------*/

/**
 * INIT plugin and create DB tables
 *
 * @link
 */
public static function init()
{
    if( !vi_scrollreveal::plugin_is_up_to_date() )
    {
        //access DB
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        /*
        //DB version 0.1
        $sql = "CREATE TABLE " . $wpdb->prefix."vi_scrollreveal (
        id int( 60 ) UNSIGNED NOT NULL AUTO_INCREMENT,
        email_address text NOT NULL,
        name_first text NOT NULL,
        name_last text NOT NULL,
        name_company text NOT NULL,
        user_id int(30) NOT NULL,
        timestamp int(30) NOT NULL,
        UNIQUE KEY id ( id )
        );";
        dbDelta( $sql );

        update_option( 'vi_scrollreveal_db', VI_SCROLLREVEAL_DB );
        //*/
    }

    //set options if they don't already exist
    /*
    if( get_option( 'vi_scrollreveal_last_update', false ) == false )
    {
        update_option( 'vi_scrollreveal_last_update', current_time( 'timestamp' ) );
    }
    //*/
}


/**
 * check if version is up to date
 *
 * @link
 */
public static function plugin_is_up_to_date()
{
    return ( floatval(get_option( "vi_scrollreveal_db", 0 )) >= VI_SCROLLREVEAL_DB ? true : false );
}

/**
 * deactivate plugin, remove SOME data
 *
 * @link
 */
public static function deactivate()
{
    //delete_option('vi_scrollreveal_db');
}

/**
 * uninstall plugin, remove ALL data
 *
 * @link
 */
public static function uninstall()
{
    if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    {
        exit();
    }
    //delete options
    delete_option( 'vi_scrollreveal_db' );

    //drop custom db table
    //global $wpdb;
    //$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}vi_scrollreveal" );
}

/**
 * ENQUEUE SCRIPTS AND STYLES
 *
 * @link https://developer.wordpress.org/themes/basics/including-css-javascript/#stylesheets
 * wp_register_style( string $handle, string|bool $src, string[] $deps = array(), string|bool|null $ver = false, string $media = 'all' )
 * wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer);
 */
public static function enqueue_scripts() {

    //style for the plugin
    //wp_register_style( 'vi_scrollreveal-style', plugins_url( '/vi_scrollreveal.css', __FILE__ ), NULL , VI_SCROLLREVEAL , 'all' );
    //wp_enqueue_style( 'vi_scrollreveal-style' );

    //scroll reveal
    wp_register_script( 'vi_scrollreveal-js', 'https://knighthawk0811.github.io/scrollreveal/scrollreveal.min.js', array( 'jquery' ), false, false );
    wp_enqueue_script('vi_scrollreveal-js');
}


/**
 * DEFER SCRIPTS AND STYLES
 *
 * @version 0.1.220726
 * @since 0.1.220726
 * @link https://kinsta.com/blog/defer-parsing-of-javascript/
 * @link https://barebones.dev/articles/how-to-defer-scripts-in-wordpress/
 * @link https://wp-mix.com/defer-async-wordpress-enqueued-scripts/
 */
public static function defer_parsing_of_js( $tag, $handle, $src ) 
{
    //Skip Conditions
    if ( is_user_logged_in() ) 
    {
        //don't break WP Admin
        //we don't need the SEO performance anyway
        return $tag; 
    }
    if ( strpos( $tag, 'jquery.js' ) ) 
    {
        //don't defer jQuery, things will break
        return $tag; 
    }
    if ( FALSE === strpos( $tag, '.js' ) ) 
    {
        //do nothing if it isn't JS
        return $tag; 
    }
    //ACTION
    if ( strpos( $tag, '-defer-' ) )
    {
        //add defer to the tag
        return '<script defer="" src="' . $src . '" id="' . $handle . '" ></script>';
        //return str_replace( ' src', ' defer="" src', $tag );
        //return $tag; 
    }
    //ALL Remaining Scripts, probably breaks things
    //return '<script defer="" src="' . $src . '" id="' . $handle . '" ></script>';
    //return str_replace( ' src', ' defer="" src', $tag );
    
    //FAILSAFE
    return $tag; 
}
//action/filter see AFTER class


/**
 * place code in the head
 *
 * @version 0.2.221220
 * @link https://developer.wordpress.org/reference/functions/wp_head/
 */
public static function set_default_meta_tags()
{
    $content = '';
    $content .= '<!-- add meta tags here -->';
    return $content;
}

/**
 * place code in the head
 *
 * @version 0.2.220824
 * @link https://developer.wordpress.org/reference/functions/wp_head/
 */
public static function insert_head()
{

    // Insert extra tags here
    ?>

    <?php

    //Insert Deferred JS Here
    /*
        <script defer="" id="vi_scrollreveal-defer-js" src="file.js?ver=<?php echo(VI_SCROLLREVEAL); ?>" ></script>
    //*/  
    ?>

    <?php
    //*/
    
    //Insert Critical CSS Here
    /*
        <link rel="stylesheet" id="vi_scrollreveal-style-critical-css"  href="style.css?ver=<?php echo(VI_SCROLLREVEAL); ?>" media="all" />
        <!-- preconnect for  deferred -->
        <link rel="preconnect" href="https://cdn.jsdelivr.net">
    //*/  
    ?>
        
    <?php
    //*/

    
    //Insert NON-Critical CSS Here
    /*
        <link rel="stylesheet" id="vi_scrollreveal-style-defer-css" href="style.css?ver=<?php echo(VI_SCROLLREVEAL); ?>" media="print" onload="this.media='all'; this.onload=null;">
    //*/  
    ?>
     
    <?php
    //*/
    

}

/**
 * place code just after the opening body tag
 *
 * @version 0.2.220311
 * @link https://developer.wordpress.org/reference/functions/wp_body_open/
 */
public static function insert_body_open()
{

    //*
    ?>
        
    <?php
    //*/
}

/**
 * output stuff in the footer
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_footer/
 * @version 0.1.221220
 * @since 0.1.220214
 */
public static function insert_foot()
{
?>
<script>
    //default
    options = {
        delay: 200,
        interval: 200,
        reset: false,
        viewOffset: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal', options);
    
    //reveal every time you scroll past
    options = {
        delay: 200,
        interval: 200,
        reset: true,
        viewOffset: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-multi', options);
    
    //mobile only
    options_m = {
        desktop: false,
        mobile: true,
        delay: 200,
        interval: 100,
        reset: false,
        viewOffset: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-mobile', options_m);
    options_m = {
        desktop: false,
        mobile: true,
        delay: 200,
        interval: 100,
        reset: true,
        viewOffset: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-mobile-multi', options_m);

    //desktop only
    options_d = {
        desktop: true,
        mobile: false,
        delay: 200,
        interval: 200,
        reset: false,
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-desktop', options_d);
    options_d = {
        desktop: true,
        mobile: false,
        delay: 200,
        interval: 200,
        reset: true,
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-desktop-multi', options_d);
    
    //sliding up from the bottom
    options_up = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: false,
        distance: '300px',
        origin: 'bottom',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-up', options_up);
    options_up = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: true,
        distance: '300px',
        origin: 'bottom',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-up-multi', options_up);
    
    //sliding down from the top
    options_down = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: false,
        distance: '300px',
        origin: 'top',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-down', options_down);
    options_down = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: true,
        distance: '300px',
        origin: 'top',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-down-multi', options_down);
    
    //sliding in from the left
    options_left = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: false,
        distance: '300px',
        origin: 'left',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-left', options_left);
    options_left = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: true,
        distance: '300px',
        origin: 'left',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-left-multi', options_left);
    
    //sliding in from the right
    options_right = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: false,
        distance: '300px',
        origin: 'right',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-right', options_right);
    options_right = {
        mobile: false,
        delay: 200,
        interval: 200,
        reset: true,
        distance: '300px',
        origin: 'right',
        viewOffset: {
            top: 50,
            right: 0,
            bottom: 50,
            left: 0,
        }
    }
    ScrollReveal().reveal('.sreveal-right-multi', options_right);
</script>

<?php
}

/*--------------------------------------------------------------
# Generic PHP functions
--------------------------------------------------------------*/
/*--------------------------------------------------------------
# Shortcodes (are plugin territory)
--------------------------------------------------------------*/

}// class vi_scrollreveal

/*--------------------------------------------------------------
>>> Filters, Hooks, and Shortcodes:
----------------------------------------------------------------*/
# define default variables
# Generic Plugin Functions
register_activation_hook( __FILE__, Array(  'vi_scrollreveal', 'init' ) );
register_deactivation_hook( __FILE__, Array(  'vi_scrollreveal', 'deactivate' ) );
register_uninstall_hook( __FILE__, Array(  'vi_scrollreveal', 'uninstall' ) );
add_action( 'wp_enqueue_scripts', Array(  'vi_scrollreveal', 'enqueue_scripts' ), 100 );
add_filter( 'script_loader_tag', Array( 'vi_scrollreveal', 'defer_parsing_of_js' ), 10, 3 );

add_action('wp_head', Array(  'vi_scrollreveal', 'set_default_meta_tags' ) );
add_action('wp_head', Array(  'vi_scrollreveal', 'insert_head' ) );
add_action('wp_body_open', Array(  'vi_scrollreveal', 'insert_body_open' ) );
add_action('wp_footer', Array(  'vi_scrollreveal', 'insert_foot' ) );
# Generic PHP functions
# Shortcodes (are plugin territory)
/*--------------------------------------------------------------*/