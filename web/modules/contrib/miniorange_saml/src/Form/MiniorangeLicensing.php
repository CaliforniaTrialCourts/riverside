<?php
/**
 * @file
 * Contains Licensing information for miniOrange SAML Login Module.
 */

/**
 * Showing Licensing form info.
 */
namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Form\FormBase;
use Drupal\miniorange_saml\Utilities;
use Drupal\Core\Form\FormStateInterface;
use Drupal\miniorange_saml\MiniorangeSAMLConstants;


class MiniorangeLicensing extends FormBase {

    public function getFormId() {
        return 'miniorange_saml_licensing';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {
         $form['miniorange_saml_licensing_tab'] = array(
            '#attached' => array(
                'library' => array(
                  'miniorange_saml/miniorange_saml.register',
                  'miniorange_saml/miniorange_saml.admin',
                )
            ),
        );

        $form['markup_1'] = array(
            '#markup' =>t('<div class="mo_saml_sp_table_layout_1"><div class="mo_saml_table_layout"><br><h2>Upgrade Plans</h2><hr><br>'),
        );

        $form['markup_free'] = array(
            '#markup' => t('<html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- Main Style -->
        </head>
        <body>
        <!-- Pricing Table Section -->
        <section id="pricing-table">
            <div class="container_1">
                <div class="row">
                    <div class="pricing">

                        <div class="pricing-table class_inline">
                            <div class="pricing-header">
                                <p class="pricing-title">Free</p>
                                <p class="pricing-rate"><sup>$</sup> 0<br><span class="miniorange_saml_one_time">[ One Time Payment ]</span></p>
                                <div class="filler-class"></div>
                                <p></p>
                                 <a class="mo_btn mo_btn-danger mo_btn-sm mo_btn_note">You are on this plan</a>
                            </div>
                            <div class="pricing-list">
                                <ul>
                                    <li>Unlimited Authentications via IdP</li>
                                    <li>Configure SP Using Metadata XML File</li>
                                    <li>Configure SP Using Metadata URL</li>
                                    <li>Basic Attribute Mapping</li>
                                    <li>Basic Role Mapping</li>
                                    <li>Step-By-Step Guide to Setup IdP</li>
                                    <li>Export Configuration</li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li>Support</li>
                                    <li>Basic Email support</li>
                                </ul>
                            </div>
                        </div>
                        <div class="pricing-table class_inline">
                            <div class="pricing-header">
                                <p class="pricing-title">Standard <br><span class="mo_description_for_plan">(Auto-Redirect to IdP)</span></p>
                                <p class="pricing-rate"><sup>$</sup> 249<br><span class="miniorange_saml_one_time">[ One Time Payment ]</span></p>
                                <div class="filler-class"></div>

                                 <p id="Standard_plan_name" hidden>\'' . self::miniorange_saml_standard_button(). '\'</p>
                                 <a  class="mo_btn btn-custom mo_btn-danger mo_btn-sm" id="StandBtn" style="display: block !important;">Upgrade Now</a>
                            </div>
                            <div class="pricing-list">
                                <ul>
                                    <li>All free version features</li>
                                    <li>+</li>
                                    <li>Options to select SAML Request Binding Type</li>
                                    <li>Sign SAML Request</li>
                                    <li>Import Configuration</li>
                                    <li>Protect your whole site</li>
                                    <li>Force authentication on each login attempt</li>
                                    <li>Default Redirect Url after Login</li>
                                    <li>Integrated Windows Authentication(With ADFS)*</li>
                                    <li></li>
                                    <li>Support</li>
                                    <li>Premium GoTo meeting support</li>
                                </ul>
                            </div>
                        </div>

                        <div class="pricing-table class_inline">
                            <div class="pricing-header">
                                <p class="pricing-title">Premium<br><span class="mo_description_for_plan">(Attribute & Role Management)</span></p>

                                <p class="pricing-rate"><sup>$</sup> 399<br><span class="miniorange_saml_one_time">[ One Time Payment ]</span></p>
                                <p id="premium_plan_name" hidden>\'' . self::miniorange_saml_premium_button(). '\'</p>
                                 <a class="mo_btn btn-custom mo_btn-danger mo_btn-sm" id="premium_btn"  style="display: block !important;">Upgrade Now</a>
                            </div>
                            <div class="pricing-list">
                                <ul>
                                    <li>All standard version features</li>
                                    <li>+</li>
                                    <li>SAML Single Logout</li>
                                    <li>Custom Attribute Mapping</li>
                                    <li>Custom Role Mapping</li>
                                    <li>End to End Identity Provider Configuration **</li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li>Support</li>
                                    <li>Premium GoTo meeting support</li>
                                </ul>
                            </div>
                        </div>

                        <div class="pricing-table class_inline">
                            <div class="pricing-header">
                                <p class="pricing-title">Enterprise <br><span class="mo_description_for_plan">(AUTO-SYNC IDP METADATA & MULTIPLE CERTIFICATE)</span></p>

                                <p class="pricing-rate"><sup>$</sup> 449<br><span class="miniorange_saml_one_time">[ One Time Payment ]</span></p>
                                <p id="enterprise_plan_name" hidden>\'' . self::miniorange_saml_enterprise_button(). '\'</p>
                                    <a id="enterprise_btn" class="mo_btn btn-custom mo_btn-danger mo_btn-sm" style="display: block !important;">Upgrade Now</a></div>
                            <div class="pricing-list">
                                <ul>
                                    <li>All premium version features</li>
                                    <li>+</li>
                                    <li>Domain Restriction</li>
                                    <li>Auto-sync IdP Configuration from metadata</li>
                                    <li>Generate Custom SP Certificate</li>
                                    <li>Signed requests using different algorithm</li>
                                    <li>Support multiple certificates of IDP</li>
                                    <li>Multiple IdP Support for Cloud Service Providers ***</li>
                                    <li></li>
                                    <li></li>
                                    <li>Support</li>
                                    <li>Premium GoTo meeting support</li>
                                </ul>
                            </div>
                        </div>

                        <div class="pricing-table class_inline">
                            <div class="pricing-header">
                                <p class="pricing-title">SAML SP Module<br><span class="miniorange_saml_one_time">[ Any version ]<br><br><span class="pricing-title"> +</span></p><br><br>
                                <p class="pricing-title">[50% off on website security]</p>
                                 <a class="mo_btn btn-custom mo_btn-danger mo_btn-sm" id="premium_btn"  style="display: block !important;" href="https://miniorange.com/contact" target="_blank">Contact us</a>
                            </div>
                            <div class="pricing-list">
                                <ul>
                                    <li>SAML SP Module</li>
                                    <li>+</li>
                                    <li>Website Security Premium Module</li>
                                    <li><a target="_blank" href="https://plugins.miniorange.com/drupal-web-security-pro"  >Features of Website Security</a></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li>Support</li>
                                    <li>Premium GoTo meeting support</li>
                                </ul>
                            </div>
                        </div>

                    </div>
            </div>
        </div>
    </section>
    <!-- Pricing Table Section End -->
    </body>
    </html>'),
        );

        global $base_url;
        $form['markup_5'] = array(
            '#markup' => t('<h3>Steps to Upgrade to Premium Module</h3>
                 <ol><li>You will be redirected to miniOrange Login Console. Enter your password with which you created an
                  account with us. After that you will be redirected to payment page.</li>
                 <li>Enter your card details and complete the payment. On successful payment completion, you will see the
                 link to download the premium module.</li>
                 Once you download the premium module, just unzip it and replace the folder with existing module.Clear Drupal Cache.</ol>')
        );



        $form['markup_7'] = array(
            '#markup' => t('<h3>* Integrated Windows Authentication</h3>'
                . 'With Integrated windows authentication, if the user comes to your Drupal Site from a domain joined machine'
                . ' then he will not even have to re-enter his credentials because <br>he already did that when he unlocked his computer.')
        );

        $form['markup_8'] = array(
            '#markup' => t('<h3>** End to End Identity Provider Integration (Additional charges may apply)</h3>'
                . 'We will setup a Conference Call / Gotomeeting and do end to end configuration for you for IDP '
                . 'as well as module. We provide services to do the configuration on your behalf.<br>
            If you have any doubts regarding the licensing plans, you can mail us at <a href="mailto:'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'"><i>'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'</i>
            </a> or submit a query using the <a href=" '. $base_url . '/admin/config/people/miniorange_saml/MiniorageSupport' .' ">support form</a>. <b></b><br>')
        );


        $form['markup_9'] = array(
            '#markup' => t('<h3>*** Multiple IDP Support (<em>Using miniOrange broker service</em>)</h3>'
                . 'If you want users from different Identity Providers to SSO into your site then you can configure the module with multiple IDPs.'
                . ' Additional charges will be applicable based on the number of Identity Providers you wish to configure. <br><br>
                <h3>Return Policy - </h3>
                  At miniOrange, we want to ensure you are 100% happy with your purchase. If the module you purchased is not working as advertised and you\'ve attempted to resolve any issues with our support team, which couldn\'t get resolved, we will refund the whole amount given that you have a raised a refund request within the first 10 days of the purchase. Please email us at <a href="mailto:'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'">'.MiniorangeSAMLConstants::SUPPORT_EMAIL.'</a> for any queries regarding the return policy.
                <br><br><br></div></div>')
        );


        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
    }
    function miniorange_saml_standard_button(){
        $admin_email = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');
        $admin_email = (isset($admin_email) && !empty($admin_email)) ? $admin_email : 'none';
        $URL_Redirect_std = 'https://login.xecurify.com/moas/login?username='.$admin_email.'&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_miniorange_saml_standard_plan';

        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('redirect_plan_after_registration_standard',$URL_Redirect_std)->save();

        return self::return_url($URL_Redirect_std, 'standard');
    }

    function miniorange_saml_premium_button(){
        $admin_email = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');
        $admin_email = (isset($admin_email) && !empty($admin_email)) ? $admin_email : 'none';
        $URL_Redirect_prem = 'https://login.xecurify.com/moas/login?username='.$admin_email.'&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_miniorange_saml_premium_plan';
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('redirect_plan_after_registration_premium',$URL_Redirect_prem)->save();
        return self::return_url($URL_Redirect_prem, 'premium');
    }
    function miniorange_saml_enterprise_button(){
        $admin_email = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_customer_admin_email');
        $admin_email = (isset($admin_email) && !empty($admin_email)) ? $admin_email : 'none';
       $URL_Redirect_enter = 'https://login.xecurify.com/moas/login?username='.$admin_email.'&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal8_miniorange_saml_enterprise_plan';
        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('redirect_plan_after_registration_enterprise',$URL_Redirect_enter)->save();
        return self::return_url($URL_Redirect_enter, 'enterprise');
    }

    function return_url($url, $payment_plan){
        if(Utilities::isCustomerRegistered()){
            return $url;
        }else{
            global $base_url;
            $SAMLrequestUrl = $base_url . '/register_user/?payment_plan=' . $payment_plan;
            return $SAMLrequestUrl;
        }
    }
}