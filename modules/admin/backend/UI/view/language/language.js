Ext.define('App.modules.admin.backend.UI.view.language.language', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.admin_language',
        
    explotation: 'Admin language view',
    
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
        var me = this;
        
        me.config.hide_datapanel_title = true;                 
        me.config.enable_publication = false;
        this.config.enable_deletion = true;
        
        me.config.model.sorters = [{
            property: 'order',
            direction: 'ASC'
        }];        
    },
            
    initGrid: function()
    {
        var me = this;
        this.config.grid = 
        {
            title: me.trans('language_view'),
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    align: 'left',
                    width: 90
                },
                {
                    text: me.trans('name'),
                    dataIndex: 'name',
                    align: 'left',
                    minWidth: 120,
                    flex: 1
                },
                {
                    text: me.trans('available'),
                    dataIndex: 'available',
                    align: 'center',
                    width: 90
                },
                {
                    text: me.trans('order'),
                    dataIndex: 'order',
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
            title: me.trans('language_form'),
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
                            xtype: 'combo',
                            name: 'code',
                            fieldLabel: '<b>' + me.trans('language') + '</b>',
                            store: Ext.create('App.core.backend.UI.store.languages', {
                                autoLoad: true
                            }),
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: true,
                            //bug//emptyText: me.trans('select_language'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%',
                            typeAhead: true,
                            forceSelection: true,
                            _disabledOnEdit: true,
                            _setFocusOnNew: true/*,                          
                            listeners: {
                                render: function(field, eOpts)
                                {
                                    field.store.load();
                                }
                            }*/
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
                            xtype: 'checkboxfield',
                            name: 'available',
                            fieldLabel: me.trans('available'),
                            boxLabel: '',
                            labelAlign: 'right',                
                            anchor: '100%',
                            _defaultValue: true // checked when new record
                        },
                        {
                            xtype: 'numberfield',
                            name: 'order',
                            fieldLabel: me.trans('order'),
                            allowBlank: true,
                            labelAlign: 'right',
                            minValue: 0, //prevents negative numbers                            
                            width: 180
//                            // Remove spinner buttons, and arrow key and mouse wheel listeners
//                            hideTrigger: true,
//                            keyNavEnabled: false,
//                            mouseWheelEnabled: false
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