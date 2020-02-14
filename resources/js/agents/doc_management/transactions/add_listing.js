if (document.URL.match(/add_listing/)) {

    $(document).ready(function () {

        form_elements();

        // search address in google
        search_address();

        $('#enter_state').change(function () {
            update_county_select($(this).val());
        });

        $('#enter_zip').keyup(function() {
            fill_location($(this).val());
        });

        $('.required').bind('change keyup', function () {
            setTimeout(function () {
                enter_address_continue();
            }, 300);
        });
    });

    function fill_location(zip) {
        if(zip.length == 5) {
            axios.get('/global_functions/get_location_details', {
                params: {
                    zip: zip
                },
            })
            .then(function (response) {
                let data = response.data;
                $('#enter_city').val(data.city).trigger('change');
                $('#enter_state').val(data.state).trigger('change');
                update_county_select(data.state);
                setTimeout(function() {
                    $('#enter_county').val(data.county);
                    select_refresh();
                }, 500);
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }

    function enter_address_continue() {
        let form = $('#enter_address_form');
        let cont = 'yes';
        form.find('.required').each(function () {
            if ($(this).val() == '') {
                cont = 'no';
            }
        });
        if (cont == 'yes') {
            $('#address_enter_continue').prop('disabled', false);
            $('#address_enter_continue').click(function () {
                $('.address-container').collapse('hide');
            });
        } else {
            $('#address_enter_continue').prop('disabled', true);
        }
    }

    function update_county_select(state) {
        axios.get('/agents/doc_management/transactions/update_county_select', {
            params: {
                state: state
            },
        })
            .then(function (response) {
                let counties = response.data;
                $('#enter_county').html('').prop('disabled', false);
                $('#enter_county').append('<option value=""></option>');
                $.each(counties, function (k, v) {
                    $('#enter_county').append('<option value="' + v.county.toUpperCase() + '">' + v.county + '</option>');
                });
                setTimeout(function () {
                    select_refresh();
                }, 500);
            })
            .catch(function (error) {
                console.log(error);
            });
    }


    function search_address() {

        // search input
        let address_search_street = document.getElementById('address_search_street');
        // select all text on focus
        $(address_search_street).focus(function () { $(this).select(); });
        // google address search
        let places = new google.maps.places.Autocomplete(address_search_street);
        google.maps.event.addListener(places, 'place_changed', function () {

            //  TODO: need to make these hidden inputs. Cannot access from $('#enter_manually_button').click(function() {
            let address_details = places.getPlace();
            let street_number = street_name = city = county = state = zip = '';
            address_details.address_components.forEach(function (address) {
                if (address.types.includes('street_number')) {
                    street_number = address.long_name;
                } else if (address.types.includes('route')) {
                    street_name = address.long_name;
                } else if (address.types.includes('locality')) {
                    city = address.long_name;
                } else if (address.types.includes('administrative_area_level_2')) {
                    county = address.long_name;
                } else if (address.types.includes('administrative_area_level_1')) {
                    state = address.long_name;
                } else if (address.types.includes('postal_code')) {
                    zip = address.long_name;
                }
            });
            //console.log(street_number, street_name, city, state, zip, county);
            let unit = $('#address_search_unit').val();

            $('#enter_manually_button').click(function () {
                console.log(street_number, street_name, city, state, zip, county);
                $('#enter_street_number').val(street_number).trigger('change');
                $('#enter_street_name').val(street_name).trigger('change');
                $('#enter_zip').val(zip).trigger('change');
                fill_location(zip);
                select_refresh();
            });

            // show continue button once address selected
            $('.address-search-error').hide();
            if (street_number != '') {
                $('.address-search-continue-div').show();
            } else {
                $('.address-search-continue-div').hide();
                $('.address-search-error').show();
                let search_val = $(address_search_street).val().replace(/^[0-9]+\s/, '');
                $(address_search_street).val(search_val)
            }

            // hide continue button if search is empty
            $('#address_search_street').bind('keyup change', function () {
                if ($(this).val() == '') {
                    $('.address-search-continue-div').hide();
                }
            });
            // show results container
            $('#address_search_continue').click(function () {
                $('.address-container').collapse('hide');

                /* axios.get('/agents/doc_management/transactions/get_property_info', {
                    params: {
                        street_number: street_number,
                        street_name: street_number,
                        street_number: street_number,
                        street_number: street_number,
                        street_number: street_number,
                        street_number: street_number,
                        street_number: street_number,
                    },
                })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                }); */

            });

        });

    }

}
