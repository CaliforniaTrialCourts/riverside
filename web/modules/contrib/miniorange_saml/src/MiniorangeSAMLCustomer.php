<?php
/**
 * @file
 * Contains miniOrange Customer class.
 */

/**
 * @file
 * This class represents configuration for customer.
 */
namespace Drupal\miniorange_saml;
use Drupal\miniorange_saml\Api\MoAuthApi;

class MiniorangeSAMLCustomer {

  public $email;

  public $phone;


  public $password;

  public $otpToken;

  /**
   * Constructor.
   */
  public function __construct($email, $phone, $password, $otp_token) {
    $this->email = $email;
    $this->phone = $phone;
    $this->password = $password;
    $this->otpToken = $otp_token;
  }

  /**
   * Check if customer exists.
   */
  public function checkCustomer() {

    $url = MiniorangeSAMLConstants::CUSTOMER_CHECK_API;
    $fields = array(
      'email' => $this->email,
    );
    $api = new MoAuthApi();
    return $api->makeCurlCall($url, $fields);

  }

  /**
   * Create Customer.
   */
  public function createCustomer() {

    $url = MiniorangeSAMLConstants::CUSTOMER_CREATE_API;

    $fields = array(
      'companyName' => $_SERVER['SERVER_NAME'],
      'areaOfInterest' => MiniorangeSAMLConstants::AREA_OF_INTEREST,
      'email' => $this->email,
      'phone' => $this->phone,
      'password' => $this->password,
    );
    $api    = new MoAuthApi();
    $header = $api->getHttpHeaderArray();
    return $api->makeCurlCall( $url, $fields, $header );
  }

  /**
   * Get Customer Keys.
   */
  public function getCustomerKeys() {

      $url = MiniorangeSAMLConstants::CUSTOMER_GET_KEYS;

      $fields = array(
        'email'    => $this->email,
        'password' => $this->password,
      );

      $api = new MoAuthApi();
      return $api->makeCurlCall($url, $fields);

  }

  /**
   * Send OTP.
   */
  public function sendOtp() {
    $url = MiniorangeSAMLConstants::AUTH_CHALLENGE_API;

    $username = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');

    $fields = array(
      'customerKey' => MiniorangeSAMLConstants::DEFAULT_CUSTOMER_ID,
      'email' => $username,
      'authType' => 'EMAIL',
    );
    $api = new MoAuthApi();
    $header = $api->getHttpHeaderArray();
    return $api->makeCurlCall($url,$fields,$header);
  }

  /**
   * Validate OTP.
   */
  public function validateOtp($transaction_id) {

    $url = MiniorangeSAMLConstants::AUTH_VALIDATE_API;

    $fields = array(
      'txId' => $transaction_id,
      'token' => $this->otpToken,
    );
    $api = new MoAuthApi();
    $header = $api->getHttpHeaderArray();
    return $api->makeCurlCall($url,$fields,$header);
  }
}