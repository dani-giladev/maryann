Ext.define('App.modules.reporting.backend.UI.view.botplusMedicines.gridToolBar', {
    extend: 'App.core.backend.UI.view.maintenance.type1.gridToolBar',
    
    alias: 'widget.reporting_botplus_medicines_gridtoolbar',
        
    refreshGrid: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.getViewController().refreshGrid(me.config);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.reporting.backend.UI.controller.botplusMedicines');       
        return controller;
    }
});