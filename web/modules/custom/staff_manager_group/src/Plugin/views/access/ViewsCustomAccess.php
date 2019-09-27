<?php

namespace Drupal\staff_manager_group\Plugin\views\access;

use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\group\Entity\Group;
use Drupal\group\GroupMembership;
use Drupal\views\Plugin\views\access\AccessPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\component\Routing\Route;

/**
 * Class ViewsCustomAccess
 *
 * @ingroup views_access_plugins
 *
 * @ViewsAccess(
 *     id = "WorkstationManager",
 *     title = @Translation("Workstation Manager"),
 *     help = @Translation("Add access for workstation manager to some views"),
 * )
 */
class ViewsCustomAccess extends AccessPluginBase
{
    /**
     * @var CurrentPathStack $path
     */
    protected $path;

    public function __construct (array $configuration, $plugin_id, $plugin_definition, CurrentPathStack $path) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('path.current')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function summaryTitle()
    {
        return $this->t('Customized settings');
    }

    /**
     * {@inheritDoc}
     */
    public function access(AccountInterface $account)
    {
        $gID = explode("/",$this->path->getPath())[2];
        $group = Group::load($gID);
        $member = $group->getMember($account);
        $user_roles = $account->getRoles();

        if(in_array('organizer',$user_roles))
            return true;
        
        if(is_object($member))
        {
            $member_group_roles = $member->getRoles();
            if(isset($member_group_roles['workstation-manager']))
            return true;
        }
        else
        {
            return false;
        }       
    }

    /**
     * {@inheritDoc}
     */
    public function alterRouteDefinition(Route $route)
    {
        // TODO: Implement alterRouteDefinition() method.
        $route->setRequirement('_access', 'TRUE');
    }
}