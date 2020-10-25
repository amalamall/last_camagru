<?php
  class Pages extends Controller {
    public function __construct(){
    }
    
    public function index(){
      $data = [
        'title' => 'Camagru',
        'description' => 'This web project is challenging you to create a small web application allowing you to
        make basic photo and video editing using your webcam and some predefined images.'
      ];
     
      $this->view('pages/index', $data);
    }

    
  }