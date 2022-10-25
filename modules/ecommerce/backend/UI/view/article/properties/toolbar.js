Ext.define('App.modules.ecommerce.backend.UI.view.article.properties.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.ecommerce_article_properties_toolbar',

    border: false,
    frame: false,
    dock: 'bottom',
    
    initComponent: function() {
        
        var me = this;
        
        this.title = '';
        
        this.items = 
        [     
            {xtype: 'tbfill'},
            {
                text: me.trans('accept'),
                _isSubmitButton: true,
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
        var window = button.up('window');
        var grid = Ext.ComponentQuery.query('#ecommerce_article_properties_grid')[0];
        var store = grid.getStore();
        var form = button.up('window').down('form');
        
        if (!form.getForm().isValid())
        {
            return;
        }
        
        // Get values
        var form_values = form.getValues();
        var code = form_values.code;
        var amount = form_values.amount;
        var value = form_values.value;
            
        // Set values on store
        if (form.is_new_record)
        {
            var new_record = 
            {
                code : code,
                amount : amount,
                value : value
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
            record.set('code', code);               
            record.set('amount', amount);    
            record.set('value', value);      
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
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});