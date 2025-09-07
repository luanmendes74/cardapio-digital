<?php

class Pages extends Controller
{
    public function __construct()
    {
        // Construtor do controller de páginas
    }

    /**
     * Método padrão, carrega a landing page do SaaS.
     */
    public function index()
    {
        $data = [
            'title' => 'Cardápio Digital SaaS - A sua solução completa!',
        ];

        // Chama o método view da classe Controller base
        $this->view('publico/landing', $data);
    }
}