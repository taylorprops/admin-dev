if(document.URL.match(/document_review/)) {

    $(document).ready(function() {


        $('.property-item').off('click').on('click', function() {
            $('.documents-div').children().addClass('animated bounceOutDown');
            $('.details-div').children().addClass('animated fadeOut');
            global_loading_on('', '<div class="h4-responsive text-white">Loading Checklist Documents...</div>');
            let id = $(this).data('id');
            let type = $(this).data('type');
            get_checklist(id, type);
            get_details(id, type);
            set_property_item_active($(this));
            show_hide_next();
        });

        $('#close_checklist_button').off('click').on('click', close_checklist);

        $('.next-button').off('click').on('click', function() {
            next_property();
        });
    });

    function set_property_item_active(ele) {
        $('.property-item').removeClass('active').addClass('list-group-item-action');
        ele.addClass('active').removeClass('list-group-item-action');
    }

    window.next_property = function() {
        let ele = $('.property-item.active');
        let index = ele.index();
        cancel = false;
        let last_index = null;
        $('.property-item').each(function() {
            if(cancel == false) {
                if($(this).index() > index) {
                    $(this).trigger('click');
                    cancel = true;
                    last_index = $(this).index();
                }
            }
        });

        show_hide_next();
    }

    function show_hide_next() {
        if($('.list-group-item.property-item.active').index() == $('.list-group-item.property-item').last().index()) {
            $('.next-button').hide();
        } else {
            $('.next-button').show();
        }
    }

    window.get_checklist = function(id, type) {

        $('#add_checklist_item_modal').remove();

        axios.get('/doc_management/get_checklist', {
            params: {
                id: id,
                type: type
            },
            headers: {
                'Accept-Version': 1,
                'Accept': 'text/html',
                'Content-Type': 'text/html'
            }
        })
        .then(function (response) {

            $('.checklist-items-div').html(response.data);
            $('.checklist-items-container').removeClass('fadeOut').show();

            $('.checklist-item-name').off('click').on('click', function(e) {

                if($(this).hasClass('active')) {
                    return false;
                }

                let checklist_item_div = $(this).closest('.checklist-item-div');
                $('.checklist-item-div').removeClass('active').find('.checklist-item-name').removeClass('active text-white');
                checklist_item_div.addClass('active').find('.checklist-item-name').addClass('active text-white');
                get_documents($(this).data('checklist-item-id'), $(this).data('checklist-item-name'));

            });

            $('.notes-div').each(function() {
                get_notes($(this).data('checklist-item-id'));
            });

            let item_div = '';
            let show_notes = false;
            if($('.checklist-item-div.pending').length > 0 || $('.checklist-item-div.notes-unread').length > 0) {
                let pending_index = '';
                let notes_index = '';
                if($('.checklist-item-div.pending').length > 0) {
                    pending_index = $('.checklist-item-div.pending').eq(0).index();
                }
                if($('.checklist-item-div.notes-unread').length > 0) {
                    notes_index = $('.checklist-item-div.notes-unread').eq(0).index();
                }
                if(pending_index < notes_index || notes_index == '') {
                    item_div = $('.checklist-item-div.pending');
                } else {
                    item_div = $('.checklist-item-div.notes-unread');
                    show_notes = true;
                }
            } else {
                item_div = $('.checklist-item-div');
            }
            item_div.eq(0).find('.checklist-item-name').trigger('click');
            setTimeout(function() {
                if(show_notes) {
                    item_div.eq(0).find('.checklist-item-notes-div').collapse('show');
                }
            }, 1000);

            scroll_checklist_item(item_div);

            $('.notes-toggle').off('click').on('click', function() {
                $(this).closest('.checklist-item-div').find('.checklist-item-name').trigger('click');
            });

            $('.modal').each(function() {
                $(this).appendTo('body');
            });

            $('.mark-required').off('click').on('click', function() {
                mark_required($(this), $(this).data('checklist-item-id'), $(this).data('required'));
            });

            $('.remove-checklist-item').off('click').on('click', function() {
                show_remove_checklist_item($(this), $(this).data('checklist-item-id'));
            });

            $('.add-checklist-item-button').off('click').on('click', show_add_checklist_item);

            $('.save-notes-button').off().on('click', save_add_notes);


            $('#property_id').val(id);
            $('#property_type').val(type);

            form_elements();

            setTimeout(function() {
                global_loading_off();
            }, 1500);

        })
        .catch(function (error) {
            console.log(error);
        });

    }

    function scroll_checklist_item(item_div) {
        let item_id = item_div.prop('id');
        $('.checklist-items-container').scrollTop(0);
        $('.checklist-items-container').animate({
            scrollTop: $('#'+item_id).offset().top - 150
        },'fast');
    }

    function get_documents(checklist_item_id, checklist_item_name) {

        axios.get('/doc_management/get_documents', {
            params: {
                checklist_item_id: checklist_item_id,
                checklist_item_name: checklist_item_name
            },
            headers: {
                'Accept-Version': 1,
                'Accept': 'text/html',
                'Content-Type': 'text/html'
            }
        })
        .then(function (response) {

            $('.checklist-item-docs-div').remove();
            $('.documents-div').html(response.data).show();

            // hide all notes before opening a new item
            $('.checklist-item-notes-div').collapse('hide');
            // clear docs sections
            $('.documents-list').hide();
            $('.list-group-item.checklist-item-div').find('.documents-list').html('');
            // add documents to checklist item and open it
            if($('.checklist-item-docs-div').length > 0) {
                $('.list-group-item.checklist-item-div.active').find('.documents-list').show()
                    .append('<div class="font-weight-bold text-primary border-bottom">Documents</div>')
                    .append($('.checklist-item-docs-div'))
                    .find('.document-link').on('click', function() {
                        let id = $(this).data('document-id');
                        $('.review-image-container').scrollTop(0);
                        $('.review-image-container').animate({
                            scrollTop: $('#document_' + id).offset().top - 70
                        },'fast');
                    });
            }

            $('.accept-checklist-item-button, .reject-checklist-item-button, .undo-accepted, .undo-rejected').data('checklist-item-id', checklist_item_id);

            $('.accept-checklist-item-button').off('click').on('click', function() {
                checklist_item_review_status($(this), 'accepted', null);
            });
            $('.reject-checklist-item-button').off('click').on('click', function() {
                show_checklist_item_review_status($(this), 'rejected');
            });

            $('.undo-accepted, .undo-rejected').off('click').on('click', function() {
                checklist_item_review_status($(this), 'not_reviewed', null);
            });

            $('.next-button').off('click').on('click', function() {
                next_property();
            });

            $('.email-agent-button').off('click').on('click', show_email_agent);

            $('#zoom').on('input change', zoom);

            $('.zoom-out').click(function() {
                $('#zoom').val(parseInt($('#zoom').val()) - 5).trigger('change');
                $('#thumb span').text($('#zoom').val());
            });
            $('.zoom-in').click(function() {
                $('#zoom').val(parseInt($('#zoom').val()) + 5).trigger('change');
                $('#thumb span').text($('#zoom').val());
            });

            $('#scroll_up').off('click').on('click', function () {
                document.querySelector('.review-image-container').scrollBy({
                    top: -500, // could be negative value
                    left: 0,
                    behavior: 'smooth'
                });
            });
            $('#scroll_down').off('click').on('click', function (evt) {
                document.querySelector('.review-image-container').scrollBy({
                    top: 500, // could be negative value
                    left: 0,
                    behavior: 'smooth'
                });
            });

            show_hide_next();


        })
        .catch(function (error) {
            console.log(error);
        });

    }

    function get_details(id, type) {

        axios.get('/doc_management/get_details', {
            params: {
                id: id,
                type: type
            },
            headers: {
                'Accept-Version': 1,
                'Accept': 'text/html',
                'Content-Type': 'text/html'
            }
        })
        .then(function (response) {
            $('.details-div').html(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });

    }



    function close_checklist() {
        $('.checklist-items-container').addClass('animated fadeOut').hide();
        $('.documents-div').children().addClass('animated bounceOutDown');
        $('.documents-div').html('<div class="h1-responsive text-primary w-100 text-center mt-5 pt-5"><i class="fa fa-arrow-left mr-2"></i> To Begin Select A Property</div>');
        $('.details-div').children().addClass('animated fadeOut');
    }

    function zoom() {
        let z = $(this).val();
        $('.review-image-div').css({ width: z+'%' });
    }

}