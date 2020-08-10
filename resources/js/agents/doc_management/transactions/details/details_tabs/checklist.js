// const { active } = require("sortablejs");

if (document.URL.match(/transaction_details/)) {

    $(document).ready(function() {



    });

    window.confirm_change_checklist = function() {

        let checklist_id = $(this).data('checklist-id');

        $('#confirm_change_checklist_modal').modal();
        $('#confirm_change_checklist_button').off('click').on('click', function() {

            $('#confirm_change_checklist_modal').modal('hide');
            change_checklist(checklist_id);

        });

    }

    window.change_checklist = function(checklist_id) {

        let Listing_ID = $('#Listing_ID').val();
        let Contract_ID = $('#Contract_ID').val();
        let transaction_type = $('#transaction_type').val();
        let Agent_ID = $('#Agent_ID').val();

        $('#change_checklist_modal').modal();

        $('#save_change_checklist_button').off('click').on('click', function() {

            let form = $('#change_checklist_form');

            let validate = validate_form(form);
            if(validate == 'yes') {

                $('#save_change_checklist_button').html('<i class="fas fa-spinner fa-pulse mr-2"></i> Saving Checklist');

                let formData = new FormData(form[0]);
                formData.append('checklist_id', checklist_id);
                formData.append('Listing_ID', Listing_ID);
                formData.append('Contract_ID', Contract_ID);
                formData.append('transaction_type', transaction_type);
                formData.append('Agent_ID', Agent_ID);

                axios.post('/agents/doc_management/transactions/change_checklist', formData, axios_options)
                .then(function (response) {

                    $('#save_change_checklist_button').html('<i class="fad fa-check mr-2"></i> Save');
                    load_tabs('checklist');
                    load_documents_on_tab_click();
                    $('#change_checklist_modal').modal('hide');
                    toastr['success']('Checklist Successfully Changed');

                })
                .catch(function (error) {
                    console.log(error);
                });

            }

        });

    }


    window.show_delete_doc = function() {
        let button = $(this);
        let document_id = button.data('document-id');

        $('#confirm_delete_checklist_item_doc_modal').modal();
        $('#delete_checklist_item_doc_button').off('click').on('click', function () {
            delete_doc(button, document_id);
        });
    }

    window.delete_doc = function(button, document_id) {
        let formData = new FormData();
        formData.append('document_id', document_id);
        axios.post('/agents/doc_management/transactions/remove_document_from_checklist_item', formData, axios_options)
        .then(function (response) {
            $('#confirm_delete_checklist_item_doc_modal').modal('hide');
            toastr['success']('Document Removed From Checklist');
            load_documents_on_tab_click();
            let doc_count = button.closest('.checklist-item-div').find('.doc-count');
            doc_count.text(parseInt(doc_count.text()) - 1);
            button.closest('.document-row').fadeOut().remove();
        })
        .catch(function (error) {
            console.log(error);
        });
    }

    window.toggle_view_docs_button = function() {
        $('.documents-collapse.show').not($(this).data('target')).collapse('hide');
    }

    window.toggle_view_notes_button = function() {
        $('.notes-collapse.show').not($(this).data('target')).collapse('hide');
    }

    window.show_add_document = function() {

        let button = $(this);

        $('#add_document_checklist_id').val(button.data('checklist-id'));
        $('#add_document_checklist_item_id').val(button.data('checklist-item-id'));

        // confirm earnest and title fields are complete
        if($('#questions_confirmed').val() == 'yes') {

            add_document(button);

        } else {

            $('#required_fields_modal').modal();

            $('#save_required_fields_button').off('click').on('click', function() {

                let form = $('#required_fields_form');
                let validate = validate_form(form);

                if(validate == 'yes') {
                    let Contract_ID = $('#Contract_ID').val();
                    let formData = new FormData(form[0]);
                    formData.append('Contract_ID', Contract_ID);
                    axios.post('/agents/doc_management/transactions/save_required_fields', formData, axios_options)
                    .then(function (response) {

                        $('#required_fields_modal').modal('hide');
                        add_document(button);
                        $('#questions_confirmed').val('yes');

                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                }

            });

        }

    }

    window.add_document = function(button) {
        let active_collapse = button.data('target');
        $('.select-document-button').off('click').on('click', function( ){
            save_add_document($(this).data('document-id'), active_collapse);
        });
        $('#add_document_modal').modal();
    }

    window.save_add_document = function(document_id, active_collapse) {

        let checklist_id = $('#add_document_checklist_id').val();
        let checklist_item_id = $('#add_document_checklist_item_id').val();
        let Listing_ID = $('#Listing_ID').val();
        let Contract_ID = $('#Contract_ID').val();
        let Referral_ID = $('#Referral_ID').val();
        let transaction_type = $('#transaction_type').val();
        let Agent_ID = $('#Agent_ID').val();
        let formData = new FormData();
        formData.append('document_id', document_id);
        formData.append('checklist_id', checklist_id);
        formData.append('checklist_item_id', checklist_item_id);
        formData.append('Listing_ID', Listing_ID);
        formData.append('Contract_ID', Contract_ID);
        formData.append('Referral_ID', Referral_ID);
        formData.append('transaction_type', transaction_type);
        formData.append('Agent_ID', Agent_ID);
        axios.post('/agents/doc_management/transactions/add_document_to_checklist_item', formData, axios_options)
        .then(function (response) {
            $('#add_document_modal').modal('hide');
            toastr['success']('Document Added To Checklist');
            load_tabs('checklist');
            load_documents_on_tab_click();
            /* setTimeout(function() {
                $('#'+active_collapse).collapse('show');
            }, 500); */
        })
        .catch(function (error) {
            console.log(error);
        });

    }

}