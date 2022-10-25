Ext.define('App.modules.marketing.backend.UI.view.articleGroup.brands.window', {
    extend: 'Ext.window.Window',
    
    alias: 'widget.marketing_articleGroup_brands_window',
        
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
        
        this.title = me.trans('select_a_brand');
        this.width = 500;
        this.height = 220;
        this.maxHeight  = size.height - 20;
            
        var form = Ext.widget('marketing_articleGroup_brands_form', {
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
            Ext.widget('marketing_articleGroup_brands_toolbar')  
        ];

        this.callParent(arguments);
    },     
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.marketing.backend.UI.controller.marketing').getLangStore();
        return App.app.trans(id, lang_store);
    },
    
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.article');       
        return controller;
    }

});