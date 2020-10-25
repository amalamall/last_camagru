<?php
  /*
   * Base Controller
   * Loads the models and views
   */
  class Controller {
    // Load model
    public function model($model){
      // Require model file
      require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/models/' . $model . '.php';

      // Instatiate model
      return new $model();
    }

    // Load view
    public function view($view, $data = []){
      // Check for view file
      if(file_exists($_SERVER['DOCUMENT_ROOT'].'/camagru/app/views/' . strtolower($view) . '.php')){
        require_once $_SERVER['DOCUMENT_ROOT'].'/camagru/app/views/' . strtolower($view) . '.php';
      } else {
        // View does not exist
        require_once($_SERVER['DOCUMENT_ROOT'].'/camagru/app/views/users/error.php');
      }
    }
  }