<?php

namespace Source\Core;

class View
{
    private array $data = [];
    // Define como padrão o template 'default'
    private ?string $template = 'default';
    private ?string $viewContent = null;

    public function __construct()
    {
        // Garante que o objeto $view esteja disponível nas templates
        $this->data['view'] = $this;
    }

    /**
     * Define o template a ser usado.
     * Se nenhum valor for passado, mantém o template atual.
     * Permite encadeamento: $view->template('main')->render('home');
     */
    public function template(?string $templateName = 'default'): self
    {
        if (empty($templateName)) {
            return $this;
        }

        $this->template = $templateName;
        return $this;
    }

    /**
     * Renderiza a view e, se um template estiver definido, injeta o conteúdo no template.
     */
    public function render(string $view, array $renderData = []): void
    {
        $viewPath = __DIR__ . "/../../views/" . $view . ".php";
        if (!file_exists($viewPath)) {
            die("View não encontrada: " . $view);
        }

        $this->handleData($renderData);
        extract($this->data);

        ob_start();
        require $viewPath;

        if (empty($this->template)) {
            ob_end_flush();
            return;
        }

        // Captura o conteúdo da view
        $this->viewContent = ob_get_clean();

        // Carrega o template, que deve estar em views/templates/{template}.php
        $templatePath = __DIR__ . "/../../views/templates/" . $this->template . ".php";

        if (!file_exists($templatePath)) {
            die("Template não encontrado: " . $this->template);
        }
        require $templatePath;
    }

    /**
     * Mescla os dados passados com os já existentes.
     */
    public function handleData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Retorna o conteúdo capturado da view.
     * Esse método pode ser chamado no template para inserir o conteúdo renderizado.
     */
    public function content(): string
    {
        return $this->viewContent ?? '';
    }

    /**
     * Insere um partial, geralmente utilizado para layouts.
     */
    public function insert(string $partial): void
    {
        $partialPath = __DIR__ . "/../../views/layouts/" . $partial . ".php";
        if (!file_exists($partialPath)) {
            die("Partial não encontrada: " . $partial);
        }
        // Garante que todas as variáveis da view sejam extraídas para o escopo atual
        extract($this->data);
        require $partialPath;
    }
}