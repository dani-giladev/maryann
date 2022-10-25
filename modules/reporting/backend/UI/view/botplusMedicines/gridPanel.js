Ext.define('App.modules.reporting.backend.UI.view.botplusMedicines.gridPanel', {
    extend: 'Ext.panel.Panel',
    
    alias: 'widget.reporting_botplus_medicines_gridpanel',
        
    region: 'center',    
    
    layout: 'fit',   
    
    border: false,
    frame: false,
    flex: 2,
    
    config: null,
    
    initComponent: function() {
        
        var me = this;
        
        this.itemId = 'maintenance_type1_gridpanel' + '_' +
                        me.config.module_id + '_' +
                        me.config.model.id;
        
        this.title = me.config.grid.title;
        //this.title = me.config.grid.title.toUpperCase();
        
        this.items = 
        [
            {
                xtype: 'panel',
                layout: 'border',
                items:
                [
                    Ext.widget('maintenance_type1_grid', {
                        config: me.config
                    }),
                    Ext.widget('reporting_botplus_medicines_gridtoolbar', {
                        config: me.config
                    }) 
                ]
            }
        ];
            
        this.callParent(arguments);
    }
});