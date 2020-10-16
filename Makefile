run_coverage:
	clear
	cd mock && java -jar wiremock.jar --verbose &
	sleep 7
	vendor/bin/phpunit --coverage-clover build/logs/clover.xml --testdox --debug -c phpunit.xml.dist 
	vendor/bin/php-coveralls -v