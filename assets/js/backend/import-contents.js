function humanizeSize(bytes)
    {
        if (typeof bytes !== 'number') {
            return '';
        }
        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }
        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }
        return (bytes / 1000).toFixed(2) + ' KB';
    }
    $( document ).ready(function() {
        
        var import_attach_skin_add_button = Ladda.create( document.querySelector('#page-header-desc-vccontentanywhere-import_vccontentanywhere' ));
        var import_attach_skin_total_files = 0;
        var success_message = 'Import successfully';
        $('#import_vcc_anywhere_1').fileupload({
            dataType: 'json',
            autoUpload: true,
            singleFileUploads: true,
            maxFileSize: 1000000000,
            success: function (e) {
                showSuccessMessage(success_message);
                window.setTimeout(function() {
                        location.reload();
                    }, 1000);
            },
            start: function (e) {               
                import_attach_skin_add_button.start();
            },
            fail: function (e, data) {
                showErrorMessage(data.errorThrown.message);
            },
            done: function (e, data) {
                if (data.result) {
                    if (typeof data.result.attachment_file !== 'undefined') {
                        if (typeof data.result.attachment_file.error !== 'undefined' && data.result.attachment_file.error.length > 0)
                            $.each(data.result.attachment_file.error, function(index, error) {
                                showErrorMessage(data.result.attachment_file.name + ' : ' + error);
                            });
                        else {
                            showSuccessMessage(success_message);
                            $('#selectAttachment2').append('<option value="'+data.result.attachment_file.id_attachment+'">'+data.result.attachment_file.filename+'</option>');
                        }
                    }
                }
            },
        }).on('fileuploadalways', function (e, data) {
            import_attach_skin_add_button.stop();
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index, file = data.files[index];
        }).on('fileuploadsubmit', function (e, data) {
            var params = new Object();

            $('input[id^="attachment_name_"]').each(function()
            {
                var id = $(this).prop("id").replace("attachment_name_", "attachment_name[") + "]";
                params[id] = $(this).val();
            });
            $('textarea[id^="attachment_description_"]').each(function()
            {
                var id = $(this).prop("id").replace("attachment_description_", "attachment_description[") + "]";
                params[id] = $(this).val();
            });
            data.formData = params;         

        });
        

        $('#page-header-desc-vccontentanywhere-import_vccontentanywhere_1').on('click', function(e) {
            e.preventDefault();
            import_attach_skin_total_files = 0;            
            $('#import_vcc_anywhere').trigger('click');
        });
        
    });