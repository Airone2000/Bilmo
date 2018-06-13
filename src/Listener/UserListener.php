<?php declare(strict_types=1);

namespace App\Listener;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class UserListener
{
  /**
   * @var BCryptPasswordEncoder
   */
  private $passwordEncoder;
  
  /**
   * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
   */
  private $tokenStorage;
  
  /**
   * @var \ApiPlatform\Core\Validator\ValidatorInterface
   */
  private $validator;
  
  function __construct(BCryptPasswordEncoder $passwordEncoder, TokenStorageInterface $tokenStorage, ValidatorInterface $validator)
  {
    $this->passwordEncoder = $passwordEncoder;
    $this->tokenStorage = $tokenStorage;
    $this->validator = $validator;
  }
  
  private function encodePassword(User $user): void
  {
    if(is_null($user->getPlainPassword())) {
      return;
    }
    
    # Encode the password (bcrypt)
    # Salt is left null (not used)
    # Validator make sure password and confirmation are the same
    $encodedPassword = $this->passwordEncoder->encodePassword($user->getPlainPassword(), '');
    $user->setPassword($encodedPassword);
    
  }
  
  public function preUpdate(User $user): void
  {
    $this->encodePassword($user);
  }
  
  public function prePersist(User $user): void
  {
    
    $this->encodePassword($user);
    
    # Setting the current APP as App owner
    $user->setApp( $this->tokenStorage->getToken()->getUser() );
    
    # Revalidate user (check UniqueEntity which is based on App)
    $this->validator->validate($user, ['groups' => ['post_users']]);
    
  }
}