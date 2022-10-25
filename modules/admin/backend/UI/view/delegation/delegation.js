Ext.define('App.modules.admin.backend.UI.view.delegation.delegation', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.admin_delegation',
        
    explotation: 'Admin delegation view',
    
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
    },   
            
    initGrid: function()
    {
        var me = this;
        this.config.grid = 
        {
            title: me.trans('delegation_view'),
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
            title: me.trans('delegation_form'),
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
                        },
                        {
                            xtype: 'textfield',
                            name: 'phone',
                            fieldLabel: me.trans('phone'),
                            allowBlank: true,
                            labelAlign: 'right',
                            anchor: '100%'
                        },
                        {
                            xtype: 'textfield',
                            name: 'email',
                            vtype: 'email',
                            fieldLabel: 'E-mail',
                            allowBlank: true,
                            labelAlign: 'right',
                            anchor: '100%'
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
    }

});