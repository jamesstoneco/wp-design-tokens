<?php
namespace MatthiasWeb\WPRJSS\general;
use MatthiasWeb\WPRJSS\base;

defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request

/**
 * The activator class handles the plugin relevant activation hooks: Uninstall, activation,
 * deactivation and installation. The "installation" means installing needed database tables.
 */
class Activator extends base\Base {
    /**
     * Method gets fired when the user activates the plugin.
     */

    public function activate() {
        // Your implementation...
        // global $wpdb;
        // $charset_collate = $wpdb->get_charset_collate();
        // $sql = "CREATE TABLE ${Activator::TABLE_NAME} (
        //     id mediumint(9) NOT NULL AUTO_INCREMENT,
        //     color_name varchar(55) NOT NULL,
        //     color_hex varchar(6) NOT NULL,
        //     UNIQUE KEY id (id)
        // ) $charset_collate;";
        // if (!function_exists('dbDelta')) {
        //     require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        // }
        // dbDelta($sql);
    }

    /**
     * Method gets fired when the user deactivates the plugin.
     */
    public function deactivate() {
        // Your implementation...
    }

    /**
     * Install tables, stored procedures or whatever in the database.
     *
     * @param boolean $errorlevel Set true to throw errors
     * @param callable $installThisCallable Set a callable to install this one instead of the default
     */
    public function install($errorlevel = false, $installThisCallable = null) {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        // Avoid errors printed out
        if ($errorlevel === false) {
            $show_errors = $wpdb->show_errors(false);
            $suppress_errors = $wpdb->suppress_errors(false);
            $errorLevel = error_reporting();
            error_reporting(0);
        }

        // Table wp_wprjss
        if ($installThisCallable === null) {
            // Your table installation here...
            $table_name = $this->getTableName('colors');
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                color_name varchar(55) NOT NULL,
                color_hex varchar(6) NOT NULL,
                UNIQUE KEY id (id)
    		) $charset_collate;";
            dbDelta($sql);

            if ($errorlevel) {
                $wpdb->print_error();
            }
        } else {
            call_user_func($installThisCallable);
        }

        if ($errorlevel === false) {
            $wpdb->show_errors($show_errors);
            $wpdb->suppress_errors($suppress_errors);
            error_reporting($errorLevel);
        }

        if ($installThisCallable === null) {
            update_option(WPRJSS_OPT_PREFIX . '_db_version', WPRJSS_VERSION);
        }
    }
}
