Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.slider.form.window', {
    extend: 'Ext.window.Window',
    
    alias: 'widget.cms_webpage_additionaldata_slider_window',
        
    modal: true,
    closable: true,
    resizable: false,    
    header: true,
    frame: false,
    border: true,
    layout: 'border',
    
    config: null,
    is_new_record: true,
    current_record: null,
    
    initComponent: function() 
    {
        var me = this;
        
        var size = me.getMaintenanceController().getSize();
        
        this.title = me.config.title +' Edition';
        this.width = 700;
        this.height = 530;
        this.maxHeight  = size.height - 20;
            
        var form = Ext.widget('cms_webpage_additionaldata_slider_form', {
            config: me.config,
            is_new_record: me.is_new_record,
            current_record: me.current_record
        });
                
        this.items = 
        [ 
            form,
            Ext.widget('cms_webpage_additionaldata_slider_toolbar', {
                config: me.config
            })  
        ];

        this.callParent(arguments);
    },     
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    },
    
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }

});