<?php


//
// hack by Vangelis Haniotakis to handle the absence of $_SERVER['REQUEST_URI']
// in IIS
//
if (!isset($_SERVER['REQUEST_URI']) && isset($_SERVER['SCRIPT_NAME']) && isset($_SERVER['QUERY_STRING'])) {
$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
}

// Add a E_USER_DEPRECATED for php versions <= 5.2
if (!defined('E_USER_DEPRECATED')) {
define('E_USER_DEPRECATED', E_USER_NOTICE);
}


// ########################################################################
//  CONSTANTS
// ########################################################################

// ------------------------------------------------------------------------
//  CAS VERSIONS
// ------------------------------------------------------------------------

/**
* phpCAS version. accessible for the user by phpCAS::getVersion().
*/
define('PHPCAS_VERSION', '1.3.5+');

/**
* @addtogroup public
* @{
*/

/**
* CAS version 1.0
*/
define("CAS_VERSION_1_0", '1.0');
/*!
* CAS version 2.0
*/
define("CAS_VERSION_2_0", '2.0');
/**
* CAS version 3.0
*/
define("CAS_VERSION_3_0", '3.0');

// ------------------------------------------------------------------------
//  SAML defines
// ------------------------------------------------------------------------

/**
* SAML protocol
*/
define("SAML_VERSION_1_1", 'S1');

/**
* XML header for SAML POST
*/
define("SAML_XML_HEADER", '<?xml version="1.0" encoding="UTF-8"?>');

/**
* SOAP envelope for SAML POST
*/
define("SAML_SOAP_ENV", '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/>');

    /**
    * SOAP body for SAML POST
    */
    define("SAML_SOAP_BODY", '<SOAP-ENV:Body>');

        /**
        * SAMLP request
        */
        define("SAMLP_REQUEST", '<samlp:Request xmlns:samlp="urn:oasis:names:tc:SAML:1.0:protocol"  MajorVersion="1" MinorVersion="1" RequestID="_192.168.16.51.1024506224022" IssueInstant="2002-06-19T17:03:44.022Z">');
            define("SAMLP_REQUEST_CLOSE", '</samlp:Request>');

        /**
        * SAMLP artifact tag (for the ticket)
        */
        define("SAML_ASSERTION_ARTIFACT", '<samlp:AssertionArtifact>');

            /**
            * SAMLP close
            */
            define("SAML_ASSERTION_ARTIFACT_CLOSE", '</samlp:AssertionArtifact>');

        /**
        * SOAP body close
        */
        define("SAML_SOAP_BODY_CLOSE", '</SOAP-ENV:Body>');

    /**
    * SOAP envelope close
    */
    define("SAML_SOAP_ENV_CLOSE", '</SOAP-ENV:Envelope>');

/**
* SAML Attributes
*/
define("SAML_ATTRIBUTES", 'SAMLATTRIBS');

/**
* SAML Attributes
*/
define("DEFAULT_ERROR", 'Internal script failure');

/** @} */
/**
* @addtogroup publicPGTStorage
* @{
*/
// ------------------------------------------------------------------------
//  FILE PGT STORAGE
// ------------------------------------------------------------------------
/**
* Default path used when storing PGT's to file
*/
define("CAS_PGT_STORAGE_FILE_DEFAULT_PATH", session_save_path());
/** @} */
// ------------------------------------------------------------------------
// SERVICE ACCESS ERRORS
// ------------------------------------------------------------------------
/**
* @addtogroup publicServices
* @{
*/

/**
* phpCAS::service() error code on success
*/
define("PHPCAS_SERVICE_OK", 0);
/**
* phpCAS::service() error code when the PT could not retrieve because
* the CAS server did not respond.
*/
define("PHPCAS_SERVICE_PT_NO_SERVER_RESPONSE", 1);
/**
* phpCAS::service() error code when the PT could not retrieve because
* the response of the CAS server was ill-formed.
*/
define("PHPCAS_SERVICE_PT_BAD_SERVER_RESPONSE", 2);
/**
* phpCAS::service() error code when the PT could not retrieve because
* the CAS server did not want to.
*/
define("PHPCAS_SERVICE_PT_FAILURE", 3);
/**
* phpCAS::service() error code when the service was not available.
*/
define("PHPCAS_SERVICE_NOT_AVAILABLE", 4);

// ------------------------------------------------------------------------
// SERVICE TYPES
// ------------------------------------------------------------------------
/**
* phpCAS::getProxiedService() type for HTTP GET
*/
define("PHPCAS_PROXIED_SERVICE_HTTP_GET", 'CAS_ProxiedService_Http_Get');
/**
* phpCAS::getProxiedService() type for HTTP POST
*/
define("PHPCAS_PROXIED_SERVICE_HTTP_POST", 'CAS_ProxiedService_Http_Post');
/**
* phpCAS::getProxiedService() type for IMAP
*/
define("PHPCAS_PROXIED_SERVICE_IMAP", 'CAS_ProxiedService_Imap');


/** @} */
// ------------------------------------------------------------------------
//  LANGUAGES
// ------------------------------------------------------------------------
/**
* @addtogroup publicLang
* @{
*/

define("PHPCAS_LANG_ENGLISH", 'CAS_Languages_English');
define("PHPCAS_LANG_FRENCH", 'CAS_Languages_French');
define("PHPCAS_LANG_GREEK", 'CAS_Languages_Greek');
define("PHPCAS_LANG_GERMAN", 'CAS_Languages_German');
define("PHPCAS_LANG_JAPANESE", 'CAS_Languages_Japanese');
define("PHPCAS_LANG_SPANISH", 'CAS_Languages_Spanish');
define("PHPCAS_LANG_CATALAN", 'CAS_Languages_Catalan');
define("PHPCAS_LANG_CHINESE_SIMPLIFIED", 'CAS_Languages_ChineseSimplified');

/** @} */

/**
* @addtogroup internalLang
* @{
*/

/**
* phpCAS default language (when phpCAS::setLang() is not used)
*/
define("PHPCAS_LANG_DEFAULT", PHPCAS_LANG_ENGLISH);


