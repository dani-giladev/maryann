Ext.define('App.modules.ecommerce.backend.UI.view.article.additionalData.google', {
    
    alias: 'widget.ecommerce_article_additionaldata_google',
    explotation: 'Google data',
    config: null,
    
    getForm: function(config, record)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: 'Google',
            width: 400,
            height: 200,
            fields:
            [
                me.getGoogleShoppingFieldset()
            ]        
        };
        
        return ret;
    },
    
    getGoogleShoppingFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Google shopping',
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'textfield',
                    name: 'gtin',
                    maskRe: /[0-9]/,
                    fieldLabel: me.trans('code') + ' GTIN',
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
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }


});