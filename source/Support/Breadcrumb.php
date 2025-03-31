<?php

namespace Source\Support;

use Source\Core\Router;

class Breadcrumb
{
    private array $items = [];

    public function add(string $label, ?string $routeName = null, array $params = []): self
    {
        $this->items[] = [
            'label' => $label,
            'route' => $routeName,
            'params' => $params
        ];
        return $this;
    }

    public function render(Router $router): string
    {
        $html = '<div class="container-fluid mt-4">';
        $html .= '<nav aria-label="breadcrumb">';
        $html .= '<ol class="breadcrumb">';

        $lastIndex = count($this->items) - 1;
        foreach ($this->items as $index => $item) {
            if ($item['route'] && $index !== $lastIndex) {
                $url = $router->url($item['route'], $item['params']);
                $html .= "<li class=\"breadcrumb-item\"><a href=\"{$url}\">{$item['label']}</a></li>";
            } else {
                $html .= "<li class=\"breadcrumb-item active\" aria-current=\"page\">{$item['label']}</li>";
            }
        }

        $html .= '</ol>';
        $html .= '</nav>';
        $html .= '</div>';
        return $html;
    }

    public function toArray(): array
    {
        return $this->items;
    }
}