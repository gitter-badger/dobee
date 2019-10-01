<?php
namespace Drupal\staff_manager_group\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\group\Entity\Group;

class AccessController extends ControllerBase
{
    /**
     * @var CurrentPathStack $path
     */
    protected $path;

    /**
     * @inheritDoc
     */
    public function __construct(CurrentPathStack $path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     *
     * @param ContainerInterface $container
     * @return void
     */
    public static function create(ContainerInterface $container)
    {
        $path = $container->get('path.current');
        return new static($path);
    }

    /**
     * Check access
     *
     * @param AccountInterface $account
     * @return void
     */
    public function access(AccountInterface $account)
    {
        $gID = explode("/",$this->path->getPath())[2];
        $group = Group::load($gID);
        $member = $group->getMember($account);
        $user_roles = $account->getRoles();

        if(in_array('organizer',$user_roles))
            return AccessResult::allowed();
        
        if(is_object($member))
        {
            $member_group_roles = $member->getRoles();
            if(isset($member_group_roles['workstation-manager']))
            return AccessResult::allowed();
        }
        
        return AccessResult::forbidden();
    }
}
