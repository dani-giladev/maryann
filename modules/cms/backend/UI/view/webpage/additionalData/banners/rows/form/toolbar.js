Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.banners.rows.form.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.cms_webpage_additionaldata_banners_rows_form_toolbar',

    region: 'south',
                
    border: true,
    frame: false,
    
//    ui: 'footer',
    
    config: null,
    
    initComponent: function() {
        
        var me = this;
        
        this.title = '';
        
        this.items = 
        [     
            {xtype: 'tbfill'},
            {
                text: me.trans('accept'),
                handler: me.accept
            },      
            {
                text: me.trans('cancel'),
                handler: me.cancel
            }            
        ];
            
        this.callParent(arguments);
    },
            
    accept: function(button, eventObject)
    {
        var me = this;
        var window = button.up('window');
        var grid = Ext.ComponentQuery.query('#cms_webpage_additionaldata_banners_rows_grid')[0];
        var store = grid.getStore();
        var form = button.up('window').down('form');
        
        if (!form.getForm().isValid())
        {
            return;
        }
        
        // Get values
        var form_values = form.getValues();
        var available = (form_values.available === 'on');
        var width = form_values.width;
        var height = form_values.height;
        // Margin
        var marginTop = form_values.marginTop;
        var marginRight = form_values.marginRight;
        var marginBottom = form_values.marginBottom;
        var marginLeft = form_values.marginLeft;
        // Padding
        var paddingTop = form_values.paddingTop;
        var paddingRight = form_values.paddingRight;
        var paddingBottom = form_values.paddingBottom;
        var paddingLeft = form_values.paddingLeft;
        
        // Columns
        var columns_grid = Ext.ComponentQuery.query('#cms_webpage_additionaldata_banners_columns_grid')[0];
        var columns_data_grid = columns_grid.getStore().getRange();
        var columns = [];
        Ext.each(columns_data_grid, function(rec)
        { 
            columns.push(rec.data);
        });
        //console.log(columns);
            
        // Set values on store
        if (form.is_new_record)
        {
            var new_record = 
            {
                available: available,
                width: width,
                height: height,
                // Margin
                marginTop: marginTop,
                marginRight: marginRight,
                marginBottom: marginBottom,
                marginLeft: marginLeft,
                // Padding
                paddingTop: paddingTop,
                paddingRight: paddingRight,
                paddingBottom: paddingBottom,
                paddingLeft: paddingLeft,
                
                // Columns
                columns: columns
            };

            //var count_rows = store.getCount();
            //store.insert(count_rows, new_record);
            store.add(new_record);
        }
        else
        {
            //var record = store.findRecord('code', code);
            var index = store.indexOf(form.current_record);
            var record = store.getAt(index);
            
            record.set('available', available);
            record.set('width', width);
            record.set('height', height);
            // Margin
            record.set('marginTop', marginTop);
            record.set('marginRight', marginRight);    
            record.set('marginBottom', marginBottom); 
            record.set('marginLeft', marginLeft); 
            // Padding
            record.set('paddingTop', paddingTop);
            record.set('paddingRight', paddingRight);    
            record.set('paddingBottom', paddingBottom); 
            record.set('paddingLeft', paddingLeft);
            
            // Columns
            record.set('columns', columns);
        }

        window.close();
    },
            
    cancel: function(button, eventObject)
    {
        var window = button.up('window');
        window.close();
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});