if (document.URL.match(/create\/upload\/files/)) {

    $(document).ready(function () {

        let data_count = $('.forms-data').length;
        $('.forms-data').each(function (index) {
            let form_group_id = $(this).data('form-group-id');
            let state = $(this).data('state');
            let order = $('#list_div_' + form_group_id).find('.uploads-filter-sort').val();
            get_forms(form_group_id, state, order);
            if (index === data_count - 1) {
                setTimeout(function () {
                    form_elements();

                    // Add file modal
                    $('.upload-file-button').off('click').on('click', function () {
                        show_upload($(this));
                    });

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

                $('.edit-upload').not('.clickready').off('click').on('click', function () {
                    edit_upload($(this));
                }).addClass('clickready');

                $('.duplicate-upload').not('.clickready').off('click').on('click', function () {
                    duplicate_upload($(this));
                }).addClass('clickready');

                $('.publish-upload').not('.clickready').off('click').on('click', function () {
                    confirm_publish_upload($(this));
                }).addClass('clickready');

                $('.activate-upload').not('.clickready').off('click').on('click', function () {
                    activate_upload($(this));
                }).addClass('clickready');

                $('.delete-upload').not('.clickready').off('click').on('click', function () {
                    confirm_delete_upload($(this));
                }).addClass('clickready');

                $('.replace-upload').not('.clickready').off('click').on('click', function () {
                    show_replace_upload($(this).data('id'));
                }).addClass('clickready');

            })
            .catch(function (error) {

            });
    }

    function upload_options() {


        $('.edit-upload').off('click').on('click', function () {
            edit_upload($(this));
        }).addClass('clickready');

        $('.duplicate-upload').off('click').on('click', function () {
            duplicate_upload($(this));
        }).addClass('clickready');

        $('.publish-upload').off('click').on('click', function () {
            confirm_publish_upload($(this));
        }).addClass('clickready');

        $('.activate-upload').off('click').on('click', function () {
            activate_upload($(this));
        }).addClass('clickready');

        $('.delete-upload').off('click').on('click', function () {
            confirm_delete_upload($(this));
        }).addClass('clickready');

        $('.replace-upload').off('click').on('click', function () {
            show_replace_upload($(this).data('id'));
        }).addClass('clickready');



        $('.uploads-filter-published, .uploads-filter-active').change(function () {
            filter_uploads($(this));
        });

        $('.uploads-filter-sort').change(function () {
            sort_uploads($(this));
        });
    }

    function show_replace_upload(form_id) {

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

        // TODO just trying to filter these

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
