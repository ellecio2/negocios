<?php

namespace App\Classes;

class Template {
    public string $name;
    public Language $language;
    public array $components;

    public function __construct(mixed $language, mixed $components, string $name) {
        $this->name = $name;
        $this->language = new Language($language['code']);
        foreach ($components as $component) {
            $this->components[] = new Component($component['type'], $component['parameters']);
        }
    }
}
