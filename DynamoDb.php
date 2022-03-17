<?php

use AWS\DynamoDb\Exception\DynamoDbException;

class DynamoDb {

    protected $dbConnection;
    protected $endpoint;
    protected $config;

    protected $usersTableName = 'Users';
    
    public function __construct( $key = 'dummy', $secret = 'dummy' ) {

        $this->endpoint = 'http://localhost:8000';

        $this->config = new \Aws\Sdk( [
            'endpoint'    => $this->endpoint,
            'region'      => 'us-west-2',
            'version'     => 'latest',
            'DynamoDb'    => [
                'region' => 'eu-central-1',
            ],
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
        ] );

        try{
            // Creating an Amazon DynamoDb client will use the "eu-central-1" AWS Region
            $this->dbConnection = $this->config->createDynamoDb();

        } catch(DynamoDbException $e){

            error_log('could not create DynamoDB instance. ' . $e->getMessage());

        }
    }
}