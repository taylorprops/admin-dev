if (document.URL.match(/transaction_required_details/)) {
// TODO: need to add company, bank, LLC  as seller
    $(document).ready(function () {

        //$('.stepper').mdbStepper();

        form_elements();

        $('.add-member-button').click(add_member);

        $('.member-delete').click(delete_member);

        $('#MLSListDate').focus(function() {
            $('.custom-picker-header').remove();
            $('.picker__box').prepend('<h3-responsive class="py-3 bg-primary text-yellow-light my-0 border-bottom custom-picker-header">List Date</h3>');
        });
        $('#ExpirationDate').focus(function() {
            $('.custom-picker-header').remove();
            $('.picker__box').prepend('<h3-responsive class="py-3 bg-primary text-yellow-light my-0 border-bottom custom-picker-header">Expiration Date</h3>');
        });

        // disable opening steps unless complete
        $('.step-title').click(function(e) {
            e.stopPropagation();
        });
        // validate section
        $('.next-step').off('click').on('click', function(e) {
            e.preventDefault();

            let step = $(this).closest('.step');
            let validate = validate_form(step);
            if(validate == 'no') {
                e.stopPropagation();
            }
        });

        $('#save_required_details').click(function(e) {
            e.preventDefault();
            save_transaction_required_details();
        });

        $('#contacts_table').DataTable({
            "aaSorting": [],
                columnDefs: [{
                orderable: false,
                targets: 0
            }]
        });

        $('.import-from-contacts-button').off('click').on('click', function() {
            show_import_modal($(this).data('member'), $(this).data('member-id'));
        });

        $('[name$=_entity_name]').removeClass('required');
        $('.bank-trust').change(show_bank_trust);


        $('#import_property_address_button').click(function() {
            $('#ClientStreet').val($(this).data('street')).trigger('change');
            $('#ClientCity').val($(this).data('city')).trigger('change');
            $('#ClientState').val($(this).data('state')).trigger('change');
            $('#ClientZip').val($(this).data('zip')).trigger('change');
            select_refresh();
        });

        $('#CommissionAmount, #AgentCommission, #OtherAgentCommission').change(function() {
            format_money_with_decimals($(this));
        });

        $('#ReferralPercentage, #CommissionAmount').keyup(total_commission);

        $('#save_details_button').click(save_details);

        let agent_search_request = null;

        function search_bright_agents() {

            let val = $(this).val();

            if (val.length > 3) {

                if (agent_search_request) {
                    agent_search_request.cancel();
                }
                agent_search_request = axios.CancelToken.source();

                axios.get('/agents/doc_management/transactions/search_bright_agents', {
                    cancelToken: agent_search_request.token,
                    params: {
                        val: val
                    },
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(function (response) {
                    let data = response.data;
                    $('.search-results').html('');
                    $.each(data, function (k, agents) {
                        if (agents.length > 0) {
                            $.each(agents, function (k, agent) {
                                let agent_div = ' \
                                <div class="search-result list-group-item" data-agent-first="'+ agent.MemberFirstName + '" data-agent-last="' + agent.MemberLastName + '" data-agent-phone="' + agent.MemberPreferredPhone + '" data-agent-email="' + agent.MemberEmail + '" data-agent-company="' + agent.OfficeName + '" data-agent-mls-id="' + agent.MemberMlsId + '" data-agent-street="' + agent.OfficeAddress1 + '" data-agent-city="' + agent.OfficeCity + '" data-agent-state="' + agent.OfficeStateOrProvince + '" data-agent-zip="' + agent.OfficePostalCode + '" data-agent-office-phone="' + agent.OfficePhone + '"> \
                                    <div class="row"> \
                                        <div class="col-6 col-md-3"> \
                                            <span class="font-weight-bold">'+ agent.MemberLastName + ', ' + agent.MemberFirstName + '</span><br><span class="small">' + agent.MemberType + ' (' + agent.MemberMlsId + ')<br>' + agent.MemberEmail + ' \
                                        </div> \
                                        <div class="col-6 col-md-3"> \
                                        <span class="font-weight-bold">'+ agent.OfficeName + '</span><br><span class="small">' + agent.OfficeMlsId + '</span>\
                                        </div> \
                                        <div class="col-12 col-md-6"> \
                                            '+ agent.OfficeAddress1 + '<br>' + agent.OfficeCity + ', ' + agent.OfficeStateOrProvince + ' ' + agent.OfficePostalCode + ' \
                                        </div> \
                                    </div> \
                                </div> \
                            ';
                                $('.search-results').show().append(agent_div);
                            });
                        } else {
                            $('.search-results').show().append('<div class="search-result list-group-item text-danger"><i class="fad fa-exclamation-triangle mr-2"></i> No Matching Results</div>');
                        }
                    });

                    $('.search-result').off('click').on('click', function () {
                        add_buyers_agent($(this));
                    });

                    $(document).mouseup(function (e) {
                        var container = $('.search-results');
                        if (!container.is(e.target) && container.has(e.target).length === 0) {
                            container.hide();
                        }
                    });
                })
                .catch(function (error) {
                    if (axios.isCancel(error)) {

                    } else {
                        //console.log(error);
                    }
                });


            } else {

                $('.search-results').hide().html('');

            }

        }

        $('.agent-search').on('keyup', search_bright_agents);

        $('#UsingHeritage').change(function() {
            if($(this).val() == 'no') {
                $('.not-using-heritage').show();
            } else {
                $('.not-using-heritage').hide();
                $('#TitleCompany').val('').trigger('change');
            }
        });

        $('.money-decimal').each(function() {
            if($(this).val() != '') {
                format_money_with_decimals($(this));
            }
            $(this).change(function() {
                format_money_with_decimals($(this));
            });
        });

    });

    function save_details() {
        let Referral_ID = $('#Referral_ID').val();
        let Agent_ID = $('#Agent_ID').val();
        let form = $('#details_form');
        let validate = validate_form(form);
        if(validate == 'yes' ){
            let formData = new FormData(form[0]);
            axios.post('/agents/doc_management/transactions/add/transaction_save_details_referral', formData, axios_options)
            .then(function (response) {
                window.location ='/agents/doc_management/transactions/transaction_details/'+Referral_ID+'/referral';
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }

    function total_commission() {
        let commission = $('#CommissionAmount').val().replace(/[,\$]/g, '');
        let percent = parseInt($('#ReferralPercentage').val()) / 100;
        if(commission > 0 && percent > 0) {
            let referral_commission = percent * commission;
            let receiving_commission = commission - referral_commission;
            $('#AgentCommission').val(referral_commission).trigger('change');
            $('#OtherAgentCommission').val(receiving_commission).trigger('change');
            format_money_with_decimals($('#AgentCommission'));
            format_money_with_decimals($('#OtherAgentCommission'));
        }

    }

    function add_buyers_agent(ele) {

        let type = $('#ReferralType').val();

        let agent_first = ele.data('agent-first');
        let agent_last = ele.data('agent-last');
        let agent_company = ele.data('agent-company');
        let office_street = ele.data('agent-street');
        let office_city = ele.data('agent-city');
        let office_state = ele.data('agent-state');
        let office_zip = ele.data('agent-zip');
        let office_phone = ele.data('agent-office-phone');

        if(type == 'receiving') {
            $('#ReferringAgentFirstName').val(agent_first).trigger('change');
            $('#ReferringAgentLastName').val(agent_last).trigger('change');
            $('#ReferringAgentOfficeName').val(agent_company).trigger('change');
            $('#ReferringAgentOfficeStreet').val(office_street).trigger('change');
            $('#ReferringAgentOfficeCity').val(office_city).trigger('change');
            $('#ReferringAgentOfficeState').val(office_state).trigger('change');
            $('#ReferringAgentOfficeZip').val(office_zip).trigger('change');
            $('#ReferringAgentOfficePhone').val(office_phone).trigger('change');
        } else {
            $('#ReceivingAgentFirstName').val(agent_first).trigger('change');
            $('#ReceivingAgentLastName').val(agent_last).trigger('change');
            $('#ReceivingAgentOfficeName').val(agent_company).trigger('change');
            $('#ReceivingAgentOfficeStreet').val(office_street).trigger('change');
            $('#ReceivingAgentOfficeCity').val(office_city).trigger('change');
            $('#ReceivingAgentOfficeState').val(office_state).trigger('change');
            $('#ReceivingAgentOfficeZip').val(office_zip).trigger('change');
            $('#ReceivingAgentOfficePhone').val(office_phone).trigger('change');
        }
        select_refresh();

        $('.search-results').fadeOut('slow');
        $('#receiving_agent_search_div, #referring_agent_search_div').collapse('hide');
    }

    function show_bank_trust() {
        let member = $(this).data('member');
        let field = $(this).closest('.form-ele').next('div').find('.bank-trust-row');
        if($(this).is(':checked')) {
            $(this).closest('.form-ele').next('div').find('[name^='+member+'_]').not('[name^='+member+'_crm]').removeClass('required').parent().find('.required-div').hide();
            field.removeClass('hidden').find('input').addClass('required').parent().find('.required-div').show();
        } else {
            $(this).closest('.form-ele').next('div').find('[name^='+member+'_]').not('[name^='+member+'_crm]').addClass('required').parent().find('.required-div').show();
            field.addClass('hidden').find('input').removeClass('required').parent().find('.required-div').hide();
        }
    }

    function show_import_modal(member, member_id) {
        member_id = member_id - 1;

        $('#import_contact_modal').modal();
        $('#contacts_table').off('click').on('click', '.add-contact-button', function() {
            let member_div = $('.'+member+'-div').eq(member_id);
            member_div.find('[name^='+member+'_first_name]').val($(this).data('contact-first'));
            member_div.find('[name^='+member+'_last_name]').val($(this).data('contact-last'));
            member_div.find('[name^='+member+'_phone]').val($(this).data('contact-phone'));
            member_div.find('[name^='+member+'_email]').val($(this).data('contact-email'));
            member_div.find('[name^='+member+'_street]').val($(this).data('contact-street'));
            member_div.find('[name^='+member+'_city]').val($(this).data('contact-city'));
            member_div.find('[name^='+member+'_state]').val($(this).data('contact-state'));
            member_div.find('[name^='+member+'_zip]').val($(this).data('contact-zip'));
            member_div.find('[name^='+member+'_crm_contact_id]').val($(this).data('contact-id'));

            member_div.find('input').trigger('change');
            setTimeout(select_refresh, 500);
            $('#import_contact_modal').modal('hide');
        });
    }

    function save_transaction_required_details() {

        if($('#MLSListDate').length > 0) {
            if($('#MLSListDate').val() > $('#ExpirationDate').val()) {
                $('#modal_danger').modal().find('.modal-body').html('List Date must be before Expiration Date');
                $('#MLSListDate').addClass('invalid invalid-input');
                $('#modal_danger').on('hidden.bs.modal', function() {
                    $('#MLSListDate').focus().trigger('click');
                });
                return false;
            }
        }
        if($('#ContractDate').length > 0) {
            if($('#ContractDate').val() > $('#CloseDate').val()) {
                $('#modal_danger').modal().find('.modal-body').html('Contract Date must be before Settlement Date');
                $('#ContractDate').addClass('invalid invalid-input');
                $('#modal_danger').on('hidden.bs.modal', function() {
                    $('#ContractDate').focus().trigger('click');
                });
                return false;
            }
        }

        let form = $('#details_form');
        let validate = validate_form(form);
        if(validate == 'yes') {
            let formData = new FormData(form[0]);
            axios.post('/agents/doc_management/transactions/save_transaction_required_details', formData, axios_options)
            .then(function (response) {
                global_loading_off();
                window.location = '/agents/doc_management/transactions/transaction_details/' + response.data.id + '/' + response.data.type;
            })
            .catch(function (error) {
                //global_loading_off();
                console.log(error);
            });
        } else {
            //global_loading_off();
        }

    }

    function add_member() {


        let member = $(this).data('member'); // seller or buyer
        let type = $(this).data('type'); // listing or contract

        let required = 'required';

        let member_id = $('.'+member+'-div').length + 1;
        let member_div = '';

        if(type == 'listing' || (type == 'contract' && member == 'buyer')) {
            member_div += ' \
            <div class="'+member+'-div mb-3 z-depth-1"> \
                <div class="h5-responsive text-orange '+member+'-header"></div> \
                <div class="d-flex justify-content-between"> \
                    <a href="javascript: void(0)" class="btn btn-sm btn-primary ml-0 import-from-contacts-button" data-member="'+member+'" data-member-id="' + member_id + '"><i class="fad fa-user-friends mr-2"></i> Import from Contacts</a> \
                    <div><a href="javascript: void(0)" class="member-delete text-danger" data-member="'+member+'"><i class="fal fa-times fa-2x"></i></a></div> \
                </div> \
            ';
        } else {
            member_div += ' \
            <div class="'+member+'-div mb-3 z-depth-1"> \
                <div class="d-flex justify-content-between"> \
                    <div class="h5-responsive text-orange '+member+'-header"></div> \
                    <div><a href="javascript: void(0)" class="member-delete text-danger" data-member="'+member+'"><i class="fal fa-times fa-2x"></i></a></div> \
                </div> \
            ';
        }
        member_div += ' \
                <div class="row"> \
                    <div class="col-12 col-md-6"> \
                        <input type="text" class="custom-form-element form-input required" name="'+member+'_first_name[]" data-label="First Name"> \
                    </div> \
                    <div class="col-12 col-md-6"> \
                        <input type="text" class="custom-form-element form-input required" name="'+member+'_last_name[]" data-label="Last Name"> \
                    </div> \
        ';

        if(type == 'listing' || (type == 'contract' && member == 'buyer')) {
            member_div += ' \
                    <div class="col-12 col-md-6 col-lg-3"> \
                        <input type="text" class="custom-form-element form-input phone '+required+'" name="'+member+'_phone[]" data-label="Phone"> \
                    </div> \
                    <div class="col-12 col-md-6 col-lg-3"> \
                        <input type="text" class="custom-form-element form-input" name="'+member+'_email[]" data-label="Email"> \
                    </div> \
                </div> \
                <div class="row"> \
                    <div class="col-12 col-md-6 col-lg-5"> \
                        <input type="text" class="custom-form-element form-input '+member+'-street '+required+' street-autocomplete" name="'+member+'_street[]" data-label="Home Address"> \
                        <div class="address-autocomplete-container"><div class="address-autocomplete-div z-depth-1"></div></div> \
                    </div> \
                    <div class="col-12 col-md-6 col-lg-3"> \
                        <input type="text" class="custom-form-element form-input '+member+'-city '+required+'" name="'+member+'_city[]" data-label="City"> \
                    </div> \
                    <div class="col-12 col-md-6 col-lg-2"> \
                        <select class="custom-form-element form-select form-select-no-cancel '+member+'-state '+required+'" name="'+member+'_state[]" data-label="State"> \
                            <option value=""></option> \
            ';

            states.forEach(function(state) {
                member_div += '<option value="' + state.state + '">' + state.state + '</option>';
            });

            member_div += ' \
                        </select> \
                    </div> \
                    <div class="col-12 col-md-6 col-lg-2"> \
                        <input type="text" class="custom-form-element form-input '+member+'-zip '+required+'" name="'+member+'_zip[]" data-label="Zip Code"> \
                    </div> \
                    <input type="hidden" name="'+member+'_crm_contact_id[]"> \
                </div> \
            </div> \
            ';
        } else {
            member_div += '</div>';
        }

        $('.'+member+'-container').append(member_div);
        let count = $('.'+member+'-div').length;
        $('.'+member+'-div').fadeIn('slow').last().find('.'+member+'-header').text((member == 'seller' ? 'Seller' : 'Buyer')+ ' ' + count);
        form_elements();
        if(count == 2) {
            $('.add-member-button[data-member="'+member+'"]').hide();
        } else {
            $('.add-member-button[data-member="'+member+'"]').show();
        }

        $('.member-delete').click(delete_member);

        $('.street-autocomplete').focus(function() {
            let step = $(this).closest('.'+member+'-div');
            if($('.'+member+'-div').eq(0).find('.'+member+'-street').val() != '') {
                let street = $('.'+member+'-div').eq(0).find('.'+member+'-street').val();
                let city = $('.'+member+'-div').eq(0).find('.'+member+'-city').val();
                let state = $('.'+member+'-div').eq(0).find('.'+member+'-state').val();
                let zip = $('.'+member+'-div').eq(0).find('.'+member+'-zip').val();

                let container = $(this).closest('.row');
                container.find('.address-autocomplete-div').show().html('<a href="javascript:void(0)" class="text-primary"> <i class="fa fa-plus mr-2"></i> Copy from '+(member == 'seller' ? 'Seller' : 'Buyer')+' 1 address </a>');

                $(document).on('mousedown', function (e) {
                    if (!$(e.target).is('.address-autocomplete-div *')) {
                        $('.address-autocomplete-div').hide();
                    } else {
                        container.find('.'+member+'-street').val(street).trigger('change');
                        container.find('.'+member+'-city').val(city).trigger('change');
                        container.find('.'+member+'-state').val(state).trigger('change');
                        container.find('.'+member+'-zip').val(zip).trigger('change');
                        select_refresh();
                        $('.address-autocomplete-div').hide();
                    }

                });


            }
        });

        $('.import-from-contacts-button').off('click').on('click', function() {
            show_import_modal($(this).data('member'), $(this).data('member-id'));
        });
    }

    function delete_member() {
        // TODO
        let member = $(this).data('member');
        $(this).closest('.'+member+'-div').fadeOut().remove();
        form_elements();

        let count = $('.'+member+'-div').length;
        $('.'+member+'-div').each(function() {
            let index = $(this).index() + 1;
            $(this).find('.'+member+'-header').text((member == 'seller' ? 'Seller' : 'Buyer') + ' ' + index);
        });
        if(count == 3) {
            $('.add-member-button[data-member="'+member+'"]').hide();
        } else {
            $('.add-member-button[data-member="'+member+'"]').removeClass('hidden').show();
        }
    }

}