<?php

namespace Lox;

require_once 'TokenType.php';

class Token {
    public $type;
    public $value;
    public $line;

    public function __construct(TokenType $type, $value, int $line) {
        $this->type = $type;
        $this->value = $value;
        $this->line = $line;
    }

    public function __toString(): string {
        return TokenType::getName($this->type) . ' ' . $this->value;
    }
}