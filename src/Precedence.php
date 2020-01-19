<?php

namespace Lox;

require_once 'Enum.php';
require_once 'TokenType.php';

class Precedence extends Enum {
    const NONE = 0;
    const EQUALITY = 1;
    const COMPARISON = 2;
    const TERM = 3;
    const FACTOR = 4;
    const UNARY = 5;
    const PRIMARY = 6;

    private static $rules = [];

    public static function getRule(TokenType $type) {
        return self::$rules[TokenType::getValue($type)] ?? [null, null, 0];
    }

    public static function setRule($type, $prefix, $infix, $precedence) {
        self::$rules[$type] = [$prefix, $infix, $precedence];
    }
}


Precedence::setRule(TokenType::LEFT_PAREN, 'grouping', NULL, Precedence::NONE);
Precedence::setRule(TokenType::BANG, 'unary', NULL, Precedence::NONE);
Precedence::setRule(TokenType::MINUS, 'unary', 'binary', Precedence::TERM);
Precedence::setRule(TokenType::PLUS, NULL, 'binary', Precedence::TERM);
Precedence::setRule(TokenType::STAR, NULL, 'binary', Precedence::FACTOR);
Precedence::setRule(TokenType::SLASH, NULL, 'binary', Precedence::FACTOR);
Precedence::setRule(TokenType::EQUAL_EQUAL, NULL, 'binary', Precedence::EQUALITY);
Precedence::setRule(TokenType::BANG_EQUAL, NULL, 'binary', Precedence::EQUALITY);
Precedence::setRule(TokenType::GREATER, NULL, 'binary', Precedence::COMPARISON);
Precedence::setRule(TokenType::GREATER_EQUAL, NULL, 'binary', Precedence::COMPARISON);
Precedence::setRule(TokenType::LESS, NULL, 'binary', Precedence::COMPARISON);
Precedence::setRule(TokenType::LESS_EQUAL, NULL, 'binary', Precedence::COMPARISON);
Precedence::setRule(TokenType::NUMBER, 'primary', NULL, Precedence::NONE);
Precedence::setRule(TokenType::STRING, 'primary', NULL, Precedence::NONE);
Precedence::setRule(TokenType::TRUE, 'primary', NULL, Precedence::NONE);
Precedence::setRule(TokenType::FALSE, 'primary', NULL, Precedence::NONE);
Precedence::setRule(TokenType::NIL, 'primary', NULL, Precedence::NONE);