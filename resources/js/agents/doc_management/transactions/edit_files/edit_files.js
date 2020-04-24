const writtenNumber = require('written-number');
import datepicker from 'js-datepicker';


if (document.URL.match(/edit_files/)) {

    $(document).ready(function () {

        form_elements();

        $('[data-address-type="state"]').addClass('uppercase').attr('maxlength', 2);
        $('[data-address-type="zip"]').addClass('numbers-only').attr('maxlength', 5);

        if ($('.field-datepicker').length > 0) {

            $('.field-datepicker').each(function() {
                let id = $(this).prop('id');
                window.picker = datepicker('#'+id, {
                    onSelect: (instance, date) => {
                        const value = date.toLocaleDateString();
                        $('#' + instance.el.id).prev('div.data-div').html(value);
                    },
                    onHide: instance => {
                        $('.field-div').removeClass('active');
                    },
                    formatter: (input, date, instance) => {
                        const value = date.toLocaleDateString();
                        input.value = value;
                    },
                    showAllDates: true,
                });
            });


        }

        let field_div_count = $('.field-div').length;
        let field_count = 0;
        $('.field-div').each(function () {
            var group_id = '';
            group_id = $(this).data('group-id');
            // add grouped class
            if ($('.group_' + group_id).length > 1) {
                $('.group_' + group_id).removeClass('standard').addClass('group');
            }
            // date field has no form-div so using field-div instead
            var type = $(this).data('type');
            var form_div;
            if (type == 'date' || type == 'radio' || type == 'checkbox') {
                form_div = $(this);
            } else {
                form_div = $(this).find('.form-div');
            }
            fill_fields(type, group_id, form_div, 'load');
            field_count += 1;
            if(field_count == field_div_count) {
                save_field_input_values('yes');
            }
        });

        field_list();

        $('#save_field_input_values').click(function() {
            save_field_input_values('no');
        });

        // on page click hide all focused els
        $(document).on('click', '.field-container', function (e) {
            if (!$(e.target).is('.field-div *')) {
                $('.field-div').removeClass('active');
                //reset_field_properties();

            }
        });

        $('.modal').on('hide.bs.modal', function () {
            reset_field_properties();
        });

        $('.field-div').click(function () {

            var group_id = $(this).data('group-id');
            // checkboxes and radios never get highlighted
            if ($(this).data('type') != 'checkbox' && $(this).data('type') != 'radio') {

                $('.field-div').removeClass('active');
                $(this).addClass('active');

                if(!$(this).hasClass('date')) {
                    $(this).find('.modal').modal('show');
                    $('.modal-backdrop').appendTo($(this).closest('.field-div'));
                    // hide sidebar. not sure why it shows
                    $('.edit-file-sidebar').css({ 'z-index': '-1' });
                    $('.modal').on('hidden.bs.modal', function (e) {
                        $('.edit-file-sidebar').css({ 'z-index': '1' });
                    });
                }

            } else {

                if ($(this).data('type') == 'radio') {

                    $('.group_' + group_id).find('.data-div').html('');
                    $('.group_' + group_id).find('input[type="radio"]').attr('checked', false);
                    $(this).find('.data-div').next('input[type="radio"]').attr('checked', true);
                    $(this).find('.data-div').html('x');

                } else {

                    // FIXME: need to show/hide checkboxes on click
                    // have to add input value to checkboxes like radios
                    let check = $(this).find('input[type="checkbox"]');
                    let checked = check.attr('checked');
                    if (checked == false || checked == undefined) {
                        check.attr('checked', true);
                        $(this).find('.data-div').html('x');
                    } else {
                        check.attr('checked', false);
                        $(this).find('.data-div').html('');
                    }

                }

            }

        });

        /* $('.field-div').not('.date').off('click').on('click', function () {
            $(this).find('.modal').modal('show');
            // setTimeout(function() {
            //     if($('.modal.show').find('input').eq(0).length == 1) {
            //         $('.modal.show').find('input').eq(0).next('label').addClass('active').focus();
            //     } else if($('.modal.show').find('textarea').eq(0).length == 1) {
            //         $('.modal.show').find('textarea').eq(0).next('label').addClass('active').focus();
            //     }
            // }, 600);
        }); */

        $('.save-fillable-fields').click(function () {
            var type = $(this).data('type');
            var group_id = $(this).data('group-id');
            var form_div = $(this).parent('div.modal-footer').prev('div.modal-body').find('.form-div');
            fill_fields(type, group_id, form_div, 'save');
        });

        // highlight active thumb when clicked and scroll into view
        $('.file-view-thumb-container').click(function () {
            $('.file-view-thumb-container').removeClass('active');
            $(this).addClass('active');
            let id = $(this).data('id');
            document.getElementById('page_' + id).scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
        });

        // change highlighted thumb on scroll when doc is over half way in view
        $('#file_viewer').scroll(function () {

            // Stop the loop once the first is found
            let cont = 'yes';

            $('.file-view-page-container').each(function () {
                if (cont == 'yes') {
                    let id, center, start, end;
                    id = $(this).data('id');
                    // see if scrolled past half way
                    center = $(window).height() / 2;
                    start = $(this).offset().top;
                    end = start + $(this).height();
                    if (start < center && end > center) {
                        // set opacity to 1 for active and .2 for not active
                        $('.file-view-page-container').removeClass('active');
                        $(this).addClass('active');
                        $('#active_page').val(id);
                        // add border to thumb and scroll into view
                        $('.file-view-thumb-container').removeClass('active');
                        $('#thumb_' + id).addClass('active');
                        document.getElementById('thumb_' + id).scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
                        cont = 'no';
                    }
                }
            });

        });

        $('.modal').on('hidden.bs.modal', function (e) {
            $('.field-div').removeClass('active');
        });

        // action buttons
        $(document).on('click', '#rotate_form_button', rotate_form);

        $('#to_pdf').off('click').on('click', function () {
            to_pdf();
        });


    });

    function to_pdf() {
        // fields that css will be changed during export to pdf. They will be reset after
        let els = '.data-div-shrink-font, .file-image-bg, .field-div, .data-div-radio-check';
        let styles;
        $(els).each(function () {
            let data_div = $(this);
            styles = ['color', 'font-size', 'line-height', 'font-weight', 'opacity', 'background', 'margin-left', 'padding-left'];
            $.each(styles, function (index, style) {
                data_div.data(style, data_div.css(style));
            });
        });

        $('.data-div').css({ 'font-size': '11px', 'padding': '7px 0px 0px 5px', 'font-family': 'Ariel' });
        //$('.data-div-shrink-font').css({ 'padding-left': '5px' }); // removed 'font-size': '.8em', 'line-height': '140%', 'font-weight': 'bold',
        $('.file-image-bg').css({ opacity: '0.0' });
        $('.field-div').css({ background: 'none' });
        $('.data-div-radio-check').css({ 'margin-left': '1px', 'font-size': '1.2em', 'line-height': '80%', 'font-weight': 'bold' });


        let file_id = $('#file_id').val();
        let file_name = $('#file_name').val();
        let Listing_ID = $('#Listing_ID').val();

        // remove datepicker html, datepicker input, background img, modals, left over input fields
        let elements_remove = '.qs-datepicker-container, .field-datepicker, .file-image-bg, .modal, .fillable-field-input';

        let formData = new FormData();
        let c = 0;
        $('.file-view-page-container').each(function () {
            c += 1;
            let container = $(this);
            let page_html = container.clone();
            page_html.find(elements_remove).remove();
            page_html = page_html.wrap('<div>').parent().html();
            formData.append('page_' + c, page_html);
        });

        formData.append('page_count', c);
        formData.append('file_id', file_id);
        formData.append('file_name', file_name);
        formData.append('Listing_ID', Listing_ID);

        axios_options['header'] = { 'content-type': 'multipart/form-data' };
        axios.post('/agents/doc_management/transactions/edit_files/convert_to_pdf', formData, axios_options)
            .then(function (response) {
                toastr['success']('PDF Exported Successfully');
            })
            .catch(function (error) {
                //console.log(error);
                });


        setTimeout(function () {
            $(els).each(function () {
                let data_div = $(this);
                $.each(styles, function (index, style) {
                    data_div.css(style, data_div.data(style));
                });
            });

        }, 1000);
    }

    function rotate_form() {
        $('.fa-sync-alt').addClass('fa-spin');
        $('.file-view-page-container, .file-view-thumb-container').addClass('fadeOut');
        let file_id = $('#file_id').val();
        let file_type = $('#file_type').val();
        let Listing_ID = $('#Listing_ID').val();
        let formData = new FormData();
        formData.append('file_id', file_id);
        formData.append('file_type', file_type);
        formData.append('Listing_ID', Listing_ID);
        axios.post('/agents/doc_management/transactions/edit_files/rotate_document', formData, axios_options)
        .then(function (response) {
            setTimeout(function() {
                location.reload();
            }, 500);
        })
        .catch(function (error) {
            console.log(error);
        });

    }

    function save_field_input_values(on_load) {
        let field_data = [];

        $('.fillable-field-input').not('div.fillable-field-input').each(function () {
            let input_value = '';
            let input_id = $(this).attr('id');
            let file_id = $('#file_id').val();
            let file_type = $('#file_type').val();
            let common_name = $(this).data('common-name');
            let Listing_ID = $('#Listing_ID').val();
            let Agent_ID = $('#Agent_ID').val();
            if ($(this).attr('type') == 'radio' || $(this).attr('type') == 'checkbox') {
                if ($(this).is(':checked')) {
                    input_value = $(this).val();
                }
            } else {
                input_value = $(this).val();
            }

            field_data.push({
                input_id: input_id,
                input_value: input_value,
                file_id: file_id,
                file_type: file_type,
                common_name: common_name,
                Listing_ID: Listing_ID,
                Agent_ID: Agent_ID
            });
        });
        axios.post('/agents/doc_management/transactions/edit_files/save_field_input_values', field_data, axios_options)
            .then(function (response) {
                if(on_load == 'no') {
                    $('#modal_success').modal().find('.modal-body').html('Fields Successfully Saved');
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    function fill_fields(type, group_id, form_div, fill_type) {

        if (type == 'number') {

            let input = form_div.find('.fillable-field-input');

            let num = '';
            if (input.val() != '') {
                num = parseInt(input.val());
            }
            // add values to data-div for each field in group
            $('.group_' + group_id).each(function () {
                // add values to all inputs in group
                $(this).find('.fillable-field-input').val(num).data('default-value', num).trigger('change');
                // only the written fields will be split.
                let subtype = $(this).data('number-type');
                let data_div = $(this).find('.data-div');
                if (input.val() == '') {
                    let data_div = $(this).find('.data-div');
                    data_div.html('');
                } else {
                    if (subtype == 'numeric') {
                        data_div.html(global_format_number(num));
                    } else {
                        split_lines(group_id, writtenNumber(num));
                    }
                }
            });



        } else if (type == 'address') {
            // get inputs array
            let inputs = form_div.find('.fillable-field-input');
            // create labels and names from array
            let address_labels = [];
            let address_names = [];
            inputs.each(function () {
                address_labels.push($(this).data('type'));
                address_names.push($(this).data('address-type'));
            });
            // add values for inputs and match with name/label
            let address_values = [];
            $.each(address_labels, function (index, address_label) {
                inputs.each(function () {
                    if ($(this).data('type') == address_label) {
                        address_values.push($(this).val());
                    }
                });
            });

            $('.group_' + group_id).each(function () {

                let group = $(this);
                let address_type = $(this).data('address-type');

                group.find('.fillable-field-input').each(function () {
                    let input = $(this);
                    $.each(address_labels, function (index, address_label) {
                        if (input.data('type') == address_label) {
                            input.val(address_values[index]).data('default-value', address_values[index]).trigger('change');
                        }
                    });
                }).trigger('change');

                $.each(address_names, function (index, address_name) {
                    if (address_type != 'full') {
                        if (group.data('address-type') == address_name) {
                            group.find('.data-div').html(address_values[index]);
                        }
                    } else {
                        let full_address = address_values[0] + ' ' + address_values[1] + ' ' + address_values[3] + ' ' + address_values[4];
                        group.find('.data-div').html(full_address);
                        split_lines(group_id, full_address);
                    }
                });

            });

        } else if (type == 'name') {

            let inputs = form_div.find('.fillable-field-input');

            // get all input labels from data-type
            let name_labels = [];
            inputs.each(function () {
                name_labels.push($(this).data('type'));
            });

            let name_values = [];
            // get values for each label
            $.each(name_labels, function (index, name_label) {
                inputs.each(function () {
                    if ($(this).data('type') == name_label) {
                        name_values.push($(this).val());
                    }
                });
            });

            $('.group_' + group_id).each(function () {
                let group = $(this);
                let name1 = $(this).find('.fillable-field-input').eq(0).val();
                let name2 = $(this).find('.fillable-field-input').eq(1).val();
                let names = name1;

                if (name2 != undefined && name2 != '') {
                    names = names + ', ' + name2;
                }

                if (!names.match(/undefined/)) {

                    group.find('.data-div').html(names);
                    split_lines(group_id, names);

                    group.find('.fillable-field-input').each(function () {
                        let inputs = $(this);
                        $.each(name_labels, function (index, name_label) {
                            if (inputs.data('type') == name_label) {
                                inputs.val(name_values[index]).data('default-value', name_values[index]).trigger('change');
                            }
                        });
                    }).trigger('change');
                }
            });


        } else if (type == 'textline') {

            let textarea = form_div.find('.fillable-field-input');
            let text = textarea.val();
            textarea.data('default-value', text);
            split_lines(group_id, text);


        } else if (type == 'date') {

            let input = form_div.find('.fillable-field-input');
            input.data('default-value', input.val());
            $('.group_' + group_id).find('.data-div').html(input.val());

        } else if (type == 'radio') {

            let input = form_div.find('.fillable-field-input');
            if (input.is(':checked')) {
                input.data('default-value', 'checked');
                input.prev('.data-div').html('x');
            }

        } else if (type == 'checkbox') {

            let input = form_div.find('.fillable-field-input');
            if (input.is(':checked')) {
                input.data('default-value', 'checked');
                input.prev('.data-div').html('x');
            }

        }

        if (fill_type == 'save') {
            $('.modal').modal('hide');
        }
    }

    function split_lines(group_id, text) {

        text = text.trim();
        //let str_len = text.length;
        let field_type = $('.group_' + group_id).data('type');

        // split value between lines
        if ($('.group_' + group_id).not('[data-number-type="numeric"]').length == 1) {
            if (field_type == 'number') {
                $('.group_' + group_id + '[data-number-type="written"]').first().find('.data-div').html(text);
            } else {
                $('.group_' + group_id).first().find('.data-div').html(text);
            }

        } else {

            $('.group_' + group_id).not('[data-number-type="numeric"]').find('.data-div').html('');
            $('.group_' + group_id).not('[data-number-type="numeric"]').each(function () {
                // if there is still text left over
                if (text != '') {

                    let width = String(Math.ceil($(this).width()));
                    let text_len = text.length;
                    let max_chars = width * .18;
                    if (text_len > max_chars) {
                        let section = text.substring(0, max_chars);
                        let end = section.lastIndexOf(' ');
                        let field_text = text.substring(0, end);
                        $(this).find('.data-div').html(field_text);
                        let start = end + 1;
                        text = text.substring(start);
                    } else {
                        $(this).find('.data-div').html(text);
                        text = '';
                    }
                }
            });

        }
    }

    function field_list() {
        $('.field-list-container').html('');
        $('.file-view-page-container').each(function () {
            let page_number = $(this).data('id');
            $('.field-list-container').append('<div class="font-weight-bold text-white bg-primary p-1 pl-2 mb-2">Page ' + page_number + '</div>');
            // get unique group ids
            var group_ids = [];
            $(this).find('.field-div').each(function () {
                group_ids.push($(this).data('group-id'));
            });
            group_ids = group_ids.filter(global_filter_array);
            // get all field names and add to field list
            $.each(group_ids, function (index, group_id) {
                let group = $('.group_' + group_id);
                let type = group.data('type');
                if (group.data('type') == 'checkbox') {
                    group.each(function () {
                        name = $(this).data('customname');
                        $('.field-list-container').append('<div class="mb-1 border-bottom border-primary"><a href="javascript: void(0)" class="field-list-link ml-3" data-group-id="' + group_id + '" data-type="' + type + '">' + name + '</a></div>');
                    });
                } else {
                    name = group.data('customname');
                    if (group.data('commonname') != undefined && group.data('commonname') != '') {
                        name = group.data('commonname');
                    }
                    if (name == undefined || name == '') {
                        name = '<span class="text-danger">Not Named</span>';
                    }
                    $('.field-list-container').append('<div class="mb-1 border-bottom border-primary"><a href="javascript: void(0)" class="field-list-link ml-3" data-group-id="' + group_id + '" data-type="' + type + '">' + name + '</a></div>');
                }

            });
            $('.field-list-link').off('click').on('click', function (e) {
                //e.stopPropagation();
                let group_id = $(this).data('group-id');
                let type = $(this).data('type');
                let ele = $('.field-div[data-group-id="' + group_id + '"]').first();

                if (type == 'date') {
                    setTimeout(function() {
                        ele.find('.field-datepicker').focus().trigger('click').next('.qs-datepicker-container').removeClass('qs-hidden');
                    }, 500);
                } else {
                    if (type != 'checkbox' && type != 'radio') {
                        ele.find('.modal').modal('show');
                    }
                }
                $('.field-div').removeClass('active');
                ele.addClass('active');

                let container = $('#file_viewer');
                let scrollTo = $('#field_' + group_id).first();
                container.animate({
                    scrollTop: (scrollTo.offset().top - container.offset().top + container.scrollTop()) - 200
                });
                setTimeout(function() {
                    ele.trigger('click');
                    //$('.modal.show').find('input').eq(0).trigger('click').focus().next('label').addClass('active');
                }, 200);

            });

        });

    }

    function reset_field_properties() {
        // reset name fields
        $('.form-div').each(function () {
            $(this).find('input, textarea').each(function () {
                $(this).val($(this).data('default-value')).trigger('change');
            });
        });
        $('.field-div').removeClass('active');
    }
}
