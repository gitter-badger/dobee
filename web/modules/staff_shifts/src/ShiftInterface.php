<?php

namespace Drupal\staff_shifts;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a shift entity type.
 */
interface ShiftInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the shift title.
   *
   * @return string
   *   Title of the shift.
   */
  public function getTitle();

  /**
   * Sets the shift title.
   *
   * @param string $title
   *   The shift title.
   *
   * @return \Drupal\staff_shifts\ShiftInterface
   *   The called shift entity.
   */
  public function setTitle($title);

  /**
   * Gets the shift creation timestamp.
   *
   * @return int
   *   Creation timestamp of the shift.
   */
  public function getCreatedTime();

  /**
   * Sets the shift creation timestamp.
   *
   * @param int $timestamp
   *   The shift creation timestamp.
   *
   * @return \Drupal\staff_shifts\ShiftInterface
   *   The called shift entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the shift status.
   *
   * @return bool
   *   TRUE if the shift is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the shift status.
   *
   * @param bool $status
   *   TRUE to enable this shift, FALSE to disable.
   *
   * @return \Drupal\staff_shifts\ShiftInterface
   *   The called shift entity.
   */
  public function setStatus($status);

}
