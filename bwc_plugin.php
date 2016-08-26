<?php

/**
 * The main plugin file.
 * 
 * This file loads the main plugin class and gets things running.
 *
 * @since 0.2.6
 * 
 * @package baltimorebrandyco
 */

/**
 * Plugin Name: baltimorebrandyco
 * Description: BWC's badass Backend
 * Author:      Ian Newton
 * Author URI:  http://iananewton.com
 * Version:     55
 * Text Domain: baltimorebrandyco
 * Domain Path: /languages/
 */

function bwc_exec_query($querystr1, $idarray){//comments here can be helpful on development
	global $wpdb;
	wp_reset_query();
	$return = $wpdb->get_results($wpdb->prepare($querystr1, $idarray));
	// $wpdb->show_errors();
	// $wpdb->print_error();
	return $return;
}

function bwc_exec_query_show($querystr1, $idarray, $show_errors = null){//comments here can be helpful on development
	global $wpdb;
	wp_reset_query();
	$return = $wpdb->get_results($wpdb->prepare($querystr1, $idarray));
	if($show_errors){
		$wpdb->show_errors();
		$wpdb->print_error();	
	}
	return $return;
}

function bwc_exec_query2($querystr1, $idarray, $errors, $output_type){//comments here can be helpful on development
	global $wpdb;
	wp_reset_query();
	$return = $wpdb->get_results($wpdb->prepare($querystr1, $idarray), $output_type);
	if($errors){
		$wpdb->show_errors();
		$wpdb->print_error();
	}
	return $return;
}

function bwc_exec_insert($table, $data, $format){
	global $wpdb;
	//var_dump($table);
	//var_dump($data);
	$return = $wpdb->insert($table, $data);
	// $wpdb->show_errors();
	// $wpdb->print_error();
	$return_new_id = strval($wpdb->insert_id);
	return $return_new_id;
}

function bwc_exec_update($table, $data, $where){
	global $wpdb;
	$return = $wpdb->update($table, $data, $where);
	// $wpdb->show_errors();
	// $wpdb->print_error();
	return $return;
}

function bwc_exec_delete($table, $where){
	global $wpdb;
	$return = $wpdb->delete($table, $where);
	//$wpdb->show_errors();
	//$wpdb->print_error();
	return $return;	
}

function bwc_be_inventory_items_query(){
	$inventory_items=bwc_exec_query("SELECT ii.itemName as item, ii.*, it.*, iit.* 
		FROM inventory_items as ii
		LEFT JOIN (
			SELECT t.item AS titem, sum(amount) FROM inventory_transactions as t WHERE t.clearingStatus IS NULL
			AND t.transactionType = 0
			GROUP BY t.item)
			AS it ON ii.itemName = it.titem 
		INNER JOIN inventory_item_types AS iit ON iit.subtype = ii.itemType 
		ORDER BY iit.type, ii.itemName", array());
	return (array($inventory_items));
}

function bwc_be_inventory_item_read($gets){
	$item = $gets['item'];
	$item_details = bwc_exec_query("SELECT inventory_transactions.item, SUM(IFNULL(amount,0)) as item_stock, inventory_items.*, inventory_item_types.* 
		FROM inventory_transactions 
		INNER JOIN inventory_items ON inventory_items.itemName = inventory_transactions.item 
		INNER JOIN inventory_item_types ON inventory_item_types.subtype = inventory_items.itemType 
		WHERE inventory_items.itemName = %s
		AND inventory_transactions.clearingStatus IS NULL 
		AND inventory_transactions.transactionType = 0", array($item));
	return (array($item_details));
}

function bwc_be_inventory_deposit_transactions_query($gets){
	$item = $gets['item'];
	$deposits = bwc_exec_query("SELECT inventory_transactions.* 
		FROM inventory_transactions 
		WHERE inventory_transactions.item = %s
		AND inventory_transactions.transactionType = 0
		ORDER BY inventory_transactions.transactionId DESC, inventory_transactions.subitem DESC", array($item));
	return (array($deposits));
}

function bwc_be_inventory_withdrawal_transactions_query($gets){
	$item = $gets['item'];
	$withdrawals = bwc_exec_query("SELECT inventory_transactions.* 
		FROM inventory_transactions 
		WHERE inventory_transactions.item = %s
		AND inventory_transactions.transactionType = 1
		ORDER BY inventory_transactions.transactionId DESC, inventory_transactions.subitem DESC", array($item));
	return (array($withdrawals));
}

function bwc_be_inventory_transactions($gets){
	$item = $gets['item'];
	$inventory_transactions = bwc_exec_query("SELECT * FROM inventory_transactions
		WHERE item = %s
		AND clearingStatus IS NULL
		ORDER BY dateTime, parentId", array($item));
	
	return (array($inventory_transactions));
}

function bwc_be_inventory_queries(){
	$inventories=bwc_exec_query("SELECT type as master_type FROM inventory_item_types GROUP BY master_type", array());
	$inventory_items=bwc_exec_query("SELECT inventory_transactions.item, SUM(IFNULL(amount,0)) as item_stock, inventory_items.*, inventory_item_types.* 
		FROM inventory_transactions 
		INNER JOIN inventory_items ON inventory_items.itemName = inventory_transactions.item 
		INNER JOIN inventory_item_types ON inventory_item_types.subtype = inventory_items.itemType 
		WHERE inventory_transactions.clearingStatus IS NULL 
		AND inventory_transactions.transactionType = 0 
		GROUP BY inventory_transactions.item 
		ORDER BY inventory_item_types.type, inventory_transactions.item", array());

	$open_transactionIds=bwc_exec_query("SELECT transactionId, subitem, item, amount, vendor, dateTime, origAmount 
		FROM inventory_transactions 
		WHERE inventory_transactions.clearingStatus IS NULL 
		AND inventory_transactions.transactionType = 0 
		GROUP BY transactionId, subitem 
		ORDER BY item, dateTime, subitem", array());

	$cleared_transactionIds_query="SELECT a.transactionId as orig_transactionId, a.item as orig_item, c.* from inventory_transactions a
		INNER JOIN inventory_transactions b ON b.transactionId = a.transactionId
		INNER JOIN inventory_transactions c ON c.transactionId = b.clearingTransaction
		WHERE (a.clearingStatus IS NULL) 
		ORDER BY orig_item, a.subitem DESC, a.dateTime";

	$cleared_transactions=bwc_exec_query($cleared_transactionIds_query, array());

	foreach ($inventories as $inventory) {
		$items_array=array();
		foreach ($inventory_items as $inventory_item) {
			if($inventory_item->type==$inventory->master_type){	
				$open_transactions_array = array();
				foreach ($open_transactionIds as $open_transaction) {
					$cleared_transactions_array =array();
					foreach ($cleared_transactions as $cleared_transaction) {
						if($cleared_transaction->orig_transactionId == $open_transaction->transactionId){
							array_push($cleared_transactions_array, $cleared_transaction);
						}
					}
					$open_transaction->clearedtransactions=$cleared_transactions_array;
					if($open_transaction->item == $inventory_item->item){
						array_push($open_transactions_array, $open_transaction);
					}	
				}
				$inventory_item->transactionIds=$open_transactions_array;
				array_push($items_array, $inventory_item);
			}
		}
		$inventory->inventory_items=$items_array;
	}
	return (array($inventories));
}

function bwc_be_show_inventory($inventories, $inventory_items, $inventory_clearing_reasons, $vendors){
	
	foreach ($inventories as $inventory_type) {
		echo '<div class="large-12 columns">
			<fieldset class="bwc_fieldset">
				<legend class="bwc_legend">'.$inventory_type->master_type.'</legend>';
				foreach ($inventory_type->inventory_items as $inventory) {
					echo '<div class="large-4 columns">
						<form id="bwc_manage_inventory" class="bwc_form">
								<fieldset name="inventory" class="bwc_fieldset">
									<legend class="bwc_legend">'.$inventory->item.' - '.$inventory->itemType.'</legend>
									<input type="text" name="table" value="inventory_transactions" style="display:none"/>
									<input type="text" name="keys" value="transactionId" style="display:none"/>
									<input type="text" name="new_values" value="date" style="display:none"/>
									<input type="text" name="function" value="bwc_inventory_table_update" style="display:none"/>
									<label for="Open Stock transactions" class="nerdy_p">Total Inventory Stock</label>
									<output class="bwc_red">'.$inventory->item_stock.'</output>
									<label for="Open transactions" class="nerdy_p">Open transactions</label>						
									<table class="bwc_be_table">
										<tr><th class="bwc_red" colspan="2">Trans Date</th><th class="bwc_red">Orig Amount</th><th class="bwc_red">Vendor</th></tr>
										<tr><th colspan="2">Trans Date</th><th>Clear Amt</th><th>Clear Reason</th></tr>';
									$transactionIds="";
									foreach ($inventory->transactionIds as $transactionId) {
										if($transactionId->item == $inventory->item){
											$transactionIds.=$transactionId->transactionId.",".$transactionId->subitem."=".$transactionId->amount."/".$transactionId->origAmount."/".$transactionId->vendor."/".$transactionId->dateTime."|";
											echo'<tr><td class="bwc_red" colspan="2">'.substr($transactionId->dateTime, 0, 10).'</td><td class="bwc_red">'.$transactionId->origAmount.'</td><td class="bwc_red">'.$transactionId->vendor.'</td></tr>';
											foreach ($transactionId->clearedtransactions as $clearedTransaction) {
												echo'<tr><td colspan="2">'.substr($clearedTransaction->dateTime, 0, 10).'</td><td>'.$clearedTransaction->amount.'</td><td>'.$clearedTransaction->clearingReason.'</td></tr>';
											}
										}
									}
									$transactionIds=chop($transactionIds,"|");
									echo '</table>
									<input type="text" name="transactionId" value="'.$transactionIds.'" style="display:none"/>

									<label for="clearingReason" class="nerdy_p">Clearing Reason</label>
									<select name="clearingReason" id="clearingReason"  required>
										<option value="" >Choose clearing reason...</option>';
										foreach ($inventory_clearing_reasons as $inventory_clearing_reason) {
											echo '<option>'.$inventory_clearing_reason->clearingReasons.'</option>';
										}
									echo '</select>
									<label for="clearingAmount" class="nerdy_p">Clearing Amount</label>
									<input type="decimal" name="clearingAmount" min="0" max="1000000" step="0.01"/>
									<label for="clearingDate" class="nerdy_p">Clearing Date</label>
									<input type="datetime-local" name="clearingDate" />
									<input type="text" name="item" value="'.$inventory->item.'" style="display:none"/>
									<input type="text" name="vendor" value="'.$transactionId->vendor.'" style="display:none"/>						
									<input type="text" name="origDate" value="'.$transactionId->dateTime.'" style="display:none"/>						
									<input type="submit" name="bwc_inventory_transactions_update" id="bwc_inventory_transactions_update" value="Clear Inventory" />
								</fieldset>	
							</form>
					</div>';
				}
			echo '</fieldset>
		</div>';
	}
	echo '<div class="large-4 columns">
		<form id="bwc_add_inventory" class="bwc_form">
			<fieldset class="bwc_fieldset">
				<legend class="bwc_legend">Add Inventory Stock</legend>
				<input type="text" name="table" value="inventory_transactions" style="display:none" />
				<label for="dateTime" class="nerdy_p">Date</label>
				<input type="datetime-local" name="dateTime" />
				<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
				<label for="item" class="nerdy_p">Item</label>
				<select name="item" id="item"  required>
					<option value="" >Choose item...</option>';
					foreach ($inventory_items as $inventory_item) {
						echo '<option>'.$inventory_item->itemName.'</option>';
					}
				echo '</select>
				<label for="amount" class="nerdy_p">Amount</label>
				<input type="number" name="amount" step="0.01" min="-1000000" max="1000000"/>
				<label for="origAmount" class="nerdy_p">Orig Amount</label>
				<input type="number" name="origAmount" step="0.01" min="-1000000" max="1000000" readonly/>
				<label for="vendor" class="nerdy_p">Vendor</label>
				<select name="vendor" id="vendor">
					<option value="" >Choose vendor...</option>';
					foreach ($vendors as $vendor) {
						if($vendor->type=="Raw Materials"){
							echo '<option>'.$vendor->vendorName.'</option>';
						}
					}
				echo '</select>	
				<input type="submit" id="inventory_new_button" value="Add Inventory Item" />
			</fieldset>
		</form>
	</div>';
}

function bwc_be_ajax_mash_queries(){
	$mashes = bwc_exec_query("SELECT m.* FROM mash as m ORDER BY m.dateTime DESC LIMIT 100", null);
	return array($mashes);
}

function bwc_be_ajax_mash_single($gets){
	$mashId = $gets['mashId'];
	$mash = bwc_exec_query("SELECT * FROM mash WHERE mashId = %s", $mashId);
	return array($mash);	
}

function bwc_be_ajax_mash_bill_queries($gets){
	$mashId = $gets['mashId'];
	$mashbills = bwc_exec_query("SELECT * FROM mashbill WHERE mashId = %s", $mashId);
	return array($mashbills);
}

function bwc_be_ajax_mash_steps($gets){
	$mashId = $gets['mashId'];
	$mash_steps = bwc_exec_query("SELECT ms.*, ms.dateTime as tdateTime FROM mash_steps as ms WHERE mashId = %s", $mashId);
	return array($mash_steps);
}

function bwc_be_ajax_mash_enzyme_additions($gets){
	$mashId = $gets['mashId'];
	$mash_enzymes = bwc_exec_query("SELECT * FROM mash_enzyme_additions WHERE mashId = %s", $mashId);
	return array($mash_enzymes);
}

function bwc_ajax_fermenters(){
	$fermenters = bwc_exec_query("SELECT * FROM fermenter_master LIMIT 100", array());
	return array($fermenters);
}

function bwc_ajax_fermentation_mass(){
	$fermentation_mass = bwc_exec_query("SELECT * FROM fermentation ORDER BY clearingStatus, dateTime DESC LIMIT 100", array());
	return array($fermentation_mass);
}

function bwc_fetch_transactions_single($gets){
	$thisTable = $gets["thisTable"];
	$thisId = $gets["thisId"];
	$results = bwc_exec_query("SELECT t.*, t.dateTime as td_dateTime FROM transactions as t WHERE thisTable = %s and thisId = %s", array($thisTable, $thisId));
	return array($results);
}

function bwc_transactions_selections(){
	//$mashes = bwc_exec_query("SELECT mashId as _id, dateTime as dt, type FROM mash ORDER BY mashId DESC", array());
	$fermenters = bwc_exec_query("SELECT f.fermenterName as tankId, f.fermenterName as tankName, e.fermId as _id, e.dateTime as dt, e.type as type FROM `fermenter_master` as f LEFT JOIN (SELECT * FROM `fermenter_master` as a LEFT JOIN fermentation as b ON a.fermenterName = b.fermenter where b.clearingStatus IS NULL ) as e ON f.fermenterName = e.fermenter ORDER BY e.fermId DESC", array());
	//$prods = bwc_exec_query("SELECT p.prodId as _id, p.dateTime as dt, p.type as type FROM production as p ORDER BY prodId DESC", array());
	$prostos = bwc_exec_query("SELECT pt.tankId as tankId, pt.tankName as tankName, p.prostoId as _id, p.type as type, p.dateTime as dt FROM production_tanks_master as pt LEFT JOIN (SELECT * FROM production_tanks_master as q LEFT JOIN prosto as r ON q.tankId = r.tank where r.clearingStatus IS NULL) as p ON p.tank = pt.tankId ORDER BY p.prostoId DESC", array());
	$storages = bwc_exec_query("SELECT b.barrelName as tankName, b.barrelId as tankId, e.storageId as _id, e.type as type FROM barrel_master as b LEFT JOIN (SELECT * FROM barrel_master as c LEFT JOIN storage as d on c.barrelId = d.barrel WHERE d.clearingStatus IS NULL) as e ON b.barrelId = e.barrelId ORDER BY e.storageId DESC", array());
	//$products = bwc_exec_query("SELECT product as type FROM spirit_classes", array());
	$return_array = [
		"fermenter_master"=>$fermenters,
		"production_tanks_master"=>$prostos,
		"barrel_master"=>$storages
		];
	return array($return_array);
}

function bwc_ajax_fermenter_single(){
	$fermenters = bwc_exec_query("SELECT fermenter_master.*, fermentation.*, fermentation.type as fermType FROM fermenter_master LEFT JOIN fermentation ON fermentation.fermenter = fermenter_master.fermenterName LIMIT 100", array());
	return array($fermenters);
}

function bwc_ajax_fermentation_single($gets){
	$fermId = $gets["fermId"];
	$ferm = bwc_exec_query("SELECT * FROM fermentation WHERE fermId = %s", array($fermId));
	return array($ferm);
}

function bwc_ajax_ferm_time_temps($gets){
	$fermId = $gets["fermId"];
	$ferm_time_temps = bwc_exec_query("SELECT ft.*, ft.dateTime as td_dateTime FROM ferm_time_temp as ft WHERE fermId = %s", array($fermId));
	return array($ferm_time_temps);
}

/*function bwc_ajax_production_single($gets){
	$prod = bwc_exec_query("SELECT p.* FROM production as p WHERE p.prodId = %s", array($gets['prodId']));
	return array($prod);
}*/

function bwc_ajax_production_single($gets){
	$prod = bwc_exec_query("SELECT p.*, j1.pg_measurements, j1.pg_weight FROM production as p 
		LEFT JOIN (
	        SELECT p2.prodId, sum(pm2.pg) as pg_measurements, sum(pm2.weight) as pg_weight
	        	FROM production as p2 
	        	INNER JOIN production_measurements as pm2
	        		ON p2.prodId = pm2.prodId
	    			GROUP BY p2.prodId)
			AS j1 ON j1.prodId = p.prodId
		    WHERE p.prodId = %s", array($gets['prodId']));
	return array($prod);
}

function bwc_be_ajax_prod_headers_query(){
	$prod_query = "SELECT pr.*, j.totalPg from production as pr LEFT JOIN
	(SELECT p.prodId, sum(t.pg) as totalPg FROM production as p LEFT JOIN transactions as t ON t.thisId = p.prodId WHERE t.thisTable = 'production' GROUP BY p.prodId)
	as j ON j.prodId = pr.prodId

GROUP BY pr.prodId ORDER BY pr.dateTime DESC LIMIT 100";
	$prod_headers = bwc_exec_query($prod_query, array(""));
	if(!$prodId){
		$prodId = $prod_headers[0]->prodId;
	}
	return array($prod_headers);
}
function bwc_ajax_prod_measurements($gets){
	$prodId=$gets["prodId"];
	$measurements = bwc_exec_query("SELECT pm.*, pm.dateTime as tdateTime FROM production_measurements as pm WHERE prodId = %s", array($prodId));
	return array($measurements);
}

function bwc_ajax_prosto_tanks(){
	$prosto_tanks = bwc_exec_query("SELECT * FROM production_tanks_master LEFT JOIN prosto ON prosto.tank = production_tanks_master.tankId GROUP BY production_tanks_master.tankId LIMIT 100", array());
	return array($prosto_tanks);
}

function bwc_ajax_prosto_mass($gets){
	$prostos = bwc_exec_query("SELECT p.*, t.tankName as tankName, tr.totalPg FROM prosto as p INNER JOIN production_tanks_master as t ON p.tank = t.tankId 
LEFT JOIN 
	(SELECT pi.*, sum(ti.pg) as totalPg FROM prosto as pi LEFT JOIN transactions AS ti ON pi.prostoId = ti.thisId WHERE ti.thisTable = 'prosto' GROUP BY pi.prostoId) 
    AS tr ON p.prostoId = tr.prostoId ORDER BY p.clearingStatus ASC, p.prostoId DESC", array(null));
	$output = $gets['output'];
	switch($output){
		case "obj":
			return $prostos;
			break;
		default:
			return array($prostos);
			break;
	}
}

function bwc_ajax_prosto_single($gets){
	$prostoId = $gets["prostoId"];
	$prostos = bwc_exec_query("SELECT p.*, t.tankName as tankName 
		FROM prosto as p 
		INNER JOIN production_tanks_master as t ON p.tank = t.tankId WHERE p.prostoId = %s", array($prostoId));
	return array($prostos);
}

function bwc_ajax_production_storage_tanks(){
	$prosto_tanks = bwc_exec_query("SELECT pt.*, j.*, j.clearingStatus as clearingStatus_img
		FROM production_tanks_master AS pt
		LEFT JOIN (
			SELECT pr.* 
			FROM production_tanks_master AS pt2
			LEFT JOIN prosto AS pr
			ON pr.tank = pt2.tankId
		) AS j
		ON j.tank = pt.tankId
		GROUP BY pt.tankId 
		ORDER BY j.clearingStatus
		LIMIT 100", array());
	return array($prosto_tanks);
}

function bwc_production_tank_single(){
	$production_tank = bwc_exec_query("SELECT pt.*
		FROM production_tanks_master AS pt
		WHERE pt.tankId = %s", array());
	return array($production_tank);
}

function bwc_tank_prostos($gets){
	$tankId = $gets['tankId'];
	$tank_prostos = bwc_exec_query("SELECT pr.*, pt.tankName AS tankName
		FROM prosto 
		INNER JOIN production_tanks_master AS pt
		ON pr.tank = pt.tankId
		LEFT JOIN (
			SELECT sum(pg) AS totalPg
			FROM transactions
			WHERE thisTable = 'prosto'
			GROUP BY thisId
			) AS t ON t.thisId = pr.prostoId
		WHERE pt.tankId = %s", array($tankId));
	return array($tank_prostos);
}

function bwc_ajax_barrels(){
	$barrels = bwc_exec_query("SELECT * FROM barrel_master LEFT JOIN storage ON storage.barrel = barrel_master.barrelId WHERE barrel_master.retired IS NULL GROUP BY barrel_master.barrelId LIMIT 100", array());
	return array($barrels);
}

function bwc_ajax_storage_mass($gets){
	$storage = bwc_exec_query("SELECT s.*, b.barrelName, tr.totalPg FROM storage as s INNER JOIN barrel_master as b ON s.barrel = b.barrelId 
LEFT JOIN (
    SELECT si.*, sum(ti.pg) as totalPg FROM storage as si LEFT JOIN transactions AS ti ON si.storageId = ti.thisId WHERE ti.thisTable = 'storage' GROUP BY si.storageId
    ) AS tr ON s.storageId = tr.storageId ORDER BY s.clearingStatus, s.dateTime, s.type", array());
	$output = $gets['output'];
	switch($output){
		case "obj":
			return $storage;
			break;
		default:
			return array($storage);
			break;
	}
}

function bwc_ajax_storage($gets){
	$storageId = $gets["storageId"];
	$storage = bwc_exec_query("SELECT s.*, b.barrelName FROM storage as s INNER JOIN barrel_master as b ON s.barrel = b.barrelId WHERE storageId = %s", array($storageId));
	return array($storage);
}

function bwc_ajax_storage_transactions($gets){
	$storageId = $gets["storageId"];
	$storage_transactions = bwc_exec_query("SELECT * FROM storage_transactions WHERE storageId = %s", array($storageId));
	return array($storage_transactions);
}

function bwc_ajax_storage_notes($gets){
	$storageId = $gets["storageId"];
	$storage_notes = bwc_exec_query("SELECT * FROM storage_notes WHERE storageId = %s", array($storageId));
	return array($storage_notes);
}

function bwc_ajax_processing_mass($gets){
	$process_headers = bwc_exec_query("SELECT * FROM processing ORDER BY dateTime DESC LIMIT 100", array());
	$output = $gets['output'];
	switch($output){
		case "obj":
			return $process_headers;
			break;
		default:
			return array($process_headers);
			break;
	}
}

function bwc_ajax_processing_single($gets){
	$processId = $gets["processId"];
	$processing = bwc_exec_query("SELECT * FROM processing WHERE processId = %s", array($processId));
	return array($processing);
}

function bwc_ajax_process_transactions($gets){
	$processId = $gets["processId"];
	$process_transactions = bwc_exec_query("SELECT * FROM process_transactions WHERE processId = %s", array($processId));
	return array($process_transactions);
}

// function bwc_transaction_tree($gets){
// 	$thisId = $gets["thisId"];
// 	$thisTable = $gets["thisTable"];
// 	$ids = bwc_exec_query("SELECT 
// 		l1.thisId as tid1, l1.thisTable as tt1, l1.otherId as oid1, l1.otherTable as ot1, 
// 		l2.thisId as tid2, l2.thisTable as tt2, l2.otherId as oid2, l2.otherTable as ot2, 
// 		l3.thisId as tid3, l3.thisTable as tt3, l3.otherId as oid3, l3.otherTable as ot3, 
// 		l4.thisId as tid4, l4.thisTable as tt4, l4.otherId as oid4, l4.otherTable as ot4, 
// 		l5.thisId as tid5, l5.thisTable as tt5, l5.otherId as oid5, l5.otherTable as ot5, 
// 		l6.thisId as tid6, l6.thisTable as tt6, l6.otherId as oid6, l6.otherTable as ot6, 
// 		l7.thisId as tid7, l7.thisTable as tt7, l7.otherId as oid7, l7.otherTable as ot7, 
// 		l8.thisId as tid8, l8.thisTable as tt8, l8.otherId as oid8, l8.otherTable as ot8, 
// 		l9.thisId as tid9, l9.thisTable as tt9, l9.otherId as oid9, l9.otherTable as ot9 
// 		FROM transactions as l1
// 		LEFT JOIN transactions as l2 ON l2.thisId = l1.otherId AND l2.thisTable = l1.otherTable AND (l2.otherId <> l1.thisId AND l2.otherTable <> l1.thisTable)
// 		LEFT JOIN transactions as l3 ON l3.thisId = l2.otherId AND l3.thisTable = l2.otherTable AND (l3.otherId <> l2.thisId AND l3.otherTable <> l2.thisTable)
// 		LEFT JOIN transactions as l4 ON l4.thisId = l3.otherId AND l4.thisTable = l3.otherTable AND (l4.otherId <> l3.thisId AND l4.otherTable <> l3.thisTable)
// 		LEFT JOIN transactions as l5 ON l5.thisId = l4.otherId AND l5.thisTable = l4.otherTable AND (l5.otherId <> l4.thisId AND l5.otherTable <> l4.thisTable)
// 		LEFT JOIN transactions as l6 ON l6.thisId = l5.otherId AND l6.thisTable = l5.otherTable AND (l6.otherId <> l5.thisId AND l6.otherTable <> l5.thisTable)
// 		LEFT JOIN transactions as l7 ON l7.thisId = l6.otherId AND l7.thisTable = l6.otherTable AND (l7.otherId <> l6.thisId AND l7.otherTable <> l6.thisTable)
// 		LEFT JOIN transactions as l8 ON l8.thisId = l7.otherId AND l8.thisTable = l7.otherTable AND (l8.otherId <> l7.thisId AND l8.otherTable <> l7.thisTable)
// 		LEFT JOIN transactions as l9 ON l9.thisId = l8.otherId AND l9.thisTable = l8.otherTable AND (l9.otherId <> l8.thisId AND l9.otherTable <> l8.thisTable)
// 		WHERE l1.thisId = %s and l1.thisTable = 'processing'", array($thisId, $thisTable));
// 	return ($ids);
// }

function bwc_config_queries(){
	$vendors_query = "SELECT * FROM vendors ORDER BY vendorName";	
	$vendors = bwc_exec_query($vendors_query, array());

	$mash_step_types_query ="SELECT * FROM mash_step_types ORDER BY mashStepTypes";
	$mash_step_types = bwc_exec_query($mash_step_types_query, array());

	$fermentables_query ="SELECT * FROM fermentables_master ORDER BY fermentableType, fermentableName";
	$fermentables = bwc_exec_query($fermentables_query, array());	

	$yeast_master_query = "SELECT * FROM yeast_master ORDER BY yeastName";
	$yeast_masters = bwc_exec_query($yeast_master_query, array());

	$production_measurement_types_query = "SELECT * FROM production_measurement_types";
	$production_measurement_types = bwc_exec_query($production_measurement_types_query, array());

	$withdrawal_reasons_query = "SELECT * FROM withdrawal_reasons";
	$withdrawal_reasons = bwc_exec_query($withdrawal_reasons_query, array());

	$production_origins_query = "SELECT * FROM production_origin";
	$production_origins = bwc_exec_query($production_origins_query, array());
	$units=bwc_exec_query("SELECT * FROM units", array());

	$inventory_clearing_reasons=bwc_exec_query("SELECT * FROM inventory_clearing_reasons", array());
	$inventory_items=bwc_exec_query("SELECT * FROM inventory_items", array());
	$enzyme_masters = bwc_exec_query("SELECT * FROM enzyme_master", array());
	$processing_origins=bwc_exec_query("SELECT * FROM processing_origins", array());
	$spirit_classes=bwc_exec_query("SELECT * FROM spirit_classes", array());
	$mash_types=bwc_exec_query("SELECT * FROM mash_types", array());
	$production_types=bwc_exec_query("SELECT * FROM production_types", array());
	$prosto_transaction_types=bwc_exec_query("SELECT * FROM prosto_transaction_types", array());
	$prosto_types=bwc_exec_query("SELECT * FROM prosto_types", array());
	$fermenters=bwc_exec_query("SELECT * FROM fermenter_master", array());
	return array($vendors, $mash_step_types, $fermentables, $yeast_masters, $production_measurement_types, $withdrawal_reasons, $production_origins, $units, $inventory_items, $inventory_clearing_reasons, $enzyme_masters, $processing_origins, $spirit_classes, $mash_types, $production_types, $prosto_transaction_types, $prosto_types, $fermenters);
}

function bwc_transactions_mass($gets){
	$transactions = bwc_exec_query_show("SELECT *
		FROM transactions as t", array(null));
	$output = $gets['output'];
	switch($output){
		case "obj":
			return $transactions;
			break;
		default:
			return array($transactions);
			break;
	}
}

function bwc_transactions_all($gets){
	// $transactions_join = new stdClass();
	$gets['output'] = "obj";
	$transactions_join = (object)[
		"transactions"=>bwc_transactions_mass($gets),
		"prosto"=>bwc_ajax_prosto_mass($gets),
		"storage"=>bwc_ajax_storage_mass($gets),
		"processing"=>bwc_ajax_storage_mass($gets)
		];
	// var_dump($transactions_join->prosto);
	return $transactions_join;

	// $transactions_join->transactions = bwc_transactions_mass($gets);
	// $transactions_join->prosto = bwc_ajax_prosto_mass($gets);
	// $transactions_join->storage = bwc_ajax_storage_mass($gets);
	// $transactions_join->processing = bwc_ajax_storage_mass($gets);
}

function bwc_beginning_bulk_spirits($gets){
	$date_st = $gets["date_st"];
	$bom_bulk = bwc_exec_query("SELECT * FROM transactions AS t 
		WHERE t.thisTable IN (prosto, storage)
		AND t.dateTime < %s", array($date_st));
	return array($bom_bulk);
}

function bwc_ending_bulk_spirits($gets){
	$date_st = $gets["date_st"];
	$date_end = $gets["date_end"];
	$eom_bulk = bwc_exec_query("SELECT * FROM transactions AS t 
		WHERE t.thisTable IN (prosto, storage)
		AND t.dateTime > %s
		AND t.dateTime < %s", array($date_st, $date_end));
	return array($eom_bulk);
}

function bwc_transfer_in_bond($gets){
	$date_st = $gets["date_st"];
	$date_end = $gets["date_end"];
	$show = $gets["show"];
	$tib_data = bwc_exec_query_show("SELECT * FROM transactions AS t 
		INNER JOIN production AS p
		ON p.prodId = t.thisId
		WHERE t.thisTable IN (production)
		AND p.type = 'Transfer in Bond'
		AND p.dateTime > %s
		AND p.dateTime < %s", array($date_st, $date_end));
	return array($tib_data);
}

?>