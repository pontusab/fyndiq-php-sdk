<?php
/**
 * Copyright (c) 2014 Pontus Abrahamsson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package Fyndiq
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author Pontus Abrahamsson
 */

namespace Fyndiq;

final class Api {
{

    /**
     * Api endpoint
     *
     * @access public
     * @var    string
    */
    public $endpoint = 'https://fyndiq.se/api/';


    /**
     * Api version
     *
     * @access public
     * @var    string
    */
    public $version = 'v1';


    /**
     * Api method parameter
     *
     * @access public
     * @var    string
    */
    public $method;


    /**
     * Test api request 
     *
     * @access public
     * @var    bool
    */
    public static $test;


    /**
     * Http Headers
     *
     * @access public
     * @var    array
    */
    public $headers = [   
        'Accept: application/json',
        'Content-Type: application/json',
    ];


    /**
     * SDK user agent
     *
     * @access public
     * @var    string
    */
    public $user_agent = 'Fyndiq-PHP-SDK';


    /**
     * Fyndiq user
     *
     * @access protected
     * @var    string
    */
    protected static $user;


    /**
     * Fyndiq api-key
     *
     * @access protected
     * @var    string
    */
    protected static $api_key;


    /**
     * Set user and API-key
     *
     * @access public
     * @var    string
    */
    public static function init( $user, $api_key, $test = false )
    {
        self::$user    = $user;
        self::$api_key = $api_key;
        self::$test    = $test;
    }
 

    /**
     * Request the specific resource
     *
     * @access public
     * @var    string
    */
    public function request( $resource, $method = 'GET', $data = null, $id = null )
    {
        // Build request url
        $url = $this->endpoint . $this->version .'/'. $resource . '/';

        if( $id ) 
        { 
            $url .= $id . '/'; 
        }

        $url .= '?';

        // Add user and api key
        $url .= http_build_query([
            'user'    => self::$user,
            'token'   => self::$api_key,
            'format'  => 'json',
            'test'    => self::$test
        ]);

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $this->headers );
        curl_setopt( $curl, CURLOPT_USERAGENT, $this->user_agent );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

        switch( $method ) 
        {
            case 'GET':
            break;
            case 'POST':
                curl_setopt( $curl, CURLOPT_POST, true );
                curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $data ) );
            break;
            case 'PUT':
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
                curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $data ) );
            break;
            case 'DELETE':
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );
            break;
        }

        // Make request and return decoded response
        $response = curl_exec( $curl );

        $response = json_decode( $response, true );

        return $response;
    }
}