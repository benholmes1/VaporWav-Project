<?php

require_once '/var/www/html/dbconfig.php';
require_once '/var/www/html/User.class.php';

class UserTest extends \PHPUNIT_Framework_TestCase
{

  public function testUser()
  {
    $user = new User;

    $userID = '1';

    $userData = array('id' => $userID);

    $this->assertEquals($user->checkName($userData), 'benjamin'); 

    $this->assertEquals($user->getPrivacy($userID), '0');
  }

}
