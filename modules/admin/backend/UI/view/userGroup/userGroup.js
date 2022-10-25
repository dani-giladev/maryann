Ext.define('App.modules.admin.backend.UI.view.userGroup.userGroup', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.admin_userGroup',
        
    explotation: 'Admin user group view',
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        // The grid
        this.initGrid();
        // The form
        this.initForm();

        this.callParent(arguments);
                    
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        form.on('newRecord', this.onNewRecord);
        form.on('editedRecord', this.onEditedRecord);
        form.on('savedRecord', this.onSavedRecord);
    }, 
            
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;                   
        this.config.enable_publication = false; 
        this.config.enable_deletion = true;   
    },   
            
    initGrid: function()
    {
        var me = this;
        this.config.grid = 
        {
            title: me.trans('user_group_view'),
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    align: 'left',
                    width: 100
                },
                {
                    text: me.trans('name'),
                    dataIndex: 'name',
                    align: 'left',
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
        this.config.form =
        {
            title: me.trans('user_group_form'),
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
                            _setFocusOnNew: true
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
                },
                {
                    xtype: 'fieldset',
                    padding: 5,
                    title: me.trans('permissions'),
                    anchor: '100%',
                    items: 
                    [                      
                        {
                            xtype: 'panel',
                            defaults: {
                                // applied to each contained panel
                                bodyStyle: 'padding:15px'
                            },
                            layout: {
                                // layout-specific configs go here
                                type: 'accordion',
                                titleCollapse: true,
                                //multi: true,
                                animate: true
                            },
                            items: 
                            [
                                {
                                    // The first accordion or item must exist and being hidden (sencha bug)
                                    hidden: true
                                }
                            ],
                            listeners: {
                                render: function(this_panel, eOpts)
                                {
                                    var permissions_store = Ext.create('App.modules.admin.backend.UI.store.userGroup.permissions');
                                    permissions_store.on('load', function(this_store, records, successful, eOpts)
                                    {
                                        Ext.each(records, function(record) {
                                            var module_id = record.data.module_id;
                                            var module_name = record.data.module_name;
                                            var widget = 'admin_user_group_permissions';
                                            
                                            var grid = Ext.widget('admin_user_group_permissions', {
                                                itemId: widget + '_' + module_id,
                                                module_id: module_id
                                            });
                                            
                                            this_panel.add({
                                                title: module_name,
                                                collapsed: true,
                                                layout: 'fit',
                                                items: 
                                                [
                                                    {
                                                        xtype: 'fieldcontainer',
                                                        fieldLabel : '',
                                                        margin: '-10 0 7 0',
                                                        anchor: '100%',
                                                        defaultType: 'radiofield',
                                                        defaults: {
                                                            flex: 1
                                                        },
                                                        layout: 'hbox',
                                                        items: 
                                                        [ 
                                                            {
                                                                boxLabel: me.trans('custom'),
                                                                name: 'grantedPermission_' + module_id,
                                                                inputValue: '',
                                                                checked: true,
                                                                listeners: {
                                                                    change: function(thisField, newValue, oldValue, eOpts )
                                                                    {
                                                                        if (newValue === true)
                                                                        {
                                                                            var grid = me.getPermissionsGrid(module_id);
                                                                            grid.setDisabled(false);
                                                                        }

                                                                    }
                                                                }
                                                            }, 
                                                            {
                                                                boxLabel: me.trans('all'),
                                                                name: 'grantedPermission_' + module_id,
                                                                inputValue: 'all',
                                                                listeners: {
                                                                    change: function(thisField, newValue, oldValue, eOpts)
                                                                    {
                                                                        if (newValue === true)
                                                                        {
                                                                            var grid = me.getPermissionsGrid(module_id);
                                                                            grid.setDisabled(true);
                                                                        }
                                                                    }
                                                                }
                                                            }, 
                                                            {
                                                                boxLabel: me.trans('none'),
                                                                name: 'grantedPermission_' + module_id,
                                                                inputValue: 'none',
                                                                listeners: {
                                                                    change: function(thisField, newValue, oldValue, eOpts )
                                                                    {
                                                                        if (newValue === true)
                                                                        {
                                                                            var grid = me.getPermissionsGrid(module_id);
                                                                            grid.setDisabled(true);
                                                                        }
                                                                    }
                                                                }
                                                            }                                                       
                                                        ]
                                                    },                                              
                                                    grid
                                                ]
                                            });
                                            
                                            grid.getStore().load({
                                                params:{
                                                    group_id: '',
                                                    module_id: module_id
                                                }
                                            });                                        
                                        });                                        
                                    }, this); 
                                    permissions_store.load();        
                                }
                            }
                        }
                    ]
                }                
            ]
        };      
    },
        
    getPermissionsGrid: function(module_id)
    {
        // Find grid by itemId
        var itemId = 'admin_user_group_permissions' + '_' + module_id;
        var grid = Ext.ComponentQuery.query('#' + itemId)[0];
        return grid;
    },
            
    onNewRecord: function()
    {
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        var grids = form.query('grid');
        Ext.each(grids, function(grid) {
            grid.getStore().load({
                params:{
                    group_id: '',
                    module_id: grid.module_id
                }
            }); 
        });        
    },
            
    onEditedRecord: function(id)
    {
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        var grids = form.query('grid');
        Ext.each(grids, function(grid) {
            grid.getStore().load({
                params:{
                    group_id: id,
                    module_id: grid.module_id
                }
            }); 
        });            
    },
            
    onSavedRecord: function(code, publish)
    {
        var me = this.getViewController().getMaintenanceView(this.config);
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        var grids = form.query('grid');
        var records = [];
        Ext.each(grids, function(grid) {
            //var modified_data_grid = grid.getStore().getUpdatedRecords(); 
            var data_grid = grid.getStore().getRange();       
            if(!Ext.isEmpty(data_grid))
            {
                var records_grid = [];
                Ext.each(data_grid, function(rc)
                { 
                    records_grid.push(Ext.apply(rc.data));
                });    
                records.push({module_id: grid.module_id,
                              records: Ext.apply(records_grid)});  
            }  
        });
        records = Ext.encode(records); 
        
        Ext.Ajax.request(
        {
            type: 'ajax',
            url : 'index.php',
            method: 'POST',
            params: {
                controller: 'modules\\admin\\backend\\controller\\userGroup', 
                method: 'savePermissions',
                code: code,
                records: records,
                publish: publish
            },
            waitMsg : me.trans('saving_custom_permissions'),
            success: function(response, opts)
            {
                var obj = Ext.JSON.decode(response.responseText);
                if(!obj.success)
                {
                    Ext.MessageBox.show({
                       title: me.trans('saving_custom_permissions_failed'),
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
                   title: me.trans('saving_custom_permissions_failed'),
                   msg: obj.data.result,
                   buttons: Ext.MessageBox.OK,
                   icon: Ext.MessageBox.ERROR
                });
            }
        });        
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.admin.backend.UI.controller.admin').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.admin.backend.UI.controller.admin').alertInitMaintenance(this.config);              
    }
    
});