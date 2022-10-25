Ext.define('App.modules.cms.backend.UI.view.webpage.webpage', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.cms_webpage',
    
    explotation: 'CMS webpage view',

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
        this.config.enable_clone = true;       
        this.config.enable_deletion = true;      
        
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
            title: me.trans('webpage_view'),
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
                {
                    text: me.trans('website'),
                    dataIndex: 'websiteName',
                    _renderer: 'bold',
                    align: 'left',
                    minWidth: 180,
                    flex: 1
                },
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
        
        var website_config = 
        {
            module_id: 'cms',
            model: 
            {
                id: 'website',
                fields: ['code', 'name'],
                filters: [
                    {field: 'available', value: true},
                    {field: 'code', value: 'authorized_delegations'}   
                ]
            },
            get_concrete_fields: ['code', 'name']
        };
        
        me.config.form =
        {
            title: me.trans('webpage_form'),
            fields:
            [
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
                            _clonable: true
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
                            anchor: '100%',
                            _clonable: true
                        },
                        {
                            xtype: 'combo',
                            name: 'website',
                            fieldLabel: '<b>' + me.trans('website') + '</b>',
                            /*_store: {
                                module_id: 'cms',
                                model_id: 'website',
                                fields: ['code', 'name'],
                                filters: [
                                    {field: 'available', value: true},
                                    {field: 'delegation', value: 'authorized_delegations'} 
                                ]                                
                            },*/
                            store: me.getMaintenanceController().getGetRecordsStore(website_config, true, false, 'true'),
                            _addSubmitValues: [
                                {field: 'name', as: 'websiteName'}
                            ],
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: false,
                            //bug//emptyText: me.trans('select_website'),
                            labelAlign: 'right',
                            allowBlank: false,
                            _disabledOnEdit: true,
                            anchor: '100%',
                            _clonable: true
                        }                            
                    ]
                },
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
                        }
                    ]
                }
            ]
        };   
    },
    
    addAdditionalToolbarButtons: function()
    {
        var me = this;
        var toolbar = me.getMaintenanceController().getGridToolBar(me.config);
        toolbar.add(
            { xtype: 'tbfill' },
            Ext.widget('cms_webpage_additionaldata', {
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