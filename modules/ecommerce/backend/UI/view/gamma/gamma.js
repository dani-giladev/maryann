Ext.define('App.modules.ecommerce.backend.UI.view.gamma.gamma', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.ecommerce_gamma',
        
    explotation: 'E-commerce gamma view',

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
        
        this.addAdditionalDataMenu();   
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_clone = true;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\ecommerce\\backend\\controller\\gamma';
        this.config.publish_controller = this.config.save_controller;
        this.config.clone_controller = this.config.save_controller;
        this.config.delete_controller = this.config.save_controller;
        this.config.save_modal_form_method = 'saveAdditionalData';
    },
            
    initGrid: function()
    {
        var me = this;
        this.config.grid = 
        {
            title: me.trans('gamma_view'),
            features: me.getGridFeatures(),
            plugins: 'bufferedrenderer',
            groupField: me.getGroupFieldGrid(),
            flex: 1,
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    width: 100,
                    align: 'left',
                    filter: {type: 'string'}
                },
                {
                    text: me.trans('name'),
                    dataIndex: 'name',
                    flex: 1,
                    align: 'left',
                    minWidth: 180,
                    filter: {type: 'string'}
                },
                {
                    text: me.trans('available'),
                    dataIndex: 'available',
                    width: 90, 
                    filter: {type: 'boolean'}
                },
                {
                    text: me.trans('visible'),
                    dataIndex: 'visible',
                    width: 90, 
                    filter: {type: 'boolean'}
                },
                {
                    text: me.trans('brand'),
                    dataIndex: 'brand',
                    width: 100, 
                    align: 'center',
                    filter: {type: 'string'}
                }
            ]
        };
    },
    
    getGridFeatures: function()
    {
        var ret =  
        [
            {
                ftype: 'grouping',
                groupHeaderTpl: '{name} ({rows.length})',
                hideGroupedHeader: true,
                startCollapsed: true
            }
        ];
        
        return ret;
    },
    
    getGroupFieldGrid: function()
    {
        return 'brandName';
    },
            
    initForm: function()
    {
        var me = this;
        
        this.config.form =
        {
            title: me.trans('gamma_form'),
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
                            name: 'brand',
                            fieldLabel: '<b>' + me.trans('brand') + '</b>',
                            _store: {
                                module_id: 'ecommerce',
                                model_id: 'brand',
                                fields: ['code', 'name'],
                                filters: [] //{field: 'available', value: true}                                
                            },
                            _addSubmitValues: [
                                {field: 'name', as: 'brandName'}
                            ],
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: true,
                            typeAhead: true,
                            forceSelection: true,                            
                            //bug//emptyText: me.trans('select_brand'),
                            allowBlank: false,
                            labelAlign: 'right',
                            _disabledOnEdit: true,
                            anchor: '100%',
                            _clonable: true,
                            listeners: {
                                beforequery: function (record) {
                                    record.query = new RegExp(record.query, 'i');
                                    record.forceAll = true;
                                }
                            }
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
                            xtype: 'checkboxfield',
                            name: 'visible',
                            fieldLabel: me.trans('visible'),
                            boxLabel: '',
                            labelAlign: 'right',                
                            anchor: '100%',
                            _defaultValue: true/*, // checked when new record
                            listeners: {
                                change: function(field, newValue, oldValue, eOpts)
                                {
                                    if (newValue == oldValue) return;
                                    var form = field.up('form');
                                    form.getForm().findField('not_visible_in_article').setDisabled(!newValue);
                                    form.getForm().findField('discard_in_composition_of_article_title').setDisabled(!newValue);
                                }
                            }*/
                        },
                        {
                            xtype: 'checkboxfield',
                            name: 'not_visible_in_article',
                            fieldLabel: ' ',
                            labelSeparator: '',
                            boxLabel: me.trans('not_visible_in_article'),
                            labelAlign: 'right',
                            margin: '-10 0 0 20'
                        },
                        {
                            xtype: 'checkboxfield',
                            name: 'discard_in_composition_of_article_title',
                            fieldLabel: ' ',
                            labelSeparator: '',
                            boxLabel: me.trans('discard_in_composition_of_article_title'),
                            labelAlign: 'right',
                            margin: '0 0 10 20'
                        },
                        me.getDiscountFieldcontainer()
                    ]
                }
            ]
        };
    },
            
    initDynamicFilterForm: function()
    {
        var me = this;
        
        var brand_config = {
            module_id: 'ecommerce',
            model: {
                id: 'brand',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}
                ]
            }
        };
        
        me.config.dynamicFilterForm =
        {
            //title: me.trans('?'),
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
                    fieldLabel: me.trans('name'),
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
                    _filtertype: 'boolean',
                    _default_value: 'yes'
                },
                {
                    xtype: 'combo',
                    name: 'visible',
                    fieldLabel: me.trans('visible'),
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
                    _filtertype: 'boolean'
                },
                {
                    xtype: 'combo',
                    name: 'brand',
                    fieldLabel: me.trans('brand'),
                    store: me.getMaintenanceController().getGetRecordsStore(brand_config, true, false, 'true'),
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
    
    getDiscountFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [              
                {
                    xtype: 'numberfield',
                    name: 'discount',
                    fieldLabel: me.trans('discount'),
                    allowBlank: true,
                    labelAlign: 'right',
                    minValue: 0, //prevents negative numbers                                    
                    decimalPrecision: 0,                   
                    width: 150,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                }
            ]
        };
        
        return ret;
    },
    
    addAdditionalDataMenu: function()
    {
        var me = this;
        var toolbar = me.getMaintenanceController().getGridToolBar(me.config);
        toolbar.add(
            { xtype: 'tbfill' },
            Ext.widget('ecommerce_gamma_additionaldata', {
                config: me.config
            })                
        );  
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').alertInitMaintenance(this.config);              
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
});