<?php
/**
 * Template Name: Backend2
 *
 * Loop container for page content
 *
 * @package WordPress
 * @subpackage Foundation, for WordPress
 * @since Foundation, for WordPress 4.0
 */

get_header(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="../wp-content/themes/drewsymo-Foundation-2ef5908/js/bwc_scripts-functions.js"></script><!--calling script for handling backend requests-->
<script type="text/javascript" src="../wp-content/themes/drewsymo-Foundation-2ef5908/js/bwc_scripts-DOM.js"></script>
<script type="text/javascript" src="../wp-content/themes/drewsymo-Foundation-2ef5908/js/bwc_scripts-DOM2.js"></script>
    <!-- Main Content -->
    <div class="large-12 columns" role="main">
    	<input id="id" style="display:none"/>
    <!--have to declare accepted query vars in WP using add_query_arg function so that URL arguments accepted-->
		<?php add_query_arg( 'mashId', 'prodId', 'processId'); ?>
		<?php $mashId = $_GET["mashId"]; 
			$prodId = $_GET["prodId"];
			$processId = $_GET["processId"];
			list($vendors, $mash_step_types, $fermentables, $yeast_masters, $production_measurement_types, $withdrawal_reasons, $production_origins, $units, $inventory_items, $inventory_clearing_reasons, $enzyme_masters, $processing_origins, $spirit_classes, $mash_types, $production_types, $prosto_transaction_types, $prosto_types, $fermenters) = bwc_config_queries();
			list($inventories) = bwc_be_inventory_queries();
		?>

		<?php if ( have_posts() ) : ?>		
			<?php while ( have_posts() ) : the_post();?>
				<div class="bwc_be_nav">
					<ul id="section-toggle" class="toggle-radio-buttons">
						<li class="first">
							<input type="radio" name="main_toggle"/>
							<label id="inventoryPanel">Inventory</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="ajax_inventoryPanel">Ajax Inventory</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="mashPanel">Mash</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="fermPanel">Fermentation</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="prodPanel">Production</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="prostoPanel">Prosto</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="prostoTanksPanel">Prosto Tanks</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="storagePanel">Storage</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="processingPanel">Processing</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="miscPanel">Misc</label>
						</li>
						<li>
							<input type="radio" name="main_toggle"/>
							<label id="taxPanel">Tax</label>
						</li>
					</ul>
				</div>

				<div class="bwc_panel" id="miscPanel" style="display:none">
					<div class="large-12 columns">
						<form id="bwc_maceration_calculator" class="bwc_form">
							<fieldset class="bwc_fieldset">
								<legend class="bwc_legend">Maceration</legend>
								<label for="spirit" class="nerdy_p">Choose Gin/Liqueur</label>
								<select name="spirit">
									<option value="">Choose Spirit...</option>
									<option>Gin</option>
									<option>1904</option>
								</select>
								<label for="pg" class="nerdy_p" style="display:none">Proof Gal</label>
								<input type="number" name="pg" class="pg" step="0.01" min="0" max="200" style="display:none"/>
								<label for="units" class="nerdy_p" style="display:none">Output Units</label>
								<select name="units" style="display:none">
									<option value="">Choose Units...</option>
									<option>lbs</option>
									<option>g</option>
								</select>
						</form>
					</div>
				</div>

<!--Inventory Panel-->				
				<div class="bwc_panel" id="inventoryPanel" style="display:none">
					<?php bwc_be_show_inventory($inventories, $inventory_items, $inventory_clearing_reasons, $vendors);?>
				</div>

<!--AJAX Inventory Panel-->				
				<div class="bwc_panel" id="ajax_inventoryPanel" style="display:none">
				<!--explorer-->
					<div class="large-12 columns">
						<div class="large-6 columns" id="inventory_explorer">
							<form id="bwc_item_explore" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Inventory Explorer</legend>
									<label for="item" class="nerdy_p">Item | Current Stock | Type</label>
									<input name="fields" value="item,item_stock,itemType" style="display:none"/>
									<select name="item" id="inventoryExplorer">
										<option value="">Choose Item...</option>
									</select>
									<br/>
								</fieldset>
							</form>
						</div>
					</div>

				<!--explode sections-->
					<div class="large-12 columns" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" />
									<label id="inventory_item_header" for="item_header">Header</label>
								</li>
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="inventory_deposits" for="item_deposits">Deposits</label>
								</li>
								<li>
									<input class="withdrawals" type="radio" name="toggle-radio-buttons" />
									<label id="inventory_item_withdrawals" for="withdrawals">Withdrawals</label>
								</li>
							</ul>
						</div>

						<div class="large-12 columns detail" id="inventory_item_header">
							<form class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Get off my lawn</legend>
									<label for="item" class="nerdy_p">Item</label>
									<output class="bwc_red" for="item"></output>
									<label for="item_stock" class="nerdy_p">Current Item Stock</label>
									<output class="bwc_red" for="item_stock"></output>
									<label for="itemType" class="nerdy_p">Item Type</label>
									<output class="bwc_red" for="itemType"></output>
									<label for="unit" class="nerdy_p">Units</label>
									<output class="bwc_red" for="unit"></output>
									<label for="itemType" class="nerdy_p">Item Type</label>
									<output class="bwc_red" for="itemType"></output>
								</fieldset>
							</form>
						</div>	

						<div class="large-12 columns detail" id="inventory_deposits" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form class='bwc_form template' id='bwc_add_inventory'>
								<fieldset class='bwc_fieldset'>
									<legend class='bwc_legend'>Inventory Item Deposits</legend>
									<input type='text' name='function' value='bwc_table_insert3' style='display:none' />
									<input type='text' name='keys' value='transactionId,subitem' style='display:none' />
									<input type='text' name='item' style='display:none' />
									
									<label for"item" class="nerdy_p">Transaction ID</label>
									<input type='text' name='transactionId' readonly />
									<output class="bwc_red" for="transactionId"></output>

									<label for"item" class="nerdy_p">Transaction Subitem</label>
									<input type='text' name='subitem' readonly />
									<output class="bwc_red" for="subitem"></output>

									<label for"item" class="nerdy_p">Item</label>
									<input type='text' name='item' readonly />
									<output class="bwc_red" for="item"></output>

									<label for="amount" class="nerdy_p">Amount</label>
									<input type="number" name="amount" step="0.01" min="-1000000" max="1000000"/>
									<output class="bwc_red" for="amount"></output>

									<label for="origAmount" class="nerdy_p">Orig Amount</label>
									<input type="number" name="origAmount" step="0.01" min="-1000000" max="1000000" readonly/>
									<output class="bwc_red" for="origAmount"></output>

									<input type='text' name='table' value='inventory_transactions' style='display:none' />
									<label for="dateTime" class="nerdy_p">Date</label>
									<input type="datetime-local" name="dateTime" />
									<output class="bwc_red" for="dateTime"></output>

									<input type="submit" value="Deposit Inventory"/>
								</fieldset>
							</form>
							<table class='bwc_be_table'>
								<tr>
									<th>TransactionId</th>
									<th>Trans SubItem</th>
									<th>Amount</th>
									<th>Date</th>
									<th>Original Amount</th>
									<th>Clearing Reason</th>
									<th>Clearing Transaction</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
								<tr class='template' style="display:none">
									<td id='transactionId'></td>
									<td id='subitem'></td>			
									<td id='amount'></td>
									<td id='dateTime'></td>
									<td id='origAmount'></td>
									<td id='clearingReason'></td>
									<td id='clearingTransaction'></td>
									<td id='amount'></td>
									<td id="edit">
										<form class='bwc_form'>
											<input type='button' id='show_edit_form' value='Edit' />
										</form>
									</td>
									<td id="delete">
										<form class='bwc_form' >
											<input type='text' name='function' value='bwc_reverse_clear_inventory_transaction' style='display:none' />
											<input type='text' name='keys' value='transactionId' style='display:none' />
											<input type='text' name='transactionId' style='display:none' />
											<input type='text' name='clearingReason' value='REVERSED' style='display:none' />
											<input type='text' name='clearingStatus' value='9' style='display:none' />
											<input type='text' name='clearingDate' style='display:none' />
											<input type='text' name='item' style='display:none' />
											<input type='text' name='table' value='inventory_transactions' style='display:none' />
											<input type='submit' value='Delete'/>
										</form>
									</td>
								</tr>
							</table>
						</div>

						<div class="large-12 columns detail" id="inventory_item_withdrawals" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form class='bwc_form template' id='bwc_add_inventory'>
								<fieldset class='bwc_fieldset'>
									<legend class='bwc_legend'>Inventory Item Withdrawals</legend>
									<input type="text" name="table" value="inventory_transactions" style="display:none"/>
									<input type="text" name="function" value="bwc_clear_inventory_transaction" style="display:none"/>
									<input type="text" name="clearingStatus" value='1' style='display:none' />
									<input type="text" name="transactionType" value='1' style='display:none' />

									<label for"item" class="nerdy_p">Item</label>
									<input type='text' name='item' readonly />
									<output class="bwc_red" for="item"></output>

									<label for="clearingReason" class="nerdy_p">Clearing Reason</label>
									<select name="clearingReason" id="clearingReason"  required>
										<option value="" >Choose clearing reason...</option>
										<?php foreach ($inventory_clearing_reasons as $inventory_clearing_reason) {
											echo '<option>'.$inventory_clearing_reason->clearingReasons.'</option>';
										}?>
									</select>

									<label for="amount" class="nerdy_p">Clearing Amount</label>
									<input type="decimal" name="amount" min="0" max="1000000" step="0.01"/>
									<output class="bwc_red" for="amount"></output>

									<label for="dateTime" class="nerdy_p">Clearing Date</label>
									<input type="datetime-local" name="dateTime" />
									<output class="bwc_red" for="dateTime"></output>

									<input type="submit" name="bwc_inventory_transactions_update" id="bwc_inventory_table_update" value="Clear Inventory" />

								</fieldset>
							</form>
							<table class='bwc_be_table'>
								<tr>
									<th>TransactionId</th>
									<th>Date</th>
									<th>Amount</th>
									<th>Clearing Reason</th>
									<th>Delete</th>
								</tr>
								<tr class='template' style="display:none">
									<td id='transactionId'></td>
									<td id='dateTime'></td>
									<td id='amount'></td>
									<td id='clearingReason'></td>
									<td id="delete">
										<form class='bwc_form' >
											<input type='text' name='function' value='bwc_reverse_clear_inventory_transaction' style='display:none' />
											<input type='text' name='keys' value='transactionId' style='display:none' />
											<input type='text' name='transactionId' style='display:none' />
											<input type='text' name='table' value='inventory_transactions' style='display:none' />
											<input type='submit' value='Delete'/>
										</form>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>		

<!--Mash Panel-->
				<div class="bwc_panel" id="mashPanel" style="display:none">
					<div class="large-12 columns">
						<div class="large-6 columns">
							<form id="bwc_mash_new" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Create Mash</legend>
									<input type="text" name="table" value="mash" style="display:none" />
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<label for="type" class="nerdy_p">Choose Type</label>
									<select name="type">
										<option value="">Choose mash type...</option>';
										<?php foreach ($mash_types as $mash_type) {
											echo '<option>'.$mash_type->mashType.'</option>';
										}?>
									</select>
									<label for="dateTime" class="nerdy_p">Date</label>
									<input type="datetime-local" name="dateTime" />
									<input type="submit" id="mash_new_button" value="Create New Mash"/>
								</fieldset>
							</form>
						</div>
						<div class="large-6 columns" id="mash_explorer">
							<form id="bwc_mash_explore" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Mash Explorer</legend>
									<label for="mashId" class="nerdy_p">MashId | Date | Type</label>
									<input name="fields" value="mashId,dateTime,type" style="display:none"/>
									<select name="mashId" id="mashExplorer">
										<option value="">Choose Mash...</option>
									</select>
									<br/>
							</form>
						</div>
					</div>

					<div class="large-12 columns" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="mash_header" for="mash_header">Header</label>
								</li>
								<li>
									<input type="radio" name="toggle-radio-buttons"/>
									<label id="mash_steps" for="mash_steps">Steps</label>
								</li>
								<li>
									<input type="radio" name="toggle-radio-buttons"/>
									<label id="mash_bill" for="mash_bills">Mash Bill</label>
								</li>
								<li>
									<input type="radio" name="toggle-radio-buttons"/>
									<label id="mash_enzymes" for="mash_enzymes">Enzymes</label>
								</li>
								<li>
									<input class="transactions" type="radio" name="toggle-radio-buttons"/>
									<label id="mash_transactions" for="transactions">Transactions</label>
								</li>
							</ul>
						</div>
						
						<div class="large-12 columns detail" id="mash_header">
							<form id="bwc_mash_header" class="bwc_form">
								<fieldset name="mash_header" class="bwc_fieldset">
									<legend class="bwc_legend">Mash Header</legend>
									<input type="text" name="table" value="mash" style="display:none"/>
									<input type="text" name="keys" value="mashId" style="display:none"/>
									<input type="text" name="function" value="bwc_table_update2" style="display:none"/>
									<label for="mashId" class="nerdy_p">Mash ID</label>
									<output class="bwc_red" for="mashId"></output>
									<input type="text" name="mashId" readonly />
									<label for="dateTime" class="nerdy_p">Mash Date/Time</label>
									<output class="bwc_red" for="dateTime"></output>
									<input type="datetime-local" name="dateTime" />
									<label for="type" class="nerdy_p">Mash Type</label>
									<output class="bwc_red" for="type"></output>
									<select name="type">
										<option value="">Choose mash type...</option>';
										<?php foreach ($mash_types as $mash_type) {
											echo '<option>'.$mash_type->mashType.'</option>';
										}?>
									</select>
									<label for="hltVolume" class="nerdy_p">HLT Volume</label>
									<output class="bwc_red" for="hltVolume"></output>
									<input type="number" name="hltVolume" id="hltVolume" step="any" />
									<label for="hltSalts" class="nerdy_p">HLT Salts</label>
									<output class="bwc_red" for="hltSalts"></output>
									<input type="number" name="hltSalts" id="hltSalts" step="any" />
									<label for="hltPh" class="nerdy_p">HLT pH</label>
									<output class="bwc_red" for="hltPh"></output>
									<input type="number" name="hltPh" id="hltPh" min="0" max="14" step="0.01" />
									<label for="strikeTemp" class="nerdy_p">Strike Temperature</label>
									<output class="bwc_red" for="strikeTemp"></output>
									<input type="number" name="strikeTemp" id="strikeTemp" min="0" max="212" step="0.01" />
									<label for="pitchTemp" class="nerdy_p">Pitch Temp</label>
									<output class="bwc_red" for="pitchTemp"></output>
									<input type="number" name="pitchTemp" id="pitchTemp" min="0" max="212" step="0.01"/>
									<label for="mashPh" class="nerdy_p">Mash pH</label>
									<output class="bwc_red" for="mashPh"></output>
									<input type="number" name="mashPh" id="mashPh" min="0" max="14" step="0.01"/>
									<input type="submit" value="Update Header"/>
								</fieldset>
							</form>
						</div>

						<div class="large-12 columns detail" id="mash_steps" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form class='bwc_form template'>
								<fieldset class='bwc_fieldset'>
									<legend class='bwc_legend'>Mash Steps</legend>
									<input type="text" name="table" value="mash_steps" style="display:none"/>
									<input type="text" name="keys" value="measId" style="display:none"/>
									<input type="text" name="measId" style="display:none"/>
									<input type="text" name="mashId" style="display:none"/>
									<input type="text" name="function" value="bwc_table_insert3" style="display:none"/>
									<label for="dateTime" class="nerdy_p">Step Start</label>
									<output class="bwc_red" for="tdateTime"></output>
									<input type="datetime-local" name="dateTime" id="dateTime" />
									<label for="startTemp" class="nerdy_p">Start Temp</label>
									<output class="bwc_red" for="startTemp"></output>
									<input type="number" min="0" max="212" step="0.01" name="startTemp" id="startTemp" placeholder="At start..."/>
									<label for="type" class="nerdy_p">Step Type</label>
									<output class="bwc_red" for="type"></output>
									<select name="type" id="type">
										<option value="" >Choose step type...</option>
										<?php foreach ($mash_step_types as $mash_step_type) {
											echo '<option>'.$mash_step_type->mashStepTypes.'</option>';
										}?>
									</select>
									<label for="pH" class="nerdy_p">pH</label>
									<output class="bwc_red" for="pH"></output>
									<input type="number" min="0" max="14" step="0.01" name="ph"/>
									<input type="submit" value="Create Step"/>
								</fieldset>
							</form>
							<table class='bwc_be_table'>
								<tr>
									<th>Start Time</th>
									<th>Start Temp</th>
									<th>Type</th>
									<th>pH</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
								<tr class='template' style="display:none">
									<td id='tdateTime'></td>
									<td id='startTemp'></td>
									<td id='type'></td>
									<td id='ph'></td>
									<td id="edit">
										<form>
											<input type='button' id='show_edit_form' value='Edit' />
										</form>
									</td>
									<td id="delete">
										<form class='bwc_form'>
											<input type='text' name='function' value='bwc_delete_table2' style='display:none' />
											<input type='text' name='keys' value='measId' style='display:none' />
											<input type='text' name='measId' style='display:none' />
											<input type='text' name='table' value='mash_steps' style='display:none' />
											<input type='submit' value='Delete'/>
										</form>
									</td>
								</tr>
							</table>
						</div>

						<div class="large-12 columns detail" id="mash_bill" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form class='bwc_form template'>
								<fieldset class='bwc_fieldset'>
									<legend class='bwc_legend'>Mash Bill</legend>
									<input type='text' name='function' value='bwc_table_insert3' style='display:none' />
									<input type='text' name='keys' value='mashBillId' style='display:none' />
									<input type='text' name='mashId' style='display:none' />
									<input type='text' name='mashBillId' style='display:none' />
									<label for"fermentable" class="nerdy_p">Fermentable</label>
									<select name="fermentable" required>
										<option value="" >Choose fermentable...</option>
										<?php foreach ($fermentables as $fermentable) {
											echo '<option value="'.$fermentable->fermentableName.'">'.$fermentable->fermentableType.' | '.$fermentable->fermentableName.'</option>';
										}?>
									</select>
									<label for"qty" class="nerdy_p">Quantity</label>
									<input type='number' min="0" max="100000" step="0.01" name='qty' />
									<label for"vendor" class="nerdy_p">Vendor</label>
									<select name="vendor" id="vendor">
										<option value="" >Choose vendor...</option>
										<?php foreach ($vendors as $vendor) {
											if($vendor->type=="Raw Materials"){
												echo '<option>'.$vendor->vendorName.'</option>';
											}
										}?>
									</select>
									<input type='text' name='table' value='mashbill' style='display:none' />
									<input type="submit" value="Add Fermentable"/>
								</fieldset>
							</form>
							<table class='bwc_be_table'>
								<tr>
									<th>Fermentable</th>
									<th>Quantity</th>
									<th>Vendor</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
								<tr class="template" style="display:none">
									<td id='fermentable'></td>
									<td id='qty'></td>
									<td id='vendor'></td>
									<td id="edit">
										<form class='bwc_form'>
											<input type='button' id='show_edit_form' value='Edit' />
										</form>
									</td>
									<td id="delete">
										<form>
											<input type='text' name='function' value='bwc_delete_table2' style='display:none' />
											<input type='text' name='keys' value='mashBillId' style='display:none' />
											<input type='text' name='mashBillId' style='display:none' />
											<input type='text' name='table' value='mashbill' style='display:none' />
											<input type='submit' value='Delete'/>
										</form>
									</td>
								</tr>
							</table>
						</div>

						<div class="large-12 columns detail" id="mash_enzymes" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form class='bwc_form template'>
								<fieldset class='bwc_fieldset'>
									<legend class='bwc_legend'>Mash Enzymes</legend>
									<input type='text' name='function' value='bwc_table_insert3' style='display:none' />
									<input type='text' name='keys' value='measId' style='display:none' />
									<input type='text' name='mashId' style='display:none' />
									<input type='text' name='measId' style='display:none' />
									<label for"fermentable" class="nerdy_p">Enzyme</label>
									<select name="enzyme"  required>
										<option value="" >Choose enzyme...</option>
										<?php 
											foreach ($enzyme_masters as $enzyme_master) {
												echo '<option value="'.$enzyme_master->enzymeName.'">'.$enzyme_master->enzymeName.' | '.$enzyme_master->enzymeName.'</option>';
											}
										?>
									</select>
									<label for"qty" class="nerdy_p">Quantity</label>
									<input type='number' min="0" max="100000" step="0.01" name='qty' />
									<input type='text' name='table' value='mash_enzyme_additions' style='display:none' />
									<input type="submit" value="Create Enzyme"/>
								</fieldset>
							</form>
							<table class='bwc_be_table'>
								<tr>
									<th>Enzyme</th>
									<th>Quantity</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
								<tr class='template' style="display:none">
									<td id='enzyme'></td>
									<td id='qty'></td>
									<td id="edit">
										<form class='bwc_form'>
											<input type='button' id='show_edit_form' value='Edit' />
										</form>
									</td>
									<td id="delete">
										<form class='bwc_form' >
											<input type='text' name='function' value='bwc_delete_table2' style='display:none' />
											<input type='text' name='keys' value='measId' style='display:none' />
											<input type='text' name='measId' style='display:none' />
											<input type='text' name='table' value='mash_enzyme_additions' style='display:none' />
											<input type='submit' value='Delete'/>
										</form>
									</td>
								</tr>
							</table>
						</div>

						<?php $kind = array();
							$kind["divStr"] = "mash";
							$kind["thisTable"] = "mash";
						show_transactions($kind);
						?>

					</div>
				</div>

<!--Ferm Panel-->
				<div class="bwc_panel" id="fermPanel" style="display:none">
					
				<!--Fermentation Explorer-->
					<div class="large-4 columns">
						<form id="bwc_ferm_new" class="bwc_form">
							<fieldset class="bwc_fieldset">
								<legend class="bwc_legend">Create Fermentation</legend>
								<input type="text" name="table" value="fermentation" style="display:none" />
								<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
								<label for="dateTime" class="nerdy_p">Date/Time</label>
								<input type="datetime-local" name="dateTime" />
								<label for="type" class="nerdy_p">Type</label>
								<select name="type">
									<option value="">Choose type...</option>';
									<?php foreach ($mash_types as $mash_type) {
										echo '<option>'.$mash_type->mashType.'</option>';
									}?>
								</select>
								<label for="fermenter" class="nerdy_p">Fermenter</label>
									<output for="fermenter" class="bwc_red"></output>
									<select name="fermenter">
										<option value="">Choose fermenter...</option>
										<?php
											foreach ($fermenters as $fermenter) {
												echo '<option>'. $fermenter->fermenterName .'</option>';
											}
										?>
									</select>
								<input type="submit" value="Create New Ferm"/>
							</fieldset>
						</form>
					</div>
					<div class="large-8 columns" id="fermentation_explorer" >
						<!--<div class="large-6 columns" >	
							<form class="bwc_form" id="ferm_explorer_filter">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Fermentation Explorer Filters</legend>
									<label for="fermentation_type" class="nerdy_p">Filter by Type</label>
									<?php
										foreach ($mash_types as $type) {
											echo '<input type="radio" name="fermTypeFilter" class="ferm_type_toggle" value="'.$type->mashType.'">'.$type->mashType.'</input>';
										}
									?>

									<label for="date" class="nerdy_p">Filter by Date: At Least...</label>
									<input type="datetime-local" name="fermDateFilter"/>
									<label for="date" class="nerdy_p">Open Only?</label>
									<input type="checkbox" name="fermOpen"/>
									<input type="button" name="reset" value="Reset Filters"/>
								</fieldset>
							</form>
						</div>-->
						<div class="large-12 columns" >
							<form class="bwc_form" id="fermentation_explorer">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Fermentation Explorer</legend>
									<input name="fields" value="fermId,dateTime,type" style="display:none"/>
									<select id="fermentations"></select>
								</fieldset>
							</form>
						</div>
					</div>

				<!--Fermentation Single-->
					<div class="large-12 columns details" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="fermentation_detail" for="fermentation_detail">Header</label>
								</li>
								<li>
									<input type="radio" name="toggle-radio-buttons"/>
									<label id="fermentation_time_temps" for="fermentation_time_temps">Time/Temps</label>
								</li>
								<li>
									<input class="transactions" type="radio" name="toggle-radio-buttons"/>
									<label id="ferm_transactions" for="ferm_transactions">Transactions</label>
								</li>
							</ul>
						</div>

					<!--Expanded Detail on Fermentation -->
						<div class="large-12 columns detail" id="fermentation_detail">
							<form class="bwc_form" id="fermentation_detail">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Ferm Header</legend>
									<input name="fermId" style="display:none"/>
									<input name="table" value="fermentation" style="display:none"/>
									<input name="keys" value="fermId" style="display:none"/>
									<input name="function" value="bwc_table_update2" style="display:none"/>
									<label for="fermId" class="nerdy_p">FermId</label>
									<output for="fermId" class="bwc_red"></output>
									<label for="dateTime" class="nerdy_p">Date/Time</label>
									<output for="dateTime" class="bwc_red"></output>
									<input name="dateTime" type="datetime-local"></input>
									<label for="type" class="nerdy_p">Type</label>
									<output for="type" class="bwc_red"></output>
									<select name="type">
										<option value="">Choose type...</option>
										<?php
											foreach ($mash_types as $type) {
												echo '<option>'. $type->mashType .'</option>';
											}
										?>
									</select>
									<label for="fermenter" class="nerdy_p">Fermenter</label>
									<output for="fermenter" class="bwc_red"></output>
									<select name="fermenter">
										<option value="">Choose fermenter...</option>
										<?php
											foreach ($fermenters as $fermenter) {
												echo '<option>'. $fermenter->fermenterName .'</option>';
											}
										?>
									</select>
									<label for="clearingStatus" class="nerdy_p">Clearing Status</label>
									<output for="clearingStatus" class="bwc_red"></output>
									<input name="clearingStatus" type="number" min="0" max="1" step="1" placeholder="0-Open, 1-Clr"/>
									<label for="yeast" class="nerdy_p">Yeast</label>
									<output for="yeast" class="bwc_red"></output>
									<select name="yeast">
										<option value="">Choose yeast...</option>
										<?php
											foreach ($yeast_masters as $yeast) {
												echo '<option>'. $yeast->yeastName .'</option>';
											}
										?>
									</select>
									<input type="submit" value="Update Header"/>
								</fieldset>
							</form>
						</div>

						<div class="large-12 columns detail" id="fermentation_time_temps" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form class="bwc_form template" id="bwc_ferm_time_temp_create">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">New Time/Temp Measurement</legend>
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<input type="text" name="table" value="ferm_time_temp" style="display:none" />
									<input name='measId' style='display:none'/>
									<label for="fermId" class="nerdy_p">FermId</label>
									<input type="text" name="fermId" readonly />
									<input type="text" name="keys" value="measId" style="display:none" />	
									<label for="dateTime" class="nerdy_p">Time of Measurement</label>
									<input type="datetime-local" name="dateTime" id="dateTime" />
									<label for="pH" class="nerdy_p">pH</label>
									<input type="number" name="pH" id="pH" min="0" max="14" step="0.01" />
									<label for="temp" class="nerdy_p">Temp</label>
									<input type="number" name="Temp" id="Temp" min="0" max="212" step="0.01"/>
									<label for="specificGravity" class="nerdy_p">Gravity</label>
									<input type="number" name="specificGravity" id="specificGravity" min="0" max="100" step="0.001"/>
									<input type="submit" value="Create"/>
								</fieldset>
							</form>

							<table class="bwc_be_table">
								<tr>
									<th>Date/Time</th>
									<th>Temperature</th>
									<th>pH</th>
									<th>Gravity/Brix</th>
									<th>Delete?</th>
									<th>Edit?</th>
								</tr>
								<tr class="template" style="display:none">
									<td id="td_dateTime"></td>
									<td id="Temp"></td>
									<td id="pH"></td>
									<td id="specificGravity"></td>
									<td id="edit">
										<form class='bwc_form'>
											<input type='button' id='show_edit_form' value='Edit' />
										</form>
									</td>
									<td id="delete">
										<form class='bwc_form'>
											<input name="function" value="bwc_delete_table2" style="display:none" />
											<input name="measId" style="display:none" />
											<input name="keys" value="measId" style="display:none" />
											<input name="table" value="ferm_time_temp" style="display:none" />
											<input type='submit' name='delete' value='Delete' />
										</form>
									</td>
								</tr>
							</table>
						</div>

						<?php $kind = array();
							$kind["divStr"] = "ferm";
							$kind["thisTable"] = "fermentation";
						show_transactions($kind);
						?>

					</div>
				</div>

			<!--Production-->
				<div class="bwc_panel" id="prodPanel" style="display:none">
					<div class="large-12 columns">
						<div class="large-6 columns">
							<form id="bwc_production_new" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Create Production</legend>
									<input type="text" name="table" value="production" style="display:none" />
									<label for="dateTime" class="nerdy_p">Date/Time</label>
									<input type="datetime-local" name="dateTime" />
									<?php echo '<select name="type">
										<option value="" >Choose type...</option>';
										foreach ($production_types as $production_type){
											echo '<option>'.$production_type->productionType.'</option>';
										}
									echo '</select>';?>
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<input type="submit" value="Create New Production"/>
								</fieldset>
							</form>
						</div>
							
						<div class="large-6 columns" id="production_explorer">
							<form id="bwc_production_explore" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Production Explorer</legend>
									<label for="prodId" class="nerdy_p">Date | Type | ProdID | Total PG</label>
									<input name="fields" value="dateTime,type,prodId,totalPg" style="display:none"/>
									<select name="prodId" id="productionExplorer">
										<option value="">Choose Production Entry...</option>
									</select>
									<br/>
							</form>
						</div>
					</div>
						
					<div class="large-12 columns details" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="production_single" for="production_single">Header</label>
								</li>
								<li>
									<input type="radio" name="toggle-radio-buttons"/>
									<label id="prod_measurements" for="prod_measurements">Measurements</label>
								</li>
								<li>
									<input class="transactions" type="radio" name="toggle-radio-buttons"/>
									<label id="prod_transactions" for="prod_transactions">Transactions</label>
								</li>
							</ul>
						</div>
						<hr />

						<div class="large-12 columns detail" id="production_single">
							<form id="bwc_prod_header_form" class="bwc_form">
								<fieldset name="prodHeader" class="bwc_fieldset">
									<legend class="bwc_legend">Prod Header</legend>
									<input type="text" name="table" value="production" style="display:none" />
									<input type="text" name="function" value="bwc_table_update2" style="display:none" />
									<input type="text" name="keys" value="prodId" style="display:none" />
									<input type="text" name="new_values" value="dateTime,type,tareWeight" style="display:none" />
									<label class="nerdy_p">Production ID</label>
									<output class="bwc_red" for="prodId"></output>
									<input type="text" name="prodId" value="" style="display:none" />
									<label class="nerdy_p">Date/Time</label>
									<output class="bwc_red" for="dateTime"></output>
									<input type="datetime-local" name="dateTime" value=""/>
									<label for="type" class="nerdy_p">Production Type</label>
									<output id="bwc_production_type" for="type" class="bwc_red type"></output>
									<?php echo '<select name="type">
										<option value="" >Choose type...</option>';
										foreach ($production_types as $production_type){
											echo '<option>'.$production_type->productionType.'</option>';
										}
									echo '</select>';?>
									<input type="submit" value="Update Production"/>	
									<br />
									<hr />
									<label for="pg_measurements" class="nerdy_p">Sum PG from Prod Measurements</label>
									<output for="pg_measurements" class="bwc_red"></output>
									<label for="pg_weight" class="nerdy_p">Sum Wt. from Prod Measurements</label>
									<output for="pg_weight" class="bwc_red"></output>
									<br />
									<input type="button" id="proof_from_pg_weight" value="Get Proof" />
									<label for="calc_proof" class="nerdy_p">Proof</label>
									<output for="calc_proof" class="bwc_red"></output>
								</fieldset>
							</form>
						</div>

						<div class="large-12 columns detail" id="prod_measurements" class="production_measurements" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form class="bwc_form template">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Measurements</legend>
										<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
										<input type="text" name="table" value="production_measurements" style="display:none"/>
										<input type="text" name="keys" value="measId" style="display:none"/>
										<input type="text" name="measId" style="display:none"/>
										<input type="text" name="prodId" style="display:none"/>
										<label class="nerdy_p">ProdId</label>
										<input type="text" name="prodId" readonly />
										<label class="nerdy_p">DateTime</label>
										<output class="bwc_red" for="tdateTime"></output>
										<input type="datetime-local" name="dateTime" />
										<label class="nerdy_p">pH</label>
										<output class="bwc_red" for="pH"></output>
										<input type="number" name="pH" min="0" max="14" step="0.01" placeholder="pH" />
										<label class="nerdy_p">Apparent Proof</label>
										<output class="bwc_red" for="appProof"></output>
										<input type="number" name="appProof" class="appProof" min="0" max="10000" step="0.01" placeholder="App Proof" />
										<label class="nerdy_p">Temp</label>
										<output class="bwc_red" for="temp"></output>
										<input type="number" name="temp" class="temp" min="0" max="10000" step="0.01" placeholder="Temp"/>
										<label class="nerdy_p">Actual Proof</label>
										<output class="bwc_red" for="appProof"></output>
										<input type="number" name="actProof" class ="actProof" min="0" max="10000" step="0.01" placeholder="Act Proof"/>
										<label class="nerdy_p">Weight</label>
										<output class="bwc_red" for="weight"></output>
										<input type="number" name="weight" class="weight" min="0" max="10000" step="0.01" placeholder="Weight"/>
										<label class="nerdy_p">Proof-Gallons</label>
										<output class="bwc_red" for="pg"></output>
										<input type="number" name="pg" class="pg" min="0" step="0.01" placeholder="Auto..."/>
										<label class="nerdy_p">Still Temp</label>
										<output class="bwc_red" for="stillTemp"></output>
										<input type="number" name="stillTemp" min="0" max="10000" step="0.01" placeholder="Still temp.."/>
										<label class="nerdy_p">Steam</label>
										<output class="bwc_red" for="steamPressure"></output>
										<input type="number" name="steamPressure" min="0" max="10000" step="0.01" placeholder="Steam pressure.."/>
										<label class="nerdy_p">Measurement Type</label>
										<output class="bwc_red" for="measurementType"></output>
										<select name="measurementType">
											<option value="">Choose Type...</option>
											<?php
												foreach ($production_measurement_types as $type) {
													echo '<option>'.$type->measurementType.'</option>';
												}
											?>
										</select>
										<input type="submit" value="Add Measurement" />
								</fieldset>
							</form>
							<table class="bwc_be_table">
								<tr>
									<th>Date/Time</th>
									<th>App Proof</th>
									<th>Temp</th>
									<th>pH</th>
									<th>Act Proof</th>
									<th>Wt.</th>
									<th>PG</th>
									<th>StillTemp</th>
									<th>Steam</th>
									<th>Type</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
								<tr class="template" style="display:none">
									<td id="tdateTime"></td>
									<td id="appProof"></td>
									<td id="temp"></td>
									<td id="pH"></td>
									<td id="actProof"></td>
									<td id="weight"></td>
									<td id="pg"></td>
									<td id="stillTemp"></td>
									<td id="steamPressure"></td>
									<td id="measurementType"></td>
									<td id="edit">
										<form class='bwc_form'>
											<input type='button' id='show_edit_form' value='Edit' />
										</form>
									</td>
									<td id="delete">
										<form class='bwc_form'>
											<input name="function" value="bwc_delete_table2" style="display:none" />
											<input name="keys" value="measId" style="display:none" />
											<input name="table" value="production_measurements" style="display:none" />
											<input name="measId" style="display:none" />
											<input type='submit' name='delete' value='Delete' />
										</form>
									</td>
								</tr>
							</table>
						</div>

						<?php $kind = array();
							$kind["divStr"] = "prod";
							$kind["thisTable"] = "production";
						show_transactions($kind);
						?>
						
					</div>
				</div>

			<!--prosto-->
				<div class="bwc_panel" id="prostoPanel" style="display:none">
					<div class="large-12 columns">
						<div class="large-6 columns">
							<form id="bwc_prosto_new" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Create</legend>
									<input type="text" name="table" value="prosto" style="display:none" />
									<input type="text" name="keys" value="dateTime" style="display:none" />
									<input type="text" name="dateTime" style="display:none" />
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<label for="type" class="nerdy_p">Prosto Type</label>
									<select name="type">
										<option value="" >Choose type...</option>
										<?php
											foreach ($prosto_types as $type){
												echo '<option>'.$type->prostoType.'</option>';
											}
										?>
									</select>
									<label for="tankId" class="nerdy_p">TankName | Prior Use | TankId</label>
									<select name="tank">
										<option value="">Choose tank</option>
									</select>
									<input type="submit" value="Create New Prosto"/>
								</fieldset>
							</form>
						</div>
						
						<div class="large-6 columns" id="prosto_explorer">
							<form id="prosto_explorer" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Prosto Explorer</legend>
									<label for="prostoId" class="nerdy_p">Date | Type | Tank | Cleared | ProstoId | PG</label>
									<input name="fields" value="dateTime,type,tankName,clearingStatus,prostoId,totalPg" style="display:none"/>
									<select name="prostoId" id="prostoExplorer">
										<option value="">Choose Prosto...</option>
									</select>
							</form>
						</div>
					</div>

					<div class="large-12 columns details" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="prosto_single" for="prosto_single">Header</label>
								</li>
								<li>
									<input class="transactions" type="radio" name="toggle-radio-buttons"/>
									<label id="prosto_transactions" for="prosto_transactions">Transactions</label>
								</li>
							</ul>
						</div>

						<div class="large-12 columns detail" id="prosto_single">
							<form id="prosto_header_form" class="bwc_form">
								<fieldset name="prostoHeader" class="bwc_fieldset">
									<legend class="bwc_legend">Prosto Header</legend>
									<input type="text" name="table" value="prosto" style="display:none" />
									<input type="text" name="function" value="bwc_table_update2" style="display:none" />
									<input type="text" name="keys" value="prostoId" style="display:none" />
									<label class="nerdy_p">Prosto ID</label>
									<output class="bwc_red" for="prostoId"></output>
									<input type="text" name="prostoId" style="display:none" />
									<input type="text" name="prosto" value="" style="display:none" />
									<label class="nerdy_p">Date/Time</label>
									<output class="bwc_red" for="dateTime"></output>
									<input type="datetime-local" name="dateTime" />
									<label for="tankId" class="nerdy_p">Tank</label>
									<output for="tankName" class="bwc_red"></output>
									<label for="tankId" class="nerdy_p">TankName | Prior Use | TankId</label>
									<select name="tank">
										<option value="" >Choose tank...</option>
									</select>
									<label for="type" class="nerdy_p">Prosto Type</label>
									<output class="bwc_red" for="type"></output>
									<select name="type">
										<option value="" >Choose type...</option>
										<?php
											foreach ($prosto_types as $type){
												echo '<option>'.$type->prostoType.'</option>';
											}
										?>
									</select>
									<label for="type" class="nerdy_p">Clearing Status</label>
									<output class="bwc_red" for="clearingStatus"></output>
									<input name="clearingStatus" type="number" min="0" max="1" step="1" placeholder="0-Open, 1-Clr"/>
									<input type="submit" value="Update Prosto" />
								</fieldset>
							</form>
						</div>

						<?php $kind = array();
							$kind["divStr"] = "prosto";
							$kind["thisTable"] = "prosto";
						show_transactions($kind);
						?>

					</div>
				</div>

			<!--prosto tanks-->
				<div class="bwc_panel" id="prostoTanksPanel" style="display:none">
					<div class="large-12 columns">
						<div class="large-6 columns" id="production_tank_explorer">
							<form id="production_tank_explorer" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Production Tank Explorer</legend>
									<label for="tankId" class="nerdy_p">ID | Name | Open Prosto: ID | Date | Type | PG</label>
									<input name="fields" value="tankId,tankName,tankMaterial,prostoId,dateTime,type,totalPg" style="display:none"/>
									<select name="tankId" id="production_tank_explorer">
										<option value="">Choose Tank...</option>
									</select>
							</form>
						</div>
						<div class="large-6 columns" id="create_production_tank">
							<form id="bwc_prosto_tank_new" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Create</legend>
									<input type="text" name="table" value="production_tanks_master" style="display:none" />
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<label for="tankId" class="nerdy_p">Capacity</label>
									<input type="number" name="tankCapacity" min="0" max="10000" step="0.01" placeholder="Capacity.."/>
									<label class="nerdy_p">Tank Name</label>
									<input type="text" name="tankName" placeholder="Slarty Bartfast..."/>
									<input type="submit" value="Create Prosto Tank"/>
								</fieldset>
							</form>
						</div>
					</div>

					<div class="large-12 columns details" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="production_tank_single" for="production_tank_single">Header</label>
								</li>
								<li>
									<input class="prostos" type="radio" name="toggle-radio-buttons"/>
									<label id="tank_prostos" for="tank_prostos">Tank's Prostos</label>
								</li>
							</ul>
						</div>

						<div class="large-12 columns detail" id="production_tank_single">
							<form id="production_tank_header_form" class="bwc_form">
								<fieldset name="prostoHeader" class="bwc_fieldset">
									<legend class="bwc_legend">Production Storage Tank</legend>
									<input type="text" name="table" value="production_tanks_master" style="display:none" />
									<input type="text" name="function" value="bwc_table_update2" style="display:none" />
									<input type="text" name="keys" value="tankId" style="display:none" />
									<label class="nerdy_p">Tank ID</label>
									<output class="bwc_red" for="tankId"></output>
									<label class="nerdy_p">Tank Name</label>
									<output class="bwc_red" for="tankName"></output>
									<input type="text" name="tankName" />
									<label for="tankMaterial" class="nerdy_p">Tank Material</label>
									<output class="bwc_red" for="tankMaterial"></output>
									<input type="text" name="tankMaterial" />
									<label for="tankCapacity" class="nerdy_p">Capacity</label>
									<output class="bwc_red" for="tankCapacity"></output>
									<input name="tankCapacity" type="number" min="0" max="10000" step="1" placeholder="(gal)"/>
									<input type="submit" value="Update Tank" />
								</fieldset>
							</form>
						</div>
						
						<div class="large-12 columns detail" id="tank_prostos" style="display:none">
							<input type="button"  id="create" value="Show Create Form" style="display:none"/>
							<form id="bwc_prosto_new" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Create</legend>
									<input type="text" name="table" value="prosto" style="display:none" />
									<label for="type" class="nerdy_p">Date/Time</label>
									<input type="datetime-local" name="dateTime" />
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<label for="type" class="nerdy_p">Prosto Type</label>
									<select name="type">
										<option value="" >Choose type...</option>
										<?php
											foreach ($prosto_types as $type){
												echo '<option>'.$type->prostoType.'</option>';
											}
										?>
									</select>
									<label for="tankId" class="nerdy_p">TankName | Prior Use | TankId</label>
									<input name="tankId" type="text" readonly />
									<input type="submit" value="Create New Prosto"/>
								</fieldset>
							</form>

							<table class="bwc_be_table">
								<tr>
									<th>ProstoId</th>
									<th>Date/Time</th>
									<th>PG</th>
									<th>Type</th>
									<th>ClearingStatus</th>
								</tr>
								<tr class="template" style="display:none">
									<td id="prostoId"></td>
									<td id="td_dateTime"></td>
									<td id="PG"></td>
									<td id="Type"></td>
									<td id="ClearingStatus"></td>
								</tr>
							</table>
						</div>
					</div>
				</div>

			<!--storage-->
				<div class="bwc_panel" id="storagePanel" style="display:none">
					<div class="large-12 columns">
						<div class="large-6 columns">
							<form id="bwc_storage_new" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Create Storage</legend>
									<input type="text" name="table" value="storage" style="display:none" />
									<input type="text" name="dateTime" style="display:none" />
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<select for="barrelId" name="barrel">
										<option value="">Choose barrel</option>
									</select>
									<label class="nerdy_p">Date/Time</label>
									<output for="dateTime" class="bwc_red dateTime"></output>
									<input type="datetime-local" name="dateTime" />
									<label for="type" class="nerdy_p">Storage Type</label>
									<output class="bwc_red" for="type"></output>
									<select name="type">
										<option value="" >Choose type...</option>
										<?php
											foreach ($prosto_types as $type){
												echo '<option>'.$type->prostoType.'</option>';
											}
										?>
									</select>
									<input type="submit" value="Create New Storage"/>
								</fieldset>	
							</form>
						</div>
						
						<div class="large-6 columns" id="storage_explorer">
							<form id="storage_explorer" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Storage Explorer</legend>
									<label for="storageId" class="nerdy_p">Type | Barrel | Cleared | StorageId | PG</label>
									<input name="fields" value="type,barrelName,clearingStatus,storageId,totalPg" style="display:none"/>
									<select name="storageId" id="storageExplorer">
										<option value="">Choose Explorer...</option>
									</select>
							</form>
						</div>
					</div>

					<div class="large-12 columns details" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="storage_single" for="storage_single">Header</label>
								</li>
								<li>
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="storage_notes" for="storage_notes">Notes</label>
								</li>
								<li>
								<li>
									<input class="transactions" type="radio" name="toggle-radio-buttons"/>
									<label id="storage_transactions" for="storage_transactions">Transactions</label>
								</li>
							</ul>
						</div>

						<div class="large-12 columns detail" id="storage_single">
							<form id="storage_header_form" class="bwc_form">
								<fieldset name="storageHeader" class="bwc_fieldset">
									<legend class="bwc_legend">Storage Header</legend>
									<input type="text" name="table" value="storage" style="display:none" />
									<input type="text" name="function" value="bwc_table_update2" style="display:none" />
									<input type="text" name="keys" value="storageId" style="display:none" />
									<label class="nerdy_p">Storage ID</label>
									<output class="bwc_red storageId"></output>
									<input type="text" name="storageId" style="display:none" />
									<label class="nerdy_p">Date/Time</label>
									<output for="dateTime" class="bwc_red dateTime"></output>
									<input type="datetime-local" name="dateTime" />
									<label for="barrelId" class="nerdy_p">Barrel</label>
									<output for="barrelName" class="bwc_red"></output>
									<select for="barrelId" name="barrel">
										<option value="" >Barrel Name | Prior Use</option>';
									</select>
									<label for="type" class="nerdy_p">Storage Type</label>
									<output class="bwc_red" for="type"></output>
									<select name="type">
										<option value="" >Choose type...</option>
										<?php
											foreach ($prosto_types as $type){
												echo '<option>'.$type->prostoType.'</option>';
											}
										?>
									</select>
									<label for="clearingStatus" class="nerdy_p">Clearing Status</label>
									<output for="clearingStatus" class="bwc_red"></output>
									<input name="clearingStatus" type="number" min="0" max="1" step="1" placeholder="0-Open, 1-Clr"/>
									<input type="submit" value="Update Storage" />	
								</fieldset>
							</form>
						</div>
		
						<?php $kind = array();
							$kind["divStr"] = "storage";
							$kind["thisTable"] = "storage";
						show_transactions($kind);
						?>

					</div>
				</div>
				
			<!--processing-->
				<div class="bwc_panel" id="processingPanel" style="display:none">
					<div class="large-12 columns">
						<div class="large-6 columns">
							<form id="bwc_process_new" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Create ProcessID</legend>
									<input type="text" name="table" value="processing" style="display:none" />
									<input type="text" name="dateTime" style="display:none" />
									<input type="text" name="function" value="bwc_table_insert3" style="display:none" />
									<select name="product">
										<option value="" >Product...</option>
										<?php
											foreach ($spirit_classes as $spirit) {
												echo '<option>'. $spirit->product .'</option>';
											}
										?>
									</select>
									<input type="submit" value="Create New Processing"/>
								</fieldset>
							</form>
						</div>
						
						<div class="large-6 columns" id="processing_explorer">
							<form id="processing_explorer" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Processing Explorer</legend>
									<label for="processId" class="nerdy_p">ProcessId | Date | Product | Bottling Proof</label>
									<input name="fields" value="processId,dateTime,product,bottlingProof" style="display:none"/>
									<select name="processId" id="processExplorer">
										<option value="">Choose Processing Run...</option>
									</select>
							</form>
						</div>
					</div>

					<div class="large-12 columns details" id="single" style="display:none">
						<div class="large-12 columns">
							<ul id="section-toggle" class="toggle-radio-buttons">
								<li>
									<input type="radio" name="toggle-radio-buttons" checked/>
									<label id="process_single" for="process_single">Header</label>
								</li>
								<li>
									<input type="radio" name="toggle-radio-buttons"/>
									<label id="process_rectification" for="process_rectification">Rectifying</label>
								</li>
								<li>
									<input class="transactions" type="radio" name="toggle-radio-buttons"/>
									<label id="processing_transactions" for="processing_transactions">Transactions</label>
								</li>
							</ul>
						</div>

						<div class="large-12 columns detail" id="process_single">
							<form id="process_header_form" class="bwc_form">
								<fieldset name="processHeader" class="bwc_fieldset">
									<legend class="bwc_legend">Header</legend>
									<input type="text" name="table" value="processing" style="display:none" />
									<input type="text" name="function" value="bwc_table_update2" style="display:none" />
									<input type="text" name="keys" value="processId" style="display:none" />
									<label class="nerdy_p">ProcessID</label>
									<output class="bwc_red" for="processId"></output>
									<input type="text" name="processId" style="display:none" />
									<label class="nerdy_p">Date/Time</label>
									<output class="bwc_red" for="dateTime"></output>
									<input type="datetime-local" name="dateTime" />
									<label for="type" class="nerdy_p">Product</label>
									<output class="bwc_red type" for="product"></output>
									<select name="product">
										<option value="">Choose product...</option>
										<?php
											foreach ($spirit_classes as $spirit) {
												echo '<option>'. $spirit->product .'</option>';
											}
										?>
									</select>
									<input type="submit" value="Update Process" />	
								</fieldset>
							</form>
						</div>

						<div class="large-12 columns detail" id="process_rectification" style="display:none">
							<form id="process_header_form" class="bwc_form">
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Proofing</legend>
									<input type="text" name="table" value="processing" style="display:none" />
									<input type="text" name="function" value="bwc_table_update2" style="display:none" />
									<input type="text" name="keys" value="processId" style="display:none" />
									<label class="nerdy_p">ProcessID</label>
									<input type="text" name="processId" id="id" readonly/>
									<label for="appProof" class="nerdy_p">App Proof Pre Blend</label>
									<output class="bwc_red type" for="appProof"></output>
									<input type="number" class="appProof" step="0.01" min="0" max="200" name="proofPreBlend" />
									<label for="temp" class="nerdy_p">Temp</label>
									<output class="bwc_red type" for="temp"></output>
									<input type="number" class="temp" step="0.01" min="0" max="200" name="temp" />
									<hr />

									<label for="proofPreBlend" class="nerdy_p">Proof Pre Blend</label>
									<output class="bwc_red type" for="proofPreBlend"></output>
									<input type="number" class="actProof" step="0.01" min="0" max="200" name="proofPreBlend" />
									<label for="weightPreBlend" class="nerdy_p">Weight Pre Blend</label>
									<output class="bwc_red type" for="weightPreBlend"></output>
									<input type="number" class="weight" step="0.01" min="0" max="10000" name="weightPreBlend" />
									<label for="pg" class="nerdy_p">PG</label>
									<input type="number" class="pg" step="0.01" min="0" max="10000" name="pg" readonly/>
									<hr />

									<label for="desiredProof" class="nerdy_p">Desired Proof</label>
									<output class="bwc_red type" for="desiredProof"></output>
									<input type="number" step="0.01" min="0" max="200" name="desiredProof" class="desiredProof"/>

									<input type="submit" value="Update" />	
									<input type="reset" value="Reset" />	
								</fieldset>

								<fieldset name="processHeader" class="bwc_fieldset">
									<legend class="bwc_legend">Rectifying Details</legend>
									<label class="nerdy_p">WG Before</label>
									<output class="bwc_red type" for="wg_before"></output>
									<input class="wg_before" type="number" step="0.01" min="0" max="10000" name="wg_before" />
									<label class="nerdy_p">WG After</label>
									<output class="bwc_red type" for="wg_after"></output>
									<input class="wg_after" type="number" step="0.01" min="0" max="10000" name="wg_after" />

									<label class="nerdy_p">Lbs H20 Added</label>
									<output class="bwc_red type" for="added_weight_h20"></output>
									<input class="added_weight_h20" type="number" step="0.01" min="0" max="10000" name="added_weight_h20" />
									<label class="nerdy_p">Gal H20 Added</label>
									<output class="bwc_red type" for="added_wg"></output>
									<input class="added_wg" type="number" step="0.01" min="0" max="10000" name="added_wg" />
									
									<label class="nerdy_p">Number of Bottles</label>
									<output class="bwc_red type" for="numbBottles"></output>
									<input class="numbBottles" type="number" step="0.01" min="0" max="10000" name="numbBottles" />
								</fieldset>
								<fieldset class="bwc_fieldset">
									<legend class="bwc_legend">Liqueur Proofing</legend>
									<label class="nerdy_p">Strength of Juice</label>
									<output class="bwc_red type" for="juiceStrength"></output>
									<input class="juiceStrength" type="number" step="0.01" min="0" max="10000" name="juiceStrength" />
									<label class="nerdy_p">Strength Molasses</label>
									<input class="molassesStrength" type="number" value="0.74" name="molassesStrength" readonly/>
									<hr/>

									<label class="nerdy_p">Sugar (oz) per Vol Liqueur (gal)</label>
									<input class="sugar_per_vol" type="number" value="18" name="sugar_per_vol" readonly />

									<label class="nerdy_p">% Molasses</label>
									<input class="sweetening_ratios" type="number" step=".01" min="0" max="1" name="percent_molasses" value="0.07">
									<label class="nerdy_p">% Sugar</label>
									<input class="sweetening_ratios" type="number" step=".01" min="0" max="1" name="percent_sugar" value=".65">
									<label class="nerdy_p">% AJ Sugar</label>
									<input class="sweetening_ratios" type="number" step=".01" min="0" max="1" name="percent_aj" value=".28">

									<label class="nerdy_p">Lbs Molasses</label>
									<input type="number" step=".01" min="0" max="10000" name="abs_molasses" />
									<label class="nerdy_p">Lbs Sugar</label>
									<input type="number" step=".01" min="0" max="10000" name="abs_sugar" />
									<label class="nerdy_p">Gal AJ</label>
									<input type="number" step=".01" min="0" max="10000" name="abs_aj" />

								</fieldset>
							</form>
						</div>


		
						<?php $kind = array();
							$kind["divStr"] = "processing";
							$kind["thisTable"] = "processing";
						show_transactions($kind);
						?>

					</div>
				</div>
			
			<!--tax-->
				<div class="bwc_panel" id="taxPanel" style="display:none">
					<div class="large-12 columns">
						<form class="bwc_form">
							<label class="nerdy_p">Date Start</label>
							<input type="date" name="date_st" />
							<label class="nerdy_p">Date End</label>
							<input type="date" name="date_end" />
						</form>	
						<ul id="tax-toggle" class="toggle-radio-buttons">
							<li class="first">
								<input type="radio" name="main_toggle"/>
								<label id="bwc_beginning_bulk_spirits">BOM Bulk</label>
							</li>
							<li>
								<input type="radio" name="main_toggle"/>
								<label id="bwc_transfer_in_bond">Transfer In Bond</label>
							</li>
							<li>
								<input type="radio" name="main_toggle"/>
								<label id="bwc_ending_bulk_spirits">EOM Bulk</label>
							</li>
						</ul>

						<div id="beginning_bulk_spirits" class="large-3 columns">
							<form>
								<label>Beg. of Month - PG in Bulk</label>
								<input type="text" name="total_pg" readonly></input>
<!-- 								<label></label>
								<input type="text" name="" readonly></input>
								<label></label>
								<input type="text" name="" readonly></input>
								<label></label>
								<input type="text" name="" readonly></input>
								<label></label>
								<input type="text" name="" readonly></input>
								<label></label>
								<input type="text" name="" readonly></input>
								<label></label>
								<input type="text" name="" readonly></input>
								<label></label>
								<input type="text" name="" readonly></input> -->
								<label>Reset</label>
								<input type="reset"/>
							</form>
						</div>
						
						<div id="bwc_transfer_in_bond" class="large-3 columns">
							<form>
								<label>Monthly Transfer in Bond</label>
								<input type="text" name="tib_pg" readonly></input>
								<label>Reset</label>
								<input type="reset"/>
							</form>
						</div>
						
						<div id="bwc_ending_bulk_spirits" class="large-3 columns">
							<form>
								<label>End of Month - PG in Bulk</label>
								<input type="text" name="total_pg" readonly></input>
								<label>Reset</label>
								<input type="reset"/>
							</form>
						</div>
					</div>
				</div>
				<!--end page content-->
			<?php endwhile; ?>				
		<?php endif;?>		
	<?php wp_reset_postdata();?>
	</div>



	<?php 
	function show_transactions($kind){//$kidn = [divStr:###, $kind:^^^]
		$divId = $kind["divStr"]."_transactions";
		$thisTable = $kind["thisTable"];

		echo '<div class="large-12 columns detail" id='.$divId.' class="transactions_div" style="display:none">
			<input type="button"  id="create" value="Show Create Form" style="display:none"/>
			<form id="create_transaction" class="bwc_form template">
				<div class="large-6 columns">
					<fieldset class="bwc_fieldset">
						<legend class="bwc_legend">Type and Destination</legend>
						<input type="text" name="function" value="bwc_add_transactions" style="display:none" />
						<input type="text" name="table" value="transactions" style="display:none"/>
						<input type="text" name="keys" value="transactionId" style="display:none"/>
						<input type="text" name="transactionId" style="display:none"/>
						<input type="text" name="thisId" style="display:none"/>
						<input type="text" name="thisTable" value='.$thisTable.' style="display:none"/>
						<select name="transactionType">
							<option value="">Choose transaction type</option>
							<option>Withdrawal</option>
							<option>Deposit</option>
							<option>Loss</option>
							<option>Destroyed</option>
						</select>
						<label class="nerdy_p">Other Table</label>
						<select name="otherTable">
							<option value="" class="placeholder">Choose Table...</option>
							<option value="fermentation">Fermentation</option>
							<option value="fermenter_master">Empty Fermenters</option>
							<option value="production">Production</option>
							<option value="prosto">Prosto</option>
							<option value="production_tanks_master">Empty Prosto Tanks</option>
							<option value="storage">Storage</option>
							<option value="barrel_master">Empty Barrels</option>
							<option value="process">Processing</option>
							<option value="inventory">Inventory</option>
						</select>
						<label class="nerdy_p">Other Entity</label>
						<select name="otherId" class="otherTableHiding">
							<option value="" class="placeholder">Other Entity</option>';
							foreach ($GLOBALS["spirit_classes"] as $class) {
								echo '<option class="inventory" >'.$class->product.'</option>';
							}
						echo '</select>
						<label class="nerdy_p">ThisId</label>
						<input type="text" name="thisId" readonly />
						<label class="nerdy_p">DateTime</label>
						<input type="datetime-local" name="dateTime" />
					</fieldset>
				</div>
				<div class="large-6 columns">
					<fieldset class="bwc_fieldset">
						<legend class="bwc_legend">Quantities</legend>
						<label class="nerdy_p">Apparent Proof</label>
						<input type="number" name="appProof" class="appProof" min="0" max="10000" step="0.01" placeholder="App Proof" />
						<label class="nerdy_p">Temp</label>
						<input type="number" name="temp" class="temp" min="0" max="10000" step="0.01" placeholder="Temp"/>
						<label class="nerdy_p">Actual Proof</label>
						<input type="number" name="actProof" class ="actProof" min="0" max="10000" step="0.01" placeholder="Act Proof"/>
						<label class="nerdy_p">Weight</label>
						<input type="number" name="weight" class="weight" min="-10000" max="10000" step="0.01" placeholder="Weight"/>
						<label class="nerdy_p">Proof-Gallons</label>
						<input type="number" name="pg" class="pg" min="-10000" step="0.01" placeholder="Auto..."/>
						<label class="nerdy_p">Gallons</label>
						<input type="number" name="gallons" min="-10000" max="10000" step="0.01" placeholder="Mash gallons.."/>
						<label class="nerdy_p">Bottles</label>
						<input type="number" name="bottles" min="-10000" max="10000" step="0.01" placeholder="Number of bottles..."/>
						<input type="submit" name="newTransaction" value="Add Transaction" />
					</fieldset>
				</div>
			</form>
			<table>
				<tr>
					<th>transId</th>
					<th>dateTime</th>
					<th>transType</th>
					<th>otherTable</th>
					<th>otherId</th>
					<th>actProof</th>
					<th>weight</th>
					<th>pg</th>
					<th>gallons</th>
					<th>bottles</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
				<tr class="template" style="display:none">
					<td id="transactionId"></td>
					<td id="td_dateTime"></td>
					<td id="transactionType"></td>
					<td id="otherTable"></td>
					<td id="otherId"></td>
					<td id="actProof"></td>
					<td id="weight"></td>
					<td id="pg"></td>
					<td id="gallons"></td>
					<td id="bottles"></td>
					<td id="edit">
						<form class="bwc_form">
							<input type="button" id="show_edit_form" value="Edit" />
						</form>
					</td>
					<td id="delete">
						<form class="bwc_form">
							<input name="function" value="bwc_delete_transaction2" style="display:none" />
							<input name="keys" value="transactionId" style="display:none" />
							<input name="transactionId" style="display:none" />
							<input type="submit" name="delete" value="Delete" />
						</form>
					</td>
				</tr>
			</table>
		</div>';
	}
?>
</html>