Ext.define('App.modules.reporting.backend.UI.controller.botplusMedicines', {
    extend: 'App.core.backend.UI.controller.maintenance.type1',

    views: [
        'App.modules.reporting.backend.UI.view.botplusMedicines.botplusMedicines',
        'App.modules.reporting.backend.UI.view.botplusMedicines.gridPanel',
        'App.modules.reporting.backend.UI.view.botplusMedicines.gridToolBar',
        'App.modules.reporting.backend.UI.view.botplusMedicines.filterForm',
        'App.modules.reporting.backend.UI.view.botplusMedicines.dynamicFilterForm'
    ],
    
    models: [

    ],
    
    stores: [

    ],

    refs: [
        
    ],
    
    module_id: 'reporting',
    
    refreshGrid: function(config)
    {
        var me = this; 
        var filterform = Ext.ComponentQuery.query('#reporting_botplus_medicines_filterform')[0];
        var formValues = filterform.getValues();
        var grid = me.getGrid(config);
        var store = grid.getStore(); 
        var params;
        
        // Check if the form values are valids
        if(!filterform.getForm().isValid())
        {
            return;
        }
        
        // The ajax params
        params = {
            module_id: config.module_id,
            model_id: config.model.id,
            start: 0,
            limit: 9999,
            stale: false
        };
            
        // Add data form values to params
        for (var key in formValues) {
            params[key] = formValues[key];
        }
        
        var scroll_position = grid.getEl().down('.x-grid-view').getScroll();

        store.on('load', function(this_store, records, successful, eOpts)
        {
            if (records.length === 1 && !records[0].raw['success'] && records[0].raw['msg'] && records[0].raw['msg'].length > 0)
            {
                Ext.MessageBox.show({
                   title: 'Error',
                   msg: records[0].raw['msg'],
                   buttons: Ext.MessageBox.OK,
                   icon: Ext.MessageBox.ERROR
                });
                this_store.removeAll();
            }
            
            // Set scroll
            var task = new Ext.util.DelayedTask(function(){
                grid.getEl().down('.x-grid-view').scrollTo('top', scroll_position.top, false);
            });        
            task.delay(100);

        }, store, {single: true});
             
        store.load({
            params: params
        });
    },
    
    exportRecords: function(config)
    {
//        var grid = this.getGrid(config);
//        var store = grid.getStore();
        var filterform = Ext.ComponentQuery.query('#reporting_botplus_medicines_filterform')[0];
        var formValues = filterform.getValues();
            
        // Add data form values to params
        var params = '';
        for (var key in formValues) {
            params += '&' + key + '=' + formValues[key];
        }
        
        var url = '/index.php?' + 
                   'controller=modules\\reporting\\backend\\controller\\botplusMedicines' + 
                   '&method=exportRecords' + 
                   params;
           
        //console.log(url);

        window.open(url);     
    },
        
    getDynamicFilterController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1DynamicFilterForm');
        return controller;
    }
    
});