Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.banners.banners', {
    
    alias: 'widget.cms_webpage_additionaldata_banners',
    explotation: 'Banners for webpage (Additional data)',
    
    config: null,
    
    getForm: function(config, record)
    {    
        var me = this;
        me.config = config;
        
        var ret =       
        {
            title: me.config.title,
            width: 600,
            height: 800,
            fields:
            [
                me.getRowsFieldset(record.data.id)                
            ]           
        };
        
        return ret;
    },     
    
    getRowsFieldset: function(record_id)
    { 
        var me = this;
        
        var ret =              
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('rows'),
            anchor: '100%',
            items: 
            [    
                Ext.widget('cms_webpage_additionaldata_banners_rows_grid', {
                    config: me.config,
                    record_id: record_id
                })
            ]
        };
        
        return ret;  
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }

});