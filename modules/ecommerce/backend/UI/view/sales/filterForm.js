Ext.define('App.modules.ecommerce.backend.UI.view.sales.filterForm', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.ecommerce_sales_filterform',
    itemId: 'ecommerce_sales_filterform',
    
    explotation: 'Sales grid view for E-commerce module',

    region: 'west',
    width: 280,
    height: '100%',
    split: true,
    collapsible: true,  
    border: false,
    frame: false,
    bodyPadding: 10,

    config: null,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = me.trans('filters'); 

        this.items = 
        [    
            me.getDatesFieldset(),    
            me.getDelegationFieldset()
        ];
        
        me.callParent(arguments);
        
        // Add listeners
        me.addListeners();        
    },
    
    addListeners: function()
    {   
        var me = this;
        
        // Add custom listeners
        me.getMaintenanceController().addListeners(me);
        // Update several properties
        me.getMaintenanceController().updateFormProperties(me);
        // set combos stores dinamically
        me.getMaintenanceController().setComboStores(me);            
    },
    
    getDatesFieldset: function()
    {   
        var me = this;
        var ret =    
        {        
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('dates'),
            anchor: '100%',
            items: 
            [
                me.getStartDateField(),    
                me.getEndDateField()       
            ]        
        };       
        
        return ret;
    },
    
    getStartDateField: function()
    {   
        var me = this;
        var ret =    
        {
            xtype: 'datefield',
            name: 'startDate',
            format: app_dateformat,
            submitFormat: app_dateformat_database,
            fieldLabel: me.trans('start_date'),
            labelAlign: 'right',
            allowBlank: true,
            editable: false,
            width: 220,
            labelWidth: 80,   
            fieldStyle: {
                'text-align': 'center'
            },
            value: new Date(),
            maxValue: new Date(),
            listeners: 
            {
                change: function(thisDateField, newValue, oldValue, options)
                {
                    var form = me.getViewController().getFilterForm(me.config);
                    var end_date = form.getForm().findField('endDate');
                    if( (end_date.getEl()) && end_date.getValue() < newValue)
                    {
                        end_date.setValue(newValue);
                    }
                }
            }
        };       
        
        return ret;
    },
    
    getEndDateField: function()
    {   
        var me = this;
        var ret =    
        {
            xtype: 'datefield',
            name: 'endDate',
            format: app_dateformat,
            submitFormat: app_dateformat_database,
            fieldLabel: me.trans('end_date'),
            labelAlign: 'right',
            allowBlank: true,
            editable: false,
            width: 220,
            labelWidth: 80,    
            fieldStyle: {
                'text-align': 'center'
            },
            value: new Date(),
            maxValue: new Date(),
            listeners: 
            {
                change: function(thisDateField, newValue, oldValue, options)
                {
                    var form = me.getViewController().getFilterForm(me.config);
                    var start_date = form.getForm().findField('startDate');
                    if( (start_date.getEl()) && start_date.getValue() > newValue)
                    {
                        start_date.setValue(newValue);
                    }
                }
            }
        };       
        
        return ret;
    },
    
    getDelegationFieldset: function()
    {   
        var me = this;
        var ret =    
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('delegation'),
            anchor: '100%',
            items: 
            [  
                {
                    xtype: 'combo',
                    name: 'delegation',
                    fieldLabel: '',
                    _store: {
                        module_id: 'admin',
                        model_id: 'delegation',
                        fields: ['code', 'name'],
                        filters: [
                            {field: 'available', value: true},
                            {field: 'code', value: 'authorized_delegations'}                                
                        ],
                        add_data: [
                            {code: '_all', name: me.trans('all_female')}
                        ]                                
                    },
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: false,
                    //bug//emptyText: me.trans('select_delegation'),
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                }                  
            ]
        };       
        
        return ret;
    },
    
    onRender: function(form, eOpts)
    {
        
        this.callParent(arguments);
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
        
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
    
});