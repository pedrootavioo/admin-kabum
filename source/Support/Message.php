<?php

namespace Source\Support;

use Source\Core\Session;

class Message
{
    private string $text;
    private string $type;
    private string $icon;

    public function __toString()
    {
        return $this->render();
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function info(string $message): Message
    {
        $this->icon = "info icon-info";
        $this->type = "info";
        $this->text = $this->filter($message);
        return $this;
    }

    public function success(string $message): Message
    {
        $this->icon = "success icon-check-square-o";
        $this->type = "success";
        $this->text = $this->filter($message);
        return $this;
    }

    public function warning(string $message): Message
    {
        $this->icon = "warning icon-warning";
        $this->type = "warning";
        $this->text = $this->filter($message);
        return $this;
    }

    public function error(string $message): Message
    {
        $this->icon = "danger icon-warning";
        $this->type = "danger";
        $this->text = $this->filter($message);
        return $this;
    }

    public function render(): string
    {
        return "<div class='alert text-center my-3 alert-{$this->getType()}' role='alert'>{$this->getText()}</div>";
    }

    public function return(): array
    {
        return [
            "text" => $this->getText(),
            "type" => $this->getType(),
            "icon" => $this->getIcon()
        ];
    }

    public function flash(): void
    {
        new Session()->set("flash", $this);
    }

    private function filter(string $message): string
    {
        return filter_var($message, FILTER_DEFAULT);
    }

    public function response(): void
    {
        header('Content-Type: application/json');
        $json['message'] = $this->return();

        if (!empty($this->upload)) {
            foreach ($this->upload as $key => $value) {
                $json[$key] = $value;
            }
        }
        echo json_encode($json);
    }
}