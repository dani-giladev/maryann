Ext.define('App.modules.cms.backend.UI.view.website.website', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.cms_website',
        
    explotation: 'CMS website view',

    config: null,
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        // The grid
        this.initGrid();
        // The form
        this.initForm();

        this.callParent(arguments);
        
        this.addAdditionalToolbarButtons();
        this.addOthersFeatures(); 
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\cms\\backend\\controller\\website';
        
        this.config.model.filters = 
        [
            {field: 'delegation', value: 'authorized_delegations'}
        ];  
    },
            
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: me.trans('website_view'),
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    align: 'left',
                    width: 120
                },
                {
                    text: me.trans('name'),
                    dataIndex: 'name',
                    align: 'left',
                    minWidth: 180,
                    flex: 1
                },
                {
                    text: me.trans('delegation'),
                    dataIndex: 'delegationName',
                    _renderer: 'bold',
                    align: 'left',
                    minWidth: 180,
                    flex: 1
                },
                /*{
                    text: me.trans('home_page'),
                    dataIndex: 'homePageName',
                    align: 'left',
                    minWidth: 100,
                    flex: 1
                },*/
                {
                    text: me.trans('available'),
                    dataIndex: 'available',
                    align: 'center',
                    width: 90
                }
            ]
        };
    },
            
    initForm: function()
    {
        var me = this;
        
        me.config.form =
        {
            title: me.trans('website_form'),
            fields:
            [
                me.getMainFieldset(),
                me.getPropertiesFieldset()
            ]
        };
    },
    
    getMainFieldset: function()
    {
        var me = this;
        
        var delegation_config = 
        {
            module_id: 'admin',
            model: 
            {
                id: 'delegation',
                fields: ['code', 'name'],
                filters: [
                    {field: 'available', value: true},
                    {field: 'code', value: 'authorized_delegations'}   
                ]
            },
            get_concrete_fields: ['code', 'name']
        };
        
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
                    _setFocusOnNew: true
                },                     
                {
                    xtype: 'combo',
                    name: 'delegation',
                    fieldLabel: '<b>' + me.trans('delegation') + '</b>',
                    /*_store: {
                        module_id: 'admin',
                        model_id: 'delegation',
                        fields: ['code', 'name'],
                        filters: [
                            {field: 'available', value: true},
                            {field: 'code', value: 'authorized_delegations'}                                
                        ]                                
                    },*/
                    store: me.getMaintenanceController().getGetRecordsStore(delegation_config, true, false, 'true'),
                    _addSubmitValues: [
                        {field: 'name', as: 'delegationName'}
                    ],
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: false,
                    //bug//emptyText: me.trans('select_delegation'),
                    allowBlank: false,
                    labelAlign: 'right',
                    _disabledOnEdit: true,
                    anchor: '100%'
                }                
            ]
        };
        
        return ret;
    },
    
    getPropertiesFieldset: function()
    {
        var me = this;
        
        var lang_config = 
        {
            module_id: 'admin',
            model: 
            {
                id: 'language',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}, 
                    {name: 'order'}                    
                ],
                filters: [
                    {field: 'available', value: true},
                    {field: 'code', value: 'authorized_delegations'}   
                ],
                sorters: [{
                    property: 'order',
                    direction: 'ASC'
                }]                
            },
            get_concrete_fields: ['code', 'name', 'order']
        };
        
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
                    fieldLabel: me.trans('name'),
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%'
                },
                {
                    xtype: 'textfield',
                    name: 'description',
                    fieldLabel: me.trans('description'),
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                },
                {
                    xtype: 'checkboxfield',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    anchor: '100%',
                    _defaultValue: true // checked when new record
                },
                {
                    xtype: 'combo',
                    name: 'websiteType',
                    fieldLabel: me.trans('type'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "website", "name": "CMS Website"},
                            {"code": "ecommerce", "name": "e-Commerce"},
                            {"code": "bookingengine", "name": "Booking engine"}
                        ]
                    }),
                    _addSubmitValues: [
                        {field: 'name', as: 'websiteTypeName'}
                    ],
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: false,
                    //bug//emptyText: me.trans('select_type'),
                    allowBlank: false,
                    labelAlign: 'right',
                    _disabledOnEdit: true,
                    anchor: '100%'
                },
                {
                    xtype: 'textfield',
                    name: 'domain',
                    fieldLabel: me.trans('domain'),
                    maskRe: /[a-zA-Z0-9\-\_\.]/,
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'                    
                },
                {
                    xtype: 'multiselect',
                    name: 'languages',
                    fieldLabel: me.trans('languages'),
                    store: me.getMaintenanceController().getGetRecordsStore(lang_config, false, false, 'true'),
                    valueField: 'code',
                    displayField: 'name',
                    delimiter: '|',
                    queryMode: 'local',
                    allowBlank: false,
                    msgTarget: 'side',
                    minHeight: 50,
                    width: 250,
                    labelAlign: 'right',
                    autoScroll: true,
                    listeners: {
                        render: function(field, eOpts)
                        {
                            var filters = [
                                {field: 'available', value: true}
                            ];
                            var json_filters = Ext.JSON.encode(filters);  
                            field.store.load({
                                params:{
                                    module_id: 'admin',
                                    model_id: 'language',
                                    stale: 'true',                                            
                                    filters: json_filters
                                }
                            });                                      
                        }   
                    }                           
                }                          
            ]
        };     
        
        return ret;
    },
    
    addAdditionalToolbarButtons: function()
    {
        var me = this;
        var toolbar = me.getMaintenanceController().getGridToolBar(me.config);
        toolbar.add(
            { xtype: 'tbfill' },
            {
                text: 'Reset frontend data',
                iconCls: "x-fa fa-fire",
                handler: function(button, eventObject)
                {
                    var grid = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getGrid(me.config);
                    var selected = grid.getSelectionModel().getSelection(); 
                    if (!selected[0])
                    {
                        Ext.MessageBox.show({
                           title: 'Reset frontend data',
                           msg: me.trans('select_register_previously'),
                           buttons: Ext.MessageBox.OK,
                           icon: Ext.MessageBox.INFO
                        });     
                        return;
                    }
                    
                    // The ajax params
                    var params = {
                        controller: me.config.save_controller, 
                        method: 'resetFrontendData',
                        websiteType: selected[0].get('websiteType')
                    };   

                    Ext.getBody().mask("Refreshing the selected website...");

                    Ext.Ajax.request(
                    {
                        type: 'ajax',
                        url : 'index.php',
                        method: 'GET',
                        params: params,
                        success: function(response, opts)
                        {
                            Ext.getBody().unmask();
                            var obj = Ext.JSON.decode(response.responseText);
                            if (!obj.success)
                            {
                                Ext.MessageBox.show({
                                   title: 'Error refreshing the selected website',
                                   msg: obj.data.result,
                                   buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.ERROR
                                });
                            }                

                        },
                        failure: function(form, data)
                        {
                            Ext.getBody().unmask();
                            var obj = Ext.JSON.decode(data.response.responseText);
                            Ext.MessageBox.show({
                               title: 'Error refreshing the selected website',
                               msg: obj.data.result,
                               buttons: Ext.MessageBox.OK,
                               icon: Ext.MessageBox.ERROR
                            });
                        }                
                    });                      


                }
            },
            Ext.widget('cms_website_additionaldata', {
                config: me.config
            })                
        );        
    },
    
    addOthersFeatures: function()
    {
        
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.cms.backend.UI.controller.cms').alertInitMaintenance(this.config);              
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
});