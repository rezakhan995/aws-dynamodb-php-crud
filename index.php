<?php

require 'vendor/autoload.php';


use AWS\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

include 'DynamoDb.php';
include 'CreateTable.php';
include 'Create.php';
include 'Read.php';
include 'Update.php';
include 'Delete.php';