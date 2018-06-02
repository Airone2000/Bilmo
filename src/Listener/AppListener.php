<?php declare(strict_types=1);

namespace App\Listener;

use App\Entity\App;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
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
  
  public function prePersist(App $app)
  {
    $encodedPassword = $this->passwordEncoder->encodePassword($app, $app->getPassword());
    $app->setSecret($encodedPassword);
  }
}