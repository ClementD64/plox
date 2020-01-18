default: build

phar-builder.phar:
	curl -L -o phar-builder.phar https://github.com/ClementD64/phar-builder/releases/download/v1.0/phar-builder.phar
	chmod +x phar-builder.phar

build: phar-builder.phar
	./phar-builder.phar src lox.phar -m main.php -l Lox.php

generateAst:
	php tool/generateAst.php src