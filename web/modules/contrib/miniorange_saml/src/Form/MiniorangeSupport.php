<?php
/**
 * @file
 * Contains support form for miniOrange 2FA Login Module.
 */
namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;
use Drupal\miniorange_saml\Utilities;

/**
 *  Showing Support form info.
 */
class MiniorangeSupport extends FormBase
{
    public function getFormId() {
        return 'miniorange_SAML_support';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $email = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');
        $phone = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_phone');
        $form['miniorange_saml_markup_library'] = array(
          '#attached' => array(
            'library' => array(
              'miniorange_saml/miniorange_saml.admin',
            )
          ),
        );
        $form['markup_1'] = array(
            '#markup' => t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container">'),
        );

        $form['mo_saml_vertical_tabs'] = array(
            '#type' => 'vertical_tabs',
            '#default_tab' => 'edit-publication',
        );

        /**
         * Support form
         */
        $form['miniorange_support'] = array (
            '#type' => 'details',
            '#title' => t('Support'),
            '#group' => 'mo_saml_vertical_tabs',
        );
        $form['miniorange_support']['markup_1'] = array(
            '#markup' => t('<h3>Support</h3><hr><p class="mo_saml_highlight_background_note">Need any help? We can help you with configuring miniOrange SAML SP module on your site. Just send us a query and we will get back to you soon.</p>'),
        );
        $form['miniorange_support']['mo_saml_support_email_address'] = array(
            '#type' => 'email',
            '#title' => t('Email <span style="color: red">*</span>'),
            '#default_value' => $email,
            '#attributes' => array('placeholder' => t('Enter your email'), 'style' => 'width:99%;margin-bottom:1%;'),
        );
        $form['miniorange_support']['mo_saml_support_phone_number'] = array(
            '#type' => 'textfield',
            '#title' => t('Phone'),
            '#default_value' => $phone,
            '#attributes' => array('placeholder' => t('Enter number with country code Eg. +00xxxxxxxxxx'), 'style' => 'width:99%;margin-bottom:1%;'),
        );
        $form['miniorange_support']['mo_saml_support_query'] = array(
            '#type' => 'textarea',
            '#title' => t('Query <span style="color: red">*</span>'),
            '#attributes' => array('placeholder' => t('Describe your query here!'), 'style' => 'width:99%'),
            '#suffix' => '<br>',
        );
        $form['miniorange_support']['actions']['submit'] = array(
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => t('Submit query'),
        );
        $form['miniorange_support']['markup_support_note'] = array(
            '#markup' => t('<div><br/><br/>If you want custom features in the module, just drop an email to <a href="mailto:info@xecurify.com">info@xecurify.com</a> or <a href="mailto:'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'">'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'</a></div><br><hr><br>'),
        );


        /**
         * Request demo form
         */
        $form['miniorange_demo_request'] = array (
            '#type' => 'details',
            '#title' => t('Request demo'),
            '#group' => 'mo_saml_vertical_tabs',
        );
        $form['miniorange_demo_request']['markup_demo_request'] = array(
            '#markup' => t('<h3>Request Demo of Paid features</h3><hr><p class="mo_saml_highlight_background_note">Want to know about how the licensed module works? Let us know and we will arrange a demo for you.</p>'),
        );
        $form['miniorange_demo_request']['mo_saml_demo_email_address'] = array(
            '#type' => 'email',
            '#title' => t('Email <span style="color: red">*</span>'),
            '#default_value' => $email,
            '#attributes' => array('placeholder' => t('Enter your email'), 'style' => 'width:99%;margin-bottom:1%;'),
        );
        $form['miniorange_demo_request']['mo_saml_demo_plan'] = array(
            '#type' => 'select',
            '#title' => t('Plan'),
            '#options' => array(
                'Drupal 8 SAML SP Standard' => t('Drupal 8 SAML SP Standard'),
                'Drupal 8 SAML SP Premium' => t('Drupal 8 SAML SP Premium'),
                'Drupal 8 SAML SP Enterprise' => t('Drupal 8 SAML SP Enterprise'),
                'Drupal 8 SAML SP + Website Security Premium' => t('Drupal 8 SAML SP + Website Security Premium'),
                'Not Sure' => t('Not Sure, Need help for selecting plan.'),
            ),
            '#attributes' => array('style' => 'width:99%;height:30px;margin-bottom:1%;'),
        );
        $form['miniorange_demo_request']['mo_saml_demo_description'] = array(
            '#type' => 'textarea',
            '#title' => t('Description <span style="color: red">*</span>'),
            '#attributes' => array('placeholder' => t('Describe your use case here!'), 'style' => 'width:99%;'),
            '#suffix' => '<br>',
        );
        $form['miniorange_demo_request']['actions']['submit'] = array(
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => t('Send Request'),
        );
        $form['miniorange_demo_request']['markup_demo_note'] = array(
            '#markup' => t('<div><br/><br/>If you are not sure with which plan you should go with, get in touch with us on <a href="mailto:'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'">'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'</a> and we will assist you with the suitable plan.</div><br><hr><br>'),
        );


        /**
         * New 2fa feature request form
         */
        $form['miniorange_feature_request'] = array (
            '#type' => 'details',
            '#title' => t('New Feature Request'),
            '#group' => 'mo_saml_vertical_tabs',
        );
        $form['miniorange_feature_request']['markup_1'] = array(
            '#markup' => t('<h3>New Feature Request</h3><hr><p class="mo_saml_highlight_background_note">Need new feature or any customization in the module? Just send us a requirement so we can help you.'),
        );
        $form['miniorange_feature_request']['mo_saml_feature_email_address'] = array(
            '#type' => 'email',
            '#title' => t('Email <span style="color: red">*</span>'),
            '#default_value' => $email,
            '#attributes' => array('placeholder' => t('Enter your email'), 'style' => 'width:99%;margin-bottom:1%;'),
        );
        $form['miniorange_feature_request']['mo_saml_feature_phone_number'] = array(
            '#type' => 'textfield',
            '#title' => t('Phone'),
            '#default_value' => $phone,
            '#attributes' => array('placeholder' => t('Enter number with country code Eg. +00xxxxxxxxxx'), 'style' => 'width:99%;margin-bottom:1%;'),
        );
        $form['miniorange_feature_request']['mo_saml_feature_query'] = array(
            '#type' => 'textarea',
            '#title' => t('Description <span style="color: red">*</span>'),
            '#attributes' => array('placeholder' => t('Describe your requirement here!'), 'style' => 'width:99%'),
            '#suffix' => '<br>',
        );
        $form['miniorange_feature_request']['actions']['submit'] = array(
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => t('Submit'),
        );
        $form['miniorange_feature_request']['markup_feature_request_note'] = array(
            '#markup' => t('<div><br/><br/>For any other queries, reach out to us using support form or just drop an email to <a href="mailto:info@xecurify.com">info@xecurify.com</a> or <a href="mailto:'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'">'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'</a></div><br><hr><br>'),
        );

        $form['miniorange_support_tab_end'] = array(
            '#markup' => t('</div>'),
        );

        Utilities::advertiseNetworkSecurity($form, $form_state);

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        $form_values = $form_state->getValues();
        //echo"<pre>";print_r($form_values);exit;
        if( $form_values['mo_saml_vertical_tabs__active_tab'] === 'edit-miniorange-support' ) {
            if( !\Drupal::service('email.validator')->isValid( $form_values['mo_saml_support_email_address'] ) ) {
                $form_state->setErrorByName('mo_saml_support_email_address', $this->t('The email address is not valid.'));
            }
            if( empty( $form_values['mo_saml_support_query'] ) ) {
                $form_state->setErrorByName('mo_saml_support_query', $this->t('The <b><u>Query</u></b> fields is mandatory'));
            }
        } elseif( $form_values['mo_saml_vertical_tabs__active_tab'] === 'edit-miniorange-demo-request' ) {
            if( !\Drupal::service('email.validator')->isValid( $form_values['mo_saml_demo_email_address'] ) ) {
                $form_state->setErrorByName('mo_saml_demo_email_address', $this->t('The email address is not valid.'));
            }
            if( empty( $form_values['mo_saml_demo_description'] ) ) {
                $form_state->setErrorByName('mo_saml_demo_description', $this->t('The <b><u>Description</u></b> fields is mandatory'));
            }
        } elseif( $form_values['mo_saml_vertical_tabs__active_tab'] === 'edit-miniorange-feature-request' ) {
            if( !\Drupal::service('email.validator')->isValid( $form_values['mo_saml_feature_email_address'] ) ) {
                $form_state->setErrorByName('mo_saml_feature_email_address', $this->t('The email address is not valid.'));
            }
            if( empty( $form_values['mo_saml_feature_query'] ) ) {
                $form_state->setErrorByName('mo_saml_feature_query', $this->t('The <b><u>Description</u></b> fields is mandatory'));
            }
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_values = $form_state->getValues();
        $email = ''; $phone = ''; $query = ''; $query_type = '';
        if( $form_values['mo_saml_vertical_tabs__active_tab'] === 'edit-miniorange-support' ) {
            $email = $form_values['mo_saml_support_email_address'];
            $phone = $form_values['mo_saml_support_phone_number'];
            $query = $form_values['mo_saml_support_query'];
            $query_type = 'Support';

        }elseif ( $form_values['mo_saml_vertical_tabs__active_tab'] === 'edit-miniorange-demo-request' ) {
            $email = $form_values['mo_saml_demo_email_address'];
            $phone = $form_values['mo_saml_demo_plan'];
            $query = $form_values['mo_saml_demo_description'];
            $query_type = 'Demo Request';

        }elseif ( $form_values['mo_saml_vertical_tabs__active_tab'] === 'edit-miniorange-feature-request' ) {
            $email = $form_values['mo_saml_feature_email_address'];
            $phone = $form_values['mo_saml_feature_phone_number'];
            $query = $form_values['mo_saml_feature_query'];
            $query_type = 'New Feature Request';
        }
        Utilities::send_support_query( $email, $phone, $query, $query_type );
    }
}