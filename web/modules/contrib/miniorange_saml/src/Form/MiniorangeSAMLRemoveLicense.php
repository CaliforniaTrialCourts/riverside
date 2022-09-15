<?php

namespace Drupal\miniorange_saml\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\miniorange_saml\MiniorangeSAMLCustomer;

class MiniorangeSAMLRemoveLicense extends FormBase
{
    public function getFormId()
    {
        return 'miniorange_saml_remove_license';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $options = NULL)
    {
        $form['miniorange_saml_markup_library'] = array(
          '#attached' => array(
            'library' => array(
              'miniorange_saml/miniorange_saml.admin',
            )
          ),
        );
        $form['#prefix'] = '<div id="modal_example_form">';
        $form['#suffix'] = '</div>';
        $form['status_messages'] = [
            '#type' => 'status_messages',
            '#weight' => -10,
        ];


        $form['miniorange_saml_content'] = array(
            '#markup' => t('Are you sure you want to remove account? The configurations saved will not be lost.')
        );

        $form['actions'] = array('#type' => 'actions');
        $form['actions']['send'] = [
            '#type' => 'submit',
            '#value' => $this->t('Confirm'),
            '#attributes' => [
                'class' => [
                    'use-ajax',
                ],
            ],
            '#ajax' => [
                'callback' => [$this, 'submitModalFormAjax'],
                'event' => 'click',
            ],
        ];

        $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
        return $form;
    }

    public function submitModalFormAjax(array $form, FormStateInterface $form_state)
    {
        $editConfig = \Drupal::configFactory()->getEditable('miniorange_saml.settings');
        $response   = new AjaxResponse();
        // If there are any form errors, AJAX replace the form.
        if ($form_state->hasAnyErrors()) {
            $response->addCommand(new ReplaceCommand('#modal_example_form', $form));
        } else {
            $editConfig->clear('miniorange_saml_license_key')
                       ->clear('miniorange_saml_customer_admin_email')
                       ->clear('miniorange_saml_customer_admin_phone')
                       ->clear('miniorange_saml_customer_id')
                       ->clear('miniorange_saml_customer_api_key')
                       ->clear('miniorange_saml_customer_admin_token')
                       ->set('miniorange_saml_status', 'CUSTOMER_SETUP')
                       ->save();

            \Drupal::messenger()->addMessage(t('Your Account Has Been Removed Successfully!'), 'status');


            $response->addCommand(new RedirectCommand(Url::fromRoute('miniorange_saml.customer_setup')->toString()));
        }
        return $response;
    }


    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
    }

    protected function getEditableConfigNames()
    {
        return ['config.miniorange_saml_remove_license'];
    }
}