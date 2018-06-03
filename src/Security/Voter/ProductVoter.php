<?php declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\App;
use App\Entity\Permission;
use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
  protected function supports($attribute, $subject): bool
  {
    if(in_array($attribute, [Permission::POST_PRODUCTS, Permission::LIST_PRODUCTS])) {
      return true;
    }
    
    if(in_array($attribute, [Permission::GET_PRODUCTS, Permission::PUT_PRODUCTS, Permission::DELETE_PRODUCTS]) && $subject instanceof Product) {
      return true;
    }
    
    return false;
  }
  
  protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
  {
    switch ($attribute) {
      case Permission::POST_PRODUCTS:
        return $this->canPost($token->getUser());
        break;
        
      case Permission::LIST_PRODUCTS:
        return $this->canList($token->getUser());
        break;
        
      case Permission::GET_PRODUCTS:
        return $this->canGet($token->getUser(), $subject);
        break;
        
      case Permission::PUT_PRODUCTS:
        return $this->canPut($token->getUser(), $subject);
        break;
        
      case Permission::DELETE_PRODUCTS:
        return $this->canDelete($token->getUser(), $subject);
        break;
    }
    
    return false;
  }
  
  private function canPost(App $app): bool
  {
    return $app->hasPermission(Permission::POST_PRODUCTS);
  }
  
  private function canList(App $app): bool
  {
    return $app->hasPermission(Permission::LIST_PRODUCTS);
  }
  
  private function canGet(App $app, Product $product): bool
  {
    return $app->hasPermission(Permission::GET_PRODUCTS);
  }
  
  private function canPut(App $app, Product $product): bool
  {
    return $app->hasPermission(Permission::PUT_PRODUCTS);
  }
  
  private function canDelete(App $app, Product $product): bool
  {
    return $app->hasPermission(Permission::DELETE_PRODUCTS);
  }
  
}