<?php
namespace Drupal\miniorange_saml;

/**
 * @file
 * This class represents constants used throughout project.
 */
class MiniorangeSAMLConstants {
    const BASE_URL                = 'https://login.xecurify.com';
    const DEFAULT_CUSTOMER_ID     = "16555";
    const DEFAULT_API_KEY         = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";

    const CUSTOMER_CHECK_API      =  self::BASE_URL.'/moas/rest/customer/check-if-exists';
    const CUSTOMER_CREATE_API     =  self::BASE_URL.'/moas/rest/customer/add';
    const CUSTOMER_GET_KEYS       =  self::BASE_URL.'/moas/rest/customer/key';
    const SUPPORT_QUERY           =  self::BASE_URL.'/moas/rest/customer/contact-us';
    const AUTH_CHALLENGE_API      =  self::BASE_URL.'/moas/api/auth/challenge';
    const AUTH_VALIDATE_API       =  self::BASE_URL.'/moas/api/auth/validate';
    const FEEDBACK_API            =  self::BASE_URL.'/moas/api/notify/send';
    const SERVER_TIME_API         =  self::BASE_URL.'/moas/rest/mobile/get-timestamp';

    const LICENSING_TAB_URL       =  '/admin/config/people/miniorange_saml/Licensing';
    const AREA_OF_INTEREST        =  'Drupal 8 SAML Plugin';
    const SUPPORT_EMAIL           =  'drupalsupport@xecurify.com';
    const USER_ATTRIBUTE          =  '/admin/config/people/accounts/fields';
}