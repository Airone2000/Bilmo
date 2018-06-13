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