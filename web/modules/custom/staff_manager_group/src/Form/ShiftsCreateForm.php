<?php

namespace Drupal\staff_manager_group\Form;

use DateTimeZone;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\TypedData\Type\DateTimeInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

class ShiftsCreateForm extends FormBase
{
    /**
     * Current path service
     *
     * @var CurrentPathStack $path
     */
    protected $path;

    /**
     * Entity Type Manager Service
     *
     * @var EntityTypeManager $entityTypeManager
     */
    protected $entityTypeManager;

    /**
     * @var Messenger $messenger
     */
    protected $messenger;

    public function  __construct(CurrentPathStack $path, EntityTypeManager $entityTypeManager, Messenger $messenger)
    {
        //parent::__construct($config_factory);
        $this->path = $path;
        $this->entityTypeManager = $entityTypeManager;
        $this->messenger = $messenger;
    }

    public static function create (ContainerInterface $container)
    {
        return new static (
            $container->get('path.current'),
            $container->get('entity_type.manager'),
            $container->get('messenger')
        );
    }

    /**
     * @inheritDoc
     */
    public function getFormId()
    {
        return 'dobee_shifts_create_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['#tree'] = TRUE;
        $num_shifts = $form_state->get('num_shifts');
        //initial number
        if($num_shifts==null)
        {
            $form_state->set('num_shifts',1);
            $num_shifts = 1;
        }
        $form['container'] = array(
            '#type'=>'container',
            '#prefix' => '<div id="shifts-fieldset-wrapper">',
            '#suffix' => '</div>',
        );

            $form['shift_fieldset']['title'] = array(
                '#type'=>'textfield',
                '#title'=>'Shift name',
            );
    
            $form['shift_fieldset']['shift_time_start'] = array(
                '#type'=>'datetime',
                '#title'=>'Shift time - start',    
            );
            $form['shift_fieldset']['shift_time_end'] = array(
                '#type'=>'datetime',
                '#title'=>'Shift time - end',
            );
    
            $form['shift_fieldset']['shift_quantity'] = array(
                '#type'=>'number',
                '#title'=>'How many?',
            );

        $form['shift_fieldset']['actions'] = array(
            '#type'=>'actions'
        );

        $form['actions']['submit_button'] = array(
            '#type' => 'submit',
            '#value' => 'Generate'
        );

        return $form;
    }

    /**
    * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $data = $form_state->getValues();
        $time_start = $form_state->getValue(['shift_fieldset','shift_time_start'])->setTimezone(new DateTimeZone(DateTimeItemInterface::STORAGE_TIMEZONE));
        $time_end = $form_state->getValue(['shift_fieldset','shift_time_end'])->setTimezone(new DateTimeZone(DateTimeItemInterface::STORAGE_TIMEZONE));
        $current_path = explode("/",$this->path->getPath());
        $group = $this->entityTypeManager->getStorage('group')->load($current_path[2]);

        if(!empty($group))
        {
            for($i = 0; $i < $data['shift_fieldset']['shift_quantity'];$i++)
            {
                $node = Node::create([
                    'type'  => 'shiftplan',
                    'title' => $data['shift_fieldset']['title'],
                    'field_shiftplan_time' => [
                        'value' => $time_start->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT),
                        'end_value' => $time_end->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT)
                    ]
                ]);
                $node->save();
                $group->addContent($node,'group_node:shiftplan');
            }
            $this->messenger->addMessage("Shifts created");
        }
        else
        {
            $this->messenger->addError("Not a valid workstation");
        }
    }

    private function pathParse(string $path)
    {
        $return = array();

        //remove first slash
        $path = ltrim($path,"/");
    
        //create array
        $path_exploded = explode('/',$path);
        for($i = 0; $i < count($path_exploded); $i+2)
        {
            $return[$path_exploded[$i]] = $path_exploded[$i+1];
        }
        return $return;
    }
}