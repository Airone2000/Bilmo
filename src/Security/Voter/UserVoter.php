<?php declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\App;
use App\Entity\Permission;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
  protected function supports($attribute, $subject): bool
  {
    if( in_array($attribute, [Permission::POST_USERS, Permission::LIST_USERS]) ) {
      return true;
    }
    
    if( in_array($attribute, [Permission::GET_USERS, Permission::DELETE_USERS, Permission::PUT_USERS]) && $subject instanceof User) {
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
        
      case Permission::GET_USERS:
        return $this->canGet($token->getUser(), $subject);
        break;
        
      case Permission::DELETE_USERS:
        return $this->canDelete($token->getUser(), $subject);
        break;
        
      case Permission::PUT_USERS:
        return $this->canPut($token->getUser(), $subject);
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
  
  private function canGet(App $app, User $user): bool
  {
    return
      $app->hasPermission(Permission::GET_USERS) &&
      $app->getUsers()->contains($user)
    ;
  }
  
  private function canDelete(App $app, User $user): bool
  {
    return
      $app->hasPermission(Permission::DELETE_USERS) &&
      $app->getUsers()->contains($user)
    ;
  }
  
  private function canPut(App $app, User $user): bool
  {
    return
      $app->hasPermission(Permission::PUT_USERS) &&
      $app->getUsers()->contains($user)
    ;
  }
}