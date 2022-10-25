Ext.define('App.modules.ecommerce.backend.UI.view.sales.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.ecommerce_sales_gridtoolbar',
    itemId: 'ecommerce_sales_gridtoolbar',
        
    explotation: 'Sales toolbar view for E-commerce module',

    region: 'north',
                
    border: true,
    frame: false,
    
//    ui: 'footer',
    
    config: null,
    
    initComponent: function() {
        
        var me = this;
        
        this.title = '';
        
        this.items = 
        [  
            {
                itemId: 'refresh_button_grid',
                text: me.trans('refresh'),
                handler: me.refreshGrid
            },          
            {
                text: me.trans('cancel'),
                disabled: !me.config.permissions.delete,
                handler: me.cancelRecord
            },          
            {
                text: me.trans('delete'),
                disabled: !is_super_user,
                handler: me.deleteRecord
            }
        ];
            
        this.callParent(arguments);
    },
            
    refreshGrid: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.getViewController().refreshGrid(me.config);
    },
            
    cancelRecord: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.getViewController().cancelRecord(me.config);
    },
            
    deleteRecord: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.getViewController().deleteRecord(me.config);
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
    }
});