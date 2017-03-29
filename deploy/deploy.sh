#!/bin/bash

PWD=`(cd $(dirname $0); pwd)`
PHP=/usr/bin/php

$PHP $PWD/initdb.php
