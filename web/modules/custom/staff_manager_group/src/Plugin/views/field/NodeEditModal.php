<?php
/**
 * @file
 * Definition of Drupal\staff_manager_group\Plugin\views\field\NodeEditModal
 */

 namespace Drupal\staff_manager_group\Plugin\views\field;

 use Drupal\Core\Form\FormStateInterface;
 use Drupal\views\Plugin\views\field\FieldPluginBase;
 use Drupal\views\ResultRow;

 /**
  * Field handler to open up the modal for editing
  * @ingroup views_field_handlers
  * @ViewsField("node_edit_modal")
  */
 class NodeEditModal extends FieldPluginBase
 {
    /**
     * @{inheritDoc}
     */
    public function query() {}
    
    
    /**
     * Define available options
     *
     * @return array
     */
    protected function defineOptions()
    {   
        $options = parent::defineOptions();
        $options['node_type'] = array('default' => 'shiftplan');

        return $options;
    }

    /**
     * @{inheritDoc}
     */
    public function buildOptionsForm(&$form, FormStateInterface $form_state) {}

    /**
     * @{inheritDoc}
     */
    public function render(ResultRow $values)
    {
        $node = $values->_entity;
        if ($node->bundle() == $this->options['node_type'])
        {
            return array('#markup'=>'<a href="/group/3/node/'.$node->id().'/assign-shift"
                        class="use-ajax"
                        data-dialog-type="modal">
                        Edit Node
                        </a>',
                    '#attached'=>array('library'=>
                        array('staff_manager_group/modal_library')
                        )
                    );
        }
    }
}
 