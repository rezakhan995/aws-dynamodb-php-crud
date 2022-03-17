<?php

class Read extends DynamoDb {

    public function getUser($userName, $userEmail){

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

            if ( !empty( $result["Item"] ) ) {
                var_dump( $result["Item"] );

            } else {
                var_dump( 'No user found with the provided username and email' );
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