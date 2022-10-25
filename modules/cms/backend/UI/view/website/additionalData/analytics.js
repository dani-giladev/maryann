Ext.define('App.modules.cms.backend.UI.view.website.additionalData.analytics', {
    
    alias: 'widget.cms_website_additionaldata_analytics',
    explotation: 'Analytics for website (Additional data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: 'Analytics',
            width: 600,
            height: 430,
            fields:
            [
                me.getGoogleAnalyticsFieldset()
            ]        
        };
        
        return ret;
    },
    
    getGoogleAnalyticsFieldset: function()
    {
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Google Analytics',
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'textarea',
                    name: 'googleAnalytics',
                    fieldLabel: '',
                    allowBlank: true,
                    labelAlign: 'right',
                    height: 250,
                    anchor: '100%'
                }                     
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