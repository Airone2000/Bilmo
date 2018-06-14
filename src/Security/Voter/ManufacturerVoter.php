<?php declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\App;
use App\Entity\Permission;
use App\Entity\Manufacturer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ManufacturerVoter
 *
 * @package App\Security\Voter
 *
 * This voter ensures that operations on Manufacturer are authorized based on the App permissions.
 * It is called when the is_granted() method is called, through ExpressionLanguage, in the
 * ApiResource annotation.
 */
class ManufacturerVoter extends Voter
{
  protected function supports($attribute, $subject): bool
  {
    if( in_array($attribute, [Permission::LIST_MANUFACTURERS, Permission::POST_MANUFACTURERS]) ) {
      return true;
    }
    
    if( in_array($attribute, [Permission::GET_MANUFACTURERS, Permission::PUT_MANUFACTURERS, Permission::DELETE_MANUFACTURERS]) && $subject instanceof Manufacturer)
    {
      return true;
    }
    
    return false;
  }
  
  protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
  {
    switch($attribute){
      case Permission::LIST_MANUFACTURERS:
        return $this->canList($token->getUser());
        break;
        
      case Permission::POST_MANUFACTURERS:
        return $this->canPost($token->getUser());
        break;
        
      case Permission::GET_MANUFACTURERS:
        return $this->canGet($token->getUser(), $subject);
        break;
        
      case Permission::PUT_MANUFACTURERS:
        return $this->canPut($token->getUser(), $subject);
        break;
        
      case Permission::DELETE_MANUFACTURERS:
        return $this->canDelete($token->getUser(), $subject);
        break;
    }
    
    return false;
  }
  
  private function canList(App $app): bool
  {
    return $app->hasPermission(Permission::LIST_MANUFACTURERS);
  }
  
  private function canPost(App $app): bool
  {
    return $app->hasPermission(Permission::POST_MANUFACTURERS);
  }
  
  private function canGet(App $app, Manufacturer $manufacturer): bool
  {
    return $app->hasPermission(Permission::GET_MANUFACTURERS);
  }
  
  private function canPut(App $app, Manufacturer $manufacturer): bool
  {
    return $app->hasPermission(Permission::PUT_MANUFACTURERS);
  }
  
  private function canDelete(App $app, Manufacturer $manufacturer): bool
  {
    return $app->hasPermission(Permission::DELETE_MANUFACTURERS);
  }
}