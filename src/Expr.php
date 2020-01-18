<?php

namespace Lox;

abstract class Expr {
    abstract public function accept(ExprVisitor $visitor);
}

trait ExprVisitor {
    abstract public function visitBianryExpr(Bianry $expr);
    abstract public function visitGroupingExpr(Grouping $expr);
    abstract public function visitLiteralExpr(Literal $expr);
    abstract public function visitUnaryExpr(Unary $expr);
}


class ExprBianry extends Expr {
    public $left;
    public $operator;
    public $right;

    public function __construct(Expr $left, Token $operator, Expr $right) {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    public function accept(ExprVisitor $visitor) {
        return $visitor->visitBianryExpr($this);
    }
}

class ExprGrouping extends Expr {
    public $expression;

    public function __construct(Expr $expression) {
        $this->expression = $expression;
    }

    public function accept(ExprVisitor $visitor) {
        return $visitor->visitGroupingExpr($this);
    }
}

class ExprLiteral extends Expr {
    public $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function accept(ExprVisitor $visitor) {
        return $visitor->visitLiteralExpr($this);
    }
}

class ExprUnary extends Expr {
    public $operator;
    public $right;

    public function __construct(Token $operator, Expr $right) {
        $this->operator = $operator;
        $this->right = $right;
    }

    public function accept(ExprVisitor $visitor) {
        return $visitor->visitUnaryExpr($this);
    }
}

