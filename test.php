<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
 <script language="javascript" type="text/javascript" src="jquery.min.js"></script>
 <script language="javascript" type="text/javascript" src="jquery.jqplot.min.js"></script>
 <link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />
 <script type="text/javascript" src="plugins/jqplot.dateAxisRenderer.js"></script>

<form action="" method="get" >
    <input type="text" name="shareBox"> 
    <input type="submit" name="submit" />
</form>
 
<?php
require(__DIR__ . "/../vendor/autoload.php");

use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\ApiClientFactory;
use GuzzleHttp\Client;

// Create a new client from the factory
$client = ApiClientFactory::createApiClient();

// Or use your own Guzzle client and pass it in
$options = [/*...*/];
$guzzleClient = new Client($options);
$client = ApiClientFactory::createApiClient($guzzleClient);

// Returns an array of Scheb\YahooFinanceApi\Results\SearchResult
//$searchResult = $client->search("Apple");



// Returns Scheb\YahooFinanceApi\Results\Quote
//$exchangeRate = $client->getExchangeRate("USD", "EUR");

// Returns an array of Scheb\YahooFinanceApi\Results\Quote
//$exchangeRates = $client->getExchangeRates([
//    ["USD", "EUR"],
//    ["EUR", "USD"],
//]);

// Returns Scheb\YahooFinanceApi\Results\Quote
//$quote = $client->getQuote("AAPL");

// Returns an array of Scheb\YahooFinanceApi\Results\Quote
//$quotes = $client->getQuotes(["AAPL", "GOOG"]);

//foreach($historicalData3 as &$value){
//        echo(date_format($value->getDate(), 'l'));
//}


if(!empty($_GET)) {
    $share = $_GET['shareBox'];
	try{
		$searchResult = $client->search($share);
		//var_dump($searchResult);
		
		echo "Similar results: <br>";
		foreach($searchResult as &$item){
			echo("<a href=\"test.php?shareBox=" . $item->getSymbol() . "\">" . $item->getName() . " [" . $item->getSymbol() . "](" . $item->getTypeDisp() .")</a><br>");
		}
		
		$share = $searchResult[0]->getSymbol();
		
		// Returns an array of Scheb\YahooFinanceApi\Results\HistoricalData
		$historicalData1 = $client->getHistoricalData($share, ApiClient::INTERVAL_1_DAY, new \DateTime("-7 days"), new \DateTime("today"));

		$historicalData2 = $client->getHistoricalData($share, ApiClient::INTERVAL_1_DAY, new \DateTime("-30 days"), new \DateTime("today"));

		$historicalData3 = $client->getHistoricalData($share, ApiClient::INTERVAL_1_DAY, new \DateTime("-90 days"), new \DateTime("today"));
	}
	catch(Exception $e){
		if(strpos($e, 'Client error') !== false){
			echo "Share not found, please check suggestions";
		}
		else{
			echo 'Caught exception: ',  $e->getMessage();
		}
		exit(0);
	}
}
else{
	echo("Please Enter a share to search for");
	exit(0);
}
echo("<h1>Showing values for " . $searchResult[0]->getName() . " [" . $searchResult[0]->getSymbol() . "](" . $item->getTypeDisp() .")</h1>");

?><div id="chart1" style="margin-top:20px; margin-left:20px; width:1000px; height:300px;"></div>
<script class="code" type="text/javascript">
$(document).ready(function(){
	var line1 = [<?php
foreach($historicalData1 as &$value){
    $currentstr = "['" . date_format($value->getDate(), 'Y-n-j h:iA') . "'," . $value->getClose() . "]";
    echo $currentstr;
	if($value != end($historicalData1)){
	    echo(",");
    }
}
?>];
	var plot1 = $.jqplot ('chart1', [line1], {
		title:'1 Week',
		animate: true,
		series:[{showMarker:false}],
		axes:{
			xaxis:{
				label:'Date',
				renderer:$.jqplot.DateAxisRenderer,
				tickOptions:{formatString:'%b %#d'}
			},
			yaxis:{
				label:'Share Value In Own Currency'
			}
		}
	});
});
</script>

<div id="chart2" style="margin-top:20px; margin-left:20px; width:1000px; height:300px;"></div>
<script class="code" type="text/javascript">
$(document).ready(function(){
	var line2 = [<?php
foreach($historicalData2 as &$value){
    $currentstr = "['" . date_format($value->getDate(), 'Y-n-j h:iA') . "'," . $value->getClose() . "]";
    echo $currentstr;
	if($value != end($historicalData2)){
	    echo(",");
    }
}
?>];
	var plot2 = $.jqplot ('chart2', [line2], {
		title:'1 Month',
		animate: true,
		series:[{showMarker:false}],
		axes:{
			xaxis:{
				label:'Date',
				renderer:$.jqplot.DateAxisRenderer,
				tickOptions:{formatString:'%b %#d'}
			},
			yaxis:{
				label:'Share Value In Own Currency'
			}
		}
	});
});
</script>

<div id="chart3" style="margin-top:20px; margin-left:20px; width:1000px; height:300px;"></div>
<script class="code" type="text/javascript">
$(document).ready(function(){
	var line3 = [<?php
foreach($historicalData3 as &$value){
    $currentstr = "['" . date_format($value->getDate(), 'Y-n-j h:iA') . "'," . $value->getClose() . "]";
    echo $currentstr;
	if($value != end($historicalData3)){
	    echo(",");
    }
}
?>];
	var plot1 = $.jqplot ('chart3', [line3], {
		title:'3 Months',
		animate: true,
		series:[{showMarker:false}],
		axes:{
			xaxis:{
				label:'Date',
				renderer:$.jqplot.DateAxisRenderer,
				tickOptions:{formatString:'%b %#d'}
			},
			yaxis:{
				label:'Share Value In Own Currency'
			}
		}
	});
});
</script>
<img src="virtucon.png" width="75" height="75" alt="Virtucon Industries" align="middle">
© Virtucon Industries 1997-2018
<img src="virtucon.png" width="75" height="75" alt="Virtucon Industries" align="middle">