<?php
namespace Drupal\miniorange_saml;

use DOMNode;
use DOMXPath;
use DOMElement;
use Drupal\Core\Render\Markup;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This file is part of miniOrange SAML plugin.
 *
 * miniOrange SAML plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * miniOrange SAML plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with miniOrange SAML plugin.  If not, see <http://www.gnu.org/licenses/>.
 */

class Utilities {

    public static function visual_tour_start(array &$form, FormStateInterface $form_state) {
        $form['miniorange_saml_tour_button'] = array(
            '#attached' => array(
                'library' => array(
                    'miniorange_saml/miniorange_saml.Vtour',
                )
            ),
        );

        $overAllTour = 'overAllTour';
        $moTour = mo_saml_visualTour::genArray();
        $overAllTour = mo_saml_visualTour::genArray($overAllTour);

        $form['tourArray'] = array(
            '#type' => 'hidden',
            '#value' => $moTour,
        );

        $form['tabtourArray'] = array(
            '#type' => 'hidden',
            '#value' => $overAllTour,
        );
    }

    public static function spConfigGuide(array &$form, FormStateInterface $form_state) {

        $form['miniorange_idp_guide_link'] = array(
            '#markup' => '<div class="mo_saml_table_layout mo_saml_sp_container_2" id="mo_guide_vt">',
        );

        $form['miniorange_idp_guide_link1'] = array(
            '#markup' => '<div>To see detailed documentation of how to configure Drupal SAML SP with any Identity Provider</div></br>',
        );

        $mo_Azure_AD              = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-sso-using-azure-ad-idp/" class="mo_guide_text-color" target="_blank">Azure AD</a></strong>');
        $mo_ADFS                  = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-sso-using-adfs-idp" class="mo_guide_text-color" target="_blank">ADFS</a></strong>');
        $mo_Okta                  = Markup::create('<strong><a class="mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-okta-idp/" target="_blank">Okta</a></strong>');
        $mo_Google_Apps           = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-sso-using-google-apps-idp/" class="mo_guide_text-color" target="_blank">Google Apps</a></strong>');
        $mo_Salesforce            = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-sso-using-salesforce-idp/" class="mo_guide_text-color" target="_blank">Salesforce</a></strong>');
        $mo_miniOrange            = Markup::create('<strong><a class="mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-miniorange-idp/" target="_blank">miniOrange</a></strong>');
        $mo_PingOne               = Markup::create('<strong><a class="mo_guide_text-color" href="https://plugins.miniorange.com/guide-for-drupal-single-sign-on-using-pingone-as-identity-provider" target="_blank">PingOne</a></strong>');
        $mo_OneLogin              = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-sso-using-onelogin-idp/" class="mo_guide_text-color" target="_blank">Onelogin</a></strong>');
        $mo_Bitium                = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-sso-using-bitium-idp" class="mo_guide_text-color" target="_blank">Bitium</a></strong>');
        $mo_centrify              = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-sso-using-centrify-idp/" class="mo_guide_text-color" target="_blank">Centrify</a></strong>');
        $mo_Oracle                = Markup::create('<strong><a href="https://plugins.miniorange.com/guide-to-configure-oracle-access-manager-as-idp-and-drupal-as-sp" class="mo_guide_text-color" target="_blank">Oracle</a></strong>');
        $mo_JBoss_KeyCloak        = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-on-sso-using-jboss-keycloak-as-idp" class="mo_guide_text-color" target="_blank">Jboss Keycloak</a></strong>');
        $mo_ping                  = Markup::create('<strong><a href="https://plugins.miniorange.com/guide-for-pingfederate-as-idp-with-drupal" class="mo_guide_text-color" target="_blank">Ping Federate</a></strong>');
        $mo_openam                = Markup::create('<strong><a href="https://plugins.miniorange.com/guide-for-openam-as-idp-with-drupal" class="mo_guide_text-color" target="_blank">OpenAM</a></strong>');
        $mo_authnevil             = Markup::create('<strong><a href="https://plugins.miniorange.com/drupal-single-sign-on-sso-using-authanvil-as-idp" class="mo_guide_text-color" target="_blank">AuthAnvil</a></strong>');
        $mo_auth0                 = Markup::create('<strong><a href="https://plugins.miniorange.com/guide-for-auth0-as-idp-with-drupal" class="mo_guide_text-color" target="_blank">auth0</a></strong>');
        $mo_rsa                   = Markup::create('<strong><a href="https://plugins.miniorange.com/guide-for-drupal-single-sign-on-sso-using-rsa-securid-as-idp" class="mo_guide_text-color" target="_blank">RSA SecurID</a></strong>');
        $mo_Document_landing_page = Markup::create('<strong><a href="https://plugins.miniorange.com/configure-drupal-saml-single-sign-on" class="mo_guide_text-color" target="_blank">Other IDP</a></strong>');

        $mo_table_content = array (
            array( $mo_Azure_AD, $mo_ADFS ),
            array( $mo_Okta, $mo_Google_Apps  ),
            array( $mo_Salesforce, $mo_OneLogin ),
            array( $mo_Oracle, $mo_JBoss_KeyCloak  ),
            array( $mo_centrify, $mo_PingOne  ),
            array( $mo_ping, $mo_openam ),
            array( $mo_authnevil, $mo_auth0 ),
            array( $mo_miniOrange, $mo_rsa ),
            array( $mo_Document_landing_page ),
        );
        $header = array( array(
                'data' => t('Identity Provider Setup Guides'),
                'colspan' => 2,
            ),
        );

        $form['modules'] = array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $mo_table_content,
            '#responsive' => TRUE ,
            '#sticky'=> TRUE,
        );

        self::faq($form, $form_state);
        $form['miniorange_sp_guide_link_end'] = array(
            '#markup' => '</div>',
        );
    }

    public static function faq(&$form, &$form_state){

        $form['miniorange_faq'] = array(
            '#markup' => t('<br><div class="mo_saml_text_center"><b></b>
                          <a class="mo_btn btn-primary-faq btn-large mo_faq_button_left" href="https://faq.miniorange.com/kb/drupal/saml-drupal/" target="_blank">FAQs</a>
                          <b></b><a class="mo_btn btn-primary-faq btn-large mo_faq_button_right" href="https://forum.miniorange.com/" target="_blank">Ask questions on forum</a></div>'),
        );
    }

    public static function advertiseNetworkSecurity( &$form, &$form_state, $moduleType = 'Network Security' ) {
        $mo_image = 'security.jpg';
        $mo_module = 'Web Security module';
        $mo_discription = 'Building a website is a time-consuming process that requires tremendous efforts. For smooth
                    functioning and protection from any sort of web attack appropriate security is essential and we
                     ensure to provide the best website security solutions available in the market.
                    We provide you enterprise-level security, protecting your Drupal site from hackers and malware.';
        $mo_knorMoreButton = 'https://plugins.miniorange.com/drupal-web-security-pro';
        $mo_downloadModule = 'https://www.drupal.org/project/security_login_secure';
        if ( $moduleType === 'SCIM' ) {
            $mo_image = 'user-sync.png';
            $mo_module = 'User Provisioning (SCIM)';
            $mo_discription = 'miniOrange provides a ready to use solution for Drupal User Provisioning using SCIM (System for Cross-domain Identity Management) standard.
            This solution ensures that you can sync add, update, delete, and deactivate user operations with Drupal user list using the SCIM User Provisioner module.';
            $mo_downloadModule = 'https://plugins.miniorange.com/drupal-scim-user-provisioning';
            $mo_knorMoreButton = 'https://plugins.miniorange.com/drupal-scim-user-provisioning';
        }

        global $base_url;
        $form['miniorange_idp_guide_link3'] = array(
            '#markup' => '<div class="mo_saml_table_layout mo_saml_sp_container_2">
                        ',
        );
        $form['mo_idp_net_adv']=array(
            '#markup'=>t('<form name="f1">
        <table id="idp_support" class="idp-table" style="border: none;">
        <h4 class="mo_ns_image1">Looking for a Drupal ' . $mo_module . '?</h4>
            <tr class="mo_ns_row">
                <th class="mo_ns_image1"><img
                            src="'.$base_url . '/' . drupal_get_path("module", "miniorange_saml") . '/includes/images/'.$mo_image.'"
                            alt="miniOrange icon" height=10% width=35%>
                <br>
                        <h4>Drupal ' . $mo_module . '</h4>
                </th>
            </tr>

            <tr class="mo_ns_row">
                <td class="mo_ns_align">' . $mo_discription . ' </td>
            </tr>
            <tr class="mo_ns_row">
                <td class="mo_ns_td"><br> <a href=" ' . $mo_downloadModule . ' " target="_blank" class="mo_btn mo_btn-primary mo_ns_padding">Download Plugin</a>
                       <a href="' . $mo_knorMoreButton . ' " class="mo_btn mo_btn-success mo_ns_padding" target="_blank">Know More</a>
                </td>
            </tr>
        </table>
    </form>')
        );
        return $form;
    }

    /**
     * SEND SUPPORT QUERY | NEW FEATURE REQUEST | DEMO REQUEST
     * @param $email
     * @param $phone
     * @param $query
     * @param $query_type = Support | Demo Request | New Feature Request
     */
    public static function send_support_query( $email, $phone, $query, $query_type )   {
        $support = new MiniorangeSamlSupport( $email, $phone, $query, $query_type );
        $support_response = $support->sendSupportQuery();
        if ( $support_response ) {
            \Drupal::messenger()->addMessage(t('Thanks for getting in touch! We will get back to you shortly.'));
        } else {
            \Drupal::messenger()->addMessage(t('Error submitting the support query. Please send us your query at <a href="mailto:info@xecurify.com">info@xecurify.com</a>.'), 'error');
        }
    }

    public static function isCustomerRegistered() {
        if (   \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email') == NULL
            || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_id') == NULL
            || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_token') == NULL
            || \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_api_key') == NULL) {
            return false;
        }
        return true;
    }

    public static function showErrorMessage($error, $message, $cause) {
        echo '<div style="font-family:Calibri;padding:0 3%;">';
        echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                                <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>'.$error.'</p>
                                    <p>'.$message.'</p>
                                    <p><strong>Possible Cause: </strong>'.$cause.'</p>
                                </div>
                                <div style="margin:3%;display:block;text-align:center;"></div>
                                <div style="margin:3%;display:block;text-align:center;">
                                    <input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();">
                                </div>';
        exit;
    }

    Public static function getVariableArray( $class_name ) {
        if( $class_name == "mo_options_enum_identity_provider" ) {
            $class_object = array (
                'SP_Base_Url'  => 'miniorange_saml_base',
                'SP_Entity_ID' => 'miniorange_saml_base',
            );

        } elseif( $class_name == "mo_options_enum_service_provider" ) {
            $class_object = array(
                'Identity_name'          => 'miniorange_saml_idp_name',
                'Login_URL'              => 'miniorange_saml_idp_login_url',
                'Issuer'                 => 'miniorange_saml_idp_issuer',
                'Name_ID_format'         => 'miniorange_saml_nameid_format',
                'X509_certificate'       => 'miniorange_saml_idp_x509_certificate',
                'Enable_login_with_SAML' => 'miniorange_saml_enable_login',
            );
        }
        return $class_object;
    }

    public static function isCurlInstalled() {
      if (in_array('curl', get_loaded_extensions())) {
        return 1;
      }
      return 0;
    }

	public static function generateID() {
		return '_' . self::stringToHex(self::generateRandomBytes(21));
	}

	public static function stringToHex($bytes) {
		$ret = '';
		for($i = 0; $i < strlen($bytes); $i++) {
			$ret .= sprintf('%02x', ord($bytes[$i]));
		}
		return $ret;
	}

	public static function generateRandomBytes($length, $fallback = TRUE) {
		return openssl_random_pseudo_bytes($length);
	}

	public static function createAuthnRequest($acsUrl, $issuer, $nameid_format, $force_authn = 'false',$rawXml=false){

		$requestXmlStr = '<?xml version="1.0" encoding="UTF-8"?>' .
						'<samlp:AuthnRequest xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" ID="' . self::generateID() .
						'" Version="2.0" IssueInstant="' . self::generateTimestamp() . '"';

		if( $force_authn == 'true'){
			$requestXmlStr .= ' ForceAuthn="true"';
		}
		$requestXmlStr .= ' ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" AssertionConsumerServiceURL="' . $acsUrl .
						'" ><saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . $issuer . '</saml:Issuer><samlp:NameIDPolicy AllowCreate="true" Format="'.$nameid_format.'"
                        /></samlp:AuthnRequest>';
		if($rawXml){
		  return $requestXmlStr;
    }
		$deflatedStr = gzdeflate($requestXmlStr);
		$base64EncodedStr = base64_encode($deflatedStr);
		$urlEncoded = urlencode($base64EncodedStr);
		return $urlEncoded;
	}



	public static function generateTimestamp($instant = NULL) {
		if($instant === NULL) {
			$instant = time();
		}
		return gmdate('Y-m-d\TH:i:s\Z', $instant);
	}

	public static function xpQuery(DOMNode $node, $query) {
        static $xpCache = NULL;

		if($node->ownerDocument==null) {
            $doc = $node;
        } else {
            $doc = $node->ownerDocument;
        }
        if ($xpCache === NULL || !$xpCache->document->isSameNode($doc)) {
            $xpCache = new DOMXPath($doc);
            $xpCache->registerNamespace('soap-env', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xpCache->registerNamespace('saml_protocol', 'urn:oasis:names:tc:SAML:2.0:protocol');
            $xpCache->registerNamespace('saml_assertion', 'urn:oasis:names:tc:SAML:2.0:assertion');
            $xpCache->registerNamespace('saml_metadata', 'urn:oasis:names:tc:SAML:2.0:metadata');
            $xpCache->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
            $xpCache->registerNamespace('xenc', 'http://www.w3.org/2001/04/xmlenc#');
        }

        $results = $xpCache->query($query, $node);
        $ret = array();
        for ($i = 0; $i < $results->length; $i++) {
            $ret[$i] = $results->item($i);
        }

		return $ret;
    }

	public static function parseNameId(DOMElement $xml)
    {
        $ret = array('Value' => trim($xml->textContent));

        foreach (array('NameQualifier', 'SPNameQualifier', 'Format') as $attr) {
            if ($xml->hasAttribute($attr)) {
                $ret[$attr] = $xml->getAttribute($attr);
            }
        }

        return $ret;
    }

	public static function xsDateTimeToTimestamp($time)
    {
        $matches = array();

        // We use a very strict regex to parse the timestamp.
        $regex = '/^(\\d\\d\\d\\d)-(\\d\\d)-(\\d\\d)T(\\d\\d):(\\d\\d):(\\d\\d)(?:\\.\\d+)?Z$/D';
        if (preg_match($regex, $time, $matches) == 0) {
            echo sprintf("invalid SAML2 timestamp passed to xsDateTimeToTimestamp ".Xss::filter($time));
            // exit;
        }

        // Extract the different components of the time from the  matches in the regex.
        // intval will ignore leading zeroes in the string.
        $year   = intval($matches[1]);
        $month  = intval($matches[2]);
        $day    = intval($matches[3]);
        $hour   = intval($matches[4]);
        $minute = intval($matches[5]);
        $second = intval($matches[6]);

        // We use gmmktime because the timestamp will always be given
        //in UTC.
        $ts = gmmktime($hour, $minute, $second, $month, $day, $year);

        return $ts;
    }

	public static function extractStrings(DOMElement $parent, $namespaceURI, $localName) {
        $ret = array();
        for ($node = $parent->firstChild; $node !== NULL; $node = $node->nextSibling) {
            if ($node->namespaceURI !== $namespaceURI || $node->localName !== $localName) {
                continue;
            }
            $ret[] = trim($node->textContent);
        }

        return $ret;
    }

	public static function validateElement(DOMElement $root)
    {
    	//$data = $root->ownerDocument->saveXML($root);

        /* Create an XML security object. */
        $objXMLSecDSig = new XMLSecurityDSig();

        /* Both SAML messages and SAML assertions use the 'ID' attribute. */
        $objXMLSecDSig->idKeys[] = 'ID';


        /* Locate the XMLDSig Signature element to be used. */
        $signatureElement = self::xpQuery($root, './ds:Signature');

        if (count($signatureElement) === 0) {
            /* We don't have a signature element to validate. */
            return FALSE;
        } elseif (count($signatureElement) > 1) {
        	echo sprintf("XMLSec: more than one signature element in root.");
        	// exit;
        }

        $signatureElement = $signatureElement[0];
        $objXMLSecDSig->sigNode = $signatureElement;

        /* Canonicalize the XMLDSig SignedInfo element in the message. */
        $objXMLSecDSig->canonicalizeSignedInfo();
       /* Validate referenced xml nodes. */
        if (!$objXMLSecDSig->validateReference()) {
        	echo sprintf("XMLsec: digest validation failed");
			exit;
        }

		/* Check that $root is one of the signed nodes. */
        $rootSigned = FALSE;
        /** @var DOMNode $signedNode */
        foreach ($objXMLSecDSig->getValidatedNodes() as $signedNode) {
            if ($signedNode->isSameNode($root)) {
                $rootSigned = TRUE;
                break;
            } elseif ($root->parentNode instanceof DOMElement && $signedNode->isSameNode($root->ownerDocument)) {
                /* $root is the root element of a signed document. */
                $rootSigned = TRUE;
                break;
            }
        }

		if (!$rootSigned) {
			echo sprintf("XMLSec: The root element is not signed.");
			exit;
        }

        /* Now we extract all available X509 certificates in the signature element. */
        $certificates = array();
        foreach (self::xpQuery($signatureElement, './ds:KeyInfo/ds:X509Data/ds:X509Certificate') as $certNode) {
            $certData = trim($certNode->textContent);
            $certData = str_replace(array("\r", "\n", "\t", ' '), '', $certData);
            $certificates[] = $certData;
        }

        $ret = array(
            'Signature' => $objXMLSecDSig,
            'Certificates' => $certificates,
        );
        return $ret;
    }

	public static function validateSignature(array $info, XMLSecurityKey $key) {
        /** @var XMLSecurityDSig $objXMLSecDSig */
        $objXMLSecDSig = $info['Signature'];

        $sigMethod = self::xpQuery($objXMLSecDSig->sigNode, './ds:SignedInfo/ds:SignatureMethod');
        if (empty($sigMethod)) {
            echo sprintf('Missing SignatureMethod element');
            // exit();
        }
        $sigMethod = $sigMethod[0];
        if (!$sigMethod->hasAttribute('Algorithm')) {
            echo sprintf('Missing Algorithm-attribute on SignatureMethod element.');
            // exit;
        }
        $algo = $sigMethod->getAttribute('Algorithm');

        if ($key->type === XMLSecurityKey::RSA_SHA1 && $algo !== $key->type) {
            $key = self::castKey($key, $algo);
        }

        /* Check the signature. */
        if (! $objXMLSecDSig->verify($key)) {
        	echo sprintf('Unable to validate Signature');
        	// exit;
        }
    }

    public static function castKey(XMLSecurityKey $key, $algorithm, $type = 'public') {
    	// do nothing if algorithm is already the type of the key
    	if ($key->type === $algorithm) {
    		return $key;
    	}

    	$keyInfo = openssl_pkey_get_details($key->key);
    	if ($keyInfo === FALSE) {
    		echo sprintf('Unable to get key details from XMLSecurityKey.');
    		// exit;
    	}
    	if (!isset($keyInfo['key'])) {
    		echo sprintf('Missing key in public key details.');
    		// exit;
    	}

    	$newKey = new XMLSecurityKey($algorithm, array('type'=>$type));
    	$newKey->loadKey($keyInfo['key']);

    	return $newKey;
    }

	public static function processResponse($currentURL, $certFingerprint, $signatureData, SAML2_Response $response) {
		$ResCert = $signatureData['Certificates'][0];
		/* Validate Response-element destination. */
		$msgDestination = $response->getDestination();
		if ($msgDestination !== NULL && $msgDestination !== $currentURL) {
			echo sprintf('Destination in response doesn\'t match the current URL. Destination is "' .
				XSS::filter($msgDestination) . '", current URL is "' . XSS::filter($currentURL) . '".');
			// exit;
		}

		$responseSigned = self::checkSign($certFingerprint, $signatureData, $ResCert);

		/* Returning boolean $responseSigned */
		return $responseSigned;
	}

	public static function checkSign($certFingerprint, $signatureData, $ResCert) {
		$certificates = $signatureData['Certificates'];

		if (count($certificates) === 0) {
			return FALSE;
		} else {
			$fpArray = array();
			$fpArray[] = $certFingerprint;
			$pemCert = self::findCertificate($fpArray, $certificates, $ResCert);
		}

		$lastException = NULL;

		$key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'public'));
		$key->loadKey($pemCert);

		try {
			/* Make sure that we have a valid signature */
			self::validateSignature($signatureData, $key);
			return TRUE;
		} catch (Exception $e) {
			$lastException = $e;
		}

		/* We were unable to validate the signature with any of our keys. */
		if ($lastException !== NULL) {
			throw $lastException;
		} else {
			return FALSE;
		}
	}

	public static function validateIssuerAndAudience($samlResponse, $spEntityId, $issuerToValidateAgainst, $base_url) {
		$issuer = current($samlResponse->getAssertions())->getIssuer();
		$audience = current(current($samlResponse->getAssertions())->getValidAudiences());
		if(strcmp($issuerToValidateAgainst, $issuer) === 0) {
			if(strcmp($audience, $base_url) === 0) {
				return TRUE;
			} else {
				if (array_key_exists ( 'RelayState', $_REQUEST ) && ($_REQUEST['RelayState'] == 'testValidate')) {
					echo '<div style="font-family:Calibri;padding:0 3%;">';
                    echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                    <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Invalid Audience URI.</p>
                    <p>Please contact your administrator and report the following error:</p>
                    <p><strong>Possible Cause: </strong>The value of \'Audience URI\' field on Identity Provider\'s side is incorrect</p>
                    <p>Expected one of the Audiences to be: '.$spEntityId.'<p>
                    </div>
                    <div style="margin:3%;display:block;text-align:center;">
                    <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
				}else{
					echo '<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><b>Error: </b>We could not sign you in. Please contact your Administrator.</p></div>';
				}
				exit;
			}
		} else {
			if (array_key_exists ( 'RelayState', $_REQUEST ) && ($_REQUEST['RelayState'] == 'testValidate')) {
				echo '<div style="font-family:Calibri;padding:0 3%;">';
                echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
					<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Issuer cannot be verified.</p>
					<p>Please contact your administrator and report the following error:</p>
					<p><strong>Possible Cause: </strong>The value in \'IdP Entity ID or Issuer\' field in Service Provider Settings is incorrect</p>
					<p><strong>Expected Entity ID: </strong>'.$issuer.'<p>
					<p><strong>Entity ID Found: </strong>'.Xss::filter($issuerToValidateAgainst).'</p>
					</div>
					<div style="margin:3%;display:block;text-align:center;">
					<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
			}else{
				echo '<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><b>Error: </b>We could not sign you in. Please contact your Administrator.</p></div>';
			}
			exit;
		}
	}

	private static function findCertificate(array $certFingerprints, array $certificates, $ResCert) {

        $ResCert = Utilities::sanitize_certificate($ResCert);
		$candidates = array();

		foreach ($certificates as $cert) {
			$fp = strtolower(sha1(base64_decode($cert)));
			if (!in_array($fp, $certFingerprints, TRUE)) {
				$candidates[] = $fp;
				continue;
			}

			/* We have found a matching fingerprint. */
			$pem = "-----BEGIN CERTIFICATE-----\n" .
				chunk_split($cert, 64) .
				"-----END CERTIFICATE-----\n";

			return $pem;
		}
		if (array_key_exists ( 'RelayState', $_REQUEST ) && ($_REQUEST['RelayState'] == 'testValidate')) {
			echo '<div style="font-family:Calibri;padding:0 3%;">';
			echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
				<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Unable to find a certificate matching the configured fingerprint.</p>
				<p><strong>Possible Cause: </strong>Content of \'X.509 Certificate\' field in Service Provider Settings is incorrect</p>
				<p><b>Expected value:</b>' . $ResCert . '</p>';
			echo str_repeat('&nbsp;', 15);
			echo'</div>
				<div style="margin:3%;display:block;text-align:center;">
				<form action="index.php">
				<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
		} else {
			echo ' <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><b>Error: </b>We could not sign you in. Please contact your Administrator.</p></div>';
		}
		exit;
	}

	public static function sanitize_certificate( $certificate, $getRaw = FALSE ) {
		$certificate = preg_replace("/[\r\n]+/", "", $certificate );
		$certificate = str_replace( "-", "", $certificate );
		$certificate = str_replace( "BEGIN CERTIFICATE", "", $certificate );
		$certificate = str_replace( "END CERTIFICATE", "", $certificate );
		$certificate = str_replace( " ", "", $certificate );
		if( $getRaw )
		  return $certificate;
		$certificate = chunk_split($certificate, 64, "\r\n");
		$certificate = "-----BEGIN CERTIFICATE-----\r\n" . $certificate . "-----END CERTIFICATE-----";
		return $certificate;
	}

	public static function Print_SAML_Request($samlRequestResponceXML, $type)
	{
        header("Content-Type: text/html");
        $doc = new \DOMDocument();
		$doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML($samlRequestResponceXML);
		if($type=='displaySAMLRequest')
            $show_value='SAML Request';
        else
            $show_value='SAML Response';
        $out = $doc->saveXML();

        $out1 = htmlentities($out);
        $out1 = rtrim($out1);

        $xml   = simplexml_load_string( $out );

        $json  = json_encode( $xml );

        $array = json_decode( $json );

        $url = \Drupal::service('extension.list.module')->getPath('miniorange_saml'). '/css/miniorange_saml.module.css';
        $jsurl = \Drupal::service('extension.list.module')->getPath('miniorange_saml').'/js/showSAMLResponse.js';

        echo '<link rel=\'stylesheet\' id=\'mo_saml_admin_settings_style-css\'  href=\''.$url.'\' type=\'text/css\' media=\'all\' />
            <script src=\''.$jsurl.'\'></script>
			<div class="mo-display-logs" ><p type="text"   id="SAML_type">'.$show_value.'</p></div>

			<div type="text" id="SAML_display" class="mo-display-block"><pre class=\'brush: xml;\'>'.$out1.'</pre></div>
			<br>
			<div style="margin:3%;display:block;text-align:center;">

			<div style="margin:3%;display:block;text-align:center;" >

            </div>
			<button id="copy" onclick="copyDivToClipboard()"  style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;" >Copy</button>
			&nbsp;
               <button id="dwn_btn" onclick="test_download()" style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;">Download</button>
			</div>
			</div>

			';

        exit;
    }

        public static function customer_setup_submit($username, $phone, $password, $login=false, $called_from_popup=false, $payment_plan=NULL){

        global $base_url;
        $customer_config = new MiniorangeSAMLCustomer($username, $phone, $password, NULL);
        $check_customer_response = json_decode($customer_config->checkCustomer());
        $db_config = \Drupal::configFactory()->getEditable('miniorange_saml.settings');

        if ($check_customer_response->status=='TRANSACTION_LIMIT_EXCEEDED'){
            if ($called_from_popup == true) {
                miniorange_saml_sp_registration::register_data(true);
            }else{
                \Drupal::messenger()->addMessage(t('An error has been occured. Please Try after some time or <a href="mailto:'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'"><i>contact us</i></a>.'), 'error');
                return;
            }
        }
        if ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') {
            if ($login == true && $called_from_popup == false) {
                \Drupal::messenger()->addMessage(t('The account with username <i>'.$username.'</i> does not exist.'), 'error');
                return;
            }
            $db_config->set('miniorange_saml_customer_admin_email', $username)->save();
            $db_config->set('miniorange_saml_customer_admin_phone', $phone)->save();
            $db_config->set('miniorange_saml_customer_admin_password', $password)->save();
            $send_otp_response = json_decode($customer_config->sendOtp());


            if ($send_otp_response->status == 'SUCCESS') {
                $db_config->set('miniorange_saml_tx_id', $send_otp_response->txId)->save();
                $db_config->set('miniorange_saml_status', 'VALIDATE_OTP')->save();

                if ($called_from_popup == true) {
                    miniorange_saml_sp_registration::miniorange_otp(false,false,false);
                }else{
                    \Drupal::messenger()->addMessage(t('Verify email address by entering the passcode sent to @username', [
                        '@username' => $username
                    ]));
                }
            }else{
                if ($called_from_popup == true) {
                    miniorange_saml_sp_registration::register_data(true);
                }else{
                    \Drupal::messenger()->addMessage(t('An error has been occured. Please try after some time.'),'error');
                }
            }
        } elseif ($check_customer_response->status == 'CURL_ERROR') {
            if ($called_from_popup == true) {
                    miniorange_saml_sp_registration::register_data(true);
            }else{
                \Drupal::messenger()->addMessage(t('cURL is not enabled. Please enable cURL'), 'error');
                return;
            }
        } else {
            $customer_keys_response = json_decode($customer_config->getCustomerKeys());

            if (json_last_error() == JSON_ERROR_NONE) {
                $db_config->set('miniorange_saml_customer_id', $customer_keys_response->id)->save();
                $db_config->set('miniorange_saml_customer_admin_token', $customer_keys_response->token)->save();
                $db_config->set('miniorange_saml_customer_admin_email', $username)->save();
                $db_config->set('miniorange_saml_customer_admin_phone', $phone)->save();
                $db_config->set('miniorange_saml_customer_api_key', $customer_keys_response->apiKey)->save();
                $db_config->set('miniorange_saml_status', 'PLUGIN_CONFIGURATION')->save();

                if ($called_from_popup == true) {
                    $redirect_url = \Drupal::config('miniorange_saml.settings')->get('redirect_plan_after_registration_' . $payment_plan);
                    $redirect_url = str_replace('none', $username, $redirect_url);
                }else{
                    $redirect_url = $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL;
                }

                $response = new RedirectResponse($redirect_url);
                $response->send();

            } else {
                if ($called_from_popup == true) {
                    miniorange_saml_sp_registration::register_data(false, true);
                }else{
                    \Drupal::messenger()->addMessage(t('Invalid credentials'), 'error');
                    return;
                }
            }
        }
    }

    public static function validate_otp_submit( $otp_token, $called_from_popup = false, $payment_plan = NULL ){
        global $base_url;
        $db_config  = \Drupal::config('miniorange_saml.settings');
        $db_edit    = \Drupal::configFactory()->getEditable('miniorange_saml.settings');
        $username   = $db_config->get('miniorange_saml_customer_admin_email');
        $phone      = $db_config->get('miniorange_saml_customer_admin_phone');
        $tx_id      = $db_config->get('miniorange_saml_tx_id');
        $customer_config = new MiniorangeSAMLCustomer($username, $phone, NULL, $otp_token);
        $validate_otp_response = json_decode($customer_config->validateOtp($tx_id));

        if ($validate_otp_response->status == 'SUCCESS') {
            $db_edit->clear('miniorange_saml_tx_id')->save();
            $password = $db_config->get('miniorange_saml_customer_admin_password');
            $customer_config = new MiniorangeSAMLCustomer($username, $phone, $password, NULL);
            $create_customer_response = json_decode( $customer_config->createCustomer() );
            if ($create_customer_response->status == 'SUCCESS') {
                $db_edit->set('miniorange_saml_status', 'PLUGIN_CONFIGURATION')->save();
                $db_edit->set('miniorange_saml_customer_admin_email', $username)->save();
                $db_edit->set('miniorange_saml_customer_admin_phone', $phone)->save();
                $db_edit->set('miniorange_saml_customer_admin_token', $create_customer_response->token)->save();
                $db_edit->set('miniorange_saml_customer_id', $create_customer_response->id)->save();
                $db_edit->set('miniorange_saml_customer_api_key', $create_customer_response->apiKey)->save();
                \Drupal::messenger()->addMessage(t('Your account has been created successfully!'));

                if ($called_from_popup == true) {
                    $redirect_url = $db_edit->get('redirect_plan_after_registration_' . $payment_plan);
                    $redirect_url = str_replace('none', $username, $redirect_url);
                }else{
                    $redirect_url = $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL;
                }
                miniorange_saml_sp_registration::miniorange_redirect_successfull($redirect_url);

            } else if (trim($create_customer_response->status) == 'INVALID_EMAIL_QUICK_EMAIL') {
                \Drupal::messenger()->addMessage(t('There was an error creating an account for you. You may have entered an invalid Email-Id
                <strong>(We discourage the use of disposable emails) </strong>
                Please try again with a valid email.'), 'error');
                $db_edit->set('miniorange_saml_status', '')->save();
                if ( $called_from_popup == true )
                    self::saml_back(true);
                else
                    return;
            } else {
                \Drupal::messenger()->addMessage(t('There was an error while creating customer. Please try after some time.'), 'error');
                if ($called_from_popup == true)
                    self::saml_back(true);
                else
                    return;
            }
        } else {
            if ( $called_from_popup == true ) {
                miniorange_saml_sp_registration::miniorange_otp(true,false,false);
            } else {
                \Drupal::messenger()->addMessage(t('Invalid  OTP'), 'error');
                return;
            }
        }
    }

    public static function saml_resend_otp($called_from_popup=false){
        $db_edit = \Drupal::configFactory()->getEditable('miniorange_saml.settings');
        $db_edit->clear('miniorange_saml_tx_id')->save();
        $username = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');
        $phone = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_phone');
        $customer_config = new MiniorangeSAMLCustomer($username, $phone, NULL, NULL);
        $send_otp_response = json_decode($customer_config->sendOtp());
        if ($send_otp_response->status == 'SUCCESS') {
            // Store txID.
            $db_edit->set('miniorange_saml_tx_id', $send_otp_response->txId)->save();
            $db_edit->set('miniorange_saml_status', 'VALIDATE_OTP')->save();
            if ($called_from_popup == true) {
                miniorange_saml_sp_registration::miniorange_otp(false,true,false);
            }else{
                \Drupal::messenger()->addMessage(t('Verify email address by entering the passcode sent to @username', array('@username' => $username)));
            }
        }else{
            if ($called_from_popup == true) {
                miniorange_saml_sp_registration::miniorange_otp(false,false,true);
            }else{
                \Drupal::messenger()->addMessage(t('An error has been occured. Please try after some time'),'error');
            }
        }
    }

    public static function saml_back($called_from_popup=false){
        $db_edit = \Drupal::configFactory()->getEditable('miniorange_saml.settings');
        $db_edit->set('miniorange_saml_status', 'CUSTOMER_SETUP')->save();
        $db_edit->clear('miniorange_saml_customer_admin_email')->save();
        $db_edit->clear('miniorange_saml_customer_admin_phone')->save();
        $db_edit->clear('miniorange_saml_tx_id')->save();

        if ($called_from_popup == true) {
            self::redirect_to_licensing();
        }else{
            \Drupal::messenger()->addMessage(t('Register/Login with your miniOrange Account'), 'status');
        }
    }

    public static function redirect_to_licensing(){
        global $base_url;
        $redirect_url = $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL;
        $response = new RedirectResponse($redirect_url);
        $response->send();
    }

    public static function is_sp_configured(){
        $db_config = \Drupal::config('miniorange_saml.settings');

        if (empty($db_config->get('miniorange_saml_idp_name')) || empty($db_config->get('miniorange_saml_idp_issuer')) || empty($db_config->get('miniorange_saml_idp_login_url'))) {
            self::moShowErrorMessage( 'Service Provider is not configured.' );
        }
    }

    /**
     * Add premium tag for premium features
     * @param $mo_tag
     * @return string
     */
    public static function mo_add_premium_tag( $mo_tag ) {
        global $base_url ;
        $url = $base_url .'/admin/config/people/miniorange_saml/Licensing';
        $mo_premium_tag = '<a href= "'.$url.'" style="color: red; font-weight: lighter;">['. $mo_tag .']</a>';
        return $mo_premium_tag;
    }

    public static function mo_get_drupal_core_version() {
      return \DRUPAL::VERSION;
    }

    public static function moShowErrorMessage( $moErrorMessage ) {
        echo '<div style="font-family:Calibri;padding:0 3%;">';
        echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                    <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>' . $moErrorMessage . '</p></div>
                    <div style="margin:3%;display:block;text-align:center;">
                    <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
        exit();
    }
    public static function drupal_is_cli()
    {
      $server = \Drupal::request()->server;
      $server_software = $server->get('SERVER_SOFTWARE');
      $server_argc = $server->get('argc');

      if(!isset($server_software) && (php_sapi_name() == 'cli' || (is_numeric($server_argc) && $server_argc > 0)))
        return TRUE;
      else
        return FALSE;
    }

    public static function getBaseUrl() {
      global $base_url;
      $saved_base_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_base_url');
      return isset( $saved_base_url ) && !empty( $saved_base_url ) ? $saved_base_url : $base_url;
    }

    public static function getIssuer(){
      $saved_issuer      = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_entity_id');
      return isset($saved_issuer) && !empty($saved_issuer)? $saved_issuer : self::getBaseUrl();
    }

    public static function getAcsUrl(){
      $b_url = self::getBaseUrl();
      return substr( $b_url, -1 ) == '/' ?  $b_url . 'samlassertion' : $b_url . '/samlassertion';
    }
}