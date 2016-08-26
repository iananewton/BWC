<?php
/**
 * Template Name: Backend AJAX2
 *
 * Loop container for page content
 *
 * @package WordPress
 * @subpackage Foundation, for WordPress
 * @since Foundation, for WordPress 4.0
 */
header("Content-Type: application/json", true);
?>
<?php 
	add_query_arg('table', 'function', 'mashId', 'dateTime', 'htlVolume', 'hltPh', 'strikeTemp', 'enzyme1', "enzyme2", "enzyme3", "pitchTemp", "mashPh", "stepStart", "startTemp", "type", 'fermId', 'gallonsMash', 'bwc_mash_header_submit', 'keys', 'yeast', "pH", "specificGravity", "Temp", 'vendor', 'item', 'clearingReason', 'clearingAmount', 'clearingDate'); ?>
<?php 
	if($_GET){
		$func = explode(",", $_GET["function"]);
		if(!$func[0]){
			$func = explode(",", $_GET["func"]);
		}
		$passed_data = $_GET;
	}
	else if($_POST){
		$func = $_POST["function"];
		$passed_data = $_POST;
	}	

//execute each funtion and encode the JSON result
	foreach ($func as $fun) {
		if($fun){
			$results = $fun($passed_data);
		}
		if(empty($results)){
		}
		else{
			foreach ($results as $result) {
				echo json_encode($result);
			}
		}
	}

//library of form handling functions
	function bwc_add_transactions($gets){
		$structure = bwc_structure($gets['table']);
		$table = $gets['table'];
		$transactionType = $gets['transactionType'];
		$testmode = $gets['testmode'];

		$data= array();
		foreach ($gets as $key => $value){
			if(in_array($key, $structure)){
				$data[$key] = $value;
			}
		}

		if($transactionType == 'Deposit'){
			$data['weight'] = abs($data['weight']);
			$data['gallons'] = abs($data['gallons']);
			$data['pg'] = abs($data['pg']);
		}
		else {
			$data['weight'] = abs($data['weight'])*-1;
			$data['gallons'] = abs($data['gallons'])*-1;
			$data['pg'] = abs($data['pg'])*-1;
		}

		if($testmode != "yes"){
			$newTransId = bwc_exec_insert($table, $data, null);	
		}
		else{
			$newTransId = "test_id";
			echo 'Insert into table '.$table.' data: ';
			foreach ($data as $key => $value) {
				echo 'key: '.$key.' value: '.$value.'<br/>';
			}
		}
		//create an offsetting transaction for non-inventory deposits/losses	
		$secondTransId = "No offset";
		if($gets['otherTable']!='inventory'){
			if($data['transactionType']=='Deposit' || $data['transactionType']=='Withdrawal'){
				if($testmode != "yes"){
					$secondTransId = bwc_add_offset_transaction($table, $newTransId, $data);
					bwc_exec_update($table, ['offsetTransId'=>$secondTransId], ['transactionId'=>$newTransId]);
				}
				else{
				}
			}
		}

		//send to the bottling function otherwise
		else{
			bwc_bottling($gets);
		}

		return array($table, $newTransId, $secondTransId);
	}

	function bwc_add_offset_transaction($table, $offsetTransId, $data_orig){
		$data = $data_orig;
		if($data_orig['transactionType']=='Deposit'){	
			$data['transactionType']='Withdrawal';
		}
		else if($data['transactionType']=='Withdrawal'){
			$data['transactionType']='Deposit';
		}
		$data["gallons"]=$data_orig['gallons']*-1;
		$data["pg"]=$data_orig['pg']*(-1);
		$data['weight']=$data_orig['weight']*(-1);
		$data['offsetTransId']=$offsetTransId;
		$data['thisId'] = $data_orig['otherId'];
		$data['thisTable'] = $data_orig['otherTable'];
		$data['otherTable'] = $data_orig['thisTable'];
		$data['otherId'] = $data_orig['thisId'];
		$return_new_id = bwc_exec_insert($table, $data, null);
		return $return_new_id;
	}

	function bwc_bottle_for_product($product){
		var_dump($product);
		$empty_bottles = bwc_exec_query("SELECT i_t.* 
			FROM inventory_transactions AS i_t 
			INNER JOIN inventory_items AS i_i ON i_t.item = i_i.itemName
			WHERE i_t.clearingStatus IS NULL 
			AND i_t.transactionType = '0'
			AND i_i.linkedProduct = %s
			ORDER BY i_t.dateTime DESC", array($product));
		$filled_bottles = bwc_exec_query("SELECT i_t.* 
			FROM inventory_transactions AS i_t 
			WHERE i_t.clearingStatus IS NULL 
			AND i_t.transactionType = '0'
			AND i_t.item = %s
			ORDER BY i_t.dateTime DESC", array($product));
		return(array($empty_bottles, $filled_bottles));
	}

	function bwc_bottling($gets){
		$product = $gets['otherId'];
		$testmode = $gets['testmode'];
		list($empty_bottles, $full_bottles) = bwc_bottle_for_product($product);
		var_dump($empty_bottles);
		echo '<br />';
		
		//transactionId: transactionId,subitem=amount/origAmount/vendor/dateTime
		//example: 178,3=1422/2800/Brown's/2016-01-01 12:00:00
		$transactionIds="";
		foreach ($empty_bottles as $bottles) {
			$transactionIds.=$bottles->transactionId.",".$bottles->subitem."=".$bottles->amount."/".$bottles->origAmount."/".$bottles->vendor."/".$bottles->dateTime."|";
		}
		$transactionIds=chop($transactionIds,"|");
		
		if($testmode == 'yes'){
				echo 'bottles transaction id '.$transactionIds;
		}

		$gets2 = [
			"table"=>"inventory_transactions",
			"clearingAmount"=>$gets['bottles'],
			"clearingReason"=>"Production",
			"clearingDate"=>$gets['dateTime'],
			"transactionId"=>$transactionIds,
			"item"=>$empty_bottles['item'],
			];

		//clearing empty bottles
		if($testmode != 'yes'){
			bwc_inventory_table_update($gets2);//table,clearingAmount,clearingDate,item,clearingReason,transactionId
		}
		else{
			echo 'clearing empty bottles to inventory <br/>';
			foreach ($gets2 as $key => $value) {
				echo 'key: '.$key.' value: '.$value;
			}
		}

		$gets3 = [
			"table"=>"inventory_transactions",
			"transactionType"=>'0',
			"item"=>$product,
			"amount"=>$gets['bottles'],
			"origAmount"=>$gets['bottles'],
			"dateTime"=>$gets['dateTime'],
			"vendor"=>"Baltimore Whiskey Company",
			];
		//adding filled bottles
		if($testmode != 'yes'){
			bwc_table_insert3($gets3);
		}
		else{
			echo 'adding filled bottles to inventory <br/>';
			foreach ($gets3 as $key => $value) {
				echo 'key: '.$key.' value: '.$value;
			}
		}
	}

	function bwc_structure($table){
		$row = bwc_exec_query("SELECT * FROM ".$table." LIMIT 1", array());
		$structure = array();
		foreach ($row[0] as $key => $value) {
			array_push($structure, $key);
		}
		return $structure;
	}

	function bwc_clear_inventory_transaction($gets){
		// First create clearing transaction
		$withdrawal_transaction = bwc_table_insert3($gets);
		$table = $gets['table'];

		// Then get open deposits - have to find at least one deposit
		$item = $gets['item'];
		$deposits = bwc_exec_query("SELECT *
			FROM  inventory_transactions
			WHERE item = %s
			AND transactionType = '0'
			AND clearingStatus IS NULL
			ORDER BY dateTime", array($item));

		if(!$deposits){
			return false;
		}
		
		// Now evaluate how to split clearing among open deposits
		$clearedAmount = $gets['clearedAmount'];
		$clearedAmountAggregation = $clearedAmount;
		$update = array();
		
		foreach ($deposits as $deposit) {
			$data = array();
			// Accumulate clearing
			if($clearedAmountAggregation >= $deposit->amount){//fully clear the oldest open deposits
				$clearedAmountAggregation 		= $clearedAmountAggregation - $deposit->amount;
				$data['clearingStatus']	 		= $gets['clearingStatus'];
				$data['clearingDate'] 			= $gets['clearingDate'];
				$data['clearingReason'] 		= $gets['clearingReason'];
				$data['clearingTransaction']	= $withdrawal_transaction;
				$data['clearedAmount'] 			= $deposit->amount;
				$where['transactionId'] 		= $deposit->transactionId;
				$where['subitem'] 				= $deposit->subitem;
				
				//Update databse for full clearing
				bwc_exec_update($table, $data, $where);
			}
			else{//partially clear deposits when the remaining clearing amount cannot fully clear
				$data = array();
				
				$remainingOpen = $deposit->amount - $clearedAmountAggregation;
				$next_subitem = $deposit->subitem + 1;

				$data['clearingStatus'] 	= $gets['clearingStatus'];
				$data['clearingDate'] 		= $gets['clearingDate'];
				$data['clearingReason'] 	= $gets['clearingReason'];
				$data['clearedAmount'] 		= $clearedAmountAggregation;
				$where['transactionId'] 	= $deposit->transactionId;
				$where['subitem'] 			= $deposit->subitem;

				//Update database for partial clearing
				bwc_exec_update($table, $data, $where);

				//Create the subitem
				if($remainingOpen > 0){
					$data = array();
					$data['transactionId'] 	= $deposit->transactionId;
					$data['subitem'] 		= $next_subitem;
					$data['amount'] 		= $remainingOpen;
					$data['origAmount'] 	= $deposit->origAmount;
					$data['dateTime'] 		= $deposit->dateTime;

					//Update database with new, reduced deposit
					$new_open = bwc_exec_insert($table, $data, "");
				}
			}
		}
		return array($new_open);
	}

	function bwc_reverse_clear_inventory_transaction($gets){
		$withdrawal_transactionId = $gets['transactionId'];
		$clearedAmount = $gets['clearedAmount'];
		$clearedAmountAggregation = $clearedAmount;

		// First reopen deposits
		$cleared_deposits = bwc_exec_query("UPDATE inventory_transactions
			SET clearingTransaction = NULL, clearingDate = NULL, clearingStatus = NULL, clearingReason = NULL, clearedAmount = NULL
			WHERE clearingTransaction = %s
			ORDER BY dateTime", array($withdrawal_transactionId));

		if($cleared_deposits){
			// Then write clearing entry as 'reversed'
			if($bwc_table_update2($gets)){
				return $cleared_deposits;	
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}

	function bwc_table_update2($gets){//'table', 'keys' are the $where columns, other $gets contain values
		$table=$gets['table'];
		$structure = bwc_structure($table);
		$keys = explode(",", $gets['keys']);
		$data=array();
		$where=array();
		foreach ($gets as $key => $value) {
			if(in_array($key, $keys) && $value){
				$where[$key]=$value;
			}
			if(in_array($key, $structure) && $value){
				$data[$key]=$value;
			}
		}
		$result_set=bwc_exec_update($table, $data, $where);
		return(array($result_set));
	}

	function bwc_close_production($gets){
		bwc_exec_update("production", ["clearingStatus"=>1], ["prodId"=>$gets['tableId']]);
		return(["prodId"=>$gets['tableId']]);
	}

	function bwc_delete_transaction2($gets){
		$transactionId = $gets["transactionId"];
		$otherId_array = bwc_exec_query("SELECT transactionId FROM transactions WHERE offsetTransId = %s", array($transactionId));
		$otherId = $otherId_array[0]->transactionId;
		if($otherId){
			return bwc_exec_delete('transactions', ['transactionId'=>$transactionId, 'transactionId'=>$otherId]);
		}
		else{
			return bwc_exec_delete('transactions', ['transactionId'=>$transactionId]);	
		}
	}

	function bwc_delete_transaction($gets){
		bwc_exec_delete($gets['table'], ["transactionId"=>$gets["transactionId"]]);
		if ($gets['transactionType']=="Deposit" || $gets['transactionType']=="Withdrawal"){
			switch ($gets['table']){
				case 'production':
					break;	
				case 'prosto_transactions':
						$gets['prostoId']=null;
					break;
				case 'storage_transactions':
						$gets['storageId']=null;
					break;
				case 'processing_transactions':
						$gets['storageId']=null;
					break;
			}
			if($gets['prostoId']){
				bwc_exec_delete("prosto_transactions", ["prostoId"=>$gets['prostoId'], "dateTime"=>$gets['dateTime']]);
			}
			else if($gets['storageId']){
				bwc_exec_delete("storage_transactions", ["storageId"=>$gets['storageId'], "dateTime"=>$gets['dateTime']]);
			}
			else if($gets['prostoId']){
				bwc_exec_delete("process_transactions", ["processId"=>$gets['processId'], "dateTime"=>$gets['dateTime']]);
			}
		}
		return(["prodId"=>$gets["prodId"], "processId"=>$gets['processId']]);
	}

	function bwc_transaction_create($gets){
		foreach ($gets as $key => $value) {
			echo $key.' | '.$value.'</br>';
		}
		
		$where4 = array();

		//$table2explode=explode("|", $gets['other']);
		switch ($gets['table']) {//cases of caller
			case 'production':
				$data["prodId"]=$gets['tableId'];
				$table2=false;
				$prodId=$gets['tableId'];
				if($gets['transactionType']=='Loss'){
					$table="prosto_transactions";
				}
				break;
			case 'prosto':
				$data["prostoId"]=$gets['tableId'];
				$table2="prosto_transactions";
				$table4d="prosto";
				break;
			case 'storage':
				$data["storageId"]=$gets['tableId'];
				$table2="storage_transactions";
				$table4d="storage";
				break;
			case 'processing':
				$data["processId"]=$gets['tableId'];
				$table2="process_transactions";
				break;
			default:
				break;
		}
		if(!$gets['otherId']){
			//var_dump($table2explode);
			//$table2explode2=explode(",", $gets['other']);
			switch ($gets['otherTable']){//cases of destination/origin
				case 'prosto':
					$data0["tank"]=$gets["otherTank"];
					$table3="prosto";
					break;
				case 'storage':
					$data0["barrel"]=$gets["otherTank"];
					$table3="storage";
					break;
				case 'fermentation':
					$data0["fermenter"]=$gets["otherTank"];
					$table3="fermentation";
					break;				
				/*case 'processing':
					$data0["tank"]=$gets["otherTank"];
					$table3="processing";
					break;*/
			}
			if($gets['table']=='production'){
				$newType=bwc_exec_query("SELECT prosto_types.prostoType FROM prosto_types INNER JOIN production as p ON p.type = prosto_types.productionType WHERE p.prodId = %s ORDER BY priority ASC LIMIT 1", array($gets['tableId']));
				$data0['type']=$newType[0]->prostoType;
			}
			else{
				$data0['type']=$table2explode2[1];
			}
			//var_dump($table3);
			//var_dump($data0);
			$gets["otherId"]=bwc_exec_insert($table3, $data0, null);//insert new entry if deposit to empty vessel to get new ID
			echo'<br/>new Id is...'.$gets['otherId'];
		}
		
		switch ($gets['otherTable']){//cases of destination/origin
			case 'fermentation':
				$data["fermId"]=$gets['other'];
				$data['table']="ferm_prod_junc";
				$data['gallonsMash']=$gets['weight'];
				$data['clearingStatus']=$gets['clearingStatus'];
				$return_set=bwc_create_ferm_prod_junc($data);
				return($return_set);
				break;
			case 'prosto':
				$data["prostoId"]=$gets["otherId"];
				$table3="prosto";
				$table="prosto_transactions";
				$where4["prostoId"]=$gets["otherId"];
				//echo $table.'</br>';
				break;
			case 'storage':
				$data["storageId"]=$gets["otherId"];
				$table3="storage";
				$table="storage_transactions";
				$where4["storageId"]=$gets["otherId"];
				break;
			case 'processing':
				$data["processId"]=$gets["otherId"];
				$table="process_transactions";
				break;
		}

		$data["transactionType"]=$gets['transactionType'];
		if($data['transactionType']=='Deposit' || $data['transactionType']=='Loss'){
			$data["pg"]=$gets['pg'];		
			$data['weight']=$gets['weight']*(-1);
			echo 'brah!!!';
		}
		else if($data['transactionType']=='Withdrawal' || $data['transactionType']=='Destroyed'){
			$data["pg"]=$gets['pg']*(-1);
			$data['weight']=$gets['weight']*(-1);
		}
		$data["dateTime"]=$gets['dateTime'];
		$data["actProof"]=$gets['actProof'];
		$data["weight"]=$gets['weight'];
		//var_dump($table);
		//var_dump($data);
		bwc_exec_insert($table, $data, null); //this is the new transaction

		//create the offset, inversing the PGs
		if($table2){
			$data2= array_slice($data, 0);
			if($data2['transactionType']=='Deposit' || $data2['transactionType']=='Withdrawal'){
				$data2['pg']*=(-1);
				$data2['weight']*=(-1);
				if($data2['transactionType']=='Deposit'){
					$data2['transactionType']='Withdrawal';
				}
				else if($data2['transactionType']=='Withdrawal'){
					$data2['transactionType']='Deposit';
				}
				//var_dump($table2);
				//var_dump($data2);
				bwc_exec_insert($table2, $data2, null); //this is the offsetting
			}
		}

		if($gets['clearingStatus']==1){
				$data4=array();
				if($gets['transactionType']=='Withdrawal'){
					//var_dump($table3);
					$table4=$table3;
					$where=$gets["otherId"];
					$data4['clearingStatus']=true;
				}
				else if($gets['transactionType']=='Deposit'){
					$table4=$table4d;
					$where=$gets['tableId'];
					$data4['clearingStatus']=true;
				}
				//var_dump($where4);		
			bwc_exec_update($table4, $data4, $where4); //update for clearing
		}
		return(["prodId"=>$prodId, "processId"=>$gets['processId']]);
	}

	function bwc_table_insert_mashbill($gets){//'table', 'keys' are relevant columns used to find values in $gets
		$table=$gets['table'];
		$keys = explode(",", $gets['keys']);
		$fermentable_array=explode(",", $gets['fermentable_array']);
		$gets['fermentable']=$fermentable_array[0];
		$gets['unit']=$fermentable_array[2];
		$gets['fermentable']=$fermentable_array[0];
		$data=array();
		foreach ($gets as $key => $value) {
			if(in_array($key, $keys)){
				$data[$key]=$value;
			}
		}
		$result_set=bwc_exec_insert($table, $data, null);
		$return_array->mashId=$gets['mashId'];
		$return_array->prodID=$gets['prodId'];
		return($return_array);
	}

	function bwc_inventory_table_update($gets){//table, clearingAmount, clearingDate, item, clearingReason, transactionId (string w/ pipe delim)
		//transactionId: transactionId,subitem=amount/origAmount/vendor/dateTime
		//example: 178,3=1422/2800/Brown's/2016-01-01 12:00:00
		$table=$gets['table'];
		$clearingAmount=$gets['clearingAmount'];
		$gets1=['transactionType'=>1, 'dateTime'=>$gets['clearingDate'], 'amount'=>$clearingAmount, 'item'=>$gets['item'], 'clearingReason'=>$gets['clearingReason']];
		
		$clearingTransactionId = (bwc_exec_insert($table, $gets1, null));//'table', 'data'

		$transactionIdsArray=explode("|", $gets['transactionId']);
		foreach ($transactionIdsArray as $transaction) {
			if($clearingAmount>0){//in case clearing is exactly the amount of a transaction
				$new_pair=explode("=",$transaction);
				$id_subitem=explode(",", $new_pair[0]);
				$amount_vendor_origdate=explode("/",$new_pair[1]);
				var_dump($amount_vendor_origdate);
				
				$gets2=['table'=>$table, 
					'new_values'=>'clearedAmount,clearingStatus,clearingReason,clearingDate,clearingTransaction,amount', 
					'keys'=>'transactionId,subitem', 
					'transactionId'=>$id_subitem[0], 
					'clearingDate'=>$gets['clearingDate'],
					'subitem'=>$id_subitem[1], 
					'clearingStatus'=>true, 
					'clearingTransaction'=>$clearingTransactionId, 
					'clearingReason'=>$gets['clearingReason']
				];
				if($amount_vendor_origdate[0]>$clearingAmount){//partial clear
					$gets2['clearedAmount']=$clearingAmount;
					$gets2['amount']=$clearingAmount;

					$gets3=[
						'transactionId'=>$id_subitem[0], 
						'subitem'=>$id_subitem[1]+1, 
						'vendor'=>$amount_vendor_origdate[2], 
						'amount'=>$amount_vendor_origdate[0]-$clearingAmount,
						'dateTime'=>$amount_vendor_origdate[3],
						'item'=>$gets['item'],
						'origAmount'=>$amount_vendor_origdate[1]
					];

					$new_subitem=bwc_exec_insert($table, $gets3, null);

					$clearingAmount=0;
				}
				
				else{//fully clear
					$gets['clearedAmount']=$amount_vendor_origdate[0];
					$clearingAmount=$clearingAmount-$amount_vendor_origdate[0];
				}
				
			bwc_table_update($gets2);//'table', 'keys' are the $where columns, 'new_values' are the $data columns, other $gets contain values
			}//endif clearingAmount>0
		}//endloop through transactions
	}//endfunction

	function bwc_table_update($gets){//'table', 'keys' are the $where columns, 'new_values' are the $data columns, other $gets contain values
		$table=$gets['table'];
		$keys = explode(",", $gets['keys']);
		$new_values=explode(",", $gets['new_values']);
		$data=array();
		$where=array();
		foreach ($gets as $key => $value) {
			if(in_array($key, $keys) && $value){
				$where[$key]=$value;
			}
			if(in_array($key, $new_values) && $value){
				$data[$key]=$value;
			}
		}
		$result_set=bwc_exec_update($table, $data, $where);
		$return_array=array();
		$return_array['prodId']=$gets['prodId'];
		$return_array['mashId']=$gets['mashId'];
		return($return_array);
	}

	function bwc_delete_table3($gets){
		$tables=explode(",", $gets['tables']);
		$where = array();
		$keys=explode("|", $gets['keys']);
		$keys1=explode(",", $keys[0]);
		$keys2=explode(",", $keys[1]);
		
		$where1=array();
		foreach ($gets as $key => $value) {
			if(in_array($key, $keys1)){
				$where1[$key]=$value;
			}
		}
		$where2=array();
		foreach ($gets as $key => $value) {
			if(in_array($key, $keys1)){
				$where2[$key]=$value;
			}
		}
			
		$result_set=bwc_exec_delete($tables[0], $where1);
		$result_set=bwc_exec_delete($tables[1], $where2);
		$return_set=["prodId"=>$gets['prodId']];
		$return_set->prodId=$gets['prodId'];
		return($return_set);
	}

	function bwc_delete_table2($gets){
		$table=$gets['table'];
		$where = array();
		$keys=explode(",", $gets['keys']);
		$where=array();
		foreach ($gets as $key => $value) {
			if(in_array($key, $keys)){
				$where[$key]=$value;
			}
		}
			
		$result_set=bwc_exec_delete($table, $where);
		$return_set = new stdClass();
		//$return_set=["mashId"=>$gets['mashId'], "prodId"=>$gets['prodId']];
		if($gets['mashId']){
			$return_set->mashId=$gets['mashId'];
		}
		if($gets['prodId']){
			$return_set->prodId=$gets['prodId'];
		}
		if($gets['processId']){
			$return_set->processId=$gets['processId'];
		}
		if($return_set){
			return($return_set);
		}
		else {
			return(array(null));
		}
	}

	function bwc_delete_table($gets){
		$table=$gets['table'];
		$where = array();
		$keysvalues=explode("/", $gets['keys']);
		$where=array();
		foreach ($keysvalues as $keyvalue) {
			$pair=explode("=", $keyvalue);
			$where[$pair[0]]=$pair[1];
		}
		var_dump($where);
		$result_set=bwc_exec_delete($table, $where);
		$return_set=array();
		$return_set['mashId']=$gets['mashId'];
		$return_set['prodId']=$gets['prodId'];
		return($return_set);
	}

	function bwc_table_insert($gets){//'table', 'keys' are relevant columns used to find values in $gets
		$table=$gets['table'];
		$keys = explode(",", $gets['keys']);
		$data=array();
		foreach ($gets as $key => $value) {
			if(in_array($key, $keys)){
				$data[$key]=$value;
			}
		}
		$result_set=bwc_exec_insert($table, $data, null);
		return($result_set);
	}

	function bwc_table_insert2($gets){
		foreach ($gets as $key => $value) {
			if($key == 'table'){
				$table=$value;
			}
			else{
				if($key!='func' && $key!='function'){
					$data[$key]=$value;
				}
			}
		}
		$returnId = bwc_exec_insert($table, $data, null);
		return array($returnId);
	}


	function bwc_table_insert3($gets){
		$structure = bwc_structure($gets['table']);
		$table = $gets['table'];
		$data = array();

		foreach ($gets as $key => $value){
			if(in_array($key, $structure)){
				$data[$key] = $value;
			}
		}
		var_dump($data);
		$returnId = bwc_exec_insert($table, $data, null);
		return array($returnId);
	}

	function bwc_new_mash($gets){
		$table=$gets['table'];
		$data = array();
		foreach ($gets as $key => $value) {
			if($key=='dateTime'){
				$data[$key]=$value;
			}
		}
		
		$result_set=bwc_exec_insert($table, $data, null);
		$id_string=strval($result_set);	
	}
?>