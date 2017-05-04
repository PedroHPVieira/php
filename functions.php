<?php
	//Function created to deliver the result to the client.
	function deliverResponse($status, $status_message, $data){
		header("HTTP://1.1 $status $status_message");

		$response['status'] = $status;
		$response['status_message'] = $status_message;
		$response['data'] = $data;

		$json_response = json_encode($response);
		return $json_response;
	}
	//Function created to get a stock information for a specific stock by Id.
	function getStockById($stockId, $connection){
		$sqlQuery = "SELECT * FROM Stock WHERE Id = $stockId";
		
		try {
			$result = $connection->query($sqlQuery);
			return $result->fetch_assoc();
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		echo $result;
		return $result;
	}
	//Function created to get all the stocks from an user
	function getStocksByUserId($userId, $connection){
		$sqlQuery = "SELECT * FROM Transactions T
					INNER JOIN Stock S ON S.Id = T.Id_Stock
					WHERE T.Id_User = $userId";
		try {
			$result = $connection->query($sqlQuery);

			$myArray = array();
			foreach ($result as $obj) {
				$myArray[] = $obj;
			}

			return $myArray;
			
		} catch (Exception $e) {
			echo $e->getMessage();			
		}

		echo $result;
		return $result;
	}
	//Function created to get the user's portfolio where it'll put together the performance of the growth of the stock price.
	function getPortfolioByUserId($userId, $connection){
		$sqlQuery = "SELECT U.Name as UserName, S.Name, S.Symbol, T.Purchase_Price, T.Quantity ,S.Price, P.Week, P.Price as PriceWeek
					FROM User U
					INNER JOIN Transactions T ON T.Id_User = U.Id
					INNER JOIN Stock S ON S.Id = T.Id_Stock
					INNER JOIN Performance P ON P.Id_Stock = S.Id
					WHERE U.Id = $userId";
		try {
			
			$result = $connection->query($sqlQuery);

			$myArray = array();

			foreach ($result as $obj) {
				$myArray[] = $obj;	
			}

			return $myArray;

		} catch (Exception $e) {
			echo $e->getMessage();	
		}
	}
	//Get all the stocks from the database.
	function getAllStocks($connection){
		$sqlQuery = "SELECT * FROM Stock";

		try {
			$result = $connection->query($sqlQuery);

			$myArray = array();
			foreach ($result as $obj) {
				$myArray[] = $obj;
			}

			return $myArray;

		} catch (Exception $e) {
			echo $e->getMessage();			
		}

		echo $result;
		return $result;
	}

	//Function to get the current value of a specific stock from an user
	function getCurrentValueOfStocksByUser($stockId, $userId, $connection){
		$sqlQuery = "SELECT T.Quantity * S.Price AS 'Total'
					FROM User U
					INNER JOIN Transactions T ON T.Id_User = U.Id
					INNER JOIN Stock S ON S.Id = T.Id_Stock
					WHERE U.Id = $userId
					AND S.Id = $stockId";

		try {
			$result = $connection->query($sqlQuery);
			return $result->fetch_assoc();

		} catch (Exception $e) {
			echo $e->getMessage();		
		}

		echo $result;
		return $result;
	}

	//Function responsable to update a stock
	function updateStock($stock, $connection){
		$sqlQuery = "UPDATE Stock SET 
						Name = '$stock->name',
						Symbol = '$stock->symbol',
						Price = $stock->price
					WHERE Id = $stock->id";

		try {
			if($connection->query($sqlQuery) === TRUE){
				return "Sucess";
			}
			else{
				return "ERROR: Stock Update didn't work";
			}

			return $result;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

?>