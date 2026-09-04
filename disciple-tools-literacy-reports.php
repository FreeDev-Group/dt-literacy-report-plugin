<?php
/**
 * Plugin Name: Disciple.Tools - Literacy Reports
 * Plugin URI:  https://github.com/lmdcis/disciple-tools-literacy-reports
 * Description: Adds a "Literacy Reports" post type to track class attendance,
 *              book/lesson progress, and teacher activity for literacy programs.
 * Version:     2.0.0
 * Author:      LMDCIS Team
 * Author URI:  https://freedev.dev
 * Text Domain: disciple-tools-literacy-reports
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Disciple_Tools_Literacy_Reports
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'after_setup_theme', function() {
    // Detect Disciple.Tools theme
    $wp_theme = wp_get_theme();
    $is_dt = strpos( $wp_theme->get_template(), "disciple-tools-theme" ) !== false
          || $wp_theme->name === "Disciple Tools"
          || class_exists( 'Disciple_Tools' );

    if ( $is_dt ) {
        require_once plugin_dir_path( __FILE__ ) . 'post-type/literacy_report.php';
    } else {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error is-dismissible"><p>Disciple.Tools - Literacy Reports requires the Disciple.Tools theme to be active.</p></div>';
        } );
    }
}, 20 );
