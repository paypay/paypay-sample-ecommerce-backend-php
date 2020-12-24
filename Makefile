run_mock:
	cd mock && java -jar wiremock.jar --verbose &
	sleep 7
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-clover build/logs/clover.xml --testdox --debug -c phpunit.xml.dist
run_coverage:
	cd mock && java -jar wiremock.jar --verbose &
	sleep 7
	vendor/bin/phpunit --coverage-clover build/logs/clover.xml --testdox --debug -c phpunit.xml.dist 
	vendor/bin/php-coveralls -v
run_tests:
	clear
	vendor/bin/phpunit --coverage-clover build/logs/clover.xml --testdox --debug -c phpunit.xml.dist
coverall_upload:
	vendor/bin/php-coveralls --coverage_clover=build/logs/clover.xml -v