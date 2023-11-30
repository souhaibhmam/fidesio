<?php

namespace Drupal\fidesio_feeds\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\fidesio_feeds\Service\FeedsHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Last articles' Block.
 *
 * @Block(
 *   id = "last_articles_block",
 *   admin_label = @Translation("Last articles"),
 *   category = @Translation("Custom"),
 * )
 */
class LastArticlesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The feeds helper.
   *
   * @var \Drupal\fidesio_feeds\Service\FeedsHelper;
   */
  protected FeedsHelper $feedsHelper;

  /**
   * Constructs a LastArticlesBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The feeds_helper.
   */
  public function __construct(
    array       $configuration,
                $plugin_id,
                $plugin_definition,
    FeedsHelper $feeds_helper
  ) {

    $this->feedsHelper = $feeds_helper;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('fidesio_feeds.feeds_helper')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'last_articles_block_limit' => 1,
      'last_articles_block_cache_age' => 12,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['last_articles_block_limit'] = [
      '#type' => 'number',
      '#min' => 1,
      '#title' => $this->t('Number of articles:'),
      '#description' => $this->t('Number of articles to list in the block'),
      '#default_value' => $this->configuration['last_articles_block_limit'],
    ];

    $form['last_articles_block_cache_age'] = [
      '#type' => 'number',
      '#min' => 0,
      '#title' => $this->t('Cache life time:'),
      '#description' => $this->t('Number of hours before cache get invalidated.'),
      '#default_value' => $this->configuration['last_articles_block_cache_age'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->configuration['last_articles_block_limit'] = $values['last_articles_block_limit'];
    $this->configuration['last_articles_block_cache_age'] = $values['last_articles_block_cache_age'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $articles = $this->feedsHelper->getArticlesByUpdateDate($this->configuration['last_articles_block_limit']);
    return [
      '#theme' => 'last_articles',
      '#articles' => $articles,
      '#cache' => [
        'max-age' => $this->configuration['last_articles_block_cache_age'] * 3600,
        'tags'=> ['node_list:article']
      ],
    ];
  }

}
