<?php

namespace Lox;

require_once 'Precedence.php';
require_once 'Scanner.php';
require_once 'Expr.php';
require_once 'Error.php';

class Parser {
    private $scanner;
    private $previous;
    private $current;

    public function __construct(string $source) {
        $this->scanner = new Scanner($source);
    }

    public function parse() {
        $this->advance();
        $expr = $this->expression();
        $this->consume(TokenType::EOF(), 'Expect end of expression.');
        return $expr;
    }

    private function parsePrecedence($prec) {
        $this->advance();
        $prefix = Precedence::getRule($this->previous->type)[0];
        if ($prefix === NULL)
            $this->error($this->previous, 'Expect expression.');
        
        $left = $this->$prefix();

        while ($prec <= Precedence::getRule($this->current->type)[2]) {
            $this->advance();
            $infix = Precedence::getRule($this->previous->type)[1];
            $left = $this->$infix($left);
        }

        return $left;
    }

    private function expression(): Expr {
        return $this->parsePrecedence(Precedence::EQUALITY);
    }

    private function primary(): Expr {
        if ($this->previous->type === TokenType::FALSE())
            return new ExprLiteral(false);
        if ($this->previous->type === TokenType::TRUE())
            return new ExprLiteral(true);
        if ($this->previous->type === TokenType::NIL())
            return new ExprLiteral(NULL);
        return new ExprLiteral($this->previous->value);
    }

    private function grouping(): Expr {
        $expr = $this->expression();
        $this->consume(TokenType::RIGHT_PAREN(), 'Expect \')\' after expression.');
        return new ExprGrouping($expr);
    }

    private function binary(Expr $left): Expr {
        $operator = $this->previous;
        $right = $this->parsePrecedence(Precedence::getRule($operator->type)[2]+1);
        return new ExprBinary($left, $operator, $right);
    }

    private function unary(): Expr {
        $operator = $this->previous;
        $right = $this->parsePrecedence(Precedence::UNARY);
        return new ExprUnary($operator, $right);
    }

    private function advance() {
        $this->previous = $this->current;

        for (;;) {
            $this->current = $this->scanner->scanToken();
            if ($this->current->type !== TokenType::ERROR())
                break;
        }
    }

    private function consume(TokenType $type, string $message) {
        if ($this->current->type !== $type)
            $this->error($this->current, $message);
        $this->advance();
    }

    private function error(Token $token, string $message) {
        throw new ParseError(
            '[line '.$token->line.'] Error'.
            ($token->type === TokenType::EOF()
                ? ' at end'
                : ($token->type === TokenType::ERROR()
                    ? ''
                    : ' at \''.$token->value.'\'')).
            ': '.$message
        );
    }
}