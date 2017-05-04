<?php

	header("Content-Type:Application/json");
	include($_SERVER['DOCUMENT_ROOT']."/wealthtab/config.php");
	include($_SERVER['DOCUMENT_ROOT']."/wealthtab/functions.php");
	include($_SERVER['DOCUMENT_ROOT']."/wealthtab/stock.php");
	include($_SERVER['DOCUMENT_ROOT']."/wealthtab/performance.php");

	define('DB_NAME', 'Stock');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_HOST', 'localhost');

	$connection = connect();

	$method = $_SERVER['REQUEST_METHOD'];

	if ($connection != null) {
		if (!empty($_GET['StockId'])) {
			$stockId = $_GET['StockId'];

			$stock = getStockById($stockId, $connection);			

			$message = "Stock found";

			echo deliverResponse(200, $message, $stock);
		}
		else if (!empty($_GET['StocksByUserId'])) {
			$userId = $_GET['StocksByUserId'];

			$stocks = getStocksByUserId($userId, $connection);
			
			$message = "Stock found";

			echo deliverResponse(200, $message, $stocks);
			
		}
		else if (!empty($_GET['PorfolioByUserId'])){
			$userId = $_GET['PorfolioByUserId'];

			$performance = getPortfolioByUserId($userId, $connection);		

			$myPerformance = array();
	
			$counter = count($performance);

			for ($i=0; $i < $counter ; $i++) {				
				if($i == 0){
					$myObj = new Performance();
									
					$myObj = createObject($performance[$i]);
					
					array_push($myPerformance, $myObj);
					
				}				
				else{
					if ($performance[$i - 1]["Name"] == $performance[$i]["Name"]) {
						array_push($myObj->priceWeek, $performance[$i]["PriceWeek"]);						
					}
					else{
						$myObj = new Performance();
											
						$myObj = createObject($performance[$i]);

						array_push($myPerformance, $myObj);
					}
					
				}			
			}

			$message = "Portfolio found";
			echo deliverResponse(200, $message, $myPerformance);			

		}
		elseif (!empty($_GET['AllStocks'])) {
			$stocks = GetAllStocks($connection);
			$message = "Stocks found";

			echo deliverResponse(200, $message, $stocks);
		}

		elseif (!empty($_GET['ProfileStockId']) and !empty($_GET['UserId'])) {
			$userId = $_GET['UserId'];
			$stockId = $_GET['ProfileStockId'];

			$result = getCurrentValueOfStocksByUser($stockId, $userId, $connection);

			$message = "Stock current price found with success";
			echo deliverResponse(200, $message, $result);
		}

		else if ($method == 'PUT') {			
			$post_vars = json_decode(file_get_contents("php://input"), true);
			$newStock = new Stock();

			$newStock->id = $post_vars["Id"];
			$newStock->name = $post_vars["Name"];
			$newStock->symbol = $post_vars["Symbol"];
			$newStock->price = $post_vars["Price"];

			$result = updateStock($newStock, $connection);
			
			$message = "Stock updated with success";
			echo deliverResponse(200, $message, $result);

		}
		else{
			$message = 'ERROR: Parameters required!';
			deliverResponse(200, $message, NULL);
		}
	}
	else{

		$message = 'ERROR: Database not connected';
		deliverResponse(200, $message, NULL);
		echo deliverResponse();
	}

	closeConnection();

	function createObject($performance){

		$myObj = new Performance();
											
		$myObj->userName = $performance["UserName"];
		$myObj->companyName = $performance["Name"];
		$myObj->companySymbol = $performance["Symbol"];
		$myObj->purchasePrice = $performance["Purchase_Price"];
		$myObj->quantity = $performance["Quantity"];
		$myObj->originalPrice = $performance["Price"];
		array_push($myObj->priceWeek, $performance["PriceWeek"]);

		return $myObj;
	}	
?>