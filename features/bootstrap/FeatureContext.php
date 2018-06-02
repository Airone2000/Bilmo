<?php

use Behat\Behat\Context\Context;

/**
 * This context class contains the definitions of the steps used by the demo 
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 * 
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements Context
{
  
  private static $container;
  
  /**
   * @var \Symfony\Bundle\FrameworkBundle\Console\Application
   */
  private static $console;
  
  /**
   * @BeforeSuite
   */
    public static function boostrapSymfony()
    {
      require_once __DIR__.'/../../vendor/autoload.php';
      require_once __DIR__.'/../../src/Kernel.php';
      
      $kernel = new \App\Kernel('test', true);
      $kernel->boot();
      self::$container = $kernel->getContainer();
      
      # Save console at class level
      $console = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
      $console->setAutoExit(false);
      self::$console = $console;
      
      # Build db
      self::dropDatabase();
      self::createDatabase();
      self::createSchema();
      self::loadFixtures();
    }
  
  /**
   * @AfterSuite
   */
    public static function shutDownTesting()
    {
      //self::dropDatabase();
    }
    
    private static function runCommand(array $cmd): void
    {
      $command = new \Symfony\Component\Console\Input\ArrayInput($cmd);
      $command->setInteractive(false);
      self::$console->run($command);
    }
    
    private static function createDatabase(): void
    {
      self::runCommand([
        'command' => 'd:d:c'
      ]);
    }
    
    private static function createSchema(): void
    {
      self::runCommand([
        'command' => 'd:s:c'
      ]);
    }
    
    private static function loadFixtures(): void
    {
      self::runCommand([
        'command' => 'd:f:l'
      ]);
    }
    
    private static function dropDatabase(): void
    {
      self::runCommand([
        'command' => 'd:d:d',
        '--force' => true
      ]);
    }
}
