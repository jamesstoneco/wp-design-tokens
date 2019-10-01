<?php
namespace MatthiasWeb\WPRJSS\rest;
use MatthiasWeb\WPRJSS\base;
use MatthiasWeb\WPRJSS\general;

defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request

/**
 * Create a REST Service.
 */
class Service extends base\Base {
    /**
     * The namespace for this service.
     *
     * @see getUrl()
     */
    const SERVICE_NAMESPACE = 'wprjss/v1';
    const TABLE_NAMESPACE = 'wprjss';
    const TABLE_NAME = 'wprjss_colors';

    /**
     * Register endpoints.
     */
    public function rest_api_init() {
        register_rest_route(Service::SERVICE_NAMESPACE, '/plugin', [
            'methods' => 'GET',
            'callback' => [$this, 'routePlugin']
        ]);
        register_rest_route(Service::SERVICE_NAMESPACE, '/test', [
            'methods' => 'GET',
            'callback' => [$this, 'routeTest']
        ]);
        register_rest_route(Service::SERVICE_NAMESPACE, '/colors', [
            'methods' => 'GET',
            'callback' => [$this, 'routeColors']
        ]);
        register_rest_route(Service::SERVICE_NAMESPACE, '/colors/add', [
            'methods' => 'POST',
            'callback' => [$this, 'routeColorsAdd']
            // 'permission_callback' => function () {
            //     return current_user_can('administrator'); // JMS - this requires a user security cookie
            // }
        ]);
    }

    /**
     * @api {get} /wprjss/v1/plugin Get plugin information
     * @apiHeader {string} X-WP-Nonce
     * @apiName GetPlugin
     * @apiGroup Plugin
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     WC requires at least: "",
     *     WC tested up to: "",
     *     Name: "WP ReactJS Starter",
     *     PluginURI: "https://matthias-web.com/wordpress",
     *     Version: "1.0.0",
     *     Description: "This WordPress plugin demonstrates how to setup a plugin that uses React and ES6 in a WordPress plugin. <cite>By <a href="https://matthias-web.com">Matthias Guenter</a>.</cite>",
     *     Author: "<a href="https://matthias-web.com">Matthias Guenter</a>",
     *     AuthorURI: "https://matthias-web.com",
     *     TextDomain: "wp-reactjs-starter",
     *     DomainPath: "/languages",
     *     Network: false,
     *     Title: "<a href="https://matthias-web.com/wordpress">WP ReactJS Starter</a>",
     *     AuthorName: "Matthias Guenter"
     * }
     */
    public function routePlugin() {
        return new \WP_REST_Response(general\Core::getInstance()->getPluginData());
    }

    /**
     * @api {get} /wprjss/v1/test Test
     * @apiHeader {string} X-WP-Nonce
     * @apiName Test
     * @apiGroup Test
     *
     * @apiSuccessExample {text} Success-Response:
     * TEST
     */

    public function routeTest() {
        return new \WP_REST_Response('TEST');
    }

    /**
     * @api {get} /wprjss/v1/colors Get Colors
     * @apiHeader {string} X-WP-Nonce
     * @apiName GetColor
     * @apiGroup Color
     *
     */

    public function routeColors() {
        $data = array(
            array('name' => 'programmer red', 'color' => 'ff0000'),
            array('name' => 'bad cat', 'color' => 'badca7')
        );
        global $wpdb;
        $table_name = $this->getTableName('colors');
        $query = 'SELECT * from ' . $table_name;
        $results = $wpdb->get_results($query, OBJECT);
        // $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'posts LIMIT 10');
        return new \WP_REST_Response($results);
    }

    /**
     * @api {get} /wprjss/v1/colors/add Add Color
     * @apiHeader {string} X-WP-Nonce
     * @apiName AddColor
     * @apiGroup Color
     *
     */

    public function routeColorsAdd($request_data) {
        global $wpdb;
        $parameters = $request_data->get_params();
        $body = $request_data->get_body();
        $table_name = $this->getTableName('colors');
        $data_to_insert = json_decode($body, true); // true because we want an associative array
        $fake_data = array(
            'color_name' => 'turn that off',
            'color_hex' => '0ff0ff'
        );
        $format = array('%s', '%s');
        $results = $wpdb->insert($table_name, $data_to_insert);
        $new_id = $wpdb->insert_id;
        $data = array(
            'response' => 'success',
            'id' => $new_id
        );
        return new \WP_REST_Response($data);
    }

    /**
     * Get the wp-json URL for a defined REST service.
     *
     * @param string $namespace The prefix for REST service
     * @param string $endpoint The path appended to the prefix
     * @returns String Example: https://example.com/wp-json
     * @example Service::url(Service::SERVICE_NAMESPACE) // => main path
     */
    public static function getUrl($namespace, $endpoint = '') {
        return site_url(rest_get_url_prefix()) . '/' . $namespace . '/' . $endpoint;
    }
}
