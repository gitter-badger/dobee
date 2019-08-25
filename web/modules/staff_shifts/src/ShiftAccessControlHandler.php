<?php

namespace Drupal\staff_shifts;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the shift entity type.
 */
class ShiftAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view shift');

      case 'update':
        return AccessResult::allowedIfHasPermissions($account, ['edit shift', 'administer shift'], 'OR');

      case 'delete':
        return AccessResult::allowedIfHasPermissions($account, ['delete shift', 'administer shift'], 'OR');

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create shift', 'administer shift'], 'OR');
  }

}
