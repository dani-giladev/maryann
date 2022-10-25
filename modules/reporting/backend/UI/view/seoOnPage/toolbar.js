Ext.define('App.modules.reporting.backend.UI.view.seoOnPage.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.reporting_seoonpage_toolbar',
    itemId: 'reporting_seoonpage_toolbar',
        
    region: 'north',
                
    border: true,
    frame: false,
    
//    ui: 'footer',
    
    config: {},
    
    initComponent: function() {
        
        var me = this;
        
        me.title = '';
        
        me.items = 
        [  
            {
                text: me.trans('refresh'),
                handler: me.refresh
            }
        ];
            
        me.callParent(arguments);
    },
            
    refresh: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.renderReport();
    },
            
    renderReport: function()
    {
        var me = this;
        var centerpanel = Ext.ComponentQuery.query('#reporting_seoonpage_centerpanel')[0];
        
        var url = 
                //location.protocol + "//" + window.location.hostname + 
                "/index.php" +
                "?controller=modules\\reporting\\backend\\controller\\seoOnPage" +
                "&method=renderReport" +
                "";
        
        centerpanel.removeAll();
        
        centerpanel.add({
            xtype: 'box',
            width: '100%',
            height: '100%',
            border: false,
            frame: false,
            autoEl: {
                tag: 'iframe',
                src: url
            },
            listeners: {
                load: {
                    element: 'el',
                    fn: function () {
                        this.parent().unmask();
                        //console.log('done');
                    }
                },
                render: function () {
                    this.up('panel').body.mask(me.trans('loading'));
                }
            }
        });
        
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
    }
});