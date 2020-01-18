<?php

namespace Lox;

require_once 'Lox.php';

if ($argc > 2) {
    echo "Usage: plox [script]\n";
    exit(64);
} else if ($argc === 2) {
    $lox = new Lox();
    $hadError = $lox->run(file_get_contents($argv[1]));
    if ($hadError) exit(65);
}
else {
    $lox = new Lox();
    for (;;) {
        $line = readline('> ');
        readline_add_history($line);
        $lox->run($line);
    }
}
