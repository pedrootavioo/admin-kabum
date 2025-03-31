<?php

namespace Source\Controllers\App;

use Source\Core\Controller;
use Source\Support\Auth;

class AppController extends Controller
{
    public function index(): void
    {
        $this->view
            ->template('default')
            ->render('app/index', [
                'title' => 'Boas-vindas',
                'person' => Auth::user()->person(),
            ]);
    }
}