<?php 

namespace Lox;

require_once 'Expr.php';
require_once 'Token.php';

class AstPrinter implements ExprVisitor {

    public function print(Expr $expr) {
        return $expr->accept($this);
    }

    public function visitBinaryExpr(ExprBinary $expr) {
        return $this->parenthesize($expr->operator->value, $expr->left, $expr->right);
    }

    public function visitGroupingExpr(ExprGrouping $expr) {
        return $this->parenthesize('group', $expr->expression);
    }

    public function visitLiteralExpr(ExprLiteral $expr) {
        if ($expr->value === NULL) return 'nil';
        return strval($expr->value);
    }

    public function visitUnaryExpr(ExprUnary $expr) {
        return $this->parenthesize($expr->operator->value, $expr->right);
    }

    private function parenthesize(string $name, Expr ...$exprs) {
        $result = '('.$name;

        foreach ($exprs as $expr) {
            $result .= ' ' . $expr->accept($this);
        }

        return $result . ')';
    }
}