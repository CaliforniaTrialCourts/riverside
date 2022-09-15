<?php


namespace Drupal\miniorange_saml\Api;
use Drupal\miniorange_saml\Utilities;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;

class MoAuthApi{

    private $apiKey;
    private $customerId;

    public function  __construct( $customerId = MiniorangeSAMLConstants::DEFAULT_CUSTOMER_ID, $apiKey = MiniorangeSAMLConstants::DEFAULT_API_KEY ){

        $this->customerId = $customerId;
        $this->apiKey = $apiKey;
    }

    /**
     * This function is used to get the timestamp value
     */
    public function getTimeStamp() {
        $url = MiniorangeSAMLConstants::SERVER_TIME_API;
        $fields = array();
        $currentTimeInMillis = $this->makeCurlCall($url,$fields);
        if (empty($currentTimeInMillis)) {
            $currentTimeInMillis = round(microtime(true) * 1000);
            $currentTimeInMillis = number_format($currentTimeInMillis, 0, '', '');
        }
        return $currentTimeInMillis;
    }

    function makeCurlCall( $url, $fields, $http_header_array =array( 'Content-Type: application/json', 'charset: UTF - 8', 'Authorization: Basic' ) ) {

        if ( gettype( $fields ) !== 'string' ) {
            $fields = json_encode( $fields );
        }

        $response = $this->postCurlCall($url, $fields,$http_header_array);
        return $response;

    }

    function getHttpHeaderArray() {

        /* Current time in milliseconds since midnight, January 1, 1970 UTC. */
        $currentTimeInMillis = $this->getTimeStamp();

        /* Creating the Hash using SHA-512 algorithm */
        $stringToHash = $this->customerId . $currentTimeInMillis . $this->apiKey;
        $hashValue = hash( "sha512", $stringToHash );

        $headers = array(
            "Content-Type: application/json",
            "Customer-Key: ".$this->customerId,
            "Timestamp: ".$currentTimeInMillis,
            "Authorization: ".$hashValue
        );

        return $headers;
    }

    public function postCurlCall ($url,$fields,$http_header_array){

        if (!Utilities::isCurlInstalled()) {
            return json_encode(array(
                "status" => 'CURL_ERROR',
                "statusMessage" => '<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.',
            ));
        }
        $ch     = curl_init( $url );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $http_header_array );
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields);
        $content = curl_exec( $ch );
        if( curl_errno( $ch ) ){
            \Drupal::logger('miniorange_saml')->notice('Error: '.curl_error($ch).' in call of '.$url);
        }
        curl_close( $ch );

        return $content;
    }
}