<?php
/**
 * This file is part of WHMCS-PHP-API.
 *
 * @copyright 2014 Léo Lam
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Innovate\WHMCS\Model;

class API {

    /**
     * URL to the WHMCS api.php file.
     *
     * @var string
     */
    private $url;

    /**
     * Constructor function
     *    
     */
    public function __construct($url) {
        $this->setUrl($url);
    }
    
    /**
     * Sends a request to the API.
     * 
     * @param string $username the admin username
     * @param string $password the admin password encoded using md5
     * @param string $action   the requested action, see: http://docs.whmcs.com/API:Functions
     * @param array  $data     the data to pass to the API call
     * @return array|bool      return the response array on success, false on error.
     */
    public function request($username, $password, $action, $data = array()) {
        $data = array_merge(
            array(
                'username' => $username,
                'password' => $password,
                'action' => $action,
                'responsetype' => 'json',
            ),
            $data
        );
        $options = array(
            'http' => array(
                'method' => 'POST',
                'content' => http_build_query($data),
            )
        );

        $context = stream_context_create($options);
        $result = json_decode(file_get_contents($this->getUrl(), false, $context), true);
        
        return $result;
    }
    
    /* Getters */
    public function getUrl() {
        return $this->url;
    }
    
    /* Setters */
    public function setUrl($url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('The URL is invalid.');
        }

        $this->url = $url;
    }

}

