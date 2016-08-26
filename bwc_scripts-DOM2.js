///////////////////////////////////////
//////On document ready
///////////////////////////////////////
$(document).ready(function(a, b){

///////////////////////////////////////
//////AJAX Activities on Navigation
///////////////////////////////////////    
// 
    $("ul#clicklist-radio-buttons").on("click", "input, label", function(){
        var toggle_value = $(this).attr('id');
        var id = $(this).siblings("input[name=id]").val();

    });

    $("li#simple_div_toggle").on("click", "input, label, div", function(){
        var toggle_value = $(this).attr('id');
        $("div[id=" + toggle_value + "]").toggle();
    });

    $("ul#section-toggle").on("click", "input, label", function(){
        var toggle_value = $(this).attr('id');
        var parent_div = $(this).closest("div");
        parent_div.siblings("div").hide();
        $(this).siblings("input").prop('checked', true);
        parent_div.siblings("div#"+toggle_value).show();
        parent_div.siblings("div#"+toggle_value).find("div#single").hide();
        switch(toggle_value){
        //inventory cases
            case 'ajax_inventoryPanel':
                bwc_be_ajax_query("inventory", null);
            break;

        //mash cases
            case 'mashPanel':
                bwc_be_ajax_query("mash", null);
            break;

        //ferm cases
            case 'fermPanel':
                bwc_be_ajax_query("fermentation_mass", null);
                bwc_be_ajax_query("fermenters", null);
            break;

        //prod cases
            case 'prodPanel':
                bwc_be_ajax_query("prod_headers", null);
            break;
        //prosto
            case 'prostoPanel':
                bwc_be_ajax_query("prosto", null);
                bwc_be_ajax_query("prosto_tanks", null);
            break;
        //productionStorageTanks
            case 'prostoTanksPanel':
                bwc_be_ajax_query("production_storage_tanks", null);
                bwc_be_ajax_query("prostos_tank", null);
            break;
        //storage
            case 'storagePanel':
                bwc_be_ajax_query("barrels", null);
                bwc_be_ajax_query("storage", null);
            break;
            case 'processingPanel':
                bwc_be_ajax_query("processing", null);
            break;
            default:
                //alert ("Why you no have case?");
            break;
        }
    });

/////////////////////////////////////////////////
//Form Submission Functions
/////////////////////////////////////////////////
    $('div[role=main]').on("submit", "form", function() {
        var current_form = $(this);

        //Default date/time to today if initial
        if(current_form.find("input[name=dateTime]").length && !current_form.find("input[name=dateTime]").val()){
            current_form.find("input[name=dateTime]").val(dateFormat(new Date(), "isoDateTime"));
        }
        else if(current_form.parent().find("input[name=dateTime]") && !current_form.parent().find("input[name=dateTime]").val()){
            current_form.parent().find("input[name=dateTime]").val(dateFormat(new Date(), "isoDateTime"));
        }
        
        // Grab current form submission data, serialize form, and prepare AJAX callback
        var divStr = current_form.closest("div.detail").attr("id");
        var thisId = $("div[role=main]").children("input#id").val();
        var callback = bwc_callback_refresh_div(divStr, thisId);
        var send_data = current_form.serialize();
        
        //Custom validations
        var validation_error = validate_submission(divStr, thisId, current_form);
        if(validation_error == true){
            return false;
        }

        resetForm(current_form);

        $.ajax({
                url        : "../bwc_be_ajaxhandler2",
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // This is the money shot
                data       : send_data,
                type       : 'GET',
                complete   : callback // etc
            });
        return false; // prevent default action
    });

/////////////////////////////////////////////////
//Client-Side Validations
/////////////////////////////////////////////////

    $('input[name=tax_dateTime]').on("change", function(){
        var tax_date = $(this).val();
        var id_str = $(this).attr('id');
        var this_form = $(this).closest("form");
        var tax_dates = return_tax_dates(tax_date, id_str);
        for(key in tax_dates){
            this_form.find("input[name="+key+"]").val(tax_dates[key]);
        }
    });

    $('form#bwc_processing_update2').on("change", ".bottlingProof", function() {
        var bottlingProof = $(this).val(),
            origProof = $(this).closest("form").find("input.actProof").val(),
            weight = $(this).closest("form").find("input.weight").val();
            
            var h20_alc_parts = find_parts_h20_alc_per_100(origProof);
            
            var current_weight_h20 = weight * h20_alc_parts.partsWater * 0.01,
                current_weight_alc = weight * h20_alc_parts.partsAlc * 0.01;

            var h20_alc_parts_desired = find_parts_h20_alc_per_100(bottlingProof);
            var new_weight_h20 = h20_alc_parts_desired.partsWater/h20_alc_parts_desired.partsAlc * current_weight_alc,
                difference_new_added_h20 = new_weight_h20 - current_weight_h20,
                final_weight = difference_new_added_h20 + Number(weight),
                WG_per_pound = wine_gallons_per_pound_at_proof(bottlingProof),
                WG = final_weight * WG_per_pound,
                bottles_750 = bottle_750s_per_WG(WG);
            var processing={
                WaterAdded:difference_new_added_h20,
                FinalWeight:final_weight,
                WineGallons:WG,
                Bottles_750:bottles_750
            };
            $(this).closest("fieldset").find(".processing_calc").remove();
            for(key in processing){
                $(this).closest("fieldset").append("<label for='"+key+"' class='processing_calc'>"+key+"</label><output class='processing_calc'>"+processing[key]+"</output>");
            }
    });

    $('div[role=main]').on("change", ".temp", function() {
        var temp = $(this).val(),
            tempRangeWeightage = temp_correction_temp_range(temp),
            appProof = $(this).closest("table").find(".appProof").val(),
            closest = $(this).closest("form"),
            proofRange = temp_correction_proof_range(appProof);
        if(!appProof){
                appProof = $(this).closest("form").find(".appProof").val();
        }
        var abcs=temp_correction_equation_get(proofRange, tempRangeWeightage.tempRange);
        var actProof=calcActProof(appProof, abcs, tempRangeWeightage.weightage);
        if($(this).parent()!="td"){
            $(this).parentsUntil("form").find(".actProof").val(actProof);
        }
        else {
            $(this).parent().siblings("td").find(".actProof").val(actProof);   
        }
    });

    $('div[role=main]').on("change", ".weight", function() {
        var weight = $(this).val(),
            actProof = $(this).closest("table").find(".actProof").val();
        if(!actProof){
                actProof = $(this).closest("form").find(".actProof").val();
        }
        if($(this).closest("table#production_measurements").length){
            var tareWeight = Number($(this).parentsUntil("table#production_measurements").find("tr.delete_production_measurement:last").find("td#prod_measurements_weight").find("output").text());
        }
        if(!tareWeight){
            var tareWeight = 0;
        }
        var newWeight = weight - tareWeight;
        proofRangeWeightage = pg_determintion_proof_range(actProof);
        var pg=pg_determination(actProof, proofRangeWeightage, newWeight);
            $(this).parentsUntil("form").find(".pg").val(pg);
    });

    $('input.desiredProof').on("change", function() {
        var desiredProof = $(this).val(),
            form = $(this).closest("form"),
            actProof = form.find("input.actProof").val(),
            pg = form.find("input.pg").val(),
            weight = form.find("input.weight").val();

        var wg_pg_before = wg_pg_per_pound_at_proof(actProof),
            wg_pg_after = wg_pg_per_pound_at_proof(desiredProof),
            new_weight = pg / wg_pg_after.pg_per_pound;

            var added_weight_h20 = Math.round((new_weight - weight) * 100) / 100,
                wg_before = Math.round((wg_pg_before.wg_per_pound * weight) * 100) / 100,
                wg_after = Math.round((new_weight * wg_pg_after.wg_per_pound) * 100) / 100,
                added_wg = Math.round((added_weight_h20 / 8.345404) * 100) / 100,
                numbBottles = Math.round((bottle_750s_per_WG(wg_after)) * 100) / 100;

            form.find("input.wg_before").val(wg_before);
            form.find("input.wg_after").val(wg_after);
            form.find("input.added_wg").val(added_wg);
            form.find("input.added_weight_h20").val(added_weight_h20);
            form.find("input.numbBottles").val(numbBottles);
    });

    $("input.sweetening_ratios").on("change", function (){
        var mol_ratio_unfixed = $(this).closest("form").find("input[name=percent_molasses]").val(),
            mol_strength = $(this).closest("form").find("input[name=molassesStrength]").val(),
            mol_ratio = mol_ratio_unfixed / mol_strength,
            sug_ratio = $(this).closest("form").find("input[name=percent_sugar]").val(),
            aj_ratio = $(this).closest("form").find("input[name=percent_aj]").val(),
            tot_sugar_ratio = $(this).closest("form").find("input[name=sugar_per_vol]").val(),
            aj_brix = $(this).closest("form").find("input[name=juiceStrength]").val(),
            wg_after = $(this).closest("form").find("input[name=wg_after]").val(),
            aj_g_per_oz = (aj_brix / 100) / (0.0352739619),
            aj_oz_per_gal = (aj_g_per_oz / 28.3495) * 128;

        var abs_molasses = Math.round((mol_ratio * wg_after * tot_sugar_ratio / 16) * 100) / 100,
            abs_sugar = Math.round((sug_ratio * wg_after * tot_sugar_ratio / 16) * 100) / 100,
            aj_sugar = aj_ratio * wg_after * tot_sugar_ratio,
            aj_gal = Math.round((aj_sugar / aj_oz_per_gal) * 100) / 100;

        $(this).siblings("input[name=abs_molasses]").val(abs_molasses),
        $(this).siblings("input[name=abs_sugar]").val(abs_sugar),
        $(this).siblings("input[name=abs_aj]").val(aj_gal);
    });

    $("input#proof_from_pg_weight").on("click", function(){
        var pg = $(this).siblings("output[for=pg_measurements]").text();
        var weight = $(this).siblings("output[for=pg_weight]").text();
        var proof = proof_by_pg_and_weight(pg, weight);
        $(this).siblings("output[for=calc_proof]").text(proof);
    });

    $('form#bwc_maceration_calculator').on("change", "select[name=spirit]", function() {
        if($(this).val().length){
            $(this).nextAll().show();
        }
        else {
            $(this).nextAll().hide();
        }
    });

    $('form#bwc_maceration_calculator').on("change", "input, select[name=units]", function() {
        var spirit = $(this).siblings("select[name=spirit]").val();
        $(this).siblings("output, br").remove();
        if(spirit == "Gin"){
            var qtys = gin_maceration($(this).closest("form").find("input[name=pg]").val(),$(this).closest("form").find("select[name=units]").val());
            for(key in qtys){
                $(this).closest("fieldset").append("<output class='bwc_red'>" + key + ": " + qtys[key] + "</output></br>");
            }
        }
        else if(spirit == "1904"){
            var qtys = liqueur_maceration($(this).closest("form").find("input[name=pg]").val(),$(this).closest("form").find("select[name=units]").val());
            for(key in qtys){
                $(this).closest("fieldset").append("<output class='bwc_red'>" + key + ": " + qtys[key] + "</output></br>");
            }
        }
    });    

/////////////////////////////////////////////////
//Tax DOM Functions
/////////////////////////////////////////////////
    $("ul#tax-toggle").on("click", "input, label", function(){
        var func = $(this).attr("id");
        var date_st = $(this).closest("ul").siblings("form").find("input[name=date_st]").val();
        var date_end = $(this).closest("ul").siblings("form").find("input[name=date_end]").val();
        bwc_tax_ajax_query(func, date_st, date_end);
    });


/////////////////////////////////////////////////
//Inventory DOM
/////////////////////////////////////////////////    
    $("form#bwc_add_inventory").on("change", "input[name=amount]", function(){
        var amount = $(this).val();
        $(this).siblings("input[name=origAmount]").val(amount);
    });
});