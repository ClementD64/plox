<?php

class File {
    private $file;

    public function __construct(string $path) {
        $this->file = fopen($path, 'w');
    }

    public function __destruct() {
        fclose($this->file);
    }

    public function print(string $data) {
        fwrite($this->file, $data);
    }

    public function println(string $data) {
        fwrite($this->file, $data . "\n");
    }
}

class GenerateAst {
    public function __construct(int $argc, array $argv) {
        if ($argc !== 2) {
            fwrite(STDERR, 'Usage: generateAst <output directory>');
            exit(1);
        }
        $outputDir = $argv[1];
        $this->defineAst($outputDir, 'Expr', [
            'Binary   : Expr $left, Token $operator, Expr $right',
            'Grouping : Expr $expression',
            'Literal  : mixed $value',
            'Unary    : Token $operator, Expr $right'
        ]);
    }

    private function defineAst(string $outputDir, string $baseName, array $types) {
        $path = $outputDir . '/' . $baseName . '.php';
        $file = new File($path);

        $file->println('<?php');
        $file->println('');
        $file->println('namespace Lox;');
        $file->println('');
        $file->println('abstract class '.$baseName.' {');
        $file->println('    abstract public function accept('.$baseName.'Visitor $visitor);');
        $file->println('}');
        $file->println('');

        $this->defineVisitor($file, $baseName, $types);
        $file->println('');

        foreach ($types as $type) {
            $class = trim(explode(':', $type)[0]);
            $fields = trim(explode(':', $type)[1]);
            $this->defineType($file, $baseName, $class, $fields);
        }
    }

    private function defineType(File $file, string $baseName, string $class, string $fieldsList) {
        $file->println('class '.$baseName.$class.' extends '.$baseName.' {');

        $fields = explode(', ', $fieldsList);
        foreach ($fields as $field) {
            $name = explode(' ', $field)[1];
            $file->println('    public '.$name.';');
        }
        $file->println('');
        $file->println('    public function __construct('.str_replace('mixed ', '', $fieldsList).') {');

        foreach ($fields as $field) {
            $name = explode(' ', $field)[1];
            $file->println('        $this->'.substr($name, 1).' = '.$name.';');
        }
        $file->println('    }');
        $file->println('');
        
        $file->println('    public function accept('.$baseName.'Visitor $visitor) {');
        $file->println('        return $visitor->visit'.$class.$baseName.'($this);');
        $file->println('    }');

        $file->println('}');
        $file->println('');
    }

    private function defineVisitor(File $file, string $baseName, array $types) {
        $file->println('interface '.$baseName.'Visitor {');
        
        foreach ($types as $type) {
            $typeName = trim(explode(':', $type)[0]);
            $file->println('    public function visit'.$typeName.$baseName.
                '('.$baseName.$typeName.' $'.strtolower($baseName).');');
        }

        $file->println('}');
        $file->println('');
    }
}

new GenerateAst($argc, $argv);