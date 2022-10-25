Ext.define('App.modules.marketing.backend.UI.view.promo.promo', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.marketing_promo',
        
    explotation: 'Marketing promo view',

    config: null,
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        this.config.save_controller = 'modules\\marketing\\backend\\controller\\promo';
        
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
            title: 'Promos',
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
                me.getTitleFieldset()
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
                    _setFocusOnNew: true
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
                    xtype: 'checkboxfield',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    _defaultValue: true // checked when new record
                },
                {
                    xtype: 'checkboxfield',
                    name: 'visibleMenu',
                    fieldLabel: 'Visible menu',
                    boxLabel: '',
                    labelAlign: 'right'         
                },                        
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    items: 
                    [
                        {
                            xtype: 'combo',
                            name: 'articleGroup',
                            fieldLabel: me.trans('articles_group'),
                            _store: {
                                module_id: 'marketing',
                                model_id: 'articleGroup',
                                fields: ['code', 'name'],
                                filters: [] //{field: 'available', value: true}                                
                            },
                            _addSubmitValues: [
                                {field: 'name', as: 'articleGroupName'}
                            ],
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: true,
                            typeAhead: true,
                            forceSelection: true, 
                            //bug//emptyText: me.trans('select_articles_group'),
                            allowBlank: false,
                            labelAlign: 'right',
                            width: '90%',
                            listeners: {
                                beforequery: function (record) {
                                    record.query = new RegExp(record.query, 'i');
                                    record.forceAll = true;
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            margin: '0 0 0 5',
                            text: "X",
                            width: 32,
                            handler: function()
                            {
                                var form = me.getMaintenanceController().getForm(me.config);
                                var field = form.getForm().findField('articleGroup');                        
                                field.setValue('');
                            }
                        } 
                    ]
                }
            ]
        };
        
        return ret;
    },
    
    getTitleFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('title'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'marketing_promo_title_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'marketing_promo_tabpanel_title'
                }
            ]
        };
        
        return ret;
    },

    initDynamicFilterForm: function()
    {
        var me = this;
        
        var article_group_config = {
            module_id: 'marketing',
            model: {
                id: 'articleGroup',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}
                ]
            }
        };
        
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
                },
                {
                    xtype: 'combo',
                    name: 'articleGroup',
                    fieldLabel: me.trans('articles_group'),
                    store: me.getMaintenanceController().getGetRecordsStore(article_group_config, true, false, 'true'),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    anchor: '100%',
                    _filtertype: 'string',
                    listConfig:{
                        minWidth: 300 // width of the list
                        //maxHeight: 400 // height of a list with scrollbar
                    }
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
        me.createTabContent('title');
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = me.down('#marketing_promo_tabpanel_' + type);
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#marketing_promo_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            i = 0;
            Ext.each(langs, function(lang) {
                lang_code = lang.code;
                lang_name = lang.name;
                
                var name;
                if (type === 'title')
                {
                    name = 'titles';
                }
                else
                {
                    name = 'keywords';
                }
                tab.add({
                    xtype: 'textfield',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _name: name,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%'
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
        
        // Clean title tab
        tab = view.down('#marketing_promo_tabpanel_title');
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
        
        // Set title tab
        tab = view.down('#marketing_promo_tabpanel_title');
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