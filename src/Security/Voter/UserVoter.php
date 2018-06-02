<?php declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\App;
use App\Entity\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
  protected function supports($attribute, $subject): bool
  {
    if( in_array($attribute, [Permission::POST_USERS, Permission::LIST_USERS]) ) {
      return true;
    }
    
    return false;
  }
  
  protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
  {
    switch ($attribute) {
      case Permission::POST_USERS:
        return $this->canPost($token->getUser());
        break;
        
      case Permission::LIST_USERS:
        return $this->canList($token->getUser());
        break;
    }
    
    return false;
  }
  
  private function canPost(App $app): bool
  {
    return $app->hasPermission(Permission::POST_USERS);
  }
  
  private function canList(App $app): bool
  {
    return $app->hasPermission(Permission::LIST_USERS);
  }
}