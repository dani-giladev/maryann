Ext.define('App.modules.marketing.backend.UI.view.voucher.voucher', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.marketing_voucher',
        
    explotation: 'Marketing voucher view',

    config: null,
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        
        // The grid
        this.initGrid();
        // The form
        this.initForm();
        // The dynamic filter form
        this.initDynamicFilterForm();

        this.callParent(arguments);
        
        this.addOthersFeatures();  
        
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        form.on('newRecord', this.onNewRecord);
        form.on('editedRecord', this.onEditedRecord); 
    },
    
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: me.trans('vouchers'),
            flex: 1,
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    align: 'left',
                    width: 100, 
                    filter: {type: 'string'}
                },
                {
                    text: me.trans('internal_name'),
                    dataIndex: 'name',
                    align: 'left',
                    width: 150, 
                    filter: {type: 'string'}
                },
                {
                    text: me.trans('available'),
                    dataIndex: 'available',
                    width: 90, 
                    filter: {type: 'boolean'}
                }
            ]
        };
    },
            
    initForm: function()
    {
        var me = this;
        me.config.form =
        {
            fields:
            [
                me.getMainFieldset(),
                me.getPropertiesFieldset(),
                me.getAvailabilityFieldset(),
                me.getMessagesFieldset()
            ]
        };
    },
    
    getMainFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('main'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'textfield',
                    name: 'code',
                    fieldLabel: '<b>' + me.trans('code') + '</b>',
                    maskRe: /[a-zA-Z0-9\-\_]/,
                    allowBlank: false,
                    labelAlign: 'right',
                    _disabledOnEdit: true,
                    _setFocusOnNew: true,
                    listeners: {
                        change: function (obj, newValue) {
                            if (!newValue) return;
                            obj.setRawValue(newValue.toUpperCase());
                        }
                    }
                }
            ]
        };
        
        return ret;
    },
    
    getPropertiesFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('properties'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'textfield',
                    name: 'name',
                    fieldLabel: me.trans('internal_name'),
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%'
                },
                {
                    xtype: 'combo',
                    name: 'voucherType',
                    fieldLabel: me.trans('type'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "sumup-in-total", "name": me.trans('sumup-in-total')},
                            {"code": "free-shippingcost", "name": me.trans('free-shippingcost')},
                            {"code": "percentage-over-total", "name": me.trans('percentage-over-total')}
                        ]
                    }),
                    _addSubmitValues: [
                        {field: 'name', as: 'voucherTypeName'}
                    ],
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: false,
                    //bug//emptyText: me.trans('select_type'),
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%'
                },
                {
                    xtype: 'numberfield',
                    name: 'value',
                    fieldLabel: me.trans('value'),
                    allowBlank: true,
                    labelAlign: 'right',
                    //decimalPrecision: 2,
                    decimalSeparator: app_decimal_separator,
                    width: 200/*,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false*/
                }                
            ]
        };
        
        return ret;
    },
    
    getAvailabilityFieldset: function()
    {
        var me = this;
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('availability'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'checkboxfield',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    _defaultValue: true // checked when new record
                },
                {
                    xtype : 'fieldcontainer',
                    layout: 'hbox',
                    fieldLabel: '',
                    combineErrors: false,
                    items: 
                    [
                        {
                            xtype: 'datefield',
                            name: 'startDate',
                            format: app_dateformat,
                            submitFormat: app_dateformat_database,
                            fieldLabel: me.trans('start_date'),
                            labelAlign: 'right',
                            allowBlank: true,
                            width: 240,
                            fieldStyle: {
                                'text-align': 'center'
                            },
                            listeners: 
                            {
                                change: function(thisDateField, newValue, oldValue, options)
                                {
                                    var form = me.getMaintenanceController().getForm(me.config);
                                    var end_date = form.getForm().findField('endDate');
                                    if( (end_date.getEl()) && end_date.getValue() < newValue)
                                    {
                                        end_date.reset();
                                        end_date.setMinValue(newValue);
                                        end_date.focus();
                                        end_date.getEl().highlight();
                                    }
                                }
                          }
                        },
                        {
                            xtype: 'datefield',
                            name: 'endDate',
                            format: app_dateformat,
                            submitFormat: app_dateformat_database,
                            fieldLabel: me.trans('end_date'),
                            labelAlign: 'right',
                            allowBlank: true,
                            width: 240,
                            fieldStyle: {
                                'text-align': 'center'
                            }
                        }
                    ]
                }
            ]
        };     
        
        return ret;
    },
    
    getMessagesFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_voucher_tabpanel_message'; 
        
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('message'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'marketing_voucher_message_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'marketing_voucher_tabpanel_message'
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };
        
        return ret;
    },

    initDynamicFilterForm: function()
    {
        var me = this;
        
        me.config.dynamicFilterForm =
        {
            fields:
            [
                {
                    xtype: 'textfield',
                    name: 'code',
                    fieldLabel: me.trans('code'),
                    maskRe: /[a-zA-Z0-9\-\_]/,
                    _filtertype: 'string'                    
                },
                {
                    xtype: 'textfield',
                    name: 'name',
                    fieldLabel: me.trans('internal_name'),
                    anchor: '100%',
                    _filtertype: 'string' 
                },                
                {
                    xtype: 'combo',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_female')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    _filtertype: 'boolean'/*,
                    _default_value: 'yes'*/
                }
            ]
        };
    },
    
    addOthersFeatures: function()
    {
        
    },
    
    onRender: function(form, eOpts)
    {
        var me = this;
        
        me.createTabsContent();                  
        
        this.callParent(arguments);
    },
    
    createTabsContent: function()
    {
        var me = this;
        me.createTabContent('message');
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = me.down('#marketing_voucher_tabpanel_' + type);
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#marketing_voucher_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            i = 0;
            Ext.each(langs, function(lang) {
                lang_code = lang.code;
                lang_name = lang.name;
                
                var name;
                if (type === 'message')
                {
                    name = 'messages';
                }
                else
                {
                    name = 'keywords';
                }
                tab.add({
                    xtype: 'htmleditor',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _name: name,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%',
                    height: 200,
                    autoScroll: true,
                    enableFont: false
                }); 
                
                i++;
            });                 

            tab.setActiveTab(0);                                            
        }         
    },
            
    onNewRecord: function()
    {
        var view = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getMaintenanceView(this.config);
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        var tab;
        
        // Clean message tab
        tab = view.down('#marketing_voucher_tabpanel_message');
        Ext.each(tab.items.items, function(item) {
            item.setValue('');
        }); 
        
        // Finally.. clear form
        //extjs6 form.clearDirty();
    },
            
    onEditedRecord: function(id)
    {
        var maintenance_controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');
        var view = maintenance_controller.getMaintenanceView(this.config);
        var form = maintenance_controller.getForm(this.config);
        var record = maintenance_controller.getCurrentRecord(this.config);
        var tab;
        
        // Set message tab
        tab = view.down('#marketing_voucher_tabpanel_message');
        Ext.each(tab.items.items, function(item) {
            var value = '';
            if (!Ext.isEmpty(record.data[item._name]) &&
                !Ext.isEmpty(record.data[item._name][item._lang_code]))
            {
                value = record.data[item._name][item._lang_code];
            }
            item.setValue(value);
        });
       
        // Finally.. clear form
        //extjs6 form.clearDirty();
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.marketing.backend.UI.controller.marketing').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.marketing.backend.UI.controller.marketing').alertInitMaintenance(this.config);              
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
});