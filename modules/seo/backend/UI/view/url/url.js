Ext.define('App.modules.seo.backend.UI.view.url.url', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.seo_url',
        
    explotation: 'SEO url view',

    config: null,
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        this.config.save_controller = 'modules\\seo\\backend\\controller\\url';
        
        // The grid
        this.initGrid();
        // The form
        this.initForm();
        // The dynamic filter form
        this.initDynamicFilterForm();

        this.callParent(arguments);
        
        this.addOthersFeatures(); 
    },
    
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: 'Urls',
            flex: 1,
            columns: 
            [
                {
                    text: 'Url',
                    dataIndex: 'url',
                    //_renderer: 'bold',
                    flex: 1
                },
                {
                    text: me.trans('action'),
                    dataIndex: 'action',
                    width: 120,
                    align: 'center',
                    renderer: function(value, meta, record) {
                        return me.trans(value);
                    }
                },
                {
                    text: me.trans('option'),
                    dataIndex: 'useAction',
                    width: 120,
                    align: 'center',
                    renderer: function(value, meta, record) {
                        if (record.get('action') === 'blocking')
                        {
                            return "";
                        }
                        return me.trans(value);
                    }
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
                me.getPropertiesFieldset()
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
                    name: 'url',
                    fieldLabel: '<b>' + 'Url' + '</b>',
                    allowBlank: false,
                    labelAlign: 'right',
                    _disabledOnEdit: true,
                    _setFocusOnNew: true,
                    anchor: '100%'
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
                    xtype: 'combo',
                    name: 'action',
                    fieldLabel: me.trans('action'),
                    labelAlign: 'right',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "blocking", "name": me.trans('blocking')},
                            {"code": "redirection", "name": me.trans('redirection')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 250,
                    allowBlank: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue == oldValue) return;
                            var form = me.getMaintenanceController().getForm(me.config);
                            var container_radiobuttons = form.down('container[itemId=seo_url_form_container_radiobuttons]'); 
                            var visible = (newValue === 'redirection');
                            container_radiobuttons.setVisible(visible);
                            /*var radiobutton_redirect2Url = form.down('radio[inputValue=redirect2Url]');   
                            var radiobutton_redirect2Article = form.down('radio[inputValue=redirect2Article]'); 
                            var radiobutton_redirect2Category = form.down('radio[inputValue=redirect2Category]');
                            
                            var radiobutton_redirect2Brand = form.down('radio[inputValue=redirect2Brand]');                          
                            var disabled = (newValue !== "redirection");
                            radiobutton_redirect2Article.setDisabled(disabled);
                            radiobutton_redirect2Category.setDisabled(disabled);
                            radiobutton_redirect2Brand.setDisabled(disabled);

                            if (newValue === "blocking")
                            {
                                radiobutton_redirect2Url.setValue(true);
                            }*/
                        }
                    }
                },
                {
                    xtype: 'container',
                    itemId: 'seo_url_form_container_radiobuttons',
                    hidden: true,
                    items:
                    [
                        // REDIRECT TO URL
                        {
                            xtype: 'radio',
                            name: 'useAction',
                            fieldLabel: '',
                            boxLabel: me.trans('redirect2Url'),
                            margin: '0 0 0 30',
                            inputValue: 'redirect2Url',
                            checked: true,
                            listeners: {
                                change: function(field, newValue, oldValue, eOpts) {
                                    //if (newValue == oldValue) return;
                                    var form = me.getMaintenanceController().getForm(me.config);
                                    var textfield = form.getForm().findField('redirect2Url');
                                    textfield.setDisabled(!newValue);
                                }
                            }
                        },
                        {
                            xtype: 'textfield',
                            name: 'redirect2Url',
                            fieldLabel: 'Url',
                            labelAlign: 'right',
                            width: 400
                        },

                        // REDIRECT TO ARTICLE
                        {
                            xtype: 'radio',
                            name: 'useAction',
                            fieldLabel: '',
                            boxLabel: me.trans('redirect2Article'),
                            margin: '0 0 0 30',
                            inputValue: 'redirect2Article',
                            listeners: {
                                change: function(field, newValue, oldValue, eOpts) {
                                    if (newValue == oldValue) return;
                                    var form = me.getMaintenanceController().getForm(me.config);
                                    var textfield = form.getForm().findField('redirect2Article');
                                    textfield.setDisabled(!newValue);
                                }
                            }
                        },
                        {
                            xtype: 'textfield',
                            name: 'redirect2Article',
                            fieldLabel: me.trans('article_code'),
                            labelAlign: 'right',
                            anchor: '100%',
                            disabled: true
                        },

                        // REDIRECT TO CATEGORY
                        {
                            xtype: 'radio',
                            name: 'useAction',
                            fieldLabel: '',
                            boxLabel: me.trans('redirect2Category'),
                            margin: '0 0 0 30',
                            inputValue: 'redirect2Category',
                            listeners: {
                                change: function(field, newValue, oldValue, eOpts) {
                                    if (newValue == oldValue) return;
                                    var form = me.getMaintenanceController().getForm(me.config);
                                    var textfield = form.getForm().findField('redirect2Category');
                                    textfield.setDisabled(!newValue);
                                }
                            }
                        },
                        {
                            xtype: 'textfield',
                            name: 'redirect2Category',
                            fieldLabel: me.trans('category_code'),
                            labelAlign: 'right',
                            anchor: '100%',
                            disabled: true
                        },

                        // REDIRECT TO BRAND
                        {
                            xtype: 'radio',
                            name: 'useAction',
                            fieldLabel: '',
                            boxLabel: me.trans('redirect2Brand'),
                            margin: '0 0 0 30',
                            inputValue: 'redirect2Brand',
                            listeners: {
                                change: function(field, newValue, oldValue, eOpts) {
                                    if (newValue == oldValue) return;
                                    var form = me.getMaintenanceController().getForm(me.config);
                                    var textfield = form.getForm().findField('redirect2Brand');
                                    textfield.setDisabled(!newValue);
                                }
                            }
                        },
                        {
                            xtype: 'textfield',
                            name: 'redirect2Brand',
                            fieldLabel: me.trans('brand_code'),
                            labelAlign: 'right',
                            anchor: '100%',
                            disabled: true
                        }                
                    ]
                }
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
                    name: 'url',
                    fieldLabel: 'Url',
                    anchor: '100%',
                    _filtertype: 'string' 
                },
                {
                    xtype: 'combo',
                    name: 'action',
                    fieldLabel: me.trans('action'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "blocking", "name": me.trans('blocking')},
                            {"code": "redirection", "name": me.trans('redirection')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 250,
                    _filtertype: 'string'
                },
                {
                    xtype: 'combo',
                    name: 'useAction',
                    fieldLabel: me.trans('option'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "redirect2Url", "name": me.trans('redirect2Url')},
                            {"code": "redirect2Article", "name": me.trans('redirect2Article')},
                            {"code": "redirect2Category", "name": me.trans('redirect2Category')},
                            {"code": "redirect2Brand", "name": me.trans('redirect2Brand')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 250,
                    _filtertype: 'string'
                }
            ]
        };
    },
    
    addOthersFeatures: function()
    {
        
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.seo.backend.UI.controller.seo').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.seo.backend.UI.controller.seo').alertInitMaintenance(this.config);              
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
});