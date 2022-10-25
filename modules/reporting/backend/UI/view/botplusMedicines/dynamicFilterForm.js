Ext.define('App.modules.reporting.backend.UI.view.botplusMedicines.dynamicFilterForm', {
    extend: 'App.core.backend.UI.view.maintenance.type1.dynamicFilterForm',
    
    alias: 'widget.reporting_botplus_medicines_dynamicfilterform',
        
    split: false,
    collapsible: false,
    width: '100%',
    
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.reporting.backend.UI.controller.botplusMedicines');       
        return controller;
    }
});