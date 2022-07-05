<?php 
require_once 'vendor/autoload.php';
use BenMorel\ApacheLogParser\Parser;

class ParseLogs {
	
    private $parseFile;
	private $logFormat; 
	private $parser; 
    private $parserProcessed;
	private $parserResult = [];
	
	function parse(string $file){
		$parseFile = file($file);
		
		$logFormat = '%h %l %u %t %m %f %h %s %b %U %h';
		$parser = new Parser($logFormat); 
		$parserResult['views'] = count($parseFile);
		$parserResult['urls'] = [];
		$parserResult['traffic'] = 0;
		$parserResult['crawlers'] = ['Google' => 0, 'Bing' => 0, 'Baidu' => 0, 'Yandex' => 0];
		$parserResult['statusCodes'] = [];
		foreach ($parseFile as $value){
			
			$parseResult = $parser->parse($value, false);
			
			array_push($parserResult['urls'], $parseResult[5]);
			
			if($parseResult[7]=='200'){
				$parserResult['traffic']+=$parseResult[8];
				$parserResult['statusCodes']['200']++;
			}else if($parseResult[7]=='301'){
				$parserResult['statusCodes']['301']++;
			}
			
			foreach ($parserResult['crawlers'] as $key => $value){
				
				if(strpos($parseResult[10],$key)){
					$parserResult['crawlers'][$key]++;
				}
			}
		}
		
		$parserResult['urls'] = count(array_unique($parserResult['urls']));
		echo $json_parserResult = json_encode($parserResult);
	
    }
}
$parserObject = new ParseLogs();
$parserObject->parse($argv[1]);
?>
