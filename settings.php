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
 * Admin settings and defaults.
 *
 * @package         auth
 * @subpackage      cassync
 * @copyright       2019 Pimenko <support@pimenko.com><pimenko.com>
 * @author          Jordan Kesraoui | Pimenko
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


if ($ADMIN->fulltree) {

    if (!function_exists('ldap_connect')) {
        $settings->add(new admin_setting_heading('auth_cassyncnotinstalled', '',
                get_string('auth_cassyncnotinstalled', 'auth_cassync')));
    } else {

        // Include needed files.

        require_once($CFG->dirroot.'/auth/cassync/auth.php');
        require_once($CFG->dirroot.'/auth/cassync/languages.php');

        // Introductory explanation.
        $settings->add(new admin_setting_heading('auth_cassync/pluginname', '',
                new lang_string('auth_cassyncdescription', 'auth_cassync')));

        // CAS server configuration label.
        $settings->add(new admin_setting_heading('auth_cassync/casserversettings',
                new lang_string('auth_cassync_server_settings', 'auth_cassync'), ''));

        // Authentication method name.
        $settings->add(new admin_setting_configtext('auth_cassync/auth_name',
                get_string('auth_cassync_auth_name', 'auth_cassync'),
                get_string('auth_cassync_auth_name_description', 'auth_cassync'),
                get_string('auth_cassync_auth_service', 'auth_cassync'),
                PARAM_RAW_TRIMMED));

        // Authentication method logo.
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
        $settings->add(new admin_setting_configstoredfile('auth_cassync/auth_logo',
                 get_string('auth_cassync_auth_logo', 'auth_cassync'),
                 get_string('auth_cassync_auth_logo_description', 'auth_cassync'), 'logo', 0, $opts));


        // Hostname.
        $settings->add(new admin_setting_configtext('auth_cassync/hostname',
                get_string('auth_cassync_hostname_key', 'auth_cassync'),
                get_string('auth_cassync_hostname', 'auth_cassync'), '', PARAM_RAW_TRIMMED));

        // Base URI.
        $settings->add(new admin_setting_configtext('auth_cassync/baseuri',
                get_string('auth_cassync_baseuri_key', 'auth_cassync'),
                get_string('auth_cassync_baseuri', 'auth_cassync'), '', PARAM_RAW_TRIMMED));

        // Port.
        $settings->add(new admin_setting_configtext('auth_cassync/port',
                get_string('auth_cassync_port_key', 'auth_cassync'),
                get_string('auth_cassync_port', 'auth_cassync'), '', PARAM_INT));

        // CAS Version.
        $casversions = array();

        $casversions[CAS_VERSION_1_0] = 'CAS 1.0';
        $casversions[CAS_VERSION_2_0] = 'CAS 2.0';
        $casversions[CAS_VERSION_3_0] = 'CAS 3.0';
        $settings->add(new admin_setting_configselect('auth_cassync/casversion',
                new lang_string('auth_cassync_casversion', 'auth_cassync'),
                new lang_string('auth_cassync_version', 'auth_cassync'), CAS_VERSION_3_0, $casversions));

        // Language.
        if (!isset($CASLANGUAGES) || empty($CASLANGUAGES)) {
            // Prevent warnings on other admin pages.
            // $CASLANGUAGES is defined in /auth/cas/languages.php.
            $CASLANGUAGES = array();
            $CASLANGUAGES[PHPCAS_LANG_ENGLISH] = 'English';
            $CASLANGUAGES[PHPCAS_LANG_FRENCH] = 'French';
        }
        $settings->add(new admin_setting_configselect('auth_cassync/language',
                new lang_string('auth_cassync_language_key', 'auth_cassync'),
                new lang_string('auth_cassync_language', 'auth_cassync'),
                PHPCAS_LANG_ENGLISH, $CASLANGUAGES));

        // Proxy.
        $yesno = array(
            new lang_string('no'),
            new lang_string('yes'),
        );
        $settings->add(new admin_setting_configselect('auth_cassync/proxycas',
                new lang_string('auth_cassync_proxycas_key', 'auth_cassync'),
                new lang_string('auth_cassync_proxycas', 'auth_cassync'), 0 , $yesno));

        // Logout option.
        $settings->add(new admin_setting_configselect('auth_cassync/logoutcas',
                new lang_string('auth_cassync_logoutcas_key', 'auth_cassync'),
                new lang_string('auth_cassync_logoutcas', 'auth_cassync'), 0 , $yesno));

        // Multi-auth.
        $settings->add(new admin_setting_configselect('auth_cassync/multiauth',
                new lang_string('auth_cassync_multiauth_key', 'auth_cassync'),
                new lang_string('auth_cassync_multiauth', 'auth_cassync'), 0 , $yesno));

        // Server validation.
        $settings->add(new admin_setting_configselect('auth_cassync/certificate_check',
                new lang_string('auth_cassync_certificate_check_key', 'auth_cassync'),
                new lang_string('auth_cassync_certificate_check', 'auth_cassync'), 0 , $yesno));

        // Certificate path.
        $settings->add(new admin_setting_configfile('auth_cassync/certificate_path',
                get_string('auth_cassync_certificate_path_key', 'auth_cassync'),
                get_string('auth_cassync_certificate_path', 'auth_cassync'), ''));

        // CURL SSL version.
        $sslversions = array();
        $sslversions[''] = get_string('auth_cassync_curl_ssl_version_default', 'auth_cassync');
        if (defined('CURL_SSLVERSION_TLSv1')) {
            $sslversions[CURL_SSLVERSION_TLSv1] = get_string('auth_cassync_curl_ssl_version_TLSv1x', 'auth_cassync');
        }
        if (defined('CURL_SSLVERSION_TLSv1_0')) {
            $sslversions[CURL_SSLVERSION_TLSv1_0] = get_string('auth_cassync_curl_ssl_version_TLSv10', 'auth_cassync');
        }
        if (defined('CURL_SSLVERSION_TLSv1_1')) {
            $sslversions[CURL_SSLVERSION_TLSv1_1] = get_string('auth_cassync_curl_ssl_version_TLSv11', 'auth_cassync');
        }
        if (defined('CURL_SSLVERSION_TLSv1_2')) {
            $sslversions[CURL_SSLVERSION_TLSv1_2] = get_string('auth_cassync_curl_ssl_version_TLSv12', 'auth_cassync');
        }
        if (defined('CURL_SSLVERSION_SSLv2')) {
            $sslversions[CURL_SSLVERSION_SSLv2] = get_string('auth_cassync_curl_ssl_version_SSLv2', 'auth_cassync');
        }
        if (defined('CURL_SSLVERSION_SSLv3')) {
            $sslversions[CURL_SSLVERSION_SSLv3] = get_string('auth_cassync_curl_ssl_version_SSLv3', 'auth_cassync');
        }
        $settings->add(new admin_setting_configselect('auth_cassync/curl_ssl_version',
                new lang_string('auth_cassync_curl_ssl_version_key', 'auth_cassync'),
                new lang_string('auth_cassync_curl_ssl_version', 'auth_cassync'), '' , $sslversions));

        // Alt Logout URL.
        $settings->add(new admin_setting_configtext('auth_cassync/logout_return_url',
                get_string('auth_cassync_logout_return_url_key', 'auth_cassync'),
                get_string('auth_cassync_logout_return_url', 'auth_cassync'), '', PARAM_URL));
    }

    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('cassync');


    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields, '', true, false,
            $authplugin->get_custom_user_profile_fields());

}
