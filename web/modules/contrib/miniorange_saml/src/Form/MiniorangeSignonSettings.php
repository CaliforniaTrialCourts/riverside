<?php
/**
 * @file
 * Contains Login Settings for miniOrange SAML Login Module.
 */

 /**
 * Showing Settings form.
 */
 namespace Drupal\miniorange_saml\Form;

 use Drupal\Core\Form\FormStateInterface;
 use Drupal\Core\Form\FormBase;
 use Drupal\miniorange_saml\Utilities;
 use Drupal\miniorange_saml\MiniorangeSAMLConstants;

 class MiniorangeSignonSettings extends FormBase {

  public function getFormId() {
    return 'miniorange_saml_login_setting';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

      global $base_url;
      $form['miniorange_saml_markup_library'] = array(
        '#attached' => array(
          'library' => array(
            'miniorange_saml/miniorange_saml.admin',
          )
        ),
      );

      $form['markup_start'] = array(
         '#markup' => t('<div class="mo_saml_sp_table_layout_1"><div id="signon_settings_tab" class="mo_saml_table_layout mo_saml_sp_container">')
      );

      /**
       * Create container to hold @SigninSettings form elements.
       */
      $form['mo_saml_singnin_settings'] = array(
          '#type' => 'fieldset',
          //'#title' => t('Signin Settings'),
          '#attributes' => array( 'style' => 'padding:2% 2% 5%; margin-bottom:2%' ),
          '#prefix'=>'<div class="mo_saml_font_for_heading_none_float">SIGNIN SETTINGS</div><hr>'
      );

      $form['mo_saml_singnin_settings']['markup_top'] = array(
          '#markup' => t('<div class="mo_saml_highlight_background_note_1"><b>Note:</b> All the features are available in <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Standard, Premium and Enterprise</a> versions of the module</div><br>')
      );

      $form['mo_saml_singnin_settings']['miniorange_saml_disable_autocreate_users'] = array(
          '#type' => 'checkbox',
          '#title' => t('Check this option if you want to disable <b>auto creation</b> of users if user does not exist.'),
          '#disabled' => TRUE,
          '#description'=> t('<b>Note: </b>If you enable this feature new user wont be created, only existing users can login using SSO.<br><br>'),
      );

      $form['mo_saml_singnin_settings']['miniorange_saml_force_auth'] = array(
        '#type' => 'checkbox',
        '#title' => t('Protect website against anonymous access'),
        '#description' => t('<b>Note: </b>Users will be redirected to your IdP for login in case user is not logged in and tries to access website.<br><br>'),
        '#disabled' => TRUE,
      );

      $form['mo_saml_singnin_settings']['miniorange_saml_auto_redirect'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option if you want to <b>auto redirect the user to IdP.</b>'),
        '#description' => t('<b>Note:</b> Users will be redirected to your IdP for login when the login page is accessed.<br><br>'),
        '#disabled' => TRUE,
      );

      $form['mo_saml_singnin_settings']['miniorange_saml_enable_backdoor'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option if you want to enable <b>backdoor login.</b>'),
        '#disabled' => TRUE,
        '#description' => t('<b>Note: </b>Checking this option <b>creates a backdoor to login to your website using Drupal credentials</b>
              <br>incase you get locked out of your IdP. <br><b>Note down this URL: <span style="color: #FF0000"><code>We provide backdoor URL in standard, premium & enterprise version of the module.</code></span></b><br><br>'),
      );

      $form['mo_saml_singnin_settings']['miniorange_saml_default_relaystate'] = array(
        '#type' => 'textfield',
        '#title' => t('Default Redirect URL after login.'),
        '#attributes' => array('style' => 'width:700px; background-color: hsla(0,0%,0%,0.08) !important','placeholder' => t('Enter Default Redirect URL')),
        '#disabled' => TRUE,
        '#suffix' => '<br>',
      );

      $form['mo_saml_singnin_settings']['mo_saml_domain_restriction'] = array(
          '#type' => 'fieldset',
          //'#title' => t('Domain Restriction'),
          '#attributes' => array( 'style' => 'padding:2% 2%; margin-bottom:2%' ),
      );

      $form['mo_saml_singnin_settings']['mo_saml_domain_restriction']['miniorange_saml_domain_restriction_checkbox'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option if you want  <b>Domain Restriction <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">[Enterprise]</a></b>'),
        '#disabled' => TRUE,
      );
      $form['mo_saml_singnin_settings']['mo_saml_domain_restriction']['miniorange_saml_allow_or_block_domains']=array(
          '#type'=>'radios',
          '#maxlength' => 1,
          '#options' => array(  0 => t('I want to allow only some of the domains'),1 => t('I want to block some of the domains')),
          '#disabled' => TRUE,

      );
      $form['mo_saml_singnin_settings']['mo_saml_domain_restriction']['miniorange_saml_domains'] = array(
        '#type' => 'textfield',
        '#title' => t('Enter list of domains'),
        '#attributes' => array('style' => 'width:700px; background-color: hsla(0,0%,0%,0.08) !important',
          'placeholder' => t('Enter semicolon(;) separated domains (Eg. xxxx.com; xxxx.com)')),
        '#disabled' => TRUE,
        '#suffix' => '',
      );
      $form['mo_saml_singnin_settings']['miniorange_saml_gateway_config_submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save Configuration'),
        '#disabled' => TRUE,
        '#prefix' => '<br><br>',
        '#suffix' => '<br><br></div>',
      );

      Utilities::advertiseNetworkSecurity($form, $form_state, 'SCIM');

  return $form;

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
 }