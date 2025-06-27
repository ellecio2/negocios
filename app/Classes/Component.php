<?php

namespace App\Classes;

class Component {
    public string $type;
    public array $parameters;

    public function __construct(string $type, array $parameters){
        $this->type = $type;
        foreach ($parameters as $parameter) {
            $this->parameters[] = new Parameter($parameter['type'], $parameter['text']);
        }
    }

}
