<?php

namespace App\God\Controllers;

class HomeController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->viewTitle = 'Home Page';
        parent::__construct();
    }

    public function index()
    {
        return view(parent::VIEW_NAMESPACE.'::'.'home');
    }
}
