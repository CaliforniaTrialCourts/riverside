<?php

/**
 * @file
 * Contains \Drupal\miniorange_saml\Form\MiniorangeIDPSetup.
 */

namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Render\Markup;
use Drupal\miniorange_saml\Utilities;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;

class MiniorangeIDPSetup extends FormBase {

    public function getFormId() {
        return 'miniorange_saml_idp_setup';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $module_path = \Drupal::service('extension.list.module')->getPath('miniorange_saml');

        Utilities::visual_tour_start($form, $form_state);

        $base_url = Utilities::getBaseUrl();
        $acs_url = $base_url . '/samlassertion';

        $form['miniorange_saml_copy_button'] = array(
            '#attached' => array(
                'library' => array(
                    'miniorange_saml/miniorange_saml_copy.icon',
                    'miniorange_saml/miniorange_saml.admin',
                )
            ),
            '#prefix' => '<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container">',
        );

        /**
         * Create container to hold @ServiceProviderMetadata form elements.
         */



        $form['mo_saml_metadata_option'] = array(
            '#markup' => t('<div class="mo_saml_font_for_heading">Service Provider Metadata</div><a id="Restart_moTour" class="mo_btn mo_btn-primary mo_btn-sm mo_tour_button_float" onclick="Restart_moTour()">Take a Tour</a><p style="clear: both"></p><hr>
                                '),
        );
        $form['mo_saml_service_provider_metadata'] = array(
            '#type' => 'fieldset',
            //'#title' => t('Service Provider Metadata'),
            '#attributes' => array( 'style' => 'padding:2% 2% 5%; margin-bottom:2%' ),
        );

        $form['mo_saml_service_provider_metadata']['markup_idp_sp_2'] = array(
            '#markup' => t('<br><div class="mo_saml_font_SP_setup_for_heading"><strong>Provide this module information to your Identity Provider team.<br> You can choose any one of the below options.</strong></div>
                          <br><b>a) Provide this metadata URL to your Identity Provider:</b><br><div>
                            <div class="mo_saml_highlight_background_url_note" id="idp_metadata_url">
                                <code><b>
                                    <span>
                                        <a target="_blank" href="' . $base_url . '/saml_metadata">' . $base_url . '/saml_metadata' . '</a>
                                    </span></b>
                                </code>
                            </div>
                            <img class ="fa fa-fw fa-lg fa-copy mo_copy mo-margin" src="'. $base_url.'/'.$module_path . '/includes/images/copy-regular.svg">
                        </div>'),
        );

        $form['mo_saml_service_provider_metadata']['mo_saml_download_btn_title'] = array(
            '#markup' => t('<br><br><div id="download_metadata_xml_file"><b>b) Download the Module XML metadata and upload it on your Identity Provider : </b>
                        <span><a href="' . $base_url . '/saml_metadata?download=true" class="mo_btn mo_btn-danger">Download XML Metadata</a></span></div>
                        <br><br><div><b>c) Provide the following information to your Identity Provider. Copy it and keep it handy.</b></div><br>'),
        );

        $copy_image = '<img class ="fa fa-fw fa-pull-right fa-lg fa-copy mo_copy" src="'. $base_url.'/'.$module_path . '/includes/images/copy-regular.svg">';

        $SP_Entity = [
            'data' => Markup::create('<span id="issuer_id">' . Utilities::getIssuer() . '</span>'. $copy_image )
        ];
        $SP_ACS = [
            'data' => Markup::create('<span id="login_url">' . $acs_url . '</span>'. $copy_image )
        ];
        $Audience = [
            'data' => Markup::create('<span id="audience_url">' . $base_url . '</span>'. $copy_image )
        ];
        $X_509_certificate = [
            'data' => Markup::create('Available in </b><a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Standard, Premium and Enterprise</a> version.' )
        ];
        $Recipient = [
            'data' => Markup::create('<span id="recipientURL">' . $acs_url . '</span>'. $copy_image )
        ];
        $Destination = [
            'data' => Markup::create('<span id="destinationURL">' . $acs_url . '</span>'. $copy_image )
        ];
        $SingleLogoutURL = [
            'data' => Markup::create('Available in </b><a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Premium and Enterprise</a> version.' )
        ];
        $NameIDFormat = [
            'data' => Markup::create('urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified' )
        ];

        $mo_table_content = array (
            array( 'SP Entity ID/Issuer', $SP_Entity ),
            array( 'SP ACS URL',          $SP_ACS ),
            array( 'Audience URI',        $Audience ),
            array( 'X.509 Certificate',    $X_509_certificate ),
            array( 'Recipient URL',       $Recipient ),
            array( 'Destination URL',     $Destination ),
            array( 'Single Logout URL',   $SingleLogoutURL ),
            array( 'NameID Format',       $NameIDFormat),
        );

        $form['mo_saml_service_provider_metadata']['mo_saml_attrs_list_idp'] = array(
            '#type' => 'table',
            '#header'=> array( 'ATTRIBUTE', 'VALUE' ),
            '#rows' => $mo_table_content,
            '#empty' => t('Something is not right. Please run the update script or contact us at <a href="'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'">'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'</a>'),
            '#responsive' => TRUE ,
            '#sticky'=> TRUE,
            '#size'=> 2,
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_update_url_note'] = array(
            '#markup'=>t('<br><hr><br><div class="mo_saml_highlight_background_note_1"><strong>Note: </strong> If you have already shared the below URLs or Metadata with your IdP, <strong>DO NOT UPDATE</strong> SP EntityID. It might break your existing login flow.
                         Available in the <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Standard, Premium and Enterprise</a> version.   </div><br>')
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_base_url'] = array(
            '#type' => 'textfield',
            '#title' => t('SP Base URL:'),
            '#default_value' => Utilities::getBaseUrl(),
            '#attributes' => array('style' => 'width:70%'),
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_entity_id'] = array(
            '#type' => 'textfield',
            '#title' => t('SP Entity ID/Issuer:'),
            '#default_value' => Utilities::getIssuer(),
            '#attributes' => array('style' => 'width:70%'),
        );

        $form['mo_saml_service_provider_metadata']['miniorange_saml_idp_config_submit'] = array(
            '#type' => 'submit',
            '#value' => t('Update'),
            '#button_type' => 'primary',
            '#prefix' => '<br>',
            '#suffix' => '<br></div>',
        );

        Utilities::spConfigGuide($form, $form_state);

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
      $form_values = $form_state->getValues();
      $b_url       = $form_values['miniorange_saml_base_url'];
      $issuer_id   = $form_values['miniorange_saml_entity_id'];
      \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_base_url', $b_url)->save();
      \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_entity_id', $issuer_id)->save();
      \Drupal::messenger()->addStatus(t('Base URL and/or Issuer updated successfully.'));
    }
}