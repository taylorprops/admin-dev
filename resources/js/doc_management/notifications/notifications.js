if(document.URL.match(/notifications/)) {

    $(function() {

        let options = {
            selector: '.notification-text-editor',
            inline: true,
            menubar: false,
            statusbar: false,
            toolbar: 'backcolor forecolor | bold italic underline'
        }
        text_editor(options);

        $('.list-group').sortable({
            handle: '.list-group-handle',
            stop: function (event, ui) {
                let items = [];

                $('.list-group-item').each(function() {
                    let config_id = $(this).data('config-id');
                    let order = $(this).index();
                    items.push({
                        config_id: config_id,
                        order: order
                    });
                });

                items = JSON.stringify(items);

                let formData = new FormData();
                formData.append('items', items);

                axios.post('/doc_management/reorder_notifications', formData, axios_options)
                .then(function (response) {
                    toastr['success']('Reorder Successfully');
                })
                .catch(function (error) {

                });

            }
        });

        //$('.list-group').disableSelection();

        $('.save-config-button').on('click', function () {

            let type = $(this).data('type');
            let container = $(this).closest('.notification-container');

            let config_id = $(this).data('config-id');
            let title = container.find('.notification-text-editor[data-field="title"]').html();
            let description = container.find('.notification-text-editor[data-field="description"]').html();
            let emails = '';
            let number = '';
            let on_off = '';
            if(type == 'emails') {
                emails = container.find('.emails').val();
            } else if(type == 'number') {
                number = container.find('.number').val();
            } else if(type == 'on_off') {
                on_off = container.find('.on-off').val();
            }

            let formData = new FormData();
            formData.append('config_id', config_id);
            formData.append('title', title);
            formData.append('description', description);
            formData.append('emails', emails);
            formData.append('number', number);
            formData.append('on_off', on_off);
            axios.post('/doc_management/save_notifications', formData, axios_options)
            .then(function (response) {
                toastr['success']('Changes Successfully Saved');
            })
            .catch(function (error) {
                console.log(error);
            });

        });

    });



}
