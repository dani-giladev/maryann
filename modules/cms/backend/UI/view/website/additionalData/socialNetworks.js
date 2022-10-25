Ext.define('App.modules.cms.backend.UI.view.website.additionalData.socialNetworks', {
    
    alias: 'widget.cms_website_additionaldata_social_networks',
    explotation: 'Social networks for website (Additional data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: me.trans('social_networks'),
            width: 650,
            height: 320,
            fields:
            [
                me.getSocialNetworksFieldset()
            ]        
        };
        
        return ret;
    },
    
    getSocialNetworksFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('social_networks'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'textfield',
                    name: 'facebook',
                    fieldLabel: me.trans('facebook'),
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                },  
                {
                    xtype: 'textfield',
                    name: 'twitter',
                    fieldLabel: me.trans('twitter'),
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                },                          
                {
                    xtype: 'textfield',
                    name: 'googleplus',
                    fieldLabel: me.trans('googleplus'),
                    allowBlank: true,
                    labelAlign: 'right',
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