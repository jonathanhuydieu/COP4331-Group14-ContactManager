<?php
	$inData = getRequestInfo();

    $operation = $inData["field"];
	$look = $inData["look"];
	$login = $inData["login"];

	$conn = new mysqli("localhost", "Admin", "Admin", "yellabook");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
        switch ($operation) 
        {
            case 0;
                $stmt = $conn->prepare("SELECT cname FROM contacts WHERE login=? AND cname like ?");
                $stmt->bind_param("ss", $inData["login"], $inData["look"]);
                $stmt->execute();
                $result = $stmt->get_result();
                break;
            case 1;
                $stmt = $conn->prepare("SELECT cname FROM emails WHERE login=? AND address like ?");
                $stmt->bind_param("ss", $inData["login"], $inData["look"]);
                $stmt->execute();
                $result = $stmt->get_result();
                break;
            case 2;
                $stmt = $conn->prepare("SELECT cname FROM phones WHERE login=? AND number like ?");
                $stmt->bind_param("ss", $inData["login"], $inData["look"]);
                $stmt->execute();
                $result = $stmt->get_result();
                break;
            case 3;
                $stmt = $conn->prepare("SELECT cname FROM locations WHERE login=? AND address like ?");
                $stmt->bind_param("ss", $inData["login"], $inData["look"]);
                $stmt->execute();
                $result = $stmt->get_result();
                break;
        }

        while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '"' . $row["cname"] . '"';
		}

		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
		}

		$stmt->close();
		$conn->close();


	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>