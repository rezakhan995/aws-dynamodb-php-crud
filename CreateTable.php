<?php

class Table extends DynamoDb {

    /**
     * Create Table On DynamoDB To Store User & Post Data
     *
     * @return void
     */
    public function createTables(): void {

        // create license usage table
        if ( !$this->isTableExist( $this->usersTableName ) ) {

            try {

                $response = $this->dbConnection->createTable( [
                    'TableName'             => $this->usersTableName,
                    'AttributeDefinitions'  => [
                        ['AttributeName' => 'UserName', 'AttributeType' => 'S'],
                        ['AttributeName' => 'UserEmail', 'AttributeType' => 'S'],
                    ],
                    'KeySchema'             => [
                        ['AttributeName' => 'UserName', 'KeyType' => 'HASH'],
                        ['AttributeName' => 'UserEmail', 'KeyType' => 'RANGE'],
                    ],
                    'ProvisionedThroughput' => [
                        'ReadCapacityUnits'  => 20,
                        'WriteCapacityUnits' => 10,
                    ],
                ] );

                $this->dbConnection->waitUntil( 'TableExists', [
                    'TableName' => $this->usersTableName,
                    '@waiter'   => [
                        'delay'       => 5,
                        'maxAttempts' => 20,
                    ],
                ] );

            } catch ( DynamoDbException $de ) {

                echo $de->getMessage() . '\n';
                exit( "Unable to create {$this->usersTableName}" );

            }

        } else {
            error_log( $this->usersTableName . 'table already exists' );
        }

        // create license status table
        if ( !$this->isTableExist( $this->postsTableName ) ) {

            try {

                $response = $this->dbConnection->createTable( [
                    'TableName'             => $this->postsTableName,
                    'AttributeDefinitions'  => [
                        ['AttributeName' => 'PostSlug', 'AttributeType' => 'S'],
                        ['AttributeName' => 'PostTitle', 'AttributeType' => 'S'],
                    ],
                    'KeySchema'             => [
                        ['AttributeName' => 'PostSlug', 'KeyType' => 'HASH'],
                        ['AttributeName' => 'PostTitle', 'KeyType' => 'RANGE'],
                    ],
                    'ProvisionedThroughput' => [
                        'ReadCapacityUnits'  => 20,
                        'WriteCapacityUnits' => 10,
                    ],
                ] );

                $this->dbConnection->waitUntil( 'TableExists', [
                    'TableName' => $this->postsTableName,
                    '@waiter'   => [
                        'delay'       => 5,
                        'maxAttempts' => 20,
                    ],
                ] );

            } catch ( DynamoDbException $de ) {

                echo $de->getMessage() . '\n';
                exit( "Unable to create {$this->postsTableName}" );

            }

        } else {
            error_log( $this->postsTableName . ' table already exists' );
        }

    }

    /**
     * Check If Table Already Exists
     *
     * @param [type] $tableName
     * @return boolean
     */
    public function isTableExist( $tableName ): bool {
        $tables = [];

        unset( $response );

        do {

            if ( isset( $response ) ) {
                $params = [
                    'Limit'                   => 2,
                    'ExclusiveStartTableName' => $response['LastEvaluatedTableName'],
                ];
            } else {
                $params = ['Limit' => 2];
            }

            $response = $this->dbConnection->listTables( $params );

            foreach ( $response['TableNames'] as $key => $value ) {

                if ( $value == $tableName ) {

                    error_log( "{$tableName} table already exists." );

                    return true;
                }

            }

            $tables = array_merge( $tables, $response['TableNames'] );

        } while ( $response['LastEvaluatedTableName'] );

        return false;
    }

    /**
     * SHow All Table Available In This Region
     *
     * @return void
     */
    public function listTable() {
        $tables = [];

        // Walk through table names, two at a time
        unset( $response );

        do {

            if ( isset( $response ) ) {
                $params = [
                    'Limit'                   => 2,
                    'ExclusiveStartTableName' => $response['LastEvaluatedTableName'],
                ];
            } else {
                $params = ['Limit' => 2];
            }

            $response = $this->dbConnection->listTables( $params );

            echo '<pre>';
            var_dump( $response );
            echo "</pre>";

        } while ( $response['LastEvaluatedTableName'] );

    }

}
