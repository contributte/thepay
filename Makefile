.PHONY: all test

all: test
	echo "Is done"

test: phpstan ecs
	composer run-script tester

phpstan:
	composer run-script phpstan
