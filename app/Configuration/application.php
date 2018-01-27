<?php
/**
 * ------------------------------------------
 * - Default Application Configuration File -
 * ------------------------------------------
 *
 * This file defines a few important details,
 * namely the default routes, and additional
 * re-mapped URLs that will be picked up
 * by the router and routed as written
 * which will skip the automagic
 * routing by default
 */

return [
    /**
     * Application Name
     */
    "app_name" => "HelloWorld",
    /**
     * Base URL
     */
    "base_url" => "https://luminance.local/",
    /**
     * Default route
     *
     * @note This is used exclusively by the router
     */
    "default_route" => "HelloWorld/index",
    /**
     * Routes List
     *
     * @note This remaps the request URI's to
     * specific controllers, ie:
     *
     * "/example" => array(
     *  "controller" => "Test/examplePage"
     * )
     */
    "routes" => array(
        "/example" => array(
            "controller" => "Test/examplePage"
        )
    )
];