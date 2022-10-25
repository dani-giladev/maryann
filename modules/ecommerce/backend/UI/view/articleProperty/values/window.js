Ext.define('App.modules.ecommerce.backend.UI.view.articleProperty.values.window', {
    extend: 'Ext.window.Window',
    
    alias: 'widget.ecommerce_article_property_values_window',
        
    modal: true,
    closable: true,
    resizable: false,    
    header: true,
    frame: false,
    border: true,
    layout: 'border',
    
    is_new_record: true,
    current_record: null,
    
    initComponent: function() 
    {
        var me = this;
        
        var size = me.getViewController().getSize();
        
        this.title = me.trans('property_value_edition_form');
        this.width = 450;
        this.height = 550;
        this.maxHeight  = size.height - 20;
            
        var form = Ext.widget('ecommerce_article_property_values_form', {
            is_new_record: me.is_new_record,
            current_record: me.current_record
        });
                
        if (!me.is_new_record)
        {
            form.getForm().loadRecord(me.current_record);
        }
            
        this.items = 
        [ 
            form,
            Ext.widget('ecommerce_article_property_values_toolbar')  
        ];

        this.callParent(arguments);
    },     
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
    
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce');       
        return controller;
    }

});