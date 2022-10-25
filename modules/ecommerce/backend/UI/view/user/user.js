Ext.define('App.modules.ecommerce.backend.UI.view.user.user', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.ecommerce_user',
        
    explotation: 'E-commerce user view',
    
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
        me.config.grid = 
        {
            title: me.trans('user_view'),
            columns: 
            [
                {
                    text: me.trans('user') + ' (E-mail)',
                    dataIndex: 'code',
                    _renderer: 'bold',
                    align: 'left',
                    width: 250
                },
                {
                    text: me.trans('first_name'),
                    dataIndex: 'firstName',
                    flex: 1,
                    align: 'left',
                    minWidth: 100
                },
                {
                    text: me.trans('last_name'),
                    dataIndex: 'lastName',
                    flex: 1,
                    align: 'left',
                    minWidth: 180
                },
                {
                    text: 'Newsletters',
                    dataIndex: 'newsletters',
                    width: 100
                },
                {
                    text: me.trans('date'),
                    dataIndex: 'signinDate',
                    _renderer: 'date',
                    width: 100
                }
            ]
        };      
    },  
            
    initForm: function()
    {
        var me = this;
        
        me.config.form =
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
                            vtype: 'email',
                            fieldLabel: '<b>' + me.trans('user') + ' (E-mail)' + '</b>',
                            maskRe: /[a-zA-Z0-9\-\_]/,
                            allowBlank: false,
                            labelAlign: 'right',
                            _disabledOnEdit: true,
                            _setFocusOnNew: true,
                            width: 400
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
                            name: 'newsletters',
                            fieldLabel: 'Newsletters',
                            boxLabel: '',
                            labelAlign: 'right',                
                            anchor: '100%',
                            _defaultValue: true // checked when new record
                        },
                        {
                            xtype: 'textfield',
                            name: 'phone',
                            fieldLabel: me.trans('phone'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%'
                        },
                        {
                            xtype: 'textfield',
                            name: 'address',
                            fieldLabel: me.trans('address'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%'
                        },                      
                        {
                            xtype: 'textfield',
                            name: 'postalcode',
                            fieldLabel: me.trans('postal_code'),
                            maskRe: /[0-9]/,
                            allowBlank: false,
                            labelAlign: 'right'
                        },                      
                        {
                            xtype: 'textfield',
                            name: 'city',
                            fieldLabel: me.trans('city'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%'
                        },
                        {
                            xtype: 'combo',
                            name: 'country',
                            fieldLabel: me.trans('country'),
                            store: Ext.create('App.core.backend.UI.store.countries'),
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: true,
                            //bug//emptyText: me.trans('select_country'),
                            allowBlank: false,
                            labelAlign: 'right',
                            width: 400,
                            typeAhead: true,
                            forceSelection: true,
                            listeners: {
                                render: function(field, eOpts)
                                {
                                    if (Ext.isEmpty(field.store))
                                    {
                                        field.store.load();
                                    }
                                }
                            }        
                        },                                            
                        {
                            xtype     : 'textareafield',
                            grow      : true,
                            name      : 'comments',
                            fieldLabel: me.trans('comments'),
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
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').alertInitMaintenance(this.config);              
    }

});