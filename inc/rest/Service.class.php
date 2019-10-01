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
            //     return current_user_can('edit_posts'); // JMS - this requires a user security cookie
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
     *     Version: "0.1.0",
     *     Description: "This WordPress plugin demonstrates how to setup a plugin that uses React and ES6 in a WordPress plugin. <cite>By <a href="https://matthias-web.com">Matthias Guenter</a>.</cite>",
     *     Author: "<a href="https://matthias-web.com">Matthias Guenter</a>",
     *     AuthorURI: "https://matthias-web.com",
     *     TextDomain: "wp-reactjs-starter",
     *     DomainPath: "/languages",
     *     Network: false,
     *     Title: "<a href="https://matthias-web.com/wordpress">WP ReactJS Starter</a>",
     *     AuthorName: "Matthias Guenter"
     * }
     * @apiVersion 0.1.0
     */
    public function routePlugin() {
        return new \WP_REST_Response(general\Core::getInstance()->getPluginData());
    }

    public function routeTest() {
        return new \WP_REST_Response('TEST');
    }

    public function routeColors() {
        $data = array(
            array('name' => 'programmer red', 'color' => 'ff0000'),
            array('name' => 'bad cat', 'color' => 'badca7')
        );
        return new \WP_REST_Response($data);
    }

    public function routeColorsAdd($request_data) {
        $parameters = $request_data->get_params();
        $body = $request_data->get_body();
        $data = array('response' => 'success', 'request_json' => json_decode($body));
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
