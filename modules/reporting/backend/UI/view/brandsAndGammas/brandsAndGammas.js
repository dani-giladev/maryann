Ext.define('App.modules.reporting.backend.UI.view.brandsAndGammas.brandsAndGammas', {
    extend: 'Ext.panel.Panel',
    
    alias: 'widget.reporting_brandsAndGammas',
        
    explotation: 'Brands and gammas view for Reporting module',
    
    border: false,
    frame: false,
    layout: 'fit',
    title: '',

    config: null,

    initComponent: function() {
        this.alert();
        
        var me = this;
        
        me.items = 
        [
            {
                xtype: 'panel',
                border: false,
                frame: false,
                title: me.config.breadscrumb,
                layout: 'border',
                items:
                [
                    Ext.widget('reporting_brands_and_gammas_toolbar', {
                        config: me.config
                    }),
                    {
                        xtype: 'panel',
                        itemId: 'reporting_brands_and_gammas_centerpanel',
                        region: 'center',
                        border: false,
                        frame: false,                
                        layout: 'fit',
                        autoScroll: true,
                        width: '100%',
                        height: '100%'
                    }             
                ]
            }
        ];

        this.callParent(arguments);    
    },
    
    onRender: function(view, eOpts)
    {
        var me = this;
        var toolbar = me.down('#reporting_brands_and_gammas_toolbar');
        
        toolbar.renderReport();                  
        
        this.callParent(arguments);
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.reporting.backend.UI.controller.reporting').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.reporting.backend.UI.controller.reporting');       
        return controller;
    },
            
    alert: function()
    {
        //App.app.getController('App.modules.reporting.backend.UI.controller.reporting').alertInitMaintenance(this.config);
    }
});