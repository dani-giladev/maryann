Ext.define('App.modules.marketing.backend.UI.view.articleGroup.articleTypes.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.marketing_articleGroup_articleTypes_toolbar',

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
        var grid = Ext.ComponentQuery.query('#marketing_articleGroup_articleTypes_grid')[0];
        var store = grid.getStore();
        var form = button.up('window').down('form');
        
        if (!form.getForm().isValid())
        {
            return;
        }
        
        // Get values
        var form_values = form.getValues();
        var code = form_values.code;
        
        var article_field = form.getForm().findField('code');
        var article_type_record = article_field.findRecord(article_field.valueField, article_field.value);
        
        var name = article_type_record.data.name;
        
        // Set values on store
        if (form.is_new_record)
        {
            var new_record = 
            {
                code : code,
                name : name
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