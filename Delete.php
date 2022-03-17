<?php

class Delete extends DynamoDb {

    function deleteUser( $userName, $userEmail ) {
        $marshaler = new Marshaler();

        $args = $marshaler->marshalJson(
            json_encode( [
                "UserName"  => $userName,
                "UserEmail" => $userEmail,
            ] )
        );

        $params = [
            'TableName'    => $this->usersTableName,
            'Key'          => $args,
            'ReturnValues' => 'ALL_OLD',
        ];

        try {
            $result = $this->dbConnection->deleteItem( $params );

            var_dump( $result['Attributes'] );

        } catch ( DynamoDbException $e ) {
            var_dump( "Unable to delete item: " . $e->getMessage() );
        }
    }
    
}