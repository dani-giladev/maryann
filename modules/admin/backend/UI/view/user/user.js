Ext.define('App.modules.admin.backend.UI.view.user.user', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.admin_user',
        
    explotation: 'Admin user view',
    
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
    },
            
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_deletion = true;
        
        this.config.model.filters = 
        [
            {field: 'superUser', value: false}
        ];
    },  
            
    initGrid: function()
    {
        var me = this;
        this.config.grid = 
        {
            title: me.trans('user_view'),
            columns: 
            [
                {
                    text: me.trans('user'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    align: 'left',
                    width: 100
                },
                {
                    text: me.trans('first_name'),
                    dataIndex: 'firstName',
                    align: 'left',
                    minWidth: 100,
                    flex: 1
                },
                {
                    text: me.trans('last_name'),
                    dataIndex: 'lastName',
                    align: 'left',
                    minWidth: 100,
                    flex: 1
                },
                {
                    text: me.trans('group'),
                    dataIndex: 'groupName',
                    align: 'center',
                    minWidth: 100,
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
                fields: ['code', 'name']
            },
            get_concrete_fields: ['code', 'name']
        };
        
        var group_config = 
        {
            module_id: 'admin',
            model: 
            {
                id: 'userGroup',
                fields: ['code', 'name'],
                filters: [
                    {field: 'available', value: true}
                ]
            },
            get_concrete_fields: ['code', 'name']
        }; 
                
        this.config.form =
        {
            title: me.trans('user_form'),
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
                            fieldLabel: '<b>' + me.trans('user') + '</b>',
                            maskRe: /[a-zA-Z0-9\-\_]/,
                            allowBlank: false,
                            labelAlign: 'right',
                            _disabledOnEdit: true,
                            _setFocusOnNew: true
                        },
                        {
                            xtype: 'textfield',
                            name: 'password',
                            fieldLabel: me.trans('password'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%',
                            inputType: 'password'
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
                            name: 'firstName',
                            fieldLabel: me.trans('first_name'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%'
                        },
                        {
                            xtype: 'textfield',
                            name: 'lastName',
                            fieldLabel: me.trans('last_name'),
                            allowBlank: false,
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
                            name: 'group',
                            fieldLabel: me.trans('group'),
                            /*_store: {
                                module_id: 'admin',
                                model_id: 'userGroup',
                                fields: ['code', 'name'],
                                filters: [
                                    {field: 'available', value: true}
                                ]                                
                            },*/
                            store: me.getMaintenanceController().getGetRecordsStore(group_config, true, false, 'true'),
                            _addSubmitValues: [
                                {field: 'name', as: 'groupName'}
                            ],
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: false,
                            //bug//emptyText: me.trans('select_user_group'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%'
                        },
                        {
                            xtype: 'multiselect',
                            name: 'delegations',
                            fieldLabel: me.trans('authorized_delegations'),
                            store: me.getMaintenanceController().getGetRecordsStore(delegation_config, false, false, 'true'),
                            valueField: 'code',
                            displayField: 'name',
                            delimiter: '|',
                            queryMode: 'local',
                            allowBlank: true,
                            msgTarget: 'side',
                            minHeight: 50,
                            anchor: '100%',
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
                                            model_id: 'delegation',
                                            stale: 'true',                                            
                                            filters: json_filters
                                        }
                                    });                                      
                                }   
                            }                           
                        }         
                    ]
                }
            ]
        };      
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.admin.backend.UI.controller.admin').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.admin.backend.UI.controller.admin').alertInitMaintenance(this.config);              
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
    
});