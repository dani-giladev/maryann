Ext.define('App.modules.ecommerce.backend.UI.view.article.properties.window', {
    extend: 'Ext.window.Window',
    
    alias: 'widget.ecommerce_article_properties_window',
        
    modal: true,
    closable: true,
    resizable: false,    
    header: true,
    frame: false,
    border: true,
    
    is_new_record: true,
    current_record: null,
    
    initComponent: function() 
    {
        var me = this;
        
        var size = me.getViewController().getSize();
        
        this.title = me.trans('article_property_edition_form');
        this.width = 500;
        this.height = 300;
        this.maxHeight  = size.height - 20;
            
        var form = Ext.widget('ecommerce_article_properties_form', {
            is_new_record: me.is_new_record,
            current_record: me.current_record
        });
                
        if (!me.is_new_record)
        {
            form.getForm().loadRecord(me.current_record);
        }
            
        this.items = 
        [ 
            form
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
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.article');       
        return controller;
    }

});