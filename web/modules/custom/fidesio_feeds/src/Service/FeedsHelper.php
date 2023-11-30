<?php

namespace Drupal\fidesio_feeds\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;

/**
 * Feeds helper.
 */
class FeedsHelper {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a FeedsHelper object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * @param int $pager
   * @param boolean $load_entity
   * @return array|\Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|Node[]|int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getArticlesByUpdateDate(int $pager = 1, bool $load_entity = TRUE): array {
    $query = $this->entityTypeManager->getStorage('node')->getQuery();
    $nids = $query->condition('status', 1)
      ->condition('type', 'article')
      ->pager($pager)->accessCheck(TRUE)
      ->sort('created', 'DESC')
      ->sort('changed', 'DESC')
      ->execute();

    if ($pager) {
      if (!empty($nids)) {
        return Node::loadMultiple($nids);
      }
    }

    return $nids;
  }

}
