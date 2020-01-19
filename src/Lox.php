<?php

namespace Lox;

require_once 'Parser.php';
require_once 'AstPrinter.php';

class Lox {
    public function run(string $code) {
        try {
            $parser = new Parser($code);
            $expr = $parser->parse();
            echo (new AstPrinter())->print($expr) . "\n";
        } catch (ParseError $e) {
            echo $e->getMessage() . "\n";
        }
    }
}