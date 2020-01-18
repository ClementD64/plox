<?php

namespace Lox;

require_once 'Enum.php';

class TokenType extends Enum {
    // Single-character tokens.
    const LEFT_PAREN = 0;
    const RIGHT_PAREN = 1;
    const LEFT_BRACE = 2;
    const RIGHT_BRACE = 3;
    const COMMA = 4;
    const DOT = 5;
    const MINUS = 6;
    const PLUS = 7;
    const SEMICOLON = 8;
    const SLASH = 9;
    const STAR = 10;

    // One or two character tokens.
    const BANG = 11;
    const BANG_EQUAL = 12;
    const EQUAL = 13;
    const EQUAL_EQUAL = 14;
    const GREATER = 15;
    const GREATER_EQUAL = 16;
    const LESS = 17;
    const LESS_EQUAL = 18;

    // Literals.
    const IDENTIFIER = 18;
    const STRING = 20;
    const NUMBER = 21;

    // Keywords.
    const AND = 22;
    const TCLASS = 23; // class constant name reserved in PHP
    const ELSE = 24;
    const FALSE = 25;
    const FUN = 26;
    const FOR = 27;
    const IF = 28;
    const NIL = 29;
    const OR = 30;
    const PRINT = 31;
    const RETURN = 32;
    const SUPER = 33;
    const THIS = 34;
    const TRUE = 35;
    const VAR = 36;
    const WHILE = 37;

    const ERROR = 38;
    const EOF = 39;
}