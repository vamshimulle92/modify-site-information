<?php

namespace Drupal\modify_site_information\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of GetNodeWithSiteApiKey.
 */
class GetNodeWithSiteApiKey extends ControllerBase {

  /**
   * Returns the data for json Node by verifying the site api key.
   */
  public function checkaccess($siteapi, $nid) {
    $config = \Drupal::service('config.factory')->getEditable('system.site');
    $configured_siteapi = $config->get('siteapikey');
    $node = '';
    $data = [];
    if (isset($nid) && is_numeric($nid)) {
      $node = Node::load($nid);
    }
    if (($configured_siteapi === $siteapi) && ($node !== NULL)) {
      if ($node->bundle() == 'page') {
        $data[] = [
          "id" => $node->id(),
          "title" => $node->getTitle(),
          "body" => $node->body->getString(),
        ];
        return new JsonResponse([
          'data' => $data,
          'method' => 'GET',
          'status' => 200,
        ]);
      }
      else {
        throw new AccessDeniedHttpException();
      }
    }
    else {
      throw new AccessDeniedHttpException();
    }
  }

}
