<?php

namespace Drupal\modify_site_information\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Returns site api key field for site information config form.
 */
class ModifiedSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('system.site');
    $form = parent::buildForm($form, $form_state);
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => 'Site API Key',
      '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
      '#description' => "Custom field to set the API Key",
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('system.site')
      ->set('siteapikey', $form_state->getValue('siteapikey'))
      ->save();
    parent::submitForm($form, $form_state);
    \Drupal::messenger()->addStatus('The Site API Key has been saved with value - ' . $form_state->getValue('siteapikey'), 'status', TRUE);
  }

}
