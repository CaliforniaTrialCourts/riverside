<?php
/**
 * @file
 * Contains Attribute and Role Mapping for miniOrange SAML Login Module.
 */

 /**
 * Showing Settings form.
 */
namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Form\FormBase;
use Drupal\miniorange_saml\Utilities;
use Drupal\Core\Form\FormStateInterface;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;

class Mapping extends FormBase {

  public function getFormId() {
    return 'miniorange_saml_mapping';
  }

 public function buildForm(array $form, FormStateInterface $form_state) {

  global $base_url;

     Utilities::visual_tour_start($form, $form_state);
     $form['miniorange_saml_markup_library'] = array(
       '#attached' => array(
         'library' => array(
           'miniorange_saml/miniorange_saml.admin',
         )
       ),
     );

     $form['markup_top'] = array(
         '#markup' => t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout mo_saml_sp_container" >
                       <div class="mo_saml_font_for_heading" >Attribute/Role Mapping</div>
                       <a id="Restart_moTour" class="mo_btn mo_btn-primary mo_btn-sm mo_tour_button_float" onclick="Restart_moTour()">Take a Tour</a><p style="clear: both"></p><hr>')
     );

     /**
      * Create container to hold @RoleMapping form elements.
      */
     $form['mo_saml_role_mapping'] = array(
         '#type' => 'fieldset',
         '#title' => t('Role Mapping'),
         '#attributes' => array( 'style' => 'padding:2% 2% 4%; margin-bottom:2%' ),
     );

     $form['mo_saml_role_mapping']['miniorange_saml_enable_rolemapping'] = array(
         '#type' => 'checkbox',
         '#title' => t('Check this option if you want to <b>enable Role Mapping</b>'),
         '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_enable_rolemapping'),
         '#attributes' => array('class="mo_saml_checkbox"'),
         '#description' => t('<b style="color: red">Note:</b> Enable this checkbox first before using any of the feature below.'),
         '#prefix' => t('<br><hr><br><div id="mo_saml_id_role_mapping_v_tour">'),
         '#suffix' => t('</div>')
     );

     $mrole = user_role_names(TRUE);
     $default_role_index = \Drupal::configFactory()->getEditable('miniorange_saml.settings')->get('miniorange_saml_default_role_index');

     $form['mo_saml_role_mapping']['miniorange_saml_default_mapping'] = array(
         '#type' => 'select',
         '#title' => t('Select default group for the new users'),
         '#options' => array_values($mrole),
         '#description'=>t('<strong>Note: </strong>This role will be assigned to user when user gets created in Drupal after SSO for the first time.'),
         '#default_value' => $default_role_index,
         '#attributes' => array('style' => 'width:70%;background-image: inherit;background-color: white;-webkit-appearance: menulist;'),
         '#prefix' => '<br><div id="Default_Mapping">',
         '#suffix' => '</div><br>',
     );

     $form['mo_saml_role_mapping']['miniorange_saml_disable_role_update'] = array(
         '#type' => 'checkbox',
         '#title' => t('Check this option if you do not want to update user role if roles not mapped '),
         '#disabled' => TRUE,
         //'#attributes' => array('background-color: hsla(0,0%,0%,0.08) !important;'),
         '#description'=>t('<b>Note:</b> This feature is availabe in <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Premium and Enterprise</a> versions of the module.'),
         '#prefix' => '<div class="mo_saml_highlight_background_note_1">',
     );

     $form['mo_saml_role_mapping']['miniorange_saml_gateway_config2_submit'] = array(
         '#type' => 'submit',
         '#button_type' => 'primary',
         '#value' => t('Save Configuration'),
         '#prefix' => '</div><br><br>',
         '#suffix' => '<br><br>',
     );


     /**
      * Create container to hold @moCustomRoleMapping form elements.
      */
     $form['mo_saml_custom_role_mapping'] = array(
         '#type' => 'fieldset',
         '#title' => t('Custom Role Mapping'),
         '#attributes' => array( 'style' => 'padding:2% 2% 4%; margin-bottom:2%' ),
     );

     $form['mo_saml_custom_role_mapping']['markup_custom_role_mapping'] = array(
         '#markup' => t('<br><hr><br><div class="mo_saml_highlight_background_note_1">
                            <b>Note: </b>Custom Role Mapping is configurable in <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Premium and Enterprise</a> versions of the module.</div>'),
     );

     $form['mo_saml_custom_role_mapping']['miniorange_saml_idp_attr1_name'] = array(
         '#type' => 'textfield',
         '#title' => t('Role Key'),
         '#attributes' => array('style' => 'width:70%; background-color: hsla(0,0%,0%,0.08) !important;','placeholder' => 'Enter Role Attribute'),
         '#description' => t('<b>Note:</b> You will find role key value in the test configuration window.<br><br><br>'),
         '#disabled' => TRUE,
     );

     $form['mo_saml_custom_role_mapping']['miniorange_saml_custom_role_buttons']=array(
         '#markup'=> t('<h5 >Add Custom Role Mapping <a class="mo_btn mo_btn-primary" style="padding:0.4% 0.8% 0.4% 0.8% !important;">+</a><h3><table class="miniorange_saml_sp_attr_table"><tr><td>Drupal Role</td><td>Idp Role</td><td></td></tr>'),
     );

     $form['mo_saml_custom_role_mapping']['user_sp_role_name'] = array(
         '#prefix'=>'<tr><td>',
         '#type' => 'select',
         '#options'=>$mrole,
         '#attributes'=>array('style'=>'width:100%;'),
         '#suffix'=>'</td>',
     );
     $form['mo_saml_custom_role_mapping']['user_idp_role_name'] = array(
         '#prefix'=>'<td>',
         '#type' => 'textfield',
         '#suffix'=>'</td>',
         '#attributes' => array('style' => 'background-color: hsla(0,0%,0%,0.08) !important;'),
         '#disabled'=>TRUE,
     );
     $form['mo_saml_custom_role_mapping']['user_role_delete'] = array(
         '#prefix'=>'<td><a class="mo_btn mo_btn-primary mo_role_attr_delete" >-</a>',
         '#suffix'=>'</td></tr></table>',
     );

     $form['mo_saml_custom_role_mapping']['miniorange_saml_gateway_config4_submit'] = array(
         '#type' => 'submit',
         '#value' => t('Save Configuration'),
         '#disabled' => TRUE,
         '#prefix' => '<br>',
         '#suffix' => '<br><br>',
     );


     /**
      * Create container to hold @AttributeMapping form elements.
      */
     $form['mo_saml_attribute_mapping'] = array(
         '#type' => 'fieldset',
         '#title' => t('Attribute Mapping'),
         '#attributes' => array( 'style' => 'padding:2% 2% 5%; margin-bottom:2%' ),
     );

     $form['mo_saml_attribute_mapping']['Configure_Attribute_Mapping_End'] = array(
         '#markup' => t('<br><hr><br><div id="configure_attribute_mapping_start"></div><div class="mo_saml_highlight_background_note_1" ><strong>Note: <code>Username Attribute</code> </strong> and <strong><code>Email Attribute</code></strong> are configurable in
                 <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Standard, Premium and Enterprise</a> versions of the module.</div>'),
     );

      $form['mo_saml_attribute_mapping']['miniorange_saml_username_attribute'] = array(
        '#type' => 'textfield',
        '#title' => t('Username Attribute'),
        '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_username_attribute'),
        '#attributes' => array('style' => 'width:70%; background-color: hsla(0,0%,0%,0.08) !important;'),
        '#disabled' => TRUE,
      );

      $form['mo_saml_attribute_mapping']['miniorange_saml_email_attribute'] = array(
          '#type' => 'textfield',
          '#title' => t('Email Attribute'),
          '#default_value' => \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_email_attribute'),
          '#attributes' => array('style' => 'width:70%;; background-color: hsla(0,0%,0%,0.08) !important;'),
          '#disabled' => TRUE,
      );

     $form['mo_saml_attribute_mapping']['miniorange_saml_gateway_config1_submit'] = array(
         '#type' => 'submit',
         '#value' => t('Save Configuration'),
         '#prefix' => '<br>',
         '#suffix' => '<br><br>',
         '#disabled'=>TRUE,
     );


     /**
      * Create container to hold @moCustomAttributeMapping form elements.
      */
     $form['mo_saml_custom_attribute_mapping'] = array(
         '#type' => 'fieldset',
         '#title' => t('Custom Attribute Mapping'),
         '#attributes' => array( 'style' => 'padding:2% 2% 4%; margin-bottom:2%' ),
     );

     $form['mo_saml_custom_attribute_mapping']['markup_cam_attr'] = array(
        '#markup' => t('<br><hr><br><div class="mo_saml_highlight_background_note_1"><b>Note: </b>Custom Attribute Mapping is configurable in
                 <a href="' . $base_url . MiniorangeSAMLConstants::LICENSING_TAB_URL .'">Premium and Enterprise</a> versions of the module.</div>'),
     );

      $form['mo_saml_custom_attribute_mapping']['markup_cam'] = array(
         '#markup' => t('<br><div class="mo_saml_highlight_background_note_1"><b>Note: </b> Add the Drupal field attributes in the Attribute Name textfield and add the IdP attibutes that you need to map with the drupal attributes in the IdP Attribute Name textfield.
                                   <ul><li><b>SP Attribute Name:</b> It is the user attribute (machine name) whose value you want to set in site.</li>
                                   <li><b>IdP Attribute Name:</b> It is the name which you want to get from your IDP. It should be unique.</li></ul>
                                   <p><b>eg: If the attribute name in the drupal is phone then its machine name will be field_phone. Click <a target="_blank" href="' . $base_url . MiniorangeSAMLConstants::USER_ATTRIBUTE .'">here</a> to check available fields.</b></p>
                                   </div><br>'
                       ),
      );

     $form['mo_saml_custom_attribute_mapping']['miniorange_saml_custom_attr_buttons']=array(
          '#markup'=>t('<h3 >Add Custom Atrribute <a class="mo_btn btn-large mo_btn-primary" style="padding:0.4% 0.8% 0.4% 0.8% !important;">+</a><h3><table class="miniorange_saml_sp_attr_table"><tr><td>Drupal Attribute Machine Name</td><td>IdP Attribute Name</td><td></td></tr>'),

     );

     $form['mo_saml_custom_attribute_mapping']['user_sp_attr_name'] = array(
          '#prefix'=>'<tr><td>',
          '#type' => 'textfield',
          '#disabled'=>TRUE,
          '#attributes' => array('style' => 'background-color: hsla(0,0%,0%,0.08) !important;'),
          '#suffix'=>'</td>',
      );
     $form['mo_saml_custom_attribute_mapping']['user_idp_attr_name'] = array(
          '#prefix'=>'<td>',
          '#type' => 'textfield',
          '#disabled'=>TRUE,
          '#suffix'=>'</td>',
          '#attributes' => array('style' => 'background-color: hsla(0,0%,0%,0.08) !important;'),

     );
     $form['mo_saml_custom_attribute_mapping']['user_delete'] = array(
          '#prefix'=>'<td><a class="mo_btn mo_btn-danger mo_btn-sm mo_role_attr_delete">-</a>',
          '#suffix'=>'</td></tr></table>',
     );
     $form['mo_saml_custom_attribute_mapping']['miniorange_saml_gateway_config3_submit'] = array(
         '#type' => 'submit',
         '#value' => t('Save Configuration'),
         '#disabled' => TRUE,
         '#prefix' => '<br>',
         '#suffix' => '<br><br></div>',
     );

     Utilities::advertiseNetworkSecurity($form, $form_state, 'SCIM');

     return $form;
 }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        $form_values = $form_state->getValues();
        $username_attribute = $form_values['miniorange_saml_username_attribute'];
        $email_attribute    = $form_values['miniorange_saml_email_attribute'];
        $enable_rolemapping = $form_values['miniorange_saml_enable_rolemapping'];
        $default_mapping    = $form_values['miniorange_saml_default_mapping'];

        $i = 0;
        $mrole = user_role_names(TRUE );
        foreach( $mrole as $key => $value ) {
            $def_role[$i] = $value;
            $i++;
        }

        $enable_rolemapping_value = $enable_rolemapping == 1 ? TRUE : FALSE;

        if( $enable_rolemapping_value ) {
            \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_default_role', $def_role[$default_mapping]);
            \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_default_role_index', $default_mapping);
        }else {
            \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_default_role', $mrole['authenticated'])->save();
        }

        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_username_attribute', $username_attribute)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_email_attribute', $email_attribute)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('miniorange_saml_enable_rolemapping', $enable_rolemapping_value)->save();
        \Drupal::messenger()->addMessage(t('Mapping Settings successfully saved'), 'status');
    }
  }