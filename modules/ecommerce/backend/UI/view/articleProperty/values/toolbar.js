Ext.define('App.modules.ecommerce.backend.UI.view.articleProperty.values.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.ecommerce_article_property_values_toolbar',

    region: 'south',
                
    border: true,
    frame: false,
    
//    ui: 'footer',
    
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
        var window = button.up('window');
        var grid = Ext.ComponentQuery.query('#ecommerce_article_property_values_grid')[0];
        var store = grid.getStore();
        var form = button.up('window').down('form');
        
        if (!form.getForm().isValid())
        {
            return;
        }
        
        // Get values
        var form_values = form.getValues();
        var code = form_values.code;
        var name = form_values.name;
        var available = (form_values.available === 'on');
        var amount = form_values.amount;
        var texts = {};
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        Ext.each(langs, function(lang) {
            texts[lang.code] = form_values['texts-' + lang.code];
        }); 
            
        // Set values on store
        if (form.is_new_record)
        {
            var new_record = 
            {
                code : code,
                name : name,
                available : available,
                amount : amount,
                texts : texts
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
            record.set('name', name);      
            record.set('available', available);             
            record.set('amount', amount);      
            record.set('texts', texts);      
        }
        
        store.sort('name', 'ASC');
        grid.getView().refresh();

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