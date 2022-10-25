Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.slider.slider', {
    
    alias: 'widget.cms_webpage_additionaldata_slider',
    explotation: 'Slider for webpage (Additional data)',
    
    config: null,
    
    getForm: function(config, record)
    {    
        var me = this;
        me.config = config;
        
        var ret =       
        {
            title: me.config.title,
            width: 1000,
            height: 650,
            fields:
            [
                me.getImagesFieldset(record.data.id)                
            ]           
        };
        
        return ret;
    },     
    
    getImagesFieldset: function(record_id)
    { 
        var me = this;
        
        var ret =              
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('images'), //me.config.title,
            anchor: '100%',
            items: 
            [    
                Ext.widget('cms_webpage_additionaldata_slider_grid', {
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