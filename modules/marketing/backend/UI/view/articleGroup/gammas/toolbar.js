Ext.define('App.modules.marketing.backend.UI.view.articleGroup.gammas.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.marketing_articleGroup_gammas_toolbar',

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
        var grid = Ext.ComponentQuery.query('#marketing_articleGroup_gammas_grid')[0];
        var store = grid.getStore();
        var form = button.up('window').down('form');
        
        if (!form.getForm().isValid())
        {
            return;
        }
        
        // Get values
        var form_values = form.getValues();
        var code = form_values.code;
        var brand = form_values.brand;
        
        var gamma_field = form.getForm().findField('code');
        var gamma_record = gamma_field.findRecord(gamma_field.valueField, gamma_field.value);
        
        var brand_field = form.getForm().findField('brand');
        var brand_record = brand_field.findRecord(brand_field.valueField, brand_field.value);
        
        var name = gamma_record.data.name;
        var brandName = brand_record.data.name;
        
        // Set values on store
        if (form.is_new_record)
        {
            var new_record = 
            {
                code : code,
                name : name,
                brand : brand,
                brandName : brandName
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
            record.set('name', name);
            record.set('brand', brand);
            record.set('brandName', brandName);
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
        var lang_store = App.app.getController('App.modules.marketing.backend.UI.controller.marketing').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});