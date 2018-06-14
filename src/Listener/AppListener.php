<?php declare(strict_types=1);

namespace App\Listener;

use App\Entity\App;
use App\Entity\Permission;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppListener
{
  /**
   * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
   */
  private $passwordEncoder;
  
  function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
    $this->passwordEncoder = $passwordEncoder;
  }
    
    /**
     * @param \App\Entity\App $app
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     *
     * This method is called as from the ->persist() is called on the manager for
     * the App entity.
     *
     * Due to the ApiPlatform normal use case, things must be proceed in event.
     *
     * There we encode the password (as the App has just been registered).
     *
     * Then we grant it with basic permissions. Note that we retrieve them with the same EntityManager which is
     * in charge of persisting the App. This way, the EM knows the Permissions and can build the relation.
     */
  public function prePersist(App $app, LifecycleEventArgs $args)
  {
    # Encode its secret
    $encodedPassword = $this->passwordEncoder->encodePassword($app, $app->getPassword());
    $app->setSecret($encodedPassword);
    
    # Give it basics permissions
    $permissions = $args->getEntityManager()->getRepository(Permission::class)->findBy(['id' => [
      Permission::POST_USERS, Permission::LIST_USERS, Permission::GET_USERS, Permission::DELETE_USERS, Permission::PUT_USERS,
      Permission::GET_MANUFACTURERS, Permission::LIST_MANUFACTURERS,
      Permission::GET_CATEGORIES,
      Permission::LIST_PRODUCTS, Permission::GET_PRODUCTS
    ]]);
    
    foreach ($permissions as $permission) {
      $app->addPermission($permission);
    }
    
  }
}