<?php
namespace app\controllers;
use app\models\Tickers;
use lithium\data\Connections;

class GraphController extends \lithium\action\Controller {

	public function index(){
		$mongodb = Connections::get('default')->connection;
		$tickers = Tickers::connection()->connection->command(array(
      'aggregate' => 'tickers',
      'pipeline' => array( 
                        array( '$project' => array(
                            '_id'=>0,
                            'year' => array('$year' => '$date'),
                            'month' => array('$month' => '$date'),                               
                            'day' => array('$dayOfMonth' => '$date'),                                
//                            'hour' => array('$hour' => '$date'),                             
                            'avg' => '$ticker.avg',
                            'last' => '$ticker.last',							
                            'high' => '$ticker.high',
                            'low' => '$ticker.low',
                            'vol' => '$ticker.vol',							
                            'inr' => '$INR',
                        )),
                        array( '$group' => array( '_id' => array(
                                'year'=>'$year',
                                'month'=>'$month',
                                'day'=>'$day',
//                              'hour'=>'$hour',								
                                ),
                            'avg' => array('$avg' => '$avg'),  
                            'last' => array('$avg' => '$last'),  							
                            'high' => array('$max' => '$high'),  							
                            'low' => array('$avg' => '$low'),  							
                            'vol' => array('$avg' => '$vol'),  														
                            'inr' => array('$avg' => '$inr'),  																					
                        )),
                        array('$sort'=>array(
                            'year'=>1,
                            'month'=>1,
                            'day'=>1,
//                            'hour'=>1
                        ))
                    )
    ));
/*  db.tickers.aggregate(
	{ $project: 
		{_id: 0,
		year: {$year: '$date'},
		month: {$month: '$date'},
		day: {$dayOfMonth: '$date'},
		hour: {$hour: '$date'},
		avg: '$ticker.avg'     
		}
	},
	{ $group: { 
		_id: { year: '$year', month: '$month', day: '$day', hour: '$hour' },
        avg: { $avg: '$avg' }
    	}
	},
	{ $sort: {
		'year':1, month:1, day:1, hour:1
		}
	}
);
		  */			
		array_multisort($tickers['result'], SORT_ASC);
		$Graphdata = "\n";
		$ymdh = "";
			foreach($tickers['result'] as $t){
//				$data = $data . "['".$t['_id']['year']."-".$t['_id']['month']."-".$t['_id']['day']." ".$t['_id']['hour'].":1:0', ".round($t['low'],2).",".round($t['avg'],2).",13, ".round($t['high'],2)."],\n";
				$Graphdata = $Graphdata . "['".$t['_id']['year']."-".$t['_id']['month']."-".$t['_id']['day']."',".round($t['low'],2).",".round($t['last'],2).",".round($t['avg'],2).",".round($t['high'],2)."],\n";
			}
		$tableData = array();

		$tableInit = array(
				'date' => $tickers['result'][0]['_id']['year']."-".$tickers['result'][0]['_id']['month']."-".$tickers['result'][0]['_id']['day'],
				'INR' => round($tickers['result'][0]['inr'],2),
				'USD' => round($tickers['result'][0]['avg'],2),
		);

		foreach($tickers['result'] as $t){
			$data = array(
				'date' => $t['_id']['year']."-".$t['_id']['month']."-".$t['_id']['day'],
				'INR' => round($t['inr'],2),
				'USD' => round($t['avg'],2),
				
			);
			array_push($tableData, $data);
		}
	
		$title = "Graph Bitcoin data"	;
		return compact('title','tickers','Graphdata','tableData','tableInit');
	}

	public function trend(){
		$mongodb = Connections::get('default')->connection;
		$tickers = Tickers::connection()->connection->command(array(
      'aggregate' => 'tickers',
      'pipeline' => array( 
                        array( '$project' => array(
                            '_id'=>0,
                            'year' => array('$year' => '$date'),
                            'month' => array('$month' => '$date'),                               
                            'day' => array('$dayOfMonth' => '$date'),                                
//                            'hour' => array('$hour' => '$date'),                             
                            'avg' => '$ticker.avg',
                            'last' => '$ticker.last',							
                            'high' => '$ticker.high',
                            'low' => '$ticker.low',
                            'vol' => '$ticker.vol',							
                            'inr' => '$INR',
                        )),
                        array( '$group' => array( '_id' => array(
                                'year'=>'$year',
                                'month'=>'$month',
                                'day'=>'$day',
//                              'hour'=>'$hour',								
                                ),
                            'avg' => array('$avg' => '$avg'),  
                            'last' => array('$avg' => '$last'),  							
                            'high' => array('$max' => '$high'),  							
                            'low' => array('$avg' => '$low'),  							
                            'vol' => array('$avg' => '$vol'),  														
                            'inr' => array('$avg' => '$inr'),  																					
                        )),
                        array('$sort'=>array(
                            'year'=>1,
                            'month'=>1,
                            'day'=>1,
//                            'hour'=>1
                        ))
                    )
    ));
	
		array_multisort($tickers['result'], SORT_ASC);
		$Graphdata = "\n";
		$ymdh = "";
			foreach($tickers['result'] as $t){
//				$data = $data . "['".$t['_id']['year']."-".$t['_id']['month']."-".$t['_id']['day']." ".$t['_id']['hour'].":1:0', ".round($t['low'],2).",".round($t['avg'],2).",13, ".round($t['high'],2)."],\n";
				$Graphdata = $Graphdata . "['".$t['_id']['year']."-".$t['_id']['month']."-".$t['_id']['day']."',".round($t['inr'],2).",".round($t['avg'],2).",".round($t['vol']/1000,2)."],\n";
			}
		$tableData = array();

		$tableInit = array(
				'date' => $tickers['result'][0]['_id']['year']."-".$tickers['result'][0]['_id']['month']."-".$tickers['result'][0]['_id']['day'],
				'INR' => round($tickers['result'][0]['inr'],2),
				'USD' => round($tickers['result'][0]['avg'],2),
		);

		foreach($tickers['result'] as $t){
			$data = array(
				'date' => $t['_id']['year']."-".$t['_id']['month']."-".$t['_id']['day'],
				'INR' => round($t['inr'],2),
				'USD' => round($t['avg'],2),
				
			);
			array_push($tableData, $data);
		}
	
		$title = "Graph Bitcoin data"	;
		return compact('title','tickers','Graphdata','tableData','tableInit');
	}

}

?>