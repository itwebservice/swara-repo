
function get_auto_values(booking_date, sub_total, payment_mode, service_charge, markup, type, charges_flag, amount_type, change = false) {

    var offset = sub_total.split('-')[1];
    $('#service_show-'+offset).html('&nbsp;');
    $('#markup_show-'+offset).html('&nbsp;');
    $('#basic_show-'+offset).html('&nbsp;');

    const rules = get_other_rules('Hotel', booking_date);
    
    var basic_amount = $('#' + sub_total).val();
    var payment_mode = $('#' + payment_mode).val();
    var markup_amount = $('#' + markup).val();

    if (basic_amount === '') basic_amount = 0;
    if (markup_amount === '') markup_amount = 0;

    if (charges_flag === 'true') {

        var service_charge_result = rules && rules.filter((rule) => rule['rule_for'] === '1');
        var markup_amount_result = rules && rules.filter((rule) => rule['rule_for'] === '2');

        /////////////////Service charge Start/////////////////
        var rules_array = get_charges_on_conditions(service_charge_result, basic_amount, payment_mode, type);


        if (parseInt(rules_array.length) === 0) {
            if ($('#' + service_charge).val() == '')
                $('#' + service_charge).val(parseInt(0).toFixed(2));
        }
        else {
            var service_charge1 = calculate_charges(rules_array, type, basic_amount, markup_amount, offset);
            service_charge1 = (service_charge1 == '' || typeof service_charge1 === NaN || service_charge1 === undefined) ? parseFloat(0).toFixed(2) : parseFloat(service_charge1).toFixed(2);

            if (change && Number($('#' + service_charge).val()) != Number(service_charge1) && Number($('#' + service_charge).val()) != 0) {

                $('#vi_confirm_box').vi_confirm_box({
                    message: "<span style='font-size:20px'>As per the Business rule Service Charge should be <b>" + service_charge1 + "</b> but the same has been altered by you with <b>" + $('#' + service_charge).val() + "</b> , Click on Yes to accept the Business Rule Service Charge.</span>",
                    callback: function (result) {
                        if (result == 'yes') {
                            $('#' + service_charge).val(service_charge1);
                            $('#' + service_charge).trigger('change');
                        }
                    }
                });
            } else {
                $('#' + service_charge).val(service_charge1);
            }

            $('#car_sc').val(rules_array[0].ledger_id);
        }
        if (rules_array.length && rules_array[0].type === "Automatic")
            $('#' + service_charge).attr({ 'readonly': 'readonly' });
        else
            $('#' + service_charge).removeAttr('readonly');


        /////////////////Service charge End/////////////////

        /////////////////Markup Start///////////////////////
        var markup_amount_rules_array = get_charges_on_conditions(markup_amount_result, basic_amount, payment_mode, type);
        if (parseInt(markup_amount_rules_array.length) === 0) {
            if ($('#' + markup).val() == '')
                $('#' + markup).val(parseInt(0).toFixed(2));
        }
        else {
            var markup_cost = calculate_charges(markup_amount_rules_array, type, basic_amount, markup_amount, offset,service_charge);
            markup_cost = (markup_cost == '' || typeof markup_cost === NaN || markup_cost === undefined) ? parseFloat(0).toFixed(2) : parseFloat(markup_cost).toFixed(2);
            if (change && Number($('#' + markup).val()) != Number(markup_cost) && Number($('#' + markup).val()) != 0) {
                $('#markup_confirm').vi_confirm_box({
                    message: "<span style='font-size:20px'>As per the Business rule Markup should be <b>" + markup_cost + "</b> but the same has been altered by you with <b>" + $('#' + markup).val() + "</b> , Click on Yes to accept the Business Rule Markup.</span>",
                    callback: function (result) {
                        if (result == 'yes') {
                            $('#' + markup).val(markup_cost);
                            $('#' + markup).trigger('change');
                        }
                    }
                });
            } else {
                $('#' + markup).val(markup_cost);
            }

            $('#car_markup').val(markup_amount_rules_array[0].ledger_id);
        }
        if (markup_amount_rules_array.length && markup_amount_rules_array[0].type === "Automatic")
            $('#' + markup).attr({ 'readonly': 'true' });
        else
            $('#' + markup).removeAttr('disabled');
        /////////////////Markup End///////////////////////
    }
    /////////////////Tax Start///////////////////////
	if (type === 'save') {
		var service_tax_subtotal = 'tax_amount-'+offset;
		var service_tax_markup = 'tax_markup-'+offset;
		var tax_apply_on1 = 'tax_apply_on-'+offset;
		var tax_value1 = 'tax_value-'+offset;
		var markup_tax_value1 = 'markup_tax_value-'+offset;
	}
	else {
		var service_tax_subtotal = 'tax_amount-'+offset;
		var service_tax_markup = 'tax_markup-'+offset;
		var tax_apply_on1 = 'atax_apply_on-'+offset;
		var tax_value1 = 'tax_value1-'+offset;
		var markup_tax_value1 = 'markup_tax_value1-'+offset;
	}
	var tax_on_amount = 0;
	var service_tax = 0;
	var service_tax_amount = 0;
	var applied_taxes = '';
	var ledger_posting = '';
	var tax_apply_on = $('#'+tax_apply_on1).val();
	var tax_value = $('#'+tax_value1).val();
	var service_charge = $('#'+service_charge).val();
	if(tax_apply_on == 1){
		tax_on_amount = (basic_amount=='') ? 0 : basic_amount;
	}
	else if(tax_apply_on == 2){
		tax_on_amount = (service_charge=='') ? 0 : service_charge;
	}
	else if(tax_apply_on == 3){
		tax_on_amount = parseFloat(basic_amount) + parseFloat(service_charge);
	}
	if(tax_apply_on!="" && tax_value!=""){
		var service_tax_subtotal1 = tax_value.split("+");
		for(var i=0;i<service_tax_subtotal1.length;i++){
			var service_tax_string = service_tax_subtotal1[i].split(':');
			if(parseInt(service_tax_string.length) > 0){
				var service_tax_string1 = service_tax_string[1] && service_tax_string[1].split('%');
				service_tax_string1[0] = service_tax_string1[0] && service_tax_string1[0].replace('(','');
				service_tax = service_tax_string1[0];
			}

			service_tax_string[2] = service_tax_string[2].replace('(','');
			service_tax_string[2] = service_tax_string[2].replace(')','');
			service_tax_amount = (( parseFloat(tax_on_amount) * parseFloat(service_tax) ) / 100).toFixed(2);
			if(applied_taxes==''){
				applied_taxes = service_tax_string[0] +':'+ service_tax_string[1] + ':' + service_tax_amount;
				ledger_posting = service_tax_string[2];
			}else{
				applied_taxes += ', ' + service_tax_string[0] +':'+ service_tax_string[1] + ':' + service_tax_amount;
				ledger_posting += ', ' + service_tax_string[2];
			}
		}
		$('#'+service_tax_subtotal).val(applied_taxes);
		$('#car_taxes').val(ledger_posting);
	}else{
		$('#'+service_tax_subtotal).val('');
		$('#car_taxes').val('');
	}

	// Markup Tax
	var applied_taxes = '';
	var ledger_posting = '';
	var markup_tax_value = $('#'+markup_tax_value1).val();
	var service_tax_subtotal1 = markup_tax_value.split("+");
	if(markup_tax_value!=''){
		for(var i=0;i<service_tax_subtotal1.length;i++){
			var service_tax_string = service_tax_subtotal1[i].split(':');
			var service_tax_string1 = service_tax_string[1].split('%');
			service_tax_string1[0] = service_tax_string1[0].replace('(','');
			var service_tax = service_tax_string1[0];

			service_tax_string[2] = service_tax_string[2].replace('(','');
			service_tax_string[2] = service_tax_string[2].replace(')','');
			service_tax_amount = (( parseFloat(markup_amount) * parseFloat(service_tax) ) / 100).toFixed(2);
			if(applied_taxes==''){
				applied_taxes = service_tax_string[0] +':'+ service_tax_string[1] + ':' + service_tax_amount;
				ledger_posting = service_tax_string[2];
			}else{
				applied_taxes += ', ' + service_tax_string[0] +':'+ service_tax_string[1] + ':' + service_tax_amount;
				ledger_posting += ', ' + service_tax_string[2];
			}
		}
		$('#'+service_tax_markup).val(applied_taxes);
		$('#car_markup_taxes').val(ledger_posting);
	}else{
		$('#'+service_tax_markup).val('');
		$('#car_markup_taxes').val('');
	}
    /////////////////Tax End///////////////////////

    cost_calculate(sub_total);
}

///////////////////////////////////// TAXES FUNCTIONS START /////////////////////////////////////////////
function get_tax_rules(rules, taxes_result, basic_amount, basic_amountid, markup, markupid, service_charge, service_chargeid, payment_mode, type, amount_type, charges_flag, offset) {

    var final_taxes_rules = [];
    taxes_result && taxes_result.filter((tax_rule) => {
        var tax_rule_array = [];
        rules && rules.forEach((rule) => {
            if (parseInt(tax_rule['entry_id']) === parseInt(rule['entry_id']) && rule['rule_id'])
                tax_rule_array.push(rule);
        });
        final_taxes_rules.push({ 'entry_id': tax_rule['entry_id'], tax_rule_array });
    });

    var new_taxes_rules = get_tax_rules_on_conditions(final_taxes_rules, basic_amount, payment_mode, type);
    var tax_for = '';
    //service_charge////////////////////////////////////
    var other_charge_results = new_taxes_rules.filter((rule) => {
        return rule['target_amount'] !== "Markup";
    });
    tax_for = 'service_charge';
    get_tax_charges(other_charge_results, taxes_result, basic_amount, basic_amountid, markup, markupid, service_charge, service_chargeid, payment_mode, type, amount_type, tax_for, offset);

    //markup/////////////////////////////////////////////
    var markup_results = new_taxes_rules.filter((rule) => {
        return rule['target_amount'] === "Markup";
    });
    tax_for = 'markup';
    get_tax_charges(markup_results, taxes_result, basic_amount, basic_amountid, markup, markupid, service_charge, service_chargeid, payment_mode, type, amount_type, tax_for, offset);
}

function get_tax_charges(new_taxes_rules, taxes_result, basic_amount, basic_amountid, markup, markupid, service_charge, service_chargeid, payment_mode, type, amount_type, tax_for, offset) {

    
    var service_tax_subtotal = 'tax_amount-'+offset;
    var service_tax_markup = 'tax_markup-'+offset;
    var ledger_posting = '';
    var applied_taxes = '';
    var total_tax = 0;
    if (new_taxes_rules.length > 0) {

        new_taxes_rules && new_taxes_rules.map((rule) => {

            var tax_data = taxes_result.find((entry_id_tax) => entry_id_tax['entry_id'] === rule['entry_id']);

            var { rate_in, rate } = tax_data;
            rate = parseFloat(rate).toFixed(2);

            var { target_amount, ledger_id, calculation_mode, name } = rule;
            // target_amount = 'Service Charge','Basic','Total','Commission','Markup','Discount'

            if (target_amount === 'Service Charge') {
                var charge_amount = service_charge;
            }
            else if (target_amount === 'Basic') {
                var charge_amount = basic_amount;
            }
            else if (target_amount === 'Markup') {
                var charge_amount = markup;
            }
            else if (target_amount === 'Total') {
                var charge_amount = parseFloat(service_charge) + parseFloat(basic_amount);
            }
            else {
                var charge_amount = 0;
            }
            if (calculation_mode === '"Exclusive"') {
                if (rate_in === 'Percentage') {
                    var rate_in_text = '%';
                    var tax_amount = (parseFloat(charge_amount) * parseFloat(rate) / 100);
                }
                else {
                    var rate_in_text = '';
                    var tax_amount = parseFloat(rate);
                }
            }
            else {

                if (rate_in === 'Percentage') {
                    var rate_in_text = '%';
                    var tax_rate = parseInt(100) + parseFloat(rate);
                    var tax_amount = parseFloat(charge_amount) - (parseFloat(charge_amount) / parseFloat(tax_rate) * 100);
                }
                else {
                    var rate_in_text = '';
                    var tax_amount = parseFloat(rate);
                }
                total_tax = parseFloat(total_tax) + parseFloat(tax_amount);
            }
            tax_amount = (tax_amount !== '' || typeof tax_amount !== NaN || tax_amount !== undefined) ? parseFloat(tax_amount).toFixed(2) : parseFloat(0).toFixed(2);

            var new_service_charge = parseFloat(charge_amount) - parseFloat(total_tax);
            new_service_charge = (new_service_charge !== '' || typeof new_service_charge !== NaN || new_service_charge !== undefined) ? parseFloat(new_service_charge).toFixed(2) : parseFloat(0).toFixed(2);

            if (applied_taxes != '') {
                applied_taxes = applied_taxes + ', ' + name + ':(' + rate + rate_in_text + '):' + tax_amount;
                ledger_posting = ledger_posting + ',' + ledger_id;

            } else {
                applied_taxes += name + ':(' + rate + rate_in_text + '):' + tax_amount;
                ledger_posting += ledger_id;
            }

            if (calculation_mode !== '"Exclusive"') {

                if (tax_for === 'service_charge') {
                    if (target_amount === 'Service Charge') {
                        $('#service_show-'+offset).html('Inclusive Amount : <span>' + new_service_charge + '</span>');
                    }
                    else if (target_amount === 'Markup') {
                        $('#markup_show-'+offset).html('Inclusive Amount : <span>' + new_service_charge + '</span>');
                    }
                    else if (target_amount === 'Basic') {
                        $('#basic_show-'+offset).html('Inclusive Amount : <span>' + new_service_charge + '</span>');
                    }
                    $('#' + service_tax_subtotal).val(applied_taxes);

                }
                else if (tax_for === 'markup') {
                    if (target_amount === 'Markup') {
                        $('#markup_show-'+offset).html('Inclusive Amount : <span>' + new_service_charge + '</span>');
                        $('#' + service_tax_markup).val(applied_taxes);
                    }
                }
            }
            else {

                if (tax_for === 'service_charge') {
                    if (target_amount === 'Service Charge') {
                        $('#' + service_chargeid).val(new_service_charge);
                    }
                    else if (target_amount === 'Markup') {
                        $('#' + markupid).val(new_service_charge);
                    }
                    else if (target_amount === 'Basic') {
                        $('#' + basic_amountid).val(new_service_charge);
                    }
                    $('#' + service_tax_subtotal).val(applied_taxes);
                }
                else if (tax_for === 'markup') {
                    if (target_amount === 'Markup') {
                        $('#' + markupid).val(new_service_charge);
                        $('#' + service_tax_markup).val(applied_taxes);
                    }
                }
            }
        });
    }
    else {
        if (tax_for === 'service_charge') {
            $('#' + service_tax_subtotal).val('');
            $('#car_taxes').val('');
        }
        else if (tax_for === 'markup') {
            $('#' + service_tax_markup).val('');
            $('#car_markup_taxes').val('');
        }
    }
}
function get_tax_rules_on_conditions(final_taxes_rules, basic_amount, payment_mode, type) {

    let applied_rules = [];
    final_taxes_rules && final_taxes_rules.map((tax) => {

        var entry_id_rules = tax['tax_rule_array'];
        var flag = false;
        var conditions_flag_array = [];
        entry_id_rules && entry_id_rules.forEach((rule) => {

            if (rule['applicableOn'] == '1')
                return;

            var condition = JSON.parse(rule['conditions']);
            condition && condition.forEach((cond) => {

                var condition = cond.condition;
                var for1 = cond.for1;
                var value = cond.value;
                var amount = cond.amount;
                //Conditions- '1-Place of supply','2-Routing','3-Payment Mode','4-Target Amount','5-Supplier','6-Customer Type','7-Customer','8-Product','9-Fee Type'
                rule['name'] = 'TAX';
                switch (condition) {
                    case '1':
                        var place_flag = null;
                        place_flag_array = [];

                        switch (for1) {
                            case '!=': {
                                place_flag = true;
                            }
                                break;
                            default: place_flag = false; break;
                        }
                        flag = place_flag;
                        break;
                    case '2':
                        flag = false;
                        break;
                    case '5':
                        flag = false;
                        break;
                    case '8':
                        if (value == 'Hotel' || value == 'All') flag = true;
                        break;
                    case '3':
                        switch (for1) {
                            case '!=':
                                if (payment_mode != value)
                                    flag = true;
                                break;
                            case '==':
                                if (payment_mode === value)
                                    flag = true;
                                break;
                        }
                        break;
                    case '7':
                        flag = false;
                        break;
                    case '4':
                        switch (for1) {
                            case '<':
                                flag = parseFloat(basic_amount) < parseFloat(amount);
                                break;
                            case '<=':
                                flag = parseFloat(basic_amount) <= parseFloat(amount);
                                break;
                            case '>':
                                flag = parseFloat(basic_amount) > parseFloat(amount);
                                break;
                            case '>=':
                                flag = parseFloat(basic_amount) >= parseFloat(amount);
                                break;
                            case '!=':
                                flag = parseFloat(basic_amount) != parseFloat(amount);
                                break;
                            case '==':
                                flag = parseFloat(basic_amount) === parseFloat(amount);
                                break;
                        }
                        break;
                        break;
                    case '6':
                        flag = false;
                        break;
                    default:
                        flag = false
                        break;
                }
                conditions_flag_array.push(flag);
            });
            if (!conditions_flag_array.includes(false))
                applied_rules.push(rule)
        });
    });
    return applied_rules;
}
//////////////////////////// TAXES FUNCTIONS END //////////////////////////////////////////

function get_charges_on_conditions(service_charge_result, basic_amount, payment_mode, type) {

    let rules_array = service_charge_result && service_charge_result.filter((rule) => {

        var cond = rule['conditions'] && JSON.parse(rule['conditions']);
        var conditions_flag_array = [];
        var flag = false;
        cond && cond.forEach((item) => {
            var condition = item.condition;
            var for1 = item.for1;
            var value = item.value;
            var amount = item.amount;

            //conditions-'2-Routing','11-Price','5-Supplier','8-Product','12-Airline','13-Transaction Type','14-Booking Cabin','15-Service(Itinerary)','10-Supplier Type','3-Payment Mode','7-Customer','6-Customer Type','16-Reissue'
            switch (condition) {
                case '2':
                    flag = false;
                    break;
                case '11':
                    switch (for1) {
                        case '<':
                            flag = parseFloat(basic_amount) < parseFloat(amount);
                            break;
                        case '<=':
                            flag = parseFloat(basic_amount) <= parseFloat(amount);
                            break;
                        case '>':
                            flag = parseFloat(basic_amount) > parseFloat(amount);
                            break;
                        case '>=':
                            flag = parseFloat(basic_amount) >= parseFloat(amount);
                            break;
                        case '!=':
                            flag = parseFloat(basic_amount) != parseFloat(amount);
                            break;
                        case '==':
                            flag = parseFloat(basic_amount) === parseFloat(amount);
                            break;
                    }
                    break;
                case '5':
                    flag = false;
                    break;
                case '8':
                    if (value == 'Hotel' || value == 'All') flag = true;
                    break;
                case '12':
                    flag = false;
                    break;
                case '13':
                    if (value == 'Sale') flag = true;
                    break;
                case '14':
                    flag = false;
                    break;
                case '15':
                    flag = false;
                    break;
                case '10':
                    if (value == 'Hotel' || value == 'All') flag = true;
                    break;
                case '3':
                    flag = false;
                    break;
                case '7':
                    flag = false;
                    break;
                case '6':
                    flag = false;
                    break;
                case '16':
                    flag = false;
                    break;
                default:
                    flag = false
                    break;
            }
            conditions_flag_array.push(flag);
        });
        if (conditions_flag_array.includes(false))
            return null;
        else {
            return rule;
        }
    });
    var final_rule = get_final_rule(rules_array);
    return final_rule;
}

function get_final_rule(rules_array) {
    if (rules_array && (rules_array.length === 1 || rules_array.length === 0))
        return rules_array; // Only one valid rule is there
    else {
        var conditional_rule = rules_array && rules_array.filter((rule) => {
            if (rule['conditions']) {
                return rule;
            }
            return null;
        });
        if (conditional_rule && (conditional_rule.length === 0 || conditional_rule.length === 1))
            return conditional_rule; // If only one Conditional rule is there
        else {
            var customer_condition_rules = conditional_rule && conditional_rule.filter((rule) => {
                var cond = rule['conditions'] && JSON.parse(rule['conditions']);
                return cond && cond.includes((obj) => obj.conditions === '7')
            });
            if (customer_condition_rules && (customer_condition_rules.length === 1))
                return customer_condition_rules; // If only one 'Customer' Conditional rule is there
            else {
                var sorted_array = (conditional_rule.sort((a, b) => a.rule_id - b.rule_id));
                var latest_arr = [];
                latest_arr.push(sorted_array[sorted_array.length - 1]);
                return latest_arr; // Return latest rule
            }
        }
    }
}

function calculate_charges(rules_array, type, basic_amount, markup_amount1, offset,service_charge) {
	if (rules_array.length) {
		var apply_on = rules_array[0].apply_on;

		if (rules_array[0].target_amount != '') {
			if (rules_array[0].target_amount === 'Basic') var target_amount = basic_amount;
			else if (rules_array[0].target_amount === 'Total'){
                var service_charge_amount = $('#' + service_charge).val();
                if(service_charge_amount == ''){
                    service_charge_amount = 0;
                }
				var target_amount = parseFloat(basic_amount) + parseFloat(service_charge_amount);
            }
		}
		else var target_amount = 0;

		if (type === 'save') {
			var adults = $('#total_adult').val();
			var cwob = $('#children_without_bed').val();
            var cwb = $('#children_with_bed').val();
			var hotel_table = 'dynamic_table_list_h_'+offset;
		}
		else {
			var adults = $('#total_adult1').val();
			var cwob = $('#children_without_bed1').val();
            var cwb = $('#children_with_bed1').val();
            var table_id_offset = offset.split('u_');
			var hotel_table = 'dynamic_table_list_h_'+table_id_offset[1];
		}

		var table = document.getElementById(hotel_table);
		var rowCount = table.rows.length;
		var hotel_count = 0;
		var total_nights = 0;
		var total_rooms = 0;
		for (var i = 0; i < rowCount; i++) {
			var row = table.rows[i];
			if (row.cells[0].childNodes[0].checked) {
				if (row.cells[3].childNodes[0].value !== '') hotel_count++;
				var no_of_nights = row.cells[9].childNodes[0].value;
				var rooms = row.cells[10].childNodes[0].value;

				if (no_of_nights === '') no_of_nights = parseInt(0);
				if (rooms === '') rooms = parseInt(0);

				total_nights = parseInt(total_nights) + parseInt(no_of_nights);
				total_rooms = parseInt(total_rooms) + parseInt(rooms);
			}
		}
		switch (apply_on) {
			case '1':
				//Per pax
				adults =

					adults === '' ? 0 :
						adults;
                cwob =

                cwob === '' ? 0 :
                    cwob;
                    cwb =

					cwb === '' ? 0 :
                    cwb;
				var service_fee =

					rules_array[0].fee_type === 'Flat' ? parseFloat(rules_array[0].fee) *
						(parseInt(adults) + parseInt(cwob) + parseInt(cwb)) :
						parseFloat(target_amount) *
						parseFloat(rules_array[0].fee) /
						100 *
						(parseInt(adults) + parseInt(cwob) + parseInt(cwb));
				return service_fee;
				break;
			case '2':
				//Per Invoice
				var service_fee =

					rules_array[0].fee_type === 'Flat' ? parseFloat(rules_array[0].fee) :
						parseFloat(target_amount) * parseFloat(rules_array[0].fee) / 100;
				return service_fee;
				break;
			case '3':
				//Per Hotel
				var service_fee =

					rules_array[0].fee_type === 'Flat' ? parseFloat(rules_array[0].fee) * parseInt(hotel_count) :
						parseFloat(target_amount) * parseFloat(rules_array[0].fee) / 100 * parseInt(hotel_count);
				return service_fee;
				break;
			case '4':
				//Per Night
				var service_fee =

					rules_array[0].fee_type === 'Flat' ? parseFloat(rules_array[0].fee) * parseInt(total_nights) :
						parseFloat(target_amount) * parseFloat(rules_array[0].fee) / 100 * parseInt(total_nights);
				return service_fee;
				break;
			case '5':
				//Per Room
				var service_fee =

					rules_array[0].fee_type === 'Flat' ? parseFloat(rules_array[0].fee) * parseInt(total_rooms) :
						parseFloat(target_amount) * parseFloat(rules_array[0].fee) / 100 * parseInt(total_rooms);
				return service_fee;
				break;
		}
	}
}