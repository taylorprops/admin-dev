if (document.URL.match(/create\/upload\/files/)) {

    $(document).ready(function () {

        // get forms for each form group
        let data_count = $('.forms-data').length;
        $('.forms-data').each(function (index) {
            let form_group_id = $(this).data('form-group-id');
            let state = $(this).data('state');
            let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
            get_forms(form_group_id, state, order);
            // if all form groups are loaded run init functions
            if (index === data_count - 1) {
                setTimeout(function () {
                    form_elements();

                    // Add file modal
                    $('.upload-file-button').off('click').on('click', function () {
                        show_upload($(this));
                    });
                    // init functions
                    upload_options();

                    $('#upload_file_button').off('click').on('click', upload_file);
                }, 500);
            }
        });

    });

    function get_forms(form_group_id, state, order=null) {
        let options = {
            params: {
                form_group_id: form_group_id,
                state: state,
                order: order
            },
            headers: {
                'Accept-Version': 1,
                'Accept': 'text/html',
                'Content-Type': 'text/html'
            }
        }

        axios.get('/doc_management/get_form_group_files', options)
            .then(function (response) {
                $('#list_div_' + form_group_id + '_files').html($(response.data));
                $('#list_div_' + form_group_id + '_file_count').text($('#list_div_' + form_group_id + '_files').find('.files-count').val());
                let ele = $('#list_div_' + form_group_id + '_files').find('.activate-upload');
                filter_uploads(ele);

                upload_options();

            })
            .catch(function (error) {

            });
    }

    function upload_options() {

        $('.edit-upload').off('click').on('click', function () {
            edit_upload($(this));
        });

        $('.duplicate-upload').off('click').on('click', function () {
            duplicate_upload($(this));
        });

        $('.publish-upload').off('click').on('click', function () {
            confirm_publish_upload($(this));
        });

        $('.activate-upload').off('click').on('click', function () {
            activate_upload($(this));
        });

        $('.delete-upload').off('click').on('click', function () {
            confirm_delete_upload($(this));
        });

        $('.manage-upload').off('click').on('click', function () {
            show_manage_upload($(this).data('id'), $(this).data('form-group-id'));
        });


        $('.uploads-filter-published, .uploads-filter-active').change(function () {
            filter_uploads($(this));
        });

        $('.uploads-filter-sort').change(function () {
            sort_uploads($(this));
        });
        tooltip();
    }

    function show_manage_upload(form_id, form_group_id) {
        $('#form_manage_modal').modal();
        axios.get('/doc_management/get_manage_upload_details', {
            params: {
                form_id: form_id,
                form_group_id: form_group_id
            },
            headers: {
                'Accept-Version': 1,
                'Accept': 'text/html',
                'Content-Type': 'text/html'
            }
        })
        .then(function (response) {

            $('#form_manage_div').html(response.data);
            form_elements();

            let state = $('#manage_form_state').val();
            let form_name = $('#manage_form_name').val();

            $('.select-form-button').off('click').on('click', function () {
                show_confirm_replace(form_group_id, state, form_id, form_name, $(this).data('form-id'), $(this).data('form-name'));
            });
            $('#remove_from_checklist_button').off('click').on('click', function () {
                show_confirm_remove(form_id, form_name, form_group_id, state);
            });
            $('#add_to_checklists_button').off('click').on('click', function () {
                show_add_to_checklists(form_id, form_name, form_group_id, state);
            });

            tooltip();

        })
        .catch(function (error) {
            console.log(error);
        });
    }

    function show_add_to_checklists(form_id, form_name, form_group_id, state) {
        $('#add_to_checklists_modal').modal();
        axios.get('/doc_management/get_add_to_checklists_details', {
            params: {
                form_id: form_id,
                form_group_id: form_group_id
            },
            headers: {
                'Accept-Version': 1,
                'Accept': 'text/html',
                'Content-Type': 'text/html'
            }
        })
        .then(function (response) {

            $('#add_form_to_checklists_div').html(response.data);
            $('#add_to_checklists_form_name').text(form_name);
            form_elements();
            $('#add_to_checklists_table').DataTable({
                'paging': false,
                'toolbar': 'add_to_checklists_table_toolbar',
                "aaSorting": [],
                columnDefs: [{
                    orderable: false,
                    targets: [0,1,2,10]
                }]
            });
            // hide all collapsible on open
            $('.show-checklist-items-collapsible').off('click').on('click', function() {
                $('.checklist-items-collapsible').collapse('hide');
            });

            sortable();
            order_checklists();

            // highlight background of selected rows
            $('.checklist-item-checkbox').change(function() {
                if($(this).is(':checked')) {
                    $(this).closest('tr').addClass('bg-blue-light');
                } else {
                    $(this).closest('tr').removeClass('bg-blue-light');
                }
            });

            $('#save_add_to_checklists_button').off('click').on('click', function() {
                save_add_to_checklists(state, form_id, form_group_id);
            });

        })
        .catch(function (error) {
            console.log(error);
        });

    }

    function save_add_to_checklists(state, checklist_form_id, checklist_item_group_id) {

        let form = $('#add_to_checklists_form');
        let form_check = validate_form(form);

        if (form_check == 'yes') {


            let els = $('.checklist-items-tr');
            let checklists = {
                checklist: []
            }

            els.each(function () {
                let el, checklist_id_checkbox, checklist_id, checklist_order;
                el = $(this);
                checklist_id_checkbox = el.find('.checklist-item-checkbox');
                checklist_id = '';
                if(checklist_id_checkbox.is(':checked')) {
                    checklist_id = checklist_id_checkbox.val();
                }
                checklist_order = el.find('.checklist-order').val();
                checklists.checklist.push(
                    {
                        'checklist_id': checklist_id,
                        'checklist_order': checklist_order
                    }
                );
            });
            checklists = JSON.stringify(checklists);
            let file_id = $('#add_to_checklist_file_id').val();
            let required = $('.checklist-item-required').val();
            let checklist_items_group_id = $('.checklist-item-group-id').val();

            let formData = new FormData();
            formData.append('checklists', checklists);
            formData.append('file_id', file_id);
            formData.append('required', required);
            formData.append('form_group_id', checklist_items_group_id);

            axios.post('/doc_management/save_add_to_checklists', formData, axios_options)
            .then(function (response) {
                $('#add_to_checklists_modal').modal('hide');
                let order = $('#list_div_' + checklist_item_group_id).find('.uploads-filter-sort').val();
                get_forms(checklist_item_group_id, state, order);
                setTimeout(function() {
                    show_manage_upload(checklist_form_id, checklist_item_group_id);
                }, 100);
            })
            .catch(function (error) {
                console.log(error);
            });

        }
    }

    function sortable() {
        $('.checklist-items-collapsible').sortable({
            handle: '.order-checklist-item-sortable-handle',
            stop: function( event, ui ) {
                reorder_checklists();
                $(ui.item).closest('tr').find('.checklist-order').val($(ui.item).index() + 1);
            }
        });
    }

    function order_checklists() {
        $('.show-checklist-items-collapsible').click(function() {
            if($(this).text() == 'Show Checklist Items') {
                $('.show-checklist-items-collapsible').text('Show Checklist Items').addClass('btn-primary').removeClass('btn-success');
                $(this).text('Hide Checklist Items').removeClass('btn-primary').addClass('btn-success');
            } else {
                $(this).text('Show Checklist Items').addClass('btn-primary').removeClass('btn-success');
            }
        });
        $('.checklist-items-collapsible').disableSelection();

        $('.checklist-order').on('keyup change', function() {
            let index;
            if($(this).val() == '') {
                index = 0;
            } else {
                index = $(this).val() - 1;
            }
            let parent = $(this).closest('tr').find('.checklist-items-collapsible');
            let count = parent.find('.order-checklist-item').length;

            if(index == 0) {
                parent.find('.order-checklist-item-sortable').prependTo(parent);
            } else if(index < (count - 1)) {
                let clone = parent.find('.order-checklist-item-sortable');
                parent.find('.order-checklist-item-sortable').remove();
                clone.insertBefore(parent.find('.order-checklist-item').eq(index));
            } else {
                parent.find('.order-checklist-item-sortable').appendTo(parent);
            }
            reorder_checklists();
        });
    }


    function reorder_checklists() {
        $('.order-checklist-item').each(function() {
            $(this).find('.checklist-item-order').text($(this).index() + 1);
        });
    }

    function show_confirm_remove(form_id, form_name, form_group_id, manage_form_state) {
        $('#remove_form_modal').modal();
        $('#remove_form_name').text(form_name);
        $('#confirm_remove_from_checklist_button').off('click').on('click', function () {
            let formData = new FormData();
            formData.append('form_id', form_id);
            axios.post('/doc_management/remove_upload', formData, axios_options)
            .then(function (response) {
                $('#remove_form_modal').modal('hide');
                let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
                get_forms(form_group_id, manage_form_state, order);
                $('#form_manage_modal').modal('hide');
                toastr['success']('Form Successfully Removed');
            })
            .catch(function (error) {
                console.log(error);
            });
        });
    }

    function show_confirm_replace(form_group_id, manage_form_state, old_form_id, manage_form_name, new_form_id, new_form_name) {
        $('#replace_form_modal').modal();
        $('#replace_old_form').text(manage_form_name);
        $('#replace_new_form').text(new_form_name);
        $('#confirm_replace_form_button').off('click').on('click', function () {
            let formData = new FormData();
            formData.append('old_form_id', old_form_id);
            formData.append('new_form_id', new_form_id);
            axios.post('/doc_management/replace_upload', formData, axios_options)
            .then(function (response) {
                $('#replace_form_modal').modal('hide');
                let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
                get_forms(form_group_id, manage_form_state, order);
                $('#form_manage_modal').modal('hide');
                toastr['success']('Form Successfully Replaced');
            })
            .catch(function (error) {
                console.log(error);
            });
        });
    }


    function sort_uploads(ele) {
        let order = ele.val();
        let list_div = ele.closest('.list-div');
        let form_group_id = list_div.find('.form-group-id').val();
        let state = list_div.find('.form-group-state').val();
        get_forms(form_group_id, state, order);

    }

    function activate_upload(ele) {
        let upload_id = ele.data('id');
        let form_group_id = ele.data('form-group-id');
        let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
        let state = ele.data('state');
        let active = ele.data('active');
        let formData = new FormData();
        formData.append('upload_id', upload_id);
        formData.append('active', active);
        axios.post('/doc_management/activate_upload', formData)
            .then(function (response) {
                get_forms(form_group_id, state, order);
                let msg;
                if (active == 'yes') {
                    msg = 'Form Activated Successfully';
                } else {
                    msg = 'Form Deactivated Successfully';
                }
                toastr['success'](msg);
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    function filter_uploads(ele) {

        let list_div = ele.closest('.list-div');
        list_div.find('.uploads-list').show();

        let filter_active = list_div.find('.uploads-filter-active').val();
        let filter_publish = list_div.find('.uploads-filter-published').val();
        list_div.find('.uploads-filter-active').prop('disabled', false);

        // filter published first since a form cannot be notactive unless it's published first
        if (filter_publish == 'published') {
            list_div.find('.notpublished').hide();
            if (filter_active == 'active') {
                list_div.find('.published.notactive').hide();
            } else if (filter_active == 'notactive') {
                list_div.find('.published.active').hide();
            }
        } else if (filter_publish == 'notpublished') {
            list_div.find('.published').hide();
            list_div.find('.uploads-filter-active').val('all').prop('disabled', true);
        } else {
            if (filter_active == 'active') {
                list_div.find('.uploads-list').show();
                list_div.find('.notactive').hide();
            } else if (filter_active == 'notactive') {
                list_div.find('.uploads-list').hide();
                list_div.find('.published.notactive').show();
            }
        }
        select_refresh();

    }

    function confirm_publish_upload(ele) {

        let upload_id = ele.data('id');
        let form_group_id = ele.data('form-group-id');
        let state = ele.data('state');

        $('#confirm_publish_modal').modal();

        $('#confirm_publish').off('click').on('click', function () {
            publish_upload(upload_id, form_group_id, state);
        });
    }

    function publish_upload(upload_id, form_group_id, state) {
        let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
        let formData = new FormData();
        formData.append('upload_id', upload_id);
        axios.post('/doc_management/publish_upload', formData)
            .then(function (response) {
                $('#confirm_publish_modal').modal('hide');
                get_forms(form_group_id, state, order);
                toastr['success']('Form Published Successfully');
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    function duplicate_upload(ele) {
        let upload_id = ele.data('id');
        let form_group_id = ele.data('form-group-id');
        let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
        let state = ele.data('state');
        let formData = new FormData();
        formData.append('upload_id', upload_id);
        axios.post('/doc_management/duplicate_upload', formData)
            .then(function (response) {
                get_forms(form_group_id, state, order);
                toastr['success']('Form Duplicated Successfully');
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    function confirm_delete_upload(ele) {

        let upload_id = ele.data('id');
        let form_group_id = ele.data('form-group-id');
        let state = ele.data('state');

        $('#confirm_delete_modal').modal();

        $('#confirm_delete').off('click').on('click', function () {
            delete_upload(upload_id, form_group_id, state);
        });
    }

    function delete_upload(upload_id, form_group_id, state) {
        let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
        let formData = new FormData();
        formData.append('upload_id', upload_id);
        axios.post('/doc_management/delete_upload', formData)
            .then(function (response) {
                $('#confirm_delete_modal').modal('hide');
                get_forms(form_group_id, state, order);
                toastr['success']('Form Deleted Successfully');
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    function edit_upload(ele) {

        let upload_id = ele.data('id');

        axios.get('/doc_management/get_upload_details', {
            params: {
                upload_id: upload_id
            },
        })
            .then(function (response) {

                let file_name_orig = response.data.file_name_orig;
                let file_name = response.data.file_name_display;
                let form_group_id = response.data.form_group_id;
                let state = response.data.state;
                let sale_type = response.data.sale_type;
                let form = $('#edit_file_form');
                form.find('select').val('').trigger('change');
                $('#edit_form_name').text(file_name_orig);
                $('#edit_file_name_display').val(file_name).trigger('change');
                $('#edit_form_group_id').val(form_group_id);
                $('#edit_state').val(state);
                sale_type = sale_type.split(',');

                $.each(sale_type, function (i, e) {
                    $('#edit_sale_type option[value="' + e + '"]').prop('selected', true);
                });
                $('#edit_sale_type').trigger('change');

                $('#edit_file_id').val(upload_id);
                setTimeout(function () {
                    select_refresh();
                }, 500);

                $('#edit_file_modal').modal();

                $('#save_edit_file_button').off('click').on('click', save_edit_file);

            })
            .catch(function (error) {
                console.log(error);
            });
    }

    function save_edit_file() {

        let form = $('#edit_file_form');
        let form_check = validate_form(form);

        if (form_check == 'yes') {

            let form_group_id = $('#edit_form_group_id').val();
            let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
            let state = $('#edit_state').val();

            $('#save_edit_file_button').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving');

            let formData = new FormData(form[0]);

            axios.post('/doc_management/save_file_edit', formData)
                .then(function (response) {
                    $('#edit_file_modal').modal('hide');
                    get_forms(form_group_id, state, order);
                    $('#save_edit_file_button').prop('disabled', false).html('<i class="fad fa-upload mr-2"></i> Save Details');
                    toastr['success']('Upload Edited Successfully');
                })
                .catch(function (error) {
                    //console.log(error);
                });

        }

    }

    function show_upload(ele) {

        $('#add_upload_modal').modal();

        let state = ele.data('state');
        let form_group_id = ele.data('form-group-id');
        $('#form_group_id').val(form_group_id);
        $('#state').val(state);
        //console.log($('#sale_type').val());
        $('#sale_type').val('');

        select_refresh();

        setTimeout(function () {
            $('.file-path').bind('change', function () {
                let form_name = $('.file-path').val().replace(/\.pdf/, '');
                $('#file_name_display').val(form_name).trigger('change');
            });
            $('#form_group_id').change(function () {
                $('#state').val(ele.find('option:selected').data('state'));
                select_refresh();
            });
        }, 500);
    }

    function upload_file() {
        let form_check = validate_form($('#upload_file_form'));

        if (form_check == 'yes') {

            let form_group_id = $('#form_group_id').val();
            let state = $('#state').val();
            let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
            console.log(order);

            $('#upload_file_button').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Uploading');

            let formData = new FormData($('#upload_file_form')[0]);

            axios_options['header'] = { 'content-type': 'multipart/form-data' };
            axios.post('/doc_management/upload_file', formData, axios_options)
                .then(function (response) {
                    $('#add_upload_modal').modal('hide');
                    $('#file_name_display, #file_upload, #sale_type').val('').trigger('change');
                    select_refresh();
                    get_forms(form_group_id, state, order);
                    $('#upload_file_button').prop('disabled', false).html('<i class="fad fa-upload mr-2"></i> Upload Form');
                })
                .catch(function (error) {
                    //console.log(error);
                });
        }
    }




}
