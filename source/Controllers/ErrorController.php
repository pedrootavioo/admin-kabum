<?php

namespace Source\Controllers;

use Source\Core\Controller;

class ErrorController extends Controller
{
    public function show(?array $data): void
    {
        $this->view
            ->template('single')
            ->render('error/show', [
                'title' => 'Error',
                'content' => 'Error page',
                'errorCode' => $data['errorCode'] ?? 404
            ]);
    }
}