<?php  
  
  // Load Config
  require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/config/config.php';
  //load Helpers
  require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/helpers/url_helper.php';
  require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/helpers/session_helper.php';
  // Autoload Core Libraries
  require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/config/Controller.php';
  require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/config/Core.php';
  require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/config/database.php';

  // spl_autoload_register(function($className){
  //   require_once 'config/' . $className . '.php';
  // });

  
