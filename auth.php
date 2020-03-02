<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Authentication Plugin: CAS Authentication
 *
 * Authentication using CAS (Central Authentication Server) with possibility to synchronize attribute between Moodle and CAS
 *
 * @package         auth
 * @subpackage      cassync
 * @author          Martin Dougiamas | Jerome GUTIERREZ | Iñaki Arenaza
 * @author          Jordan Kesraoui | Pimenko
 * @copyright       2019 Pimenko <support@pimenko.com><pimenko.com>
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot.'/auth/cas/CAS/CAS.php');

/**
 * CAS authentication plugin.
 *
 * @property string roleauth
 * @property bool userexists
 */
class auth_plugin_cassync extends auth_plugin_base {


    private $userexists = false;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'cassync';
        $this->roleauth = 'auth_cassync';
        $this->config = get_config('auth_cassync');
        $this->errorlogtag = '[AUTH CASSYNC] ';
    }

    /**
     * Old syntax of class constructor. Deprecated in PHP7.
     *
     * @deprecated since Moodle 3.1
     */
    public function auth_plugin_cassync() {
        debugging('Use of class name as constructor is deprecated', DEBUG_DEVELOPER);
        self::__construct();
    }

    public function prevent_local_passwords() {
        return true;
    }

    /**
     * Authenticates user against CAS
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     * @return bool Authentication success or failure.
     */
    public function user_login ($username, $password) {
        $this->connect_cas();
        return phpCAS::isAuthenticated() && (trim(core_text::strtolower(phpCAS::getUser())) == $username);
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    public function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    public function can_change_password() {
        return false;
    }

    /**
     * Authentication choice (CAS or other)
     * Redirection to the CAS form or to login/index.php
     * for other authentication
     */
    public function loginpage_hook() {
        global $frm, $SESSION;

        $username = optional_param('username', '', PARAM_RAW);
        $courseid = optional_param('courseid', 0, PARAM_INT);

        if (!empty($username)) {
            if (isset($SESSION->wantsurl) && (strstr($SESSION->wantsurl, 'ticket') ||
                                              strstr($SESSION->wantsurl, 'NOCAS'))) {
                unset($SESSION->wantsurl);
            }
            return;
        }

        // Return if CAS enabled and settings not specified yet.
        if (empty($this->config->hostname)) {
            return;
        }

        // If the multi-authentication setting is used, check for the param before connecting to CAS.
        if ($this->config->multiauth) {

            // If there is an authentication error, stay on the default authentication page.
            if (!empty($SESSION->loginerrormsg)) {
                return;
            }

            $authcas = optional_param('authCAS', '', PARAM_RAW);
            if ($authcas != 'CAS') {
                return;
            }

        }

        // Connection to CAS server.
        $this->connect_cas();

        if (phpCAS::checkAuthentication()) {
            $frm = new stdClass();
            $frm->username = phpCAS::getUser();
            $frm->password = 'passwdCas';
            $frm->logintoken = \core\session\manager::get_login_token();

            // Redirect to a course if multi-auth is activated, authCAS is set to CAS and the courseid is specified.
            if ($this->config->multiauth && !empty($courseid)) {
                redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
            }

            return;
        }

        if (isset($_GET['loginguest']) && ($_GET['loginguest'] == true)) {
            $frm = new stdClass();
            $frm->username = 'guest';
            $frm->password = 'guest';
            $frm->logintoken = \core\session\manager::get_login_token();
            return;
        }

        // Force CAS authentication (if needed).
        if (!phpCAS::isAuthenticated()) {
            phpCAS::setLang($this->config->language);
            phpCAS::forceAuthentication();
        }
    }


    /**
     * Connect to the CAS (clientcas connection or proxycas connection)
     *
     */
    public function connect_cas() {
        global $CFG;
        static $connected = false;

        if (!$connected) {
            // Make sure phpCAS doesn't try to start a new PHP session when connecting to the CAS server.
            if ($this->config->proxycas) {
                phpCAS::proxy($this->config->casversion, $this->config->hostname, (int) $this->config->port,
                        $this->config->baseuri, false);
            } else {
                phpCAS::client($this->config->casversion, $this->config->hostname, (int) $this->config->port,
                        $this->config->baseuri, false);
            }
            // Some CAS installs require SSLv3 that should be explicitly set.
            if (!empty($this->config->curl_ssl_version)) {
                phpCAS::setExtraCurlOption(CURLOPT_SSLVERSION, $this->config->curl_ssl_version);
            }

            $connected = true;
        }

        // If Moodle is configured to use a proxy, phpCAS needs some curl options set.
        if (!empty($CFG->proxyhost) && !is_proxybypass(phpCAS::getServerLoginURL())) {
            phpCAS::setExtraCurlOption(CURLOPT_PROXY, $CFG->proxyhost);
            if (!empty($CFG->proxyport)) {
                phpCAS::setExtraCurlOption(CURLOPT_PROXYPORT, $CFG->proxyport);
            }
            if (!empty($CFG->proxytype)) {
                // Only set CURLOPT_PROXYTYPE if it's something other than the curl-default http.
                if ($CFG->proxytype == 'SOCKS5') {
                    phpCAS::setExtraCurlOption(CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                }
            }
            if (!empty($CFG->proxyuser) and !empty($CFG->proxypassword)) {
                phpCAS::setExtraCurlOption(CURLOPT_PROXYUSERPWD, $CFG->proxyuser.':'.$CFG->proxypassword);
                if (defined('CURLOPT_PROXYAUTH')) {
                    // Any proxy authentication if PHP 5.1.
                    phpCAS::setExtraCurlOption(CURLOPT_PROXYAUTH, CURLAUTH_BASIC | CURLAUTH_NTLM);
                }
            }
        }

        if ($this->config->certificate_check && $this->config->certificate_path) {
            phpCAS::setCasServerCACert($this->config->certificate_path);
        } else {
            // Don't try to validate the server SSL credentials.
            phpCAS::setNoCasServerValidation();
        }
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    public function change_password_url() {
        return null;
    }

    /**
     *  Hook for logout page
     */
    public function logoutpage_hook() {
        global $USER, $redirect;

        // Only do this if the user is actually logged in via CAS.
        if ($USER->auth === $this->authtype) {
            // Check if there is an alternative logout return url defined.
            if (isset($this->config->logout_return_url) && !empty($this->config->logout_return_url)) {
                // Set redirect to alternative return url.
                $redirect = $this->config->logout_return_url;
            }
        }
    }

    /**
     * Post logout hook.
     *
     * Note: this method replace the prelogout_hook method to avoid redirect to CAS logout
     * before the event userlogout being triggered.
     *
     * @param stdClass $user clone of USER object object before the user session was terminated
     */
    public function postlogout_hook($user) {
        global $CFG;
        // Only redirect to CAS logout if the user is logged as a CAS user.
        if (!empty($this->config->logoutcas) && $user->auth == $this->authtype) {
            $backurl = !empty($this->config->logout_return_url) ? $this->config->logout_return_url : $CFG->wwwroot;
            $this->connect_cas();
            phpCAS::logoutWithRedirectService($backurl);
        }
    }

    /**
     * Return a list of identity providers to display on the login page.
     *
     * @param string|moodle_url $wantsurl The requested URL.
     * @return array List of arrays with keys url, iconurl and name.
     */
    public function loginpage_idp_list($wantsurl) {
        if (empty($this->config->hostname)) {
            // CAS is not configured.
            return [];
        }

        $iconurl = moodle_url::make_pluginfile_url(
            context_system::instance()->id,
            'auth_cassync',
            'logo',
            null,
            '/',
            $this->config->auth_logo);

        return [
            [
                'url' => new moodle_url(get_login_url(), [
                        'authCAS' => 'CAS',
                    ]),
                'iconurl' => $iconurl,
                'name' => format_string($this->config->auth_name),
            ],
        ];
    }

    public function pre_user_login_hook(&$user) {
        // Check if the user is present.
        $this->userexists = false;
        if ($user->id) {
            $this->userexists = true;
        }
    }

    public function user_authenticated_hook(&$user, $username, $password) {
        // Update the user in fonction of the _updatelocal settings.

        if ($user->auth == 'cassync') {
            $userinfo = $this->get_userinfo($user->username);

            $this->update_user_record($user->username, $this->get_profile_keys(), false, false);
        }
    }

    /**
     * Get the list of profile fields.
     *
     * @return  array
     */
    protected function get_profile_keys() {
        $keys = array_keys(get_object_vars($this->config));
        $updatekeys = [];
        foreach ($keys as $key) {
            if (preg_match('/^field_updatelocal_(.+)$/', $key, $match)) {
                // If we have a field to update it from and it must be updated 'onlogin' we update it on cron.
                if (!empty($this->config->{'field_map_'.$match[1]})) {
                    if (($this->userexists && $this->config->{$match[0]} === 'onlogin') || !$this->userexists) {
                        array_push($updatekeys, $match[1]); // The actual key name.
                    }
                }
            }
        }
        return $updatekeys;
    }

    /**
     * Reads user information from CAS
     *
     * Function should return all information available. If you are saving
     * this information to moodle user-table you should honor syncronization flags
     *
     * @param string $username username
     *
     * @return mixed array with no magic quotes or false on error
     */
    public function get_userinfo($username) {
        $user = [];
        $profilekeys = $this->get_profile_keys();

        if (phpCAS::checkAuthentication()) {
            $attributs = phpCAS::getAttributes();

            foreach ($profilekeys as $value) {
                if (isset($attributs[$this->config->{'field_map_'.$value}])) {
                    $user[$value] = $attributs[$this->config->{'field_map_'.$value}];
                }
            }
            return $user;
        }
        return false;
    }
}
