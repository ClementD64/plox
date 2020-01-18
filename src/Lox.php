<?php

namespace Lox;

require_once 'TokenType.php';
require_once 'Scanner.php';

class Lox {
    public function run(string $code) {
        $scanner = new Scanner($code);

        for (;;) {
            $token = $scanner->scanToken();
            if ($token->type === TokenType::EOF()) return;
            echo ($token->type === TokenType::ERROR()
                ? $token->value
                : $token). "\n";
        } 
    }
}