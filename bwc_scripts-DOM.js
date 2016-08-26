///////////////////////////////////////
//////On document ready
///////////////////////////////////////
$(document).ready(function(a, b){
        bwc_be_ajax_query("mash", null);
        bwc_be_ajax_query("fermentation_mass", null);
        bwc_be_ajax_query("fermenters", null);
        bwc_be_ajax_query("prod_headers", null);
        bwc_be_ajax_query("prosto", null);
        bwc_be_ajax_query("prosto_tanks", null);
        bwc_be_ajax_query("production_storage_tanks", null);
        bwc_be_ajax_query("barrels", null);
        bwc_be_ajax_query("storage", null);
        bwc_be_ajax_query("processing", null);
        bwc_be_ajax_query("transactions_selections", null);

/////////////////////////////////////////////////
//Hide/Show in Forms
/////////////////////////////////////////////////
    //Show hidden otherId field - transactions field
    $("select[name=otherTable]").on("change", function() {
        var kind = $(this).val();
        var select_otherId = $(this).siblings("select[name=otherId]")
        select_otherId.children('option').hide();
        select_otherId.find("option."+kind).show();
    });


/////////////////////////////////////////////////
//HTML Explorers
/////////////////////////////////////////////////
    $("select#inventoryExplorer").on("change", function(){
        var item = $(this).val();
        bwc_be_ajax_query("inventory_single", item);
        bwc_be_ajax_query("inventory_deposits", item);
        bwc_be_ajax_query("inventory_withdrawals", item);
        $(this).parents("div.bwc_panel").find("div#single").show();
        $("div[role=main]").children("input#id").val($(this).val());
    });

    $("select#mashExplorer").on("change", function(){
        var mashId = $(this).val();
        bwc_be_ajax_query("mash_single", mashId);
        bwc_be_ajax_query("mash_steps", mashId);
        bwc_be_ajax_query("mash_enzymes", mashId);
        bwc_be_ajax_query("mash_bill", mashId);
        bwc_be_ajax_query("mash_transactions", mashId);
        $(this).parents("div.bwc_panel").find("div#single").show();
        $("div[role=main]").children("input#id").val($(this).val());
    });

    $("select#productionExplorer").on("change", function(){
        var prodId = $(this).val();
        bwc_be_ajax_query("prod_single", prodId);
        bwc_be_ajax_query("prod_measurements", prodId);
        bwc_be_ajax_query("prod_transactions", prodId);
        $(this).parents("div.bwc_panel").find("div#single").show();
        $("div[role=main]").children("input#id").val($(this).val());
    });

    $("form#fermentation_explorer").on("change", "select#fermentations", function(){
        var fermId = $(this).val();
        bwc_be_ajax_query("ferm_single", fermId);
        bwc_be_ajax_query("ferm_tts", fermId);
        bwc_be_ajax_query("ferm_transactions", fermId);
        $(this).parents("div.bwc_panel").find("div#single").show();
        $("div[role=main]").children("input#id").val($(this).val());
    });

    $("select#prostoExplorer").on("change", function(){
        var id = $(this).val();
        bwc_be_ajax_query("prosto_single", id);
        bwc_be_ajax_query("prosto_transactions", id);
        $(this).parents("div.bwc_panel").find("div#single").show();
        $("div[role=main]").children("input#id").val($(this).val());
    });

    $("select#production_tank_explorer").on("change", function(){
        var id = $(this).val();
        bwc_be_ajax_query("production_storage_tank_single", id);
        bwc_be_ajax_query("tank_prostos", id);
        $(this).parents("div.bwc_panel").find("div#single").show();
        $("div[role=main]").children("input#id").val($(this).val());
    });

    $("select#storageExplorer").on("change", function(){
        var id = $(this).val();
        bwc_be_ajax_query("storage_single", id);
        bwc_be_ajax_query("storage_transactions", id);
        bwc_be_ajax_query("storage_notes", id);
        $(this).parents("div.bwc_panel").find("div#single").show();
        $("div[role=main]").children("input#id").val($(this).val());
    });

    $("select#processExplorer").on("change", function(){
        var id = $(this).val();
        if(id>0){
            bwc_be_ajax_query("process_single", id);
            bwc_be_ajax_query("process_rectification", id);
            bwc_be_ajax_query("processing_transactions", id);
            $(this).parents("div.bwc_panel").find("div#single").show();
            $("div[role=main]").children("input#id").val($(this).val());
        }
        else{
            window.alert("Whoa! That weird thing happened. Click another tab (like Header or Rectifying) to see if this doesn't go away). Or select from the explorer again, please. May take a couple tries.");
        }
    });

    //should generically show the cloned edit form matching the row of the table clicked
    $('div[role=main]').on("click", "input#show_edit_form", function(){
        var row = $(this).closest("tr").attr('id');
        var form = $(this).closest("table").siblings("form#"+row);
        $(this).closest("table").siblings("form").hide();
        $(this).closest("table").siblings("input#create").show();
        form.show();
    });

    $("input#create").on("click", function(){
        $(this).siblings("form").hide();
        $(this).siblings("form.template").show();
        $(this).hide();
    });

    $("select.otherTableHiding").on("change", function(){
        if($("option:selected", this).attr("class").includes("empty")){
            var name = $("option:selected", this).text();
            var value = $(this).val().split(",");
            window.alert("There is no  " + value[0] + " for " + name + ". Please create the " + value[0] + " directly from that section for the meantime, then try to create the transaction again. Sorry, bro - Ian");
        }
    });
});