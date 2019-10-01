<?php

namespace Drupal\staff_manager_group\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\group\Entity\GroupInterface;
use Drupal\node\NodeInterface;
use Symfony\component\HttpFoundation\Request;

class AssignShifts extends ControllerBase
{
    public function assign(GroupInterface $group, NodeInterface $node, AccountInterface $user, Request $request=null)        
    {
        $node->set('field_shiftplan_assignee',$user->id());
        $node->save();

        $response = new AjaxResponse();
        $response->addCommand(new ReplaceCommand("#node-".$node->id(),"<p id='node-".$node->id()."'>".$user->getUsername()."</p>"));
        $response->addCommand(new CloseModalDialogCommand());

        return $response;
    }
}
