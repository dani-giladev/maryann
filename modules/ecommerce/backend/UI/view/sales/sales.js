Ext.define('App.modules.ecommerce.backend.UI.view.sales.sales', {
    extend: 'Ext.panel.Panel',
    
    alias: 'widget.ecommerce_sales',
        
    explotation: 'Sales main view for E-commerce module',

    layout: 'border',
    
    border: false,
    frame: false,
    title: '',

    config: null,

    initComponent: function() {
        this.alert();
        
        var me = this;
        
        // General properties
        me.initGeneralProperties();
        
        // Create the getRecords store (main store of maintenance)
        me.config.store = me.getMaintenanceController().getGetRecordsStore(me.config, false, false, 'update_after'); 
        
        me.items = 
        [
            {
                xtype: 'panel',
                //title: me.trans('sales_view'),
                title: me.config.breadscrumb,
                region: 'center',                
                layout: 'border',
                border: false,
                frame: false,
                items: 
                [
                    Ext.widget('ecommerce_sales_gridtoolbar', {
                        config: me.config
                    }),
                    Ext.widget('ecommerce_sales_resultsgrid', {
                        config: me.config
                    })                 
                ]
            },        
            Ext.widget('ecommerce_sales_filterform', {
                config: me.config
            })
        ];

        this.callParent(arguments);        
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;       
        this.config.enable_deletion = true;
        this.config.get_controller = 'modules\\ecommerce\\backend\\controller\\sales';        
        this.config.cancel_controller = 'modules\\ecommerce\\backend\\controller\\sales';            
        this.config.delete_controller = 'modules\\ecommerce\\backend\\controller\\sales';  
        this.config.alias = 'ecommerce_sales';          
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.sales');       
        return controller;
    },
            
    alert: function()
    {
        App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').alertInitMaintenance(this.config);              
    },
        
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
});