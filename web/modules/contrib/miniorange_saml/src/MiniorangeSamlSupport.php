<?php

namespace Drupal\miniorange_saml;

use Drupal\miniorange_saml\Api\MoAuthApi;

/**
 * @file
 * This class represents support information for customer.
 */

class MiniorangeSamlSupport
{

    public $email;
    public $phone;
    public $query;
    public $query_type;

    /**
     * Constructor.
     */
    public function __construct($email, $phone, $query, $query_type)
    {
        $this->email      = $email;
        $this->phone      = $phone;
        $this->query      = $query;
        $this->query_type = $query_type;

    }

    /**
     * Send support query.
     */
    public function sendSupportQuery() {
        $modules_info = \Drupal::service('extension.list.module')->getExtensionInfo('miniorange_saml');
        $modules_version = $modules_info['version'];

        if( $this->query_type === 'Demo Request' ){
            $this->query = 'Demo request for ' . $this->phone . ' .<br> '. $this->query;
        }

        $this->query = '[Drupal-' . Utilities::mo_get_drupal_core_version() . ' SAML SP ' . $this->query_type . ' | ' .$modules_version. '] ' . $this->query;

        $fields = array (
            'company' => $_SERVER['SERVER_NAME'],
            'email'   => $this->email,
            'phone'   => $this->query_type != 'Demo Request' ? $this->phone : '',
            'ccEmail' => MiniorangeSAMLConstants::SUPPORT_EMAIL,
            'query'   => $this->query
        );
        $url = MiniorangeSAMLConstants::SUPPORT_QUERY;
        $api = new MoAuthApi();
        return $api->makeCurlCall($url,$fields);
    }
}