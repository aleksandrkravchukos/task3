# Task 3 

Given we have a Redis DB. 
We need to implement user creation and authorization. 
On creation - we need to check for existing user.
Also, username and password should have length validation and email should have appropriate validation.

## Prerequisites

Install Docker and optionally Make utility.

Commands from Makefile could be executed manually in case Make utility is not installed.

## Build container and install composer dependencies

    Make build

## Build container and install composer dependencies

If dist files are not copied to actual destination, then
    
    Make copy-dist-configs

## Run containers 

    Make up
    
## Check docker containers

    docker ps    

## Run all tests

Runs container and executes both Unit and functional tests.

    Make all-tests

## Static analysis

Static analysis check

    Make static-analysis