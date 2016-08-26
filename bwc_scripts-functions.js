///////////////////////////////
/////Ajax Queries to DB for Entities
///////////////////////////////

    function bwc_be_ajax_query(type, id){
        switch(type){
            //inventory
            case "inventory":
                var callback = bwc_callback_explorer_closure("div#inventory_explorer", "item");
                var data = {func:"bwc_be_inventory_items_query"};
                break;
            case "inventory_single":
                var callback = bwc_callback_generic_header_closure("div#inventory_item_header", {itemName:id});
                var data = {func:"bwc_be_inventory_item_read", item: id};
                break;
            case "inventory_deposits":
                var callback = bwc_callback_detail_initial("div#inventory_deposits", {item:id});
                var data = {func:"bwc_be_inventory_deposit_transactions_query", item: id};
                break;
            case "inventory_withdrawals":
                var callback = bwc_callback_detail_initial("div#inventory_item_withdrawals", {item:id});
                var data = {func:"bwc_be_inventory_withdrawal_transactions_query", item: id};
                break;    
            //mash
            case "mash":
                var callback = bwc_callback_explorer_closure("div#mash_explorer", "mashId");
                var data = {func:"bwc_be_ajax_mash_queries"};
                break;
            case "mash_single":
                var callback = bwc_callback_generic_header_closure("div#mash_header", {mashId:id});
                var data = {func:"bwc_be_ajax_mash_single", mashId: id};
                break;
            case "mash_header":
                var callback = bwc_callback_generic_header_closure("div#mash_header", {mashId:id});
                var data = {func:"bwc_be_ajax_mash_single", mashId: id};
                break;
            case "mash_steps":
                var callback = bwc_callback_detail_initial("div#mash_steps", {mashId:id});
                var data = {func:"bwc_be_ajax_mash_steps", mashId:id};
                break;
            case "mash_enzymes":
                var callback = bwc_callback_detail_initial("div#mash_enzymes", {mashId:id});
                var data = {func:"bwc_be_ajax_mash_enzyme_additions", mashId:id};
                break;
            case "mash_bill":
                var callback = bwc_callback_detail_initial("div#mash_bill", {mashId:id});
                var data = {func:"bwc_be_ajax_mash_bill_queries", mashId:id};
                break;
            case "mash_transactions":
                var callback = bwc_callback_detail_initial("div#mash_transactions", {mash:id});
                var data = {func:"bwc_fetch_transactions_single", thisTable:"mash", thisId:id};
                break;
            
            //fermentation
            case "fermentation_mass":
                var callback = bwc_callback_explorer_closure("div#fermentation_explorer", "fermId");
                var data = {func:"bwc_ajax_fermentation_mass"};
            break;
            case "fermenters":
                var callback = bwc_callback_fermenters;
                var data = {func:"bwc_ajax_fermenters"};
                break;
            case "ferm_single":
                var callback = bwc_callback_generic_header_closure("div#fermentation_detail", "fermId");
                var data = {func:"bwc_ajax_fermentation_single", fermId:id};
                break;
            case "fermentation_detail":
                var callback = bwc_callback_generic_header_closure("div#fermentation_detail", "fermId");
                var data = {func:"bwc_ajax_fermentation_single", fermId:id};
                break;    
            case "ferm_tts":
                var callback = bwc_callback_detail_initial("div#fermentation_time_temps", {fermId:id});
                var data = {func:"bwc_ajax_ferm_time_temps", fermId:id};
                break;
            case "fermentation_time_temps":
                var callback = bwc_callback_detail_initial("div#fermentation_time_temps", {fermId:id});
                var data = {func:"bwc_ajax_ferm_time_temps", fermId:id};
                break;
            case "ferm_transactions":
                var callback = bwc_callback_detail_initial("div#ferm_transactions", {fermId:id});
                var data = {func:"bwc_fetch_transactions_single", thisTable:"fermentation", thisId:id};
                break;

            //production
            case "prod_headers":
                var callback = bwc_callback_explorer_closure("div#production_explorer", "prodId");
                var data = {func:"bwc_be_ajax_prod_headers_query"};
                break;
            case "prod_single":
                var callback = bwc_callback_generic_header_closure("div#production_single", "prodId");
                var data = {func:"bwc_ajax_production_single", prodId:id};
                break;
            case "production_single":
                var callback = bwc_callback_generic_header_closure("div#production_single", "prodId");
                var data = {func:"bwc_ajax_production_single", prodId:id};
                break;
            case "prod_measurements":
                var callback = bwc_callback_detail_initial("div#prod_measurements", {prodId:id});
                var data = {func:"bwc_ajax_prod_measurements", prodId:id};
                break;
            case "prod_transactions":
                var callback = bwc_callback_detail_initial("div#prod_transactions", {prodId:id});
                var data = {func:"bwc_fetch_transactions_single", thisTable:"production", thisId:id};
                break;

            //prosto
            case "prosto":
                var callback = bwc_callback_explorer_closure("div#prosto_explorer", "prostoId");
                var data = {func:"bwc_ajax_prosto_mass"};
                break;
            case "prosto_tanks":
                var callback = bwc_callback_prosto_tanks;
                var data = {func:"bwc_ajax_prosto_tanks"};
                break;
            case "prosto_single":
                var callback = bwc_callback_generic_header_closure("div#prosto_single", "prostoId");
                var data = {func:"bwc_ajax_prosto_single", prostoId:id};
                break;
            case "prosto_transactions":
                var callback = bwc_callback_detail_initial("div#prosto_transactions", {prostoId:id});
                var data = {func:"bwc_fetch_transactions_single", thisTable:"prosto", thisId:id};
                break;

            //production storage tanks
            case "production_storage_tanks":
                var callback = bwc_callback_explorer_closure("div#production_tanks_explorer", "tankId");
                var data = {func:"bwc_ajax_production_storage_tanks"};
                break;
            case "production_storage_tank_single":
                var callback = bwc_callback_generic_header_closure("div#production_tank_single", "tankId");
                var data = {func:"bwc_production_tank_single", tankId:id};
                break;
            case "tank_prostos":
                var callback = bwc_callback_detail_initial("div#tank_prostos", {tankId:id});
                var data = {func:"bwc_tank_prostos"};
                break;

            //storage
            case "storage":
                var callback = bwc_callback_explorer_closure("div#storage_explorer", "storageId");
                var data = {func:"bwc_ajax_storage_mass"}
                break;
            case "barrels":
                var callback = bwc_callback_barrels;
                var data = {func:"bwc_ajax_barrels"};
                break;
            case "storage_single":
                var callback = bwc_callback_generic_header_closure("div#storage_single", "storageId");
                var data = {func:"bwc_ajax_storage", storageId:id};
                break;
            case "storage_transactions":
                var callback = bwc_callback_detail_initial("div#storage_transactions", {storageId:id});
                var data = {func:"bwc_fetch_transactions_single", thisTable:"storage", thisId:id};
                break;
            case "storage_notes":
                var callback = bwc_callback_detail_initial("div#storage_notes", {storageId:id});
                var data = {func:"bwc_ajax_storage_notes", storage:id};
                break;

            //processing
            case "processing":
                var callback = bwc_callback_explorer_closure("div#processing_explorer", "processId");
                var data = {func:"bwc_ajax_processing_mass"};
                break;
            case "process_single":
                var callback = bwc_callback_generic_header_closure("div#process_single", "processId");
                var data = {func:"bwc_ajax_processing_single", processId:id};    
                break;
            case "process_rectification":
                var callback = bwc_callback_generic_header_closure("div#process_rectification", "processId");
                var data = {func:"bwc_ajax_processing_single", processId:id};
                break;
            case "processing_transactions":
                var callback = bwc_callback_detail_initial("div#processing_transactions", {processId: id});
                var data = {func:"bwc_fetch_transactions_single", thisTable:"processing", thisId:id};
                break;

            //transactions selections
            case "transactions_selections":
                var callback = bwc_callback_transaction_selections();
                var data = {func:"bwc_transactions_selections"};
                break;
        }

        $.ajax({
                url        : "../bwc_be_ajaxhandler2",
                dataType   : 'json',
                contentType: 'application/json; charset=UTF-8',
                data       : data,
                type       : 'GET',
                success   : callback // etc
            });
    }



    function validate_submission(divStr, thisId, current_form){
        var err = false;
        var func = current_form.find("input[name=function]").val();
        switch(divStr){
            case 'inventory_deposits':
                switch(func){
                    case "bwc_delete_table2":
                        var subitem = current_form.find("input[name=subitem]").val();
                        if(subitem >= 1){
                            err = true;
                            alert("This deposit is partially cleared. Must reverse clearing to open before reversing deposit.");
                        }
                    break;
                    case "bwc_inventory_deposit_update":
                    break;
                    default:
                    break;
                } 
            break;
            default:
            break;
        }
        return err;
    }
////////////////////////////////
//////Closure functions for callbacks
////////////////////////////////
    var bwc_callback_explorer_closure = function(divStr, id) {
        return function(data, textStatus, jqXHR){
            bwc_callback_explorer(data, textStatus, jqXHR, divStr, id);    
        }
    };

    var bwc_tank_explorer_closure = function(divStr, id) {
        return function(data, textStatus, jqXHR){
            bwc_tank_explorer(data, textStatus, jqXHR, divStr, id);    
        }
    };

    var bwc_callback_generic_header_closure = function(divStr,id) {
        return function(data, textStatus, jqXHR){
            bwc_callback_generic_header(data, textStatus, jqXHR, divStr, id);    
        }  
    };
    
    var bwc_callback_detail_initial = function(divStr,id) {
        var updateFunc = "";
        switch(divStr){
            case "div#inventory_deposits":
                updateFunc = "bwc_inventory_deposit_update";
            break;
            default:
                updateFunc = "bwc_table_update2";
            break;
        }
        return function(data, textStatus, jqXHR){
            bwc_callback_detail(data, textStatus, jqXHR, divStr, id, updateFunc);    
        }  
    };

    var bwc_callback_transaction_selections = function (){
        return function(data, textStatus, jqXHR){
            bwc_fill_transaction_selections(data, textStatus, jqXHR);
        }
    }

    var bwc_entity_on_the_fly = function (context){
        return function (data, textStatus, jqXHR){
            bwc_select_new_entity(data, textStatus, jqXHR, context);
        }
    }   

    var bwc_callback_refresh_div = function(divStr, id){
        return function (data, textStatus, jqXHR){
            bwc_refresh_div(data, textStatus, jqXHR, divStr, id);
        }
    }

/////////////////////////////////////////////////
//Generic Callbacks
/////////////////////////////////////////////////

//Refresh div
 function bwc_refresh_div(data, textStatus, jqXHR, divStr, id){
    bwc_be_ajax_query(divStr, id);
 }

//Select otherId after creating new entity
 function bwc_select_new_entity(data, textStatus, jqXHR, context){
    if(textStatus=="success"){
        divStr = context.divStr;
        formId = context.formId;
        $(divStr).find("form#"+formId).find("select[name=otherId]").val(data);
    }
 }

//generic transaction selections fill
    function bwc_fill_transaction_selections(data, textStatus, jqXHR){
        select_otherId = $("select[name=otherId]");

        for(key in data){
            var assoc ="";
            switch(key){
                case "fermenter_master":
                    assoc = "fermentation";
                    break;
                case "production_tanks_master":
                    assoc = "prosto";
                    break;
                case "barrel_master":
                    assoc = "storage";
                    break;
            }
            for(var i = 0; i<data[key].length; i++){
                var row = data[key][i];
                if(!row._id){
                    select_otherId.append("<option class='empty " + key + "' value='" + assoc + "," + row.tankId + "' style='display:none'>" + row.tankName + "</option>")
                }
            }
        }
    }

//generic detail callback
    function bwc_callback_generic_header(data, textStatus, jqXHR, divStr, id){
        //some work must be done even if nothing found
        var div = $(divStr);
        var new_form = div.find("form");

        //some work contingent on elements in JSON response
        if(data.length != 0){
            var entities = data[0];
            var currentId = entities[id];

            for(key in entities){
                switch(key){
                    case "dateTime":
                        var shortDate = dateFormat(entities[key], "shorterDate");
                        var currentISODateString = dateFormat(entities[key], "isoDateTime");
                        new_form.find("input[name="+key+"]").val(currentISODateString);
                        new_form.find("output[for="+key+"]").text(shortDate);
                    break;
                    case "tdateTime":
                        var shortTime = dateFormat(entities[key], "shortTime");
                        var currentISODateString = dateFormat(entities[key], "isoDateTime");
                        new_form.find("input[name="+key+"]").val(currentISODateString);
                        new_form.find("output[for="+key+"]").text(shortTime);
                    break;
                    case "td_dateTime":
                        var shortTime = dateFormat(entities[key], "shortDateShortTime");
                        var currentISODateString = dateFormat(entities[key], "isoDateTime");
                        new_form.find("input[name="+key+"]").val(currentISODateString);
                        new_form.find("output[for="+key+"]").text(shortTime);
                    break;
                    default:
                        new_form.find("output[for="+key+"]").text(entities[key]);
                        new_form.find("input[name="+key+"]").val(entities[key]);
                        new_form.find("select[for=" + key + "]").val(entities[key]);
                    break;
                }
            }
        }
        //lastly, fill all currentId fields in the div with the id
        if(currentId != "" && currentId != null && currentId != 0){
            div.find("form").find("input[name="+id+"]").val(currentId);    
        }
    };


//generic explorer fill
    function bwc_callback_explorer(data, textStatus, jqXHR, divStr, id) {
        var div = $(divStr);
        var explorer = div.find("form[id*=explore]");
        var select = div.find("select");
        var fields = explorer.find("input[name=fields]").val().split(",");
        select.empty();
        select.append("<option value=''>Choose...</option>");

    //otherIds in transactions 
        var select_otherIds = $("select[name=otherId]");
        var otherTable = divStr.split("_")[0].split("#")[1];
        select_otherIds.find("option."+otherTable).remove();
        
        var entities = data;
        var texts = bwc_format_explorer_fields(entities, fields, id);
        for(var i=0; i<texts.length; i++){
            select.append("<option value='" + texts[i]._id + "'>" + texts[i]._text + "</option>");
            select_otherIds.append("<option class='" + otherTable + "' value='" + texts[i]._id + "'>" + texts[i]._text + "</option>");
        }
    }

//generic tank explorer
    function bwc_tank_explorer(data, textStatus, jqXHR, divStr, id){
        var div = $(divStr);
        var list = div.find("ul.clicklist-radio-buttons");
        var template_item = list.find("li#clicklist-template");
        var tanks = data;

        list.find("li.clone").remove();

        for(var i=0; i<tanks.length; i++){
            var clone = template_item.clone().appendTo(list);
            clone.attr("id", i),
            clone.attr("class", "clone");
            clone.show();

            for(key in tanks[i]){
            //format the value according to field type, and put into any outputs
                if(clone.find("output[for=" + key + "]").length || clone.find("label[for=" + key + "]").length || clone.find("div[for=" + key + "]").length){
                    var html_current = bwc_format_element(key, tanks[i][key]);
                    clone.find("output[for=" + key + "]").text(html_current);
                    clone.find("label[for=" + key + "]").text(html_current);
                    clone.find("div[for=" + key + "]").html(html_current);
                };
            }
        }
    }

    function bwc_callback_detail(data, textStatus, jqXHR, divStr, id, updateFunc){
        var div = $(divStr);
        var table = div.find("table");
        div.find("form[class=clone]").remove();
        table.find("tr[class=clone]").remove();
        for(key in id){
            var id_key = key;
            var currentId = id[key];
        }
        
    //build table of details
        var entities = data;
        if(entities.length > 0){
            for(var i=0;i<entities.length;i++){
                //clone template row for table display
                var this_row = table.find("tr.template").clone().appendTo(table);
                this_row.attr({id:i, 'class':"clone"});
                this_row.removeAttr("style");
                this_row.find("td#edit").find("form").attr({id:i});
                childKeysConc = this_row.find("td#delete").find("input[name=keys]").val();
                childKeys = childKeysConc.split(",");
             
                // clone from for edit
                var new_form = table.siblings("form.template").clone().appendTo(div);
                new_form.attr({id:i, 'class':"clone", style:"display:none"});
                new_form.find("input[type=submit]").val("Update");
                //new_form.find("input[name=function]").val("bwc_table_update2");
                new_form.find("input[name=function]").val(updateFunc);
                new_form.find("legend").text("Change Existing Measurement");

                for(key in entities[i]){
                    if(key!="dateTime"){
                        this_row.find("td#"+key).text(entities[i][key]);
                        new_form.find("input[name="+key+"]").val(entities[i][key]);
                        new_form.find("output[for="+key+"]").text(entities[i][key]);
                        new_form.find("select[name="+key+"]").val(entities[i][key]);
                    }
                    else{
                        this_row.find("td#"+key).text(entities[i][key]);
                        new_form.find("output[for="+key+"]").text(entities[i][key]);
                        var currentISODateString = dateFormat(entities[i][key], "isoDateTime")
                        new_form.find("input[name="+key+"]").val(currentISODateString);
                    }
                    if(childKeys.indexOf(key) >= 0){
                        childKey = this_row.find("td#delete").find("input[name="+key+"]").val(entities[i][key]);

                    }
                }
            }
            table.appendTo(div);
        }
        //lastly, fill all currentId fields in the div with the id
        if(currentId != "" && currentId != null && currentId != 0){
            div.find("form").find("input[name="+id_key+"]").val(currentId);
            div.find("form").find("input[name=thisId]").val(currentId);
        }
    }

///////////////////////////////////////
//////Idiosyncratic callbacks
///////////////////////////////////////
//Mash Callbacks
    function bwc_callback_mash(data){
        var mashes=data;
        if(mashes.length > 0){
            $("select#mashExplorer").empty();
            $("select#mashExplorer").append("<option value=''>Choose Mash</option>");
            for(var i=0;i<mashes.length; i++){
                $("select#mashExplorer").append("<option value='" + mashes[i].mashId + "'>" + mashes[i].mashId + " | " + mashes[i].mashDateTime + " | " + mashes[i].mashType + "</option>");
            }
        }
    }

//ferm callback    
    function bwc_callback_fermenters(data){
        var fermenters=data;
        //create list of items for each fermenter
        if(fermenters.length>0){
            for(var i=0;i<fermenters.length;i++){
                $("form#ferm_explorer_filter").find("label[for=fermenter]").after("<div class='large-12 columns fermenter_toggle' id='" + fermenters[i].fermenterName + "'><h2>" + fermenters[i].fermenterName + "</h2></div>");
            }
        }
    }

//Prosto Callback
    function bwc_callback_prosto_tanks(data){
        var selects = $("div#prostoPanel").find("select[name=tank]");
        selects.empty();
        selects.append("<option value=''>Choose Tank...</option>");

        var tanks =data;
        if(tanks.length>0){
            for(var i=0; i<tanks.length; i++){
                //for(var j=0; j<selects.length; j++){
                    selects.append("<option class='tank' value='" + tanks[i].tankId + "'>" + tanks[i].tankName + " | " + tanks[i].priorUse + " | " + tanks[i].tankId + "</option>");
                //}
            }
        }
    }

//Storage Callback
    function bwc_callback_barrels(data){
        var barrels =data;
        var selects = $("div#storagePanel").find("select[for=barrelId]");
        selects.empty();
        selects.append("<option value=''>Choose Barrel...</option>");
        if(barrels.length>0){
            for(var i=0; i<barrels.length; i++){
                selects.append("<option value='" + barrels[i].barrelId + "'>" + barrels[i].barrelName + " | " + barrels[i].priorUse + "</option>");
            }
        }
    }

/////////////////////////////////////////////////
//Tax Config
/////////////////////////////////////////////////
    var tax_config = {
        "beginning_bulk_spirits":prod_transfer_in_bond_pg
    };


/////////////////////////////////////////////////
//Tax Functions
/////////////////////////////////////////////////
    function bwc_tax_ajax_query(func, date_st, date_end){
        var callback = bwc_callback_tax_closure(func, date_st, date_end);
        var data = {func:"bwc_transactions_all"};

        $.ajax({
                url        : "../bwc_be_ajaxhandler2",
                dataType   : 'json',
                contentType: 'application/json; charset=UTF-8',
                data       : data,
                type       : 'GET',
                success    : callback
            });
    }

    var bwc_callback_tax_closure = function(divStr, date_st, date_end) {
        return function(data, textStatus, jqXHR){
            var funcStr = tax_config.divStr;

            if(textStatus == 200){
                var processed_response = funcStr(date_st, date_end, data, divStr);
                bwc_tax_output_display(processed_response);
            }
        };
    };

    function bwc_tax_output_display(data, divStr){
        var outputDiv = $(divStr);
        outputDiv.find("input[type=reset").click();

        for(key in data[key]){
            outputDiv.find("input[name="+key+"]").value(data[key]);
        }
    }

    var period_trans_pg_by_type = function(date_st, date_end, trans_join, divStr){
        if(trans_prosto_join.length == 0){
            return;
        }

        var whiskey_pg_dep = 0, brandy_pg_dep = 0, gin_pg_dep = 0;
        var whiskey_pg_wth = 0, brandy_pg_wth = 0, gin_pg_wth = 0;
        var whiskey_pg_loss = 0, brandy_pg_loss = 0, gin_pg_loss = 0;
        var whiskey_pg_dest = 0, brandy_pg_dest = 0, gin_pg_dest = 0;

        for(var i = 0; i < trans_prosto_join.length; i++){
            if(trans_prosto_join[i][dateTime] > date_st && trans_prosto_join[i][dateTime] <= date_end){
                if(trans_prosto_join[i][transactionType] == "Deposit"){
                    switch(trans_prosto_join[i][type]){
                        case "Whiskey Spirit":
                            whiskey_pg_dep += trans_prosto_join[i][pg];
                            break;
                        case "Brandy Spirit":
                            brandy_pg_dep += trans_prosto_join[i][pg];
                            break;
                        case "Flavored Brandy Spirit":
                            brandy_pg_dep += trans_prosto_join[i][pg];
                            break;
                        case "Gin Spirit":
                            gin_pg_dep += trans_prosto_join[i][pg];
                            break;
                    }
                }
                else if(trans_prosto_join[i][transactionType] == "Withdrawal"){
                    switch(trans_prosto_join[i][type]){
                        case "Whiskey Spirit":
                            whiskey_pg_wth += trans_prosto_join[i][pg];
                            break;
                        case "Brandy Spirit":
                            brandy_pg_wth += trans_prosto_join[i][pg];
                            break;
                        case "Flavored Brandy Spirit":
                            brandy_pg_wth += trans_prosto_join[i][pg];
                            break;
                        case "Gin Spirit":
                            gin_pg_wth += trans_prosto_join[i][pg];
                            break;
                    }
                }
                else if(trans_prosto_join[i][transactionType] == "Loss" || trans_prosto_join[i][transactionType] == "Destroyed"){
                    switch(trans_prosto_join[i][type]){
                        case "Whiskey Spirit":
                            whiskey_pg_loss += trans_prosto_join[i][pg];
                            break;
                        case "Brandy Spirit":
                            brandy_pg_loss += trans_prosto_join[i][pg];
                            break;
                        case "Flavored Brandy Spirit":
                            brandy_pg_loss += trans_prosto_join[i][pg];
                            break;
                        case "Gin Spirit":
                            gin_pg_loss += trans_prosto_join[i][pg];
                            break;
                    }
                }
            }
        }

        var pg_object =
            {   
                "whiskey_deposits":whiskey_pg_dep, 
                "brandy_deposits":brandy_pg_dep, 
                "gin_deposits":gin_pg_dep,
                "whiskey_withdrawals":whiskey_pg_wth, 
                "brandy_withdrawals":brandy_pg_wth, 
                "gin_withdrawals":gin_pg_wth,
                "whiskey_losses":whiskey_pg_loss, 
                "brandy_losses":brandy_pg_loss, 
                "gin_losses":gin_pg_loss
            };
        return pg_object;
    };

    var prod_transfer_in_bond_pg = function(trans_prod_join){
        if(trans_prod_join.length == 0){
            return;
        }

        var tib_pg = 0;

        for(var i = 0; i < trans_prod_join.length; i++){
            if(trans_prod_join[i][transactionType] == "Withdrawal"){
                tib_pg += trans_prosto_join[i][pg];
            }
        }
        return {"tib_pg":tib_pg};
    }

    var total_pg = function(transactions){
        if(transactions.length == 0){
            return;
        }
        
        var total_pg = 0;

        for(var i = 0; i < transactions.length; i++){
            total_pg += transactions[i][pg];    
        }

        return {"total_pg":total_pg};
    };

/////////////////////////////////////////////////
//Misc Services
/////////////////////////////////////////////////
    function return_tax_dates(tax_date, id_str){
        var date_mm = dateFormat(tax_date, "isoDate");
        var return_obj = new Object;
        return_obj[id_str] = date_mm;
        return return_obj;
    }
    
/////////////////////////////////////////////////
//Spirits Gauging Calculations
/////////////////////////////////////////////////
    function proof_by_pg_and_weight(pg, weight){
        var pg_per_pound = pg/weight;
        for(var i = 0; i < tab4_inverse.length; i++){
            if(tab4_inverse[i].pg > pg_per_pound){
                var high_proof = tab4_inverse[i].proof;
                var low_proof = tab4_inverse[i-1].proof;
                var weightage_high = (tab4_inverse[i].pg - pg_per_pound)/(tab4_inverse[i].pg - tab4_inverse[i-1].pg);
                var return_proof = high_proof * weightage_high + low_proof * (1 - weightage_high);
                var return_proof_round = Math.round(return_proof * 100)/100;
                return return_proof_round;
            }
        }
    }

    function bottle_750s_per_WG(wg){
        var bottles_750 = wg * 3.78 *(1/0.75);
            return bottles_750;
    }

    function wine_gallons_per_pound_at_proof(proof){
        var proof_down = Math.floor(Number(proof/2)),
            proof_up = Number(proof_down) + 1,
            down = tab4[proof_down],
            up = tab4[proof_up];
            
        var WG_per_pound = (down.wg + up.wg) / 2;
            return WG_per_pound;
    }

    function wg_pg_per_pound_at_proof(proof){
        var proof_down = Math.floor(Number(proof/2)),
            proof_up = Number(proof_down) + 1,
            down = tab4[proof_down],
            up = tab4[proof_up];
            
        var wg_per_pound = (down.wg + up.wg) / 2,
            pg_per_pound = (down.pg + up.pg) / 2;

            return {wg_per_pound, pg_per_pound};
    }

    function find_parts_h20_alc_per_100(origProof){
        var a = -0.000371454,
            b = -0.420056266,
            c = 99.41019199;
        var partsWater = a*origProof*origProof + b*origProof + c;
        var partsAlc = origProof*0.5;
        return {partsWater:partsWater, partsAlc:partsAlc};
    }

    function calcActProof(appProof, abcs, weightage){
        var highEstimate = (abcs.abc.a*appProof*appProof + abcs.abc.b*appProof + abcs.abc.c);
        var lowEstimate = (abcs.prevabc.a*appProof*appProof + abcs.prevabc.b*appProof + abcs.prevabc.c);
        var actProof = Math.round(((1-weightage)*lowEstimate + weightage*highEstimate)*100)/100;
        return actProof;
    }

    function pg_determintion_proof_range(actProof){
        var weightage = ((actProof)%20)/20;
        if(actProof <= 80){
            return {proofRange:"1", weightage:weightage};
        }
        else if(actProof<=100){
            return {proofRange:"2", weightage:weightage};
        }
        else if(actProof<=120){
            return {proofRange:"3", weightage:weightage};
        }
        else if(actProof<=140){
            return {proofRange:"4", weightage:weightage};
        }
        else if(actProof<=160){
            return {proofRange:"5", weightage:weightage};
        }
        else if(actProof){
            return {proofRange:"6", weightage:weightage};
        }
    }

    var tab4 = {
        "1":{pg:0.00241, wg:0.12025},
        "2":{pg:0.00482, wg:0.12043},
        "3":{pg:0.00724, wg:0.12061},
        "4":{pg:0.00966, wg:0.12078},
        "5":{pg:0.01209, wg:0.12094},
        "6":{pg:0.01453, wg:0.1211},
        "7":{pg:0.01698, wg:0.12126},
        "8":{pg:0.01943, wg:0.12141},
        "9":{pg:0.02188, wg:0.12156},
        "10":{pg:0.02434, wg:0.1217},
        "11":{pg:0.02681, wg:0.12185},
        "12":{pg:0.02928, wg:0.12199},
        "13":{pg:0.03175, wg:0.12213},
        "14":{pg:0.03423, wg:0.12226},
        "15":{pg:0.03672, wg:0.1224},
        "16":{pg:0.03921, wg:0.12253},
        "17":{pg:0.0417, wg:0.12266},
        "18":{pg:0.0442, wg:0.12278},
        "19":{pg:0.04671, wg:0.12291},
        "20":{pg:0.04921, wg:0.12303},
        "21":{pg:0.05173, wg:0.12316},
        "22":{pg:0.05425, wg:0.12329},
        "23":{pg:0.05677, wg:0.12342},
        "24":{pg:0.0593, wg:0.12355},
        "25":{pg:0.06185, wg:0.12369},
        "26":{pg:0.06439, wg:0.12382},
        "27":{pg:0.06694, wg:0.12396},
        "28":{pg:0.0695, wg:0.1241},
        "29":{pg:0.07206, wg:0.12424},
        "30":{pg:0.07463, wg:0.12439},
        "31":{pg:0.07721, wg:0.12454},
        "32":{pg:0.07981, wg:0.1247},
        "33":{pg:0.08241, wg:0.12486},
        "34":{pg:0.08502, wg:0.12503},
        "35":{pg:0.08764, wg:0.1252},
        "36":{pg:0.09027, wg:0.12538},
        "37":{pg:0.09292, wg:0.12557},
        "38":{pg:0.09558, wg:0.12576},
        "39":{pg:0.09825, wg:0.12596},
        "40":{pg:0.10093, wg:0.12616},
        "41":{pg:0.10228, wg:0.12627},
        "42":{pg:0.10634, wg:0.12659},
        "43":{pg:0.10906, wg:0.12681},
        "44":{pg:0.1118, wg:0.12704},
        "45":{pg:0.11454, wg:0.12727},
        "46":{pg:0.11731, wg:0.12751},
        "47":{pg:0.12009, wg:0.12776},
        "48":{pg:0.12289, wg:0.12801},
        "49":{pg:0.1257, wg:0.12827},
        "50":{pg:0.12853, wg:0.12853},
        "51":{pg:0.13138, wg:0.1288},
        "52":{pg:0.13424, wg:0.12908},
        "53":{pg:0.13712, wg:0.12936},
        "54":{pg:0.14001, wg:0.12964},
        "55":{pg:0.14293, wg:0.12994},
        "56":{pg:0.14586, wg:0.13023},
        "57":{pg:0.1488, wg:0.13053},
        "58":{pg:0.15177, wg:0.13084},
        "59":{pg:0.15476, wg:0.13115},
        "60":{pg:0.15776, wg:0.13147},
        "61":{pg:0.16078, wg:0.13179},
        "62":{pg:0.16383, wg:0.13212},
        "63":{pg:0.16689, wg:0.13245},
        "64":{pg:0.16997, wg:0.13279},
        "65":{pg:0.17307, wg:0.13313},
        "66":{pg:0.17619, wg:0.13348},
        "67":{pg:0.17935, wg:0.13384},
        "68":{pg:0.18251, wg:0.1342},
        "69":{pg:0.18569, wg:0.13456},
        "70":{pg:0.18892, wg:0.13494},
        "71":{pg:0.19214, wg:0.13531},
        "72":{pg:0.19541, wg:0.1357},
        "73":{pg:0.19869, wg:0.13609},
        "74":{pg:0.20201, wg:0.13649},
        "75":{pg:0.20534, wg:0.13689},
        "76":{pg:0.20871, wg:0.13731},
        "77":{pg:0.21209, wg:0.13772},
        "78":{pg:0.21551, wg:0.13815},
        "79":{pg:0.21897, wg:0.13859},
        "80":{pg:0.22245, wg:0.13903},
        "81":{pg:0.22596, wg:0.13948},
        "82":{pg:0.2295, wg:0.13994},
        "83":{pg:0.23308, wg:0.14014},
        "84":{pg:0.2367, wg:0.14089},
        "85":{pg:0.24035, wg:0.14138},
        "86":{pg:0.24405, wg:0.14189},
        "87":{pg:0.24779, wg:0.14241},
        "88":{pg:0.25156, wg:0.14293},
        "89":{pg:0.25539, wg:0.14348},
        "90":{pg:0.25927, wg:0.14404},
        "91":{pg:0.26321, wg:0.14462},
        "92":{pg:0.2672, wg:0.14522},
        "93":{pg:0.27126, wg:0.14584},
        "94":{pg:0.27542, wg:0.1465},
        "95":{pg:0.27964, wg:0.14718},
        "96":{pg:0.28397, wg:0.1479},
        "97":{pg:0.28953, wg:0.14866},
        "98":{pg:0.29296, wg:0.14947},
        "99":{pg:0.29767, wg:0.15034},
        "100":{pg:0.30258, wg:0.15129}
    }

    var tab4_inverse = [
        {pg:0.00241, wg:0.12025,proof:2},
        {pg:0.00482, wg:0.12043,proof:4},
        {pg:0.00724, wg:0.12061,proof:6},
        {pg:0.00966, wg:0.12078,proof:8},
        {pg:0.01209, wg:0.12094,proof:10},
        {pg:0.01453, wg:0.1211,proof:12},
        {pg:0.01698, wg:0.12126,proof:14},
        {pg:0.01943, wg:0.12141,proof:16},
        {pg:0.02188, wg:0.12156,proof:18},
        {pg:0.02434, wg:0.1217,proof:20},
        {pg:0.02681, wg:0.12185,proof:22},
        {pg:0.02928, wg:0.12199,proof:24},
        {pg:0.03175, wg:0.12213,proof:26},
        {pg:0.03423, wg:0.12226,proof:28},
        {pg:0.03672, wg:0.1224,proof:30},
        {pg:0.03921, wg:0.12253,proof:32},
        {pg:0.0417, wg:0.12266,proof:34},
        {pg:0.0442, wg:0.12278,proof:36},
        {pg:0.04671, wg:0.12291,proof:38},
        {pg:0.04921, wg:0.12303,proof:40},
        {pg:0.05173, wg:0.12316,proof:42},
        {pg:0.05425, wg:0.12329,proof:44},
        {pg:0.05677, wg:0.12342,proof:46},
        {pg:0.0593, wg:0.12355,proof:48},
        {pg:0.06185, wg:0.12369,proof:50},
        {pg:0.06439, wg:0.12382,proof:52},
        {pg:0.06694, wg:0.12396,proof:54},
        {pg:0.0695, wg:0.1241,proof:56},
        {pg:0.07206, wg:0.12424,proof:58},
        {pg:0.07463, wg:0.12439,proof:60},
        {pg:0.07721, wg:0.12454,proof:62},
        {pg:0.07981, wg:0.1247,proof:64},
        {pg:0.08241, wg:0.12486,proof:66},
        {pg:0.08502, wg:0.12503,proof:68},
        {pg:0.08764, wg:0.1252,proof:70},
        {pg:0.09027, wg:0.12538,proof:72},
        {pg:0.09292, wg:0.12557,proof:74},
        {pg:0.09558, wg:0.12576,proof:76},
        {pg:0.09825, wg:0.12596,proof:78},
        {pg:0.10093, wg:0.12616,proof:80},
        {pg:0.10228, wg:0.12627,proof:82},
        {pg:0.10634, wg:0.12659,proof:84},
        {pg:0.10906, wg:0.12681,proof:86},
        {pg:0.1118, wg:0.12704,proof:88},
        {pg:0.11454, wg:0.12727,proof:90},
        {pg:0.11731, wg:0.12751,proof:92},
        {pg:0.12009, wg:0.12776,proof:94},
        {pg:0.12289, wg:0.12801,proof:96},
        {pg:0.1257, wg:0.12827,proof:98},
        {pg:0.12853, wg:0.12853,proof:100},
        {pg:0.13138, wg:0.1288,proof:102},
        {pg:0.13424, wg:0.12908,proof:104},
        {pg:0.13712, wg:0.12936,proof:106},
        {pg:0.14001, wg:0.12964,proof:108},
        {pg:0.14293, wg:0.12994,proof:110},
        {pg:0.14586, wg:0.13023,proof:112},
        {pg:0.1488, wg:0.13053,proof:114},
        {pg:0.15177, wg:0.13084,proof:116},
        {pg:0.15476, wg:0.13115,proof:118},
        {pg:0.15776, wg:0.13147,proof:120},
        {pg:0.16078, wg:0.13179,proof:122},
        {pg:0.16383, wg:0.13212,proof:124},
        {pg:0.16689, wg:0.13245,proof:126},
        {pg:0.16997, wg:0.13279,proof:128},
        {pg:0.17307, wg:0.13313,proof:130},
        {pg:0.17619, wg:0.13348,proof:132},
        {pg:0.17935, wg:0.13384,proof:134},
        {pg:0.18251, wg:0.1342,proof:136},
        {pg:0.18569, wg:0.13456,proof:138},
        {pg:0.18892, wg:0.13494,proof:140},
        {pg:0.19214, wg:0.13531,proof:142},
        {pg:0.19541, wg:0.1357,proof:144},
        {pg:0.19869, wg:0.13609,proof:146},
        {pg:0.20201, wg:0.13649,proof:148},
        {pg:0.20534, wg:0.13689,proof:150},
        {pg:0.20871, wg:0.13731,proof:152},
        {pg:0.21209, wg:0.13772,proof:154},
        {pg:0.21551, wg:0.13815,proof:156},
        {pg:0.21897, wg:0.13859,proof:158},
        {pg:0.22245, wg:0.13903,proof:160},
        {pg:0.22596, wg:0.13948,proof:162},
        {pg:0.2295, wg:0.13994,proof:164},
        {pg:0.23308, wg:0.14014,proof:166},
        {pg:0.2367, wg:0.14089,proof:168},
        {pg:0.24035, wg:0.14138,proof:170},
        {pg:0.24405, wg:0.14189,proof:172},
        {pg:0.24779, wg:0.14241,proof:174},
        {pg:0.25156, wg:0.14293,proof:176},
        {pg:0.25539, wg:0.14348,proof:178},
        {pg:0.25927, wg:0.14404,proof:180},
        {pg:0.26321, wg:0.14462,proof:182},
        {pg:0.2672, wg:0.14522,proof:184},
        {pg:0.27126, wg:0.14584,proof:186},
        {pg:0.27542, wg:0.1465,proof:188},
        {pg:0.27964, wg:0.14718,proof:190},
        {pg:0.28397, wg:0.1479,proof:192},
        {pg:0.28953, wg:0.14866,proof:194},
        {pg:0.29296, wg:0.14947,proof:196},
        {pg:0.29767, wg:0.15034,proof:198},
        {pg:0.30258, wg:0.15129,proof:200}
    ]

    function tab4_pg_determination(proof, weight){
        var tab4 = tab4;

        var highRange = Math.round(proof/2);
        var highWeight = ((proof-highRange)/proof)*-1;
        var lowWeight = 1/highWeight;
        var lowRange = (Number(highRange)-1).toString();

        var wgHigh = per_lb[highRange].wg*weight,
            pgHigh = per_lb[highRange].wg*weight,
            wgLow = per_lb[lowRange].wg*weight,
            pgLow = per_lb[lowRange].wg*weight;

        var pg = pgHigh * highWeight + pgLow * lowWeight;
        var wg = wgHigh * highWeight + wgLow * lowWeight;
        
        pg_round = Math.round(pg*100)/100;
        wg_round = Math.round(wg*100)/100;
        return {pg:pg_round, wg:wg_round};
    }

   function pg_determination(proof, proofRangeWeightage, weight){
        var mb = {
            "1":{m:0.001258091583,b:-0.000515647986},
            "2":{m:0.0013704,b:-0.008745},
            "3":{m:0.00145,b:-0.01652},
            "4":{m:0.0015444,b:-0.027631},
            "5":{m:0.0016594,b:-0.043484},
            "6":{m:0.002019948454,b:-0.1022010309},
        };
        if(Number(proofRangeWeightage.proofRange)>1 && Number(proofRangeWeightage.proofRange)<7){
            var lowProofRange = (Number(proofRangeWeightage.proofRange)-1).toString();
        }
        else {
            var lowProofRange = proofRangeWeightage.proofRange;    
        }
        var mbHigh=mb[proofRangeWeightage.proofRange],
            mbLow=mb[lowProofRange];
        var pg = (mbHigh.m*proof+mbHigh.b)*weight*(1-proofRangeWeightage.weightage) + (mbLow.m*proof + mbLow.b)*weight*(proofRangeWeightage.weightage);
        pg_round = Math.round(pg*100)/100;
        return pg_round;
    }

    function temp_correction_proof_range(appProof){
        if(appProof <= 80){
            return "00";
        }
        else if(80<appProof<=120){
            return "01";
        }
        else if(120<appProof<=200){
            return "02";
        }
    }

    function temp_correction_temp_range(temp){
        var weightage = ((temp-1)%5)/5;
        if(temp<41){
            return {tempRange:"0", weightage:weightage};
        }
        else if(temp<46){
            return {tempRange:"1", weightage:weightage};
        }
        else if(temp<51){
            return {tempRange:"2", weightage:weightage};
        }
        else if(temp<56){
            return {tempRange:"3", weightage:weightage};
        }
        else if(temp==60){
            return {tempRange:"60", weightage:weightage};
        }
        else if(temp<61){
            return {tempRange:"4", weightage:weightage};
        }
        else if(temp<66){
            return {tempRange:"5", weightage:weightage};
        }
        else if(temp<71){
            return {tempRange:"6", weightage:weightage};
        }
        else if(temp<76){
            return {tempRange:"7", weightage:weightage};
        }
        else if(temp<81){
            return {tempRange:"8", weightage:weightage};
        }
        else {
            return {tempRange:"9", weightage:weightage};
        }
    }

    function temp_correction_equation_get(proofRange, tempRange){
        var abc = {"000":{a:-0.00363879,b:1.46690968,c:-5.98106944}, "001":{a:-0.00278,b:1.347782,c:-4.25562}, "002":{a:-0.00179976,b:1.22740036,c:-2.88014653}, "003":{a:-0.00041,b:1.062342,c:-0.52898}, "004":{a:-0.0000017,b:0.992863,c:-0.02405}, "005":{a:0.000532,b:0.915172,c:0.554193}, "006":{a:0.00053137,b:0.88920513,c:-0.000084665}, "007":{a:0.000900326,b:0.831593722,c:-0.0079837}, "008":{a:0.001278,b:0.773738,c:-0.01074}, "009":{a:0.001621,b:0.717263,c:-0.01554}, "010":{a:-0.0011962,b:1.209150995,c:-0.997765}, "011":{a:-0.00089,b:1.156575,c:-0.90172}, "012":{a:-0.00057773,b:1.10399807,c:-0.80568223}, "013":{a:-0.00034,b:1.061623,c:-1.05832}, "014":{a:0.000054,b:0.990575,c:-0.02651}, "015":{a:0.000352,b:0.939144,c:-0.00127}, "016":{a:0.00062313,b:0.89047822,c:-0.00226079}, "017":{a:0.000894386,b:0.841628148,c:-0.00325674}, "018":{a:0.001179,b:0.791427,c:-0.00428}, "019":{a:0.001467,b:0.740416,c:-0.00531}, "020":{a:-0.00050962,b:1.120882782,c:-0.20413205}, "021":{a:-0.00037,b:1.089338,c:-0.20499}, "022":{a:-0.00024155,b:1.05830459,c:-0.20584109}, "023":{a:-0.000144252,b:1.039550207,c:-1.334635818}, "024":{a:-0.000010750,b:1.006275561,c:-1.038401105}, "025":{a:0.000159,b:0.961969,c:-0.00053}, "026":{a:0.00028374,b:0.93148415,c:-0.00090544}, "027":{a:0.000485541,b:0.889126457,c:-0.00143269}, "028":{a:0.000533,b:0.869967,c:-0.00168}, "029":{a:0.000663,b:0.837986,c:-0.00209}, "0060":{a:0,b:1,c:0}, "0160":{a:0,b:1,c:0}, "0260":{a:0,b:1,c:0}};
        //var abc = {"000":{a:-0.00359156466686899,b:1.4626883900066,c:-5.9009273299589}, "001":{a:-0.00278179,b:1.34778192,c:-4.25561947}, "002":{a:-0.00180362932332375,b:1.2277561341546,c:-2.88728917839347}, "003":{a:-0.000409903642413515,b:1.06260320122008,c:-0.534214198981523}, "004":{a:-0.0000017,b:0.992863,c:-0.02405}, "005":{a:0.000551319583181013,b:0.913909344882567,c:0.562888857751573}, "006":{a:0.00053137,b:0.88920513,c:-0.000084665}, "007":{a:0.000900326,b:0.831593722,c:-0.0079837}, "008":{a:0.001278,b:0.773738,c:-0.01074}, "009":{a:0.001621,b:0.717263,c:-0.01554}, "010":{a:-0.0012,b:1.209151,c:-0.99777}, "011":{a:-0.000861513671677533,b:1.14895525429925,c:-0.386609370427133}, "012":{a:-0.000535100447688088,b:1.09372010400185,c:-0.215330487347692}, "013":{a:-0.00034,b:1.061623,c:-1.05832}, "014":{a:0.000054,b:0.990575,c:-0.02651}, "015":{a:0.000293402586303515,b:0.950918512758986,c:-0.584046305573812}, "016":{a:0.00062313,b:0.89047822,c:-0.00226079}, "017":{a:0.000894386,b:0.841628148,c:-0.00325674}, "018":{a:0.001179,b:0.791427,c:-0.00428}, "019":{a:0.001467,b:0.740416,c:-0.00531}, "020":{a:-0.00050962,b:1.12088278,c:-0.20413205}, "021":{a:-0.000451008736856611,b:1.11402157900952,c:-2.1600880723451}, "022":{a:-0.000253896028094246,b:1.06227881685123,c:-0.52039623921153}, "023":{a:-0.000110429558590149,b:1.02864577091777,c:-0.469507282971794}, "024":{a:-0.0000092,b:1,c:-0.00000000031}, "025":{a:0.000127974605526982,b:0.971889816424186,c:-0.786849000248007}, "026":{a:0.00028374,b:0.93148415,c:-0.00090544}, "027":{a:0.000485541,b:0.889126457,c:-0.00143269}, "028":{a:0.000533,b:0.869967,c:-0.00168}, "029":{a:0.000663,b:0.837986,c:-0.00209}, "0ZZ":{a:0,b:1,c:0}, "1ZZ":{a:0,b:1,c:0}, "2ZZ":{a:0,b:1,c:0}}
        if(Number(tempRange)>1 && Number(tempRange)<10){
            var prevTempRange = (Number(tempRange)-1).toString();    
        }
        else {
            var prevTempRange = tempRange;    
        }
        return {abc:abc[proofRange+tempRange], prevabc:abc[proofRange+prevTempRange]};
    }

    // function loadproduction(prodId){
    //     return($("#prodPanel_load").load("../BE_production", prodId));  
    //     }
    // });

/////////////////////////////
////maceration services//////
/////////////////////////////
    function liqueur_maceration(pg, units){
        units = units || 'lbs';
        var botanicals_per_pg={"Ginger":75.6, "Coriander":0.945, "Bitter Orange":0.945, "Cinammon":0.378};
        var botanicals_qty={"Ginger":0, "Coriander":0, "Bitter Orange":0, "Cinammon":0};
        for(key in botanicals_per_pg){
            if(units=="lbs"){
                botanicals_qty[key]=pg*botanicals_per_pg[key]*0.00220462;
            }
            else{
                botanicals_qty[key]=pg*botanicals_per_pg[key];
            }
        }
        return(botanicals_qty);
    }

    function gin_maceration(pg, units){
        units = units || 'lbs';
        var botanicals_per_pg={"Juniper":56.18, "Coriander":26.22, "Bitter Orange":14.98, "Cinammon":0.75, "Gentian Root":1.87, "Angelica Root":1.87, "Jasmine":3.75};
        var botanicals_qty={"Juniper":0, "Coriander":0, "Bitter Orange":0, "Cinammon":0, "Gentian Root":0, "Angelica Root":0, "Jasmine":0};
        for(key in botanicals_per_pg){
            if(units=="lbs"){
                botanicals_qty[key]=pg*botanicals_per_pg[key]*0.00220462;
            }
            else{
                botanicals_qty[key]=pg*botanicals_per_pg[key];
            }
        }
        return(botanicals_qty);
    }

/////////////////////////////
////////Services
/////////////////////////////
    function bwc_format_explorer_fields(entities, keys, idStr){
        var texts = [];
        if(entities.length > 0){
            for(var i=0;i<entities.length;i++){
                var text = "";
                for(var j=0; j<keys.length;j++){
                    formattedText = bwc_format_element(keys[j], entities[i][keys[j]]);
                    text += " | " + formattedText;
                }
                text = text.slice(3);
                texts.push({_id:entities[i][idStr], _text:text});
            }
        }
        return texts;
    }

    function bwc_format_element(type, value){
        var formattedText = "";
        //
        switch(type){
            case "inventoryTransactionType":
                if(value == 0){
                    formattedText = 'Deposit';
                }
                else if(value == 1){
                    formattedText = 'Withdrawal';
                }
                else {
                    formattedText = value;
                }
            break;
            case "dateTime":
                formattedText = dateFormat(value, "shorterDate");
            break;
            case "tdateTime":
                formattedText = dateFormat(value, "shortTime");
            break;
            case "clearingStatus":
                if(value==true){
                    formattedText = 'Clear';
                }
                else{
                    formattedText = 'Open';    
                }
            break;
            case "clearingStatus_img":
                if(value==true){
                    formattedText = '<img src="http://thebaltimorebrandycompany.com/test/wp-content/uploads/2016/04/Battery_Empty.png" />';
                }
                else{
                    formattedText = '<img src="http://thebaltimorebrandycompany.com/test/wp-content/uploads/2016/04/Battery_Full.jpg" />';
                }
            break;
            case "type":
                switch(value){
                    case "Grain":
                        formattedText = value;
                    break;
                    case "Fruit":
                        formattedText = value;
                    break;
                    case "Separating Run - Brandy":
                        formattedText = value;
                    break;
                    case "Separating Run - Whiskey":
                        formattedText = value;
                    break;
                    case "Spirit Run - Brandy":
                        formattedText = value;
                    break;
                    case "Spirit Run - Flavored Brandy":
                        formattedText = value;
                    break;
                    case "Spirit Run - Whiskey":
                        formattedText = value;
                    break;
                    case "Spirit Run - Gin":
                        formattedText = value;
                    break;
                    case "GNS":
                        formattedText = value;
                    break;
                    case "Brandy - Spirit":
                        formattedText = value;
                    break;
                    case "Brandy - Low Wines":
                        formattedText = value;
                    break;
                    case "Whiskey - Spirit":
                        formattedText = value;
                    break;
                    case "Flavored Brandy":
                        formattedText = value;
                    break;
                    case "Gin - Spirit":
                        formattedText = value;
                    break;
                    case "Tails - Brandy":
                        formattedText = value;
                    break;
                    case "Tails - Whiskey":
                        formattedText = value;
                    break;
                    default:
                        formattedText = value;
                    break;
                }
            break;
            case null:
            formattedText = " ";
            break;
            default:
                formattedText = value;
            break;
        }
        return formattedText;
    }

    function bwc_tank_type_determine(table){
        var tank="";
        switch(table){
            case "fermentation":
                tank = "fermenter";
                break;
            case "prosto":
                tank = "tank";
                break;
            case "storage":
                tank = "barrel";
                break;
            default:
                tank = "Invalid table";
                break;
        }
        return tank;
    }

/////////////////////////////
////date service/////////////
/////////////////////////////
var dateFormat = function () {
    var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
        timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
        timezoneClip = /[^-+\dA-Z]/g,
        pad = function (val, len) {
            val = String(val);
            len = len || 2;
            while (val.length < len) val = "0" + val;
            return val;
        };

    // Regexes and supporting functions are cached through closure
    return function (date, mask, utc) {
        var dF = dateFormat;

        // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
        if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
            mask = date;
            date = undefined;
        }

        // Passing date through Date applies Date.parse, if necessary
        if(date != "0000-00-00 00:00:00"){
            date = date ? new Date(date) : new Date;
        }
        else{
            return;
        }
        if (isNaN(date)) throw SyntaxError("invalid date");
        

        mask = String(dF.masks[mask] || mask || dF.masks["default"]);

        // Allow setting the utc argument via the mask
        if (mask.slice(0, 4) == "UTC:") {
            mask = mask.slice(4);
            utc = true;
        }

        var _ = utc ? "getUTC" : "get",
            d = date[_ + "Date"](),
            D = date[_ + "Day"](),
            m = date[_ + "Month"](),
            y = date[_ + "FullYear"](),
            H = date[_ + "Hours"](),
            M = date[_ + "Minutes"](),
            s = date[_ + "Seconds"](),
            L = date[_ + "Milliseconds"](),
            o = utc ? 0 : date.getTimezoneOffset(),
            flags = {
                d:    d,
                dd:   pad(d),
                ddd:  dF.i18n.dayNames[D],
                dddd: dF.i18n.dayNames[D + 7],
                m:    m + 1,
                mm:   pad(m + 1),
                mmm:  dF.i18n.monthNames[m],
                mmmm: dF.i18n.monthNames[m + 12],
                yy:   String(y).slice(2),
                yyyy: y,
                h:    H % 12 || 12,
                hh:   pad(H % 12 || 12),
                H:    H,
                HH:   pad(H),
                M:    M,
                MM:   pad(M),
                s:    s,
                ss:   pad(s),
                l:    pad(L, 3),
                L:    pad(L > 99 ? Math.round(L / 10) : L),
                t:    H < 12 ? "a"  : "p",
                tt:   H < 12 ? "am" : "pm",
                T:    H < 12 ? "A"  : "P",
                TT:   H < 12 ? "AM" : "PM",
                Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
            };

        return mask.replace(token, function ($0) {
            return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
        });
    };
}();

// Some common format strings
dateFormat.masks = {
    "default":          "ddd mmm dd yyyy HH:MM:ss",
    dayMonthDate:       "ddd mmm dd", 
    shorterDate:        "m/d",
    shortDateShortTime: "m/d h:MM",
    shortDate:          "m/d/yy",
    mediumDate:         "mmm d, yyyy",
    longDate:           "mmmm d, yyyy",
    fullDate:           "dddd, mmmm d, yyyy",
    shortTime:          "h:MM TT",
    mediumTime:         "h:MM:ss TT",
    longTime:           "h:MM:ss TT Z",
    isoDate:            "yyyy-mm-dd",
    isoTime:            "HH:MM:ss",
    isoDateTime:        "yyyy-mm-dd'T'HH:MM:ss",
    isoUtcDateTime:     "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
    dayNames: [
        "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
        "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
    ],
    monthNames: [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
        "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
    ]
}

// For convenience...
Date.prototype.format = function (mask, utc) {
    return dateFormat(this, mask, utc);
}

function resetForm($form) {

}