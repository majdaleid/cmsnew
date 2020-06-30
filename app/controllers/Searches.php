<?php
  class Searches extends Controller {
    public function __construct(){

    }

    public function index(){

      $data = [
        'title' => 'search result',
        'description' => 'search result'
      ];

      $this->view('searches/index', $data);
    }
}
