# plox

Lox interpreter written in PHP

From Bob Nystrom's book [crafting interpreters](https://www.craftinginterpreters.com/)

> It doesn't really follow the book because it's funny to rewrite some parts

:warning: Work in progress

## Progress

* [x] Scanning
* [x] Representing Code
* [x] Parsing Expressions
* [ ] Evaluating Expressions
* [ ] Statements and State
* [ ] Control Flow
* [ ] Functions
* [ ] Resolving and Binding
* [ ] Classes
* [ ] Inheritance

## Change with the book

* Nested multi-line comments
* Scan Token on the fly
* Pratt parser

## Use It

clone this repository and run

```
php src/main.php [script]
```

You can also use
```
make
```
to create the phar
