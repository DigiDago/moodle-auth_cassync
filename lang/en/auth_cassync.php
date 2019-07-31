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
 * Strings for component 'auth_cassync', language 'en'.
 *
 * @package         auth
 * @subpackage      cassync
 * @copyright       2019 Pimenko <support@pimenko.com><pimenko.com>
 * @author          Jordan Kesraoui | Pimenko
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_cassync_auth_name'] = 'Authentication method name';
$string['auth_cassync_auth_name_description'] = 'Provide a name for the CAS authentication method that is familiar to your users.';
$string['auth_cassync_auth_logo'] = 'Authentication method logo';
$string['auth_cassync_auth_logo_description'] = 'Provide a logo for the CAS authentication method that is familiar to your users.';
$string['auth_cassync_auth_user_create'] = 'Create users externally';
$string['auth_cassync_auth_service'] = 'CAS';
$string['auth_cassync_baseuri'] = 'URI of the server (nothing if no baseUri)<br />For example, if the CAS server responds to host.domaine.fr/CAS/ then<br />cas_baseuri = CAS/';
$string['auth_cassync_baseuri_key'] = 'Base URI';
$string['auth_cassync_broken_password'] = 'You cannot proceed without changing your password, however there is no available page for changing it. Please contact your Moodle Administrator.';
$string['auth_cassync_cantconnect'] = 'LDAP part of CAS-module cannot connect to server: {$a}';
$string['auth_cassync_casversion'] = 'CAS protocol version';
$string['auth_cassync_certificate_check'] = 'Select \'yes\' if you want to validate the server certificate';
$string['auth_cassync_certificate_path_empty'] = 'If you turn on Server validation, you need to specify a certificate path';
$string['auth_cassync_certificate_check_key'] = 'Server validation';
$string['auth_cassync_certificate_path'] = 'Path of the CA chain file (PEM Format) to validate the server certificate';
$string['auth_cassync_certificate_path_key'] = 'Certificate path';
$string['auth_cassync_create_user'] = 'Turn this on if you want to insert CAS-authenticated users in Moodle database. If not then only users who already exist in the Moodle database can log in.';
$string['auth_cassync_create_user_key'] = 'Create user';
$string['auth_cassync_curl_ssl_version'] = 'The SSL version (2 or 3) to use. By default PHP will try to determine this itself, although in some cases this must be set manually.';
$string['auth_cassync_curl_ssl_version_default'] = 'Default';
$string['auth_cassync_curl_ssl_version_key'] = 'cURL SSL Version';
$string['auth_cassync_curl_ssl_version_SSLv2'] = 'SSLv2';
$string['auth_cassync_curl_ssl_version_SSLv3'] = 'SSLv3';
$string['auth_cassync_curl_ssl_version_TLSv1x'] = 'TLSv1.x';
$string['auth_cassync_curl_ssl_version_TLSv10'] = 'TLSv1.0';
$string['auth_cassync_curl_ssl_version_TLSv11'] = 'TLSv1.1';
$string['auth_cassync_curl_ssl_version_TLSv12'] = 'TLSv1.2';
$string['auth_cassyncdescription'] = 'This method uses a CAS server (Central Authentication Service) to authenticate users in a Single Sign On environment (SSO). You can also use a simple LDAP authentication. If the given username and password are valid according to CAS, Moodle creates a new user entry in its database, taking user attributes from LDAP if required. On following logins only the username and password are checked.';
$string['auth_cassync_enabled'] = 'Turn this on if you want to use CAS authentication.';
$string['auth_cassync_hostname'] = 'Hostname of the CAS server <br />eg: host.domain.fr';
$string['auth_cassync_hostname_key'] = 'Hostname';
$string['auth_cassync_changepasswordurl'] = 'Password-change URL';
$string['auth_cassync_invalidcaslogin'] = 'Sorry, your login has failed - you could not be authorised';
$string['auth_cassync_language'] = 'Select language for authentication pages';
$string['auth_cassync_language_key'] = 'Language';
$string['auth_cassync_logincas'] = 'Secure connection access';
$string['auth_cassync_logout_return_url_key'] = 'Alternative logout return URL';
$string['auth_cassync_logout_return_url'] = 'Provide the URL that CAS users shall be redirected to after logging out.<br />If left empty, users will be redirected to the location that moodle will redirect users to';
$string['auth_cassync_logoutcas'] = 'Select \'yes\' if you want to logout from CAS when you disconnect from Moodle';
$string['auth_cassync_logoutcas_key'] = 'CAS logout option';
$string['auth_cassync_multiauth'] = 'Select \'yes\' if you want to have multi-authentication (CAS + other authentication)';
$string['auth_cassync_multiauth_key'] = 'Multi-authentication';
$string['auth_cassyncnotinstalled'] = 'Cannot use CAS authentication. The PHP LDAP module is not installed.';
$string['auth_cassync_port'] = 'Port of the CAS server';
$string['auth_cassync_port_key'] = 'Port';
$string['auth_cassync_proxycas'] = 'Select \'yes\' if you use CAS in proxy-mode';
$string['auth_cassync_proxycas_key'] = 'Proxy mode';
$string['auth_cassync_server_settings'] = 'CAS server configuration';
$string['auth_cassync_text'] = 'Secure connection';
$string['auth_cassync_use_cas'] = 'Use CAS';
$string['auth_cassync_version'] = 'CAS protocol version to use';
$string['CASform'] = 'Authentication choice';
$string['noldapserver'] = 'No LDAP server configured for CAS! Syncing disabled.';
$string['pluginname'] = 'CAS Sync';
$string['synctask'] = 'CAS users sync job';
$string['privacy:metadata'] = 'The CAS Sync authentication plugin does not store any personal data.';

// Deprecated since Moodle 3.7.
$string['accesCAS'] = 'CAS users';
$string['accesNOCAS'] = 'other users';