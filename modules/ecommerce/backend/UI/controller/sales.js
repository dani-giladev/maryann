Ext.define('App.modules.ecommerce.backend.UI.controller.sales', {
    extend: 'App.modules.ecommerce.backend.UI.controller.ecommerce',

    views: [
        'App.modules.ecommerce.backend.UI.view.sales.sales',
        'App.modules.ecommerce.backend.UI.view.sales.resultsGrid',
        'App.modules.ecommerce.backend.UI.view.sales.filterForm',
        'App.modules.ecommerce.backend.UI.view.sales.toolbar'
    ],
    
    models: [

    ],
    
    stores: [

    ],

    refs: [
        {ref: 'startDate', selector: 'datefield[name=startDate]'},
        {ref: 'endDate', selector: 'datefield[name=endDate]'}
    ],
    
    init: function() 
    {
        this.control({

        }); 
    },
            
    getResultsGrid: function(config)
    {
        // Find grid by itemId
        var itemId = config.alias + '_resultsgrid';
        var grid = Ext.ComponentQuery.query('#' + itemId)[0];
        return grid;
    },
            
    getFilterForm: function(config)
    {
        // Find form by itemId
        var itemId = config.alias + '_filterform';
        var form = Ext.ComponentQuery.query('#' + itemId)[0];
        return form;
    },
    
    refreshGrid: function(config)
    {
        var me = this; 
        var form = me.getFilterForm(config);
        var formValues = form.getValues();
        var grid = me.getResultsGrid(config);
        var store = grid.getStore();
        var params;
        
        // Check if the form values are valids
        if(!form.getForm().isValid())
        {
            return;
        }
        
        // The ajax params
        params = {
            module_id: config.module_id,
            model_id: config.model.id,
            startDate: me.getStartDate().getValue(),
            endDate: me.getEndDate().getValue(),
            start: 0,
            limit: 9999,
            stale: false
        };
            
        // Add data form values to params
        for (var key in formValues) {
            params[key] = formValues[key];
        }
        
        // Add filters to params
        var filters = [
            {field: 'isFake', value: false}
        ];
        var json_filters = Ext.JSON.encode(filters);
        params['filters'] = json_filters;
            
        // Load!
        store.load({
            params: params
        });
            
    },
    
    cancelRecord: function(config)
    {
        var me = this;
        var record_id, params;
        var form = me.getFilterForm(config);
        var formValues = form.getValues();
        var grid = this.getResultsGrid(config);
        var cancel_controller = 'modules\\ecommerce\\backend\\controller\\sales';
        
        var selection = grid.getSelectionModel().getSelection();
        if (Ext.isEmpty(selection[0]))
        {
            Ext.MessageBox.show({
               title: me.trans('cancel_record'),
               msg: me.trans('select_register_previously'),
               buttons: Ext.MessageBox.OK,
               icon: Ext.MessageBox.WARNING
            });
            return false;
        }
        
        // Check if the form values are valids
        if(!form.getForm().isValid())
        {
            return;
        }
        
        Ext.MessageBox.prompt(
            me.trans('cancel_record'),
            me.trans('are_you_sure_to_cancel') + '</br></br>' + me.trans('enter_cancellation_reason') + ':',
            function(btn, text)
            {
                if(btn === 'ok')
                {
                    if (Ext.isEmpty(text))
                    {
                        Ext.MessageBox.show({
                           title: me.trans('cancel_record'),
                           msg: me.trans('cancellation_reason_is_required'),
                           buttons: Ext.MessageBox.OK,
                           icon: Ext.MessageBox.ERROR
                        });
                        return false;
                    }                    
                    
                    record_id = selection[0].data.id;

                    // Check for overrided cencelcontroller
                    if(config.cancel_controller)
                    {
                        cancel_controller = config.cancel_controller;
                    }
        
                    // The ajax params
                    params = {
                        controller: cancel_controller, 
                        method: 'cancelRecord',
                        module_id: config.module_id,
                        model_id: config.model.id,
                        record_id: record_id,
                        cancellation_reason: text
                    };
            
                    // Add data form values to params
                    for (var key in formValues) {
                        params[key] = formValues[key];
                    }        

                    Ext.Ajax.request(
                    {
                        type: 'ajax',
                        url : 'index.php',
                        method: 'GET',
                        params: params,
                        waitMsg : me.trans('cancelling_record'),
                        success: function(response, opts)
                        {
                            var obj = Ext.JSON.decode(response.responseText);
                            if(obj.success)
                            {
                                // It also works!!  
                                var store = grid.getStore();                      
                                // It also works!!
//                                var store = config.store;  
                                store.reload();
                            }
                            else
                            {
                                Ext.MessageBox.show({
                                   title: me.trans('cancelling_record_failed'),
                                   msg: obj.data.result,
                                   buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.ERROR
                                });
                            }
                        },
                        failure: function(form, data)
                        {
                            var obj = Ext.JSON.decode(data.response.responseText);
                            Ext.MessageBox.show({
                               title: me.trans('cancelling_record_failed'),
                               msg: obj.data.result,
                               buttons: Ext.MessageBox.OK,
                               icon: Ext.MessageBox.ERROR
                            });
                        }
                    });
                }
            }
        );
        
        return true;
    },
            
    deleteRecord: function(config)
    {
        var me = this;
        var record_id, params;
        var grid = this.getResultsGrid(config);
        var delete_controller = 'modules\\ecommerce\\backend\\controller\\sales';
        
        var selection = grid.getSelectionModel().getSelection();
        if (Ext.isEmpty(selection[0]))
        {
            Ext.MessageBox.show({
               title: me.trans('delete_record'),
               msg: me.trans('select_register_previously'),
               buttons: Ext.MessageBox.OK,
               icon: Ext.MessageBox.WARNING
            });
            return false;
        }
            
        Ext.MessageBox.show({
            title: me.trans('delete_record'),
            msg: me.trans('are_you_sure_to_delete'),
            buttons: Ext.MessageBox.YESNO,
            icon: Ext.MessageBox.QUESTION,
            fn: function(btn, text)
            {
                if(btn === 'yes')
                {    
                    
                    record_id = selection[0].data.id;

                    // Check for overrided cencelcontroller
                    if(config.delete_controller)
                    {
                        delete_controller = config.cancel_controller;
                    }
        
                    // The ajax params
                    params = {
                        controller: delete_controller, 
                        method: 'deleteRecord',
                        module_id: config.module_id,
                        model_id: config.model.id,
                        record_id: record_id
                    };     

                    Ext.Ajax.request(
                    {
                        type: 'ajax',
                        url : 'index.php',
                        method: 'GET',
                        params: params,
                        waitMsg : me.trans('deleting_record'),
                        success: function(response, opts)
                        {
                            var obj = Ext.JSON.decode(response.responseText);
                            if(obj.success)
                            {
                                // It also works!!  
                                var store = grid.getStore();                      
                                // It also works!!
//                                var store = config.store;  
                                store.reload();
                            }
                            else
                            {
                                Ext.MessageBox.show({
                                   title: me.trans('deleting_record_failed'),
                                   msg: obj.data.result,
                                   buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.ERROR
                                });
                            }
                        },
                        failure: function(form, data)
                        {
                            var obj = Ext.JSON.decode(data.response.responseText);
                            Ext.MessageBox.show({
                               title: me.trans('deleting_record_failed'),
                               msg: obj.data.result,
                               buttons: Ext.MessageBox.OK,
                               icon: Ext.MessageBox.ERROR
                            });
                        }
                    });
                }
            }
        });
        
        return true;
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }
});