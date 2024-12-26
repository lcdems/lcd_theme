jQuery(document).ready(function($) {
    // Store the original quick edit function
    var $wp_inline_edit = inlineEditPost.edit;

    // Override the quick edit function
    inlineEditPost.edit = function(id) {
        // Call the original quick edit function
        $wp_inline_edit.apply(this, arguments);

        // Get the post ID
        var post_id = 0;
        if (typeof(id) === 'object') {
            post_id = parseInt(this.getId(id));
        }

        if (post_id > 0) {
            // Get the row data
            var $row = $('#post-' + post_id);
            
            // Get the order value
            var order = $row.find('.section-order').text();
            
            // Populate the quick edit fields
            var $editRow = $('#edit-' + post_id);
            $editRow.find('input[name="section_order"]').val(order);
        }
    };

    // Handle bulk edit
    $('#bulk_edit').on('click', function(e) {
        e.preventDefault();
        
        // Get the selected post IDs
        var post_ids = [];
        $('input[name="post[]"]:checked').each(function() {
            post_ids.push($(this).val());
        });

        // Get the order value
        var order = $('input[name="section_order"]').val();

        // Make ajax request
        $.ajax({
            url: lcdAdminList.ajaxurl,
            type: 'POST',
            data: {
                action: 'lcd_save_bulk_edit_order',
                post_ids: post_ids.join(','),
                section_order: order,
                nonce: lcdAdminList.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Reload the page to show updated values
                    location.reload();
                }
            }
        });
    });
}); 