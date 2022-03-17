<?php

class Delete extends DynamoDb {

    public function updateUser( $userName, $userEmail ): void {

        $marshaler = new Marshaler();

        try {
            $key = [
                "UserName" => ["S" => "$userName"],
            ];

            $exVal = $marshaler->marshalJson( json_encode( [':s' => "$userEmail"] ) );

            $params = [
                'TableName'                 => $this->usersTableName,
                'Key'                       => $key,
                'UpdateExpression'          => 'set UserEmail = :s',
                'ExpressionAttributeValues' => $exVal,
                'ReturnValues'              => 'UPDATED_NEW',
            ];

            $result = $this->dbConnection->updateItem( $params );

        } catch ( DynamoDbException $e ) {
            error_log( "Unable to update item: " . $e->getMessage() );
        }

    }

}
