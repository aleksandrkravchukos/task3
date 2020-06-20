# Docker / PHP 7.4 console / composer / phpunit 

Blank docker project for console php 7.4 projects with composer and phpunit.

## Prerequisites

Install Docker and optionally Make utility.

Commands from Makefile could be executed manually in case Make utility is not installed.

## Build container and install composer dependencies

    Make build

## Build container and install composer dependencies

If dist files are not copied to actual destination, then
    
    Make copy-dist-configs

## Run docker 

    docker-compose up -d
    
## Check docker containers

    docker ps    

## Create database 

    docker exec -i mysql8 mysql -uroot -proot  content < dump/task1.sql        

## Run functional tests

Runs container and executes functional tests.

    Make functional-tests

## Static analysis

Static analysis check

    Make static-analysis
    
## Algorithm 

* Create simple user and check if exist
* Authorize user