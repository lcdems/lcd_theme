jQuery(document).ready(function($) {
    // Store the initial section type
    var currentSectionType = $('#section_type').val();

    // Handle icon upload button clicks
    $(document).on('click', '.upload-icon-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var cardNumber = button.data('card');
        
        // Create a new media uploader instance for this button
        var mediaUploader = wp.media({
            title: 'Select Icon Image',
            button: {
                text: 'Use this image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });

        // When an image is selected, run a callback
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            var previewContainer = button.siblings('.icon-preview');
            var inputField = button.siblings('.card-icon-input');
            
            // Update the preview
            previewContainer.html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto;">');
            
            // Update the hidden input
            inputField.val(attachment.url);
            
            // Add remove button if it doesn't exist
            if (button.siblings('.remove-icon-button').length === 0) {
                button.after('<button type="button" class="button remove-icon-button" data-card="' + cardNumber + '">Remove Icon</button>');
            }
        });

        // Open the uploader dialog
        mediaUploader.open();
    });

    // Handle icon removal
    $(document).on('click', '.remove-icon-button', function(e) {
        e.preventDefault();
        var button = $(this);
        var previewContainer = button.siblings('.icon-preview');
        var inputField = button.siblings('.card-icon-input');
        
        // Clear the preview
        previewContainer.empty();
        
        // Clear the input
        inputField.val('');
        
        // Remove the remove button
        button.remove();
    });

    // Handle section type changes
    $('#section_type').on('change', function() {
        var type = $(this).val();
        currentSectionType = type;
        
        // Save current form data
        var formData = new FormData($('form#post')[0]);
        
        // Add action and section type
        formData.append('action', 'lcd_load_section_fields');
        formData.append('section_type', type);
        formData.append('nonce', lcdHomepageBuilder.nonce);
        
        // Show loading indicator
        $('#section-content-fields').html('<p class="loading">Loading...</p>');
        
        // Load new fields via AJAX
        $.ajax({
            url: lcdHomepageBuilder.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Replace only the inner content, preserving the wrapper and nonce field
                    $('#section-content-fields').html(response.data);
                    
                    // Reinitialize WordPress editors if present
                    if (typeof tinymce !== 'undefined') {
                        // Remove all existing editors first
                        tinymce.remove();
                        
                        // Find all editor textareas
                        $('.section-text-editor').each(function() {
                            var editorId = $(this).attr('id');
                            
                            // Initialize quicktags
                            if (typeof quicktags !== 'undefined') {
                                quicktags({
                                    id: editorId,
                                    buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
                                });
                                QTags._buttonsInit();
                            }
                            
                            // Initialize TinyMCE
                            if (typeof tinyMCEPreInit !== 'undefined') {
                                var init = Object.assign({}, tinyMCEPreInit.mceInit.content);
                                init.selector = '#' + editorId;
                                init.id = editorId;
                                init.height = '300';
                                tinymce.init(init);
                                
                                // Switch to Visual mode by default
                                if (typeof switchEditors !== 'undefined') {
                                    switchEditors.go(editorId, 'tmce');
                                }
                            }
                        });
                    }
                } else {
                    console.error('AJAX Error:', response);
                    alert(response.data || 'Error loading section fields. Please try again.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                alert('Error loading section fields. Please try again.');
            }
        });
    });

    // Handle form submission
    $('form#post').on('submit', function(e) {
        // Update the section type field
        var sectionTypeField = $('select[name="section_type"]');
        if (sectionTypeField.length) {
            sectionTypeField.val(currentSectionType);
        } else {
            // If the field doesn't exist, add it as hidden input
            $(this).append($('<input>').attr({
                type: 'hidden',
                name: 'section_type',
                value: currentSectionType
            }));
        }
    });
}); 