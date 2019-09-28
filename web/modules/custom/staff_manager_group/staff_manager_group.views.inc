<?php
/**
 * Implements hook_views_data_alter().
 */
function staff_manager_group_views_data_alter(array &$data) {
  $data['node']['modal_edit'] = array(
    'title' => t('Edit link - modal'),
    'field' => array(
      'title' => t('Edit link - modal'),
      'help' => t('Allows editing a specific node using a modal window'),
      'id' => 'node_edit_modal',
    ),
  );
}