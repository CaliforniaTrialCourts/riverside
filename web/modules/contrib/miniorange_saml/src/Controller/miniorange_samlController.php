<?php /**
 * @file
 * Contains \Drupal\miniorange_saml\Controller\DefaultController.
 */

namespace Drupal\miniorange_saml\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\miniorange_saml\Utilities;
use Drupal\miniorange_saml\Api\MoAuthApi;
use Drupal\miniorange_saml\MiniOrangeAcs;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Symfony\Component\HttpFoundation\Response;
use Drupal\miniorange_saml\MiniOrangeAuthnRequest;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\miniorange_saml\miniorange_saml_sp_registration;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Default controller for the miniorange_saml module.
 */
class miniorange_samlController extends ControllerBase {
    protected $formBuilder;

    public function __construct(FormBuilder $formBuilder = NULL){
        $this->formBuilder = $formBuilder;
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get("form_builder")
        );
    }

    public function miniorange_saml_feedback_func() {
        if(isset($_POST['email']) && $_POST['email'] !== "") {
          $modules_info = \Drupal::service('extension.list.module')
            ->getExtensionInfo('miniorange_saml');
          $modules_version = $modules_info['version'];

          $res = json_encode($_POST);
          $outarr = json_decode($res, TRUE);
          $_SESSION['mo_other'] = "False";
          $reason = $outarr['reason'];
          $q_feedback = $outarr['q_feedback'];
          $message = 'Reason: ' . $reason . '<br>Feedback: ' . $q_feedback;
          $email = \Drupal::config('miniorange_saml.settings')
            ->get('miniorange_saml_customer_admin_email');
          if (empty($email)) {
            $email = $outarr['email'];
          }
          if (\Drupal::service('email.validator')->isValid($email)) {
            $phone = \Drupal::config('miniorange_saml.settings')
              ->get('miniorange_saml_customer_admin_phone');
            $customerKey = \Drupal::config('miniorange_saml.settings')
              ->get('miniorange_saml_customer_id');
            $apikey = \Drupal::config('miniorange_saml.settings')
              ->get('miniorange_saml_customer_api_key');

            $mo_drupal_version = Utilities::mo_get_drupal_core_version();

            $fromEmail = $email;
            $subject = 'Drupal ' . $mo_drupal_version . ' SAML SP Free Module Feedback | ' . $modules_version;
            $query = '[Drupal ' . $mo_drupal_version . ' SAML SP Free | ' . $modules_version . ']: ' . $message;
            $content = '<div >Hello, <br><br>Company :<a href="' . $_SERVER['SERVER_NAME'] . '" target="_blank" >' . $_SERVER['SERVER_NAME'] . '</a><br><br>Phone Number :' . $phone . '<br><br>Email :<a href="mailto:' . $fromEmail . '" target="_blank">' . $fromEmail . '</a><br><br>Query :' . $query . '</div>';
            $fields = [
              'customerKey' => !isset($customerKey) || empty($customerKey) ? MiniorangeSAMLConstants::DEFAULT_CUSTOMER_ID : $customerKey,
              'sendEmail' => TRUE,
              'email' => [
                'customerKey' => !isset($customerKey) || empty($customerKey) ? MiniorangeSAMLConstants::DEFAULT_CUSTOMER_ID : $customerKey,
                'fromEmail' => $fromEmail,
                'fromName' => 'miniOrange',
                'toEmail' => MiniorangeSAMLConstants::SUPPORT_EMAIL,
                'toName' => MiniorangeSAMLConstants::SUPPORT_EMAIL,
                'subject' => $subject,
                'content' => $content
              ],
            ];
            $url = MiniorangeSAMLConstants::FEEDBACK_API;
            $api = $customerKey == '' ? new MoAuthApi() : new MoAuthApi($customerKey, $apikey);
            $header = $api->getHttpHeaderArray();
            $api->makeCurlCall($url, $fields, $header);
          }
        }
      \Drupal::configFactory()->getEditable('miniorange_saml.settings')->clear('miniorange_saml_feedback')->save();
      \Drupal::service('module_installer')
        ->uninstall(['miniorange_saml']);
      return new Response();
    }

    public function openModalForm() {
        $response = new AjaxResponse();
        $modal_form = $this->formBuilder->getForm('\Drupal\miniorange_saml\Form\MiniorangeSAMLRemoveLicense');
        $response->addCommand(new OpenModalDialogCommand('Remove Account', $modal_form, ['width' => '800']));
        return $response;
    }

    public function saml_login($relay_state="") {
        $base_url = Utilities::getBaseUrl();
        Utilities::is_sp_configured();
        $issuer =  Utilities::getIssuer();
        $saml_login_url = $base_url . '/samllogin';

        if ( empty($relay_state) || $relay_state==$saml_login_url ) {
            $relay_state = $_SERVER['HTTP_REFERER'];
        }
        if(empty($relay_state) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])){
          $pre = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
          $url = $pre . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          $relay_state = $url;
        }
        if ( empty($relay_state) || $relay_state==$saml_login_url ) {
            $relay_state = $base_url;
        }

        $acs_url = Utilities::getAcsUrl();
        $sso_url = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_login_url');
        $nameid_format = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_nameid_format');
        $authn_request = new MiniOrangeAuthnRequest();
        $redirect = $authn_request->initiateLogin( $acs_url, $sso_url, $issuer, $nameid_format, $relay_state );
        $response = new RedirectResponse( $redirect );
        $response->send();
        return new Response();
    }

    public function saml_response() {
        $base_url = Utilities::getBaseUrl();
        $acs_url = Utilities::getAcsUrl();
        $cert_fingerprint = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_x509_certificate');
        $issuer = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_idp_issuer');
        $sp_entity_id = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_sp_issuer');

        $username_attribute = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_email_attribute');

        if ( isset( $_GET['SAMLResponse'] ) ) {
            session_destroy();
            $response = new RedirectResponse($base_url);
            $response->send();
            return new Response();
        }
        $attrs = array();
        $role = array();
        $response_obj = new MiniOrangeAcs();
        $response = $response_obj->processSamlResponse($_POST, $acs_url, $cert_fingerprint, $issuer, $base_url, $sp_entity_id, $username_attribute, $attrs, $role);

        $account = user_load_by_name( $response['username'] );

        // Create user if not already present.
        if ( $account == NULL ) {

            $random_password = user_password(8);

            $new_user = [
                'name' => $response['username'],
                'mail' => $response['email'],
                'pass' => $random_password,
                'status' => 1,
            ];
            // user_save() is now a method of the user entity.
            $account = User::create( $new_user );
            $account->save();

            $enable_roleMapping = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_enable_rolemapping');

            if ( $enable_roleMapping ) {
                /**
                 * Getting machine names of the roles.
                 */
                $roles_with_machine_name = \Drupal::entityTypeManager()->getStorage('user_role')->loadMultiple();
                $roles_with_machine_name_array = array();
                foreach ( $roles_with_machine_name as $key => $values ) {
                    $roles_with_machine_name_array[$key] = strtolower( $values->label() );
                }

                /**
                 * Get machine name of the default role. (eg. Authenticated User(role) = authenticated(machine name))
                 */
                $default_role = \Drupal::config('miniorange_saml.settings')->get( 'miniorange_saml_default_role' );
                foreach ( $roles_with_machine_name_array as $machine_name => $role_name ) {
                    if ( $role_name == strtolower( $default_role ) ) {
                        $default_role_value = $machine_name;
                    }
                }

                /**
                 * Assign default role for user is default role is other than AUTHENTICATED USER.
                 */
                if ( isset( $default_role_value ) && $default_role_value != 'authenticated' ) {
                    $account->addRole( $default_role_value );
                    $account->save();
                }
            }
        }

        if ( user_is_blocked($response['username'] ) == FALSE ) {
            $rediectUrl = $base_url;
            if ( array_key_exists('relay_state', $response) && !empty( trim($response['relay_state']) ) ) {
                $rediectUrl = $response['relay_state'];
            }
            $_SESSION['sessionIndex'] = $response['sessionIndex'];
            $_SESSION['NameID'] = $response['NameID'];
            $_SESSION['mo_saml']['logged_in_with_idp'] = TRUE;

			     /**
            * Invoke the hook and check whether 2FA is enabled or not.
            */
            \Drupal::moduleHandler()->invokeAll( 'invoke_miniorange_2fa_before_login', [$account] );

            user_login_finalize($account);

            $response = new RedirectResponse( $rediectUrl );
            $request  = \Drupal::request();
            $request->getSession()->save();
            $response->prepare($request);
            \Drupal::service('kernel')->terminate($request, $response);
            $response->send();exit();
            return new Response();

        } else {
            $error = t('User Blocked By Administrator.');
            $message = t('Please Contact your administrator.');
            $cause = t('This user account is not allowed to login.');
            Utilities::showErrorMessage($error, $message, $cause);
            return new Response();
        }
    }

    /**
     * Test configuration callback
     */

    function test_configuration() {
        $this->saml_login('testValidate');
        return new Response();
    }

    function saml_request() {
        $this->saml_login("displaySAMLRequest");
        return new Response();
    }

    function saml_response_generator() {
        $this->saml_login('showSamlResponse');
        return new Response();
    }

    function saml_metadata() {
        $entity_id = Utilities::getIssuer();
        $acs_url   = Utilities::getAcsUrl();
        $header    = isset($_REQUEST['download']) && boolval($_REQUEST['download']) ? 'Content-Disposition: attachment; filename="Metadata.xml"' : 'Content-Type: text/xml';
        header($header);
        echo '<?xml version="1.0"?>
                <md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2024-03-27T23:59:59Z" cacheDuration="PT1446808792S" entityID="' . $entity_id . '">
                  <md:SPSSODescriptor AuthnRequestsSigned="false" WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
                    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified</md:NameIDFormat>
                    <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="' . $acs_url . '" index="1"/>
                  </md:SPSSODescriptor>
                  <md:Organization>
                    <md:OrganizationName xml:lang="en-US">miniOrange</md:OrganizationName>
                    <md:OrganizationDisplayName xml:lang="en-US">miniOrange</md:OrganizationDisplayName>
                    <md:OrganizationURL xml:lang="en-US">http://miniorange.com</md:OrganizationURL>
                  </md:Organization>
                  <md:ContactPerson contactType="technical">
                    <md:GivenName>miniOrange</md:GivenName>
                    <md:EmailAddress>info@xecurify.com</md:EmailAddress>
                  </md:ContactPerson>
                  <md:ContactPerson contactType="support">
                    <md:GivenName>miniOrange</md:GivenName>
                    <md:EmailAddress>info@xecurify.com</md:EmailAddress>
                  </md:ContactPerson>
                </md:EntityDescriptor>';
        exit;
    }
    public function miniorange_saml_register(){
        $payment_plan = isset($_GET['payment_plan']) ? $_GET['payment_plan'] : '';
        miniorange_saml_sp_registration::miniorange_saml_register_popup($payment_plan);
        return new Response();
    }

    public function miniorange_saml_close_registration(){
        Utilities::saml_back(true);
        return new Response();
    }
}