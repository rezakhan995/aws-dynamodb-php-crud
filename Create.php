<?php

class Create extends DynamoDb {

    public function createUser($userName, $userEmail){

        $marshaler = new Marshaler();

        $args = $marshaler->marshalJson(
            json_encode( [
                "UserName"   => $userName,
                "UserEmail"   => $userEmail,
            ] )
        );

        $params = [
            'TableName' => $this->usersTableName,
            'Key'       => $args,
        ];

        try {
            $result = $this->dbConnection->getItem( $params );

            if ( empty( $result["Item"] ) ) {
                // insert....
                var_dump( 'no status found, need to insert status' );
                $this->insert( $userName, $userEmail, $marshaler );

            } else {
                var_dump( 'existing user found, try again with a unique username and email' );
            }

        } catch ( DynamoDbException $e ) {
            var_dump("Unable to get item: " . $e->getMessage());
        }
    }

    public function insert( $userName, $userEmail, $marshaler ){
        $item = $marshaler->marshalJson( json_encode( [
                "UserName"   => "$userName",
                "UserEmail"  => "$userEmail",
            ] ) 
        );

        $params = [
            'TableName' => $this->usersTableName,
            'Item'      => $item,
        ];

        $result = $this->dbConnection->putItem( $params );
    }
}