(function() {
    tinymce.PluginManager.add('lcd_shortcodes', function(editor, url) {
        // Helper function to create and insert shortcode
        function insertShortcode(shortcode, atts, content) {
            var attsString = '';
            for (var key in atts) {
                if (atts.hasOwnProperty(key) && atts[key] !== '') {
                    attsString += ' ' + key + '="' + atts[key] + '"';
                }
            }
            content = content || '';
            var shortcodeText = '[' + shortcode + attsString + ']';
            if (content) {
                shortcodeText += content + '[/' + shortcode + ']';
            } else if (shortcode === 'lcd_cols') { // Self-closing for lcd_col, content for lcd_cols
                 shortcodeText += 'Place your columns here[/' + shortcode + ']';
            } else {
                shortcodeText += 'Content[/' + shortcode + ']';
            }
            editor.insertContent(shortcodeText);
        }

        // Add button for lcd_cols
        editor.addButton('lcd_cols_button', {
            text: 'Cols',
            tooltip: 'Insert Column Container',
            icon: false,
            onclick: function() {
                editor.windowManager.open({
                    title: 'Insert Column Container ([lcd_cols])',
                    body: [
                        {type: 'textbox', name: 'class', label: 'CSS Class(es)'},
                        {type: 'textbox', name: 'id', label: 'CSS ID'}
                    ],
                    onsubmit: function(e) {
                        insertShortcode('lcd_cols', {
                            class: e.data.class,
                            id: e.data.id
                        });
                    }
                });
            }
        });

        // Add button for lcd_col
        editor.addButton('lcd_col_button', {
            text: 'Col',
            tooltip: 'Insert Column',
            icon: false,
            onclick: function() {
                editor.windowManager.open({
                    title: 'Insert Column ([lcd_col])',
                    body: [
                        {type: 'textbox', name: 'width', label: 'Width (e.g., 50%)', value: ''},
                        {type: 'textbox', name: 'class', label: 'CSS Class(es)'},
                        {type: 'textbox', name: 'id', label: 'CSS ID'}
                    ],
                    onsubmit: function(e) {
                        insertShortcode('lcd_col', {
                            width: e.data.width,
                            class: e.data.class,
                            id: e.data.id
                        });
                    }
                });
            }
        });
    });
})(); 