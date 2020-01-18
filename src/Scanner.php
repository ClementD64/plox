<?php

namespace Lox;

require_once 'TokenType.php';
require_once 'Token.php';

class Scanner {
    private $src = '';
    private $start = 0;
    private $current = 0;
    private $line = 1;
    private static $keyword = [];

    public function __construct(string $src) {
        $this->src = $src;
        self::$keyword = [
            'and' => TokenType::AND(),
            'class' => TokenType::TCLASS(),
            'else' => TokenType::ELSE(),
            'false' => TokenType::FALSE(),
            'for' => TokenType::FOR(),
            'fun' => TokenType::FUN(),
            'if' => TokenType::IF(),
            'nil' => TokenType::NIL(),
            'or' => TokenType::OR(),
            'print' => TokenType::PRINT(),
            'return' => TokenType::RETURN(),
            'super' => TokenType::SUPER(),
            'this' => TokenType::THIS(),
            'true' => TokenType::TRUE(),
            'var' => TokenType::VAR(),
            'while' => TokenType::WHILE()
        ];
    }

    public function scanToken(): Token {
        $this->skipWhitspace();
        if ($this->isAtEnd()) return $this->makeToken(TokenType::EOF());

        $this->start = $this->current;
        $c = $this->advance();

        if (self::isDigit($c)) return $this->number();
        if (self::isAlpha($c)) return $this->identifier();

        switch ($c) {
            case '(': return $this->makeToken(TokenType::LEFT_PAREN());
            case ')': return $this->makeToken(TokenType::RIGHT_PAREN());
            case '{': return $this->makeToken(TokenType::LEFT_BRACE());
            case '}': return $this->makeToken(TokenType::RIGHT_BRACE());
            case ',': return $this->makeToken(TokenType::COMMA());
            case '.': return $this->makeToken(TokenType::DOT());
            case '-': return $this->makeToken(TokenType::MINUS());
            case '+': return $this->makeToken(TokenType::PLUS());
            case ';': return $this->makeToken(TokenType::SEMICOLON());
            case '*': return $this->makeToken(TokenType::STAR());
            case '/': return $this->makeToken(TokenType::SLASH());
            case '!': return $this->makeToken($this->match('=') ? TokenType::BANG_EQUAL() : TokenType::BANG());
            case '=': return $this->makeToken($this->match('=') ? TokenType::EQUAL_EQUAL() : TokenType::EQUAL());
            case '<': return $this->makeToken($this->match('=') ? TokenType::LESS_EQUAL() : TokenType::LESS());
            case '>': return $this->makeToken($this->match('=') ? TokenType::GREATER_EQUAL() : TokenType::GREATER());
            case '"': return $this->string();
        }

        return $this->makeToken(TokenType::ERROR(), 'Unexpected character \''.$c.'\'.');
    }

    private function skipWhitspace() {
        while (!$this->isAtEnd()) {
            switch ($this->peek()) {
                case "\n":
                    $this->line++;
                case ' ':
                case "\t":
                case "\r":
                    $this->current++;
                    break;
                case '/':
                    if ($this->peekNext() === '/') {
                        while (!$this->isAtEnd() && $this->peek() !== "\n")
                            $this->current++;
                        break;
                    } else if ($this->peekNext() === '*') {
                        $this->current += 2; // consume the /*
                        $deep = 1;
                        while ($deep > 0) {
                            if ($this->isAtEnd()) return;
                            else if ($this->peek() === '*' && $this->peekNext() === '/') {
                                $deep--;
                                $this->current += 2;
                            } else if ($this->peek() === '/' && $this->peekNext() === '*') {
                                $deep++;
                                $this->current += 2;
                            } else $this->current++;
                        }
                    }
                default:
                    return;
            }
        }
    }

    private function identifier(): Token {
        while (self::isAlphaNumeric($this->peek()))
            $this->current++;
        
        $text = $this->slice($this->start, $this->current);
        return $this->makeToken(self::$keyword[$text] ?? TokenType::IDENTIFIER(), $text);
    }

    private function number(): Token {
        while (self::isDigit($this->peek()))
            $this->current++;
        
        if ($this->peek() === '.' && self::isDigit($this->peekNext())) {
            $this->current++; // consume the '.'
            while (self::isDigit($this->peek()))
                $this->current++;
        }

        return $this->makeToken(TokenType::NUMBER(),
            floatval($this->slice($this->start, $this->current)));
    }

    private function string(): Token {
        while (!$this->isAtEnd() && $this->peek() !== '"') {
            if ($this->peek() === "\n") $this->line++;
            $this->current++;
        }

        if ($this->isAtEnd())
            return $this->makeToken('Unterminated string.');
        
        $this->current++; // consume the closing ".
        return $this->makeToken(TokenType::STRING(),
            $this->slice($this->start + 1, $this->current - 1));
    }

    private function match(string $expected): bool {
        if ($this->isAtEnd()) return false;
        if ($this->peek() !== $expected) return false;

        $this->current++;
        return true;
    }

    private function makeToken(TokenType $type, $value = NULL): Token {
        return new Token($type, $value ?? $this->slice($this->start, $this->current), $this->line);
    }

    private function isAtEnd(): bool {
        return $this->current >= strlen($this->src);
    }

    private function advance(): string {
        return $this->src[$this->current++];
    }

    private function peek(): string {
        return $this->src[$this->current];
    }

    private function peekNext(): string {
        return $this->src[$this->current + 1];
    }

    private function slice(int $start, int $end): string {
        return substr($this->src, $start, $end - $start);
    }

    private static function isDigit(string $c): bool {
        return $c >= '0' && $c <= '9';
    }

    private static function isAlpha(string $c): bool {
        return $c >= 'a' && $c <= 'z' ||
               $c >= 'A' && $c <= 'Z' ||
               $c === '_';
    }

    private static function isAlphaNumeric(string $c): bool {
        return self::isAlpha($c) || self::isDigit($c);
    }
}