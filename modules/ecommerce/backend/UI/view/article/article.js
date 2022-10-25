Ext.define('App.modules.ecommerce.backend.UI.view.article.article', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.ecommerce_article',
        
    explotation: 'E-Commerce article view',

    config: null,
    change_tab_automatically_when_change_lang: false,
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        // The grid
        this.initGrid();
        // The form
        this.initForm();
        // The filter form
        this.initFilterForm();
        // The dynamic filter form
        this.initDynamicFilterForm();

        this.callParent(arguments);               
        
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        form.on('newRecord', this.onNewRecord);
        form.on('editedRecord', this.onEditedRecord);    
        
        this.addAdditionalDataMenu();
        this.addOthersFeatures();
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;
        this.config.enable_publication = false;
        this.config.enable_clone = true;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\ecommerce\\backend\\controller\\article';
        this.config.publish_controller = this.config.save_controller;
        this.config.clone_controller = this.config.save_controller;
        this.config.delete_controller = this.config.save_controller;
        this.config.get_controller = this.config.save_controller;
        this.config.export_controller = this.config.save_controller;
        this.config.save_modal_form_method = 'saveAdditionalData';
        this.config.group_action_buttons = true;
    },
            
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: me.trans('article_view'),
            features: me.getGridFeatures(),
            plugins: 'bufferedrenderer',
            groupField: me.getGroupFieldGrid(),
            columns: me.getGridColumns(),
            contextmenu: me.getGridContextMenu()          
        };
    },
    
    getGridContextMenu: function()
    {
        var me = this;
        
        return [   
            {                       
                text: me.trans('view_webpage'),
                icon: 'resources/ico/blue-eye.png',
                handler: function(e, t)
                {
                    var grid = me.getViewController().getGrid(me.config);
                    var record = grid.getSelectionModel().getSelection()[0];
                    //console.log(record);
                    
                    Ext.getBody().mask(me.trans('loading'));

                    Ext.Ajax.request({
                        type: 'ajax',
                        url : 'index.php',
                        method: 'GET',
                        params: {
                            controller: 'modules\\ecommerce\\backend\\controller\\article', 
                            method: 'getUrl',
                            code: record.get('code')              
                        },
                        success: function(response, opts)
                        {
                            Ext.getBody().unmask();
                            var obj = Ext.JSON.decode(response.responseText);
                            if(!obj.success)
                            {
                                Ext.MessageBox.show({
                                   title: 'Error',
                                   msg: obj.data.result,
                                   buttons: Ext.MessageBox.OK,
                                   icon: Ext.MessageBox.INFO
                                });
                            }

                            var url = obj.data.result;
                            //console.log(url);
                            window.open(url, '_blank');
                        },
                        failure: function(form, data)
                        {
                            Ext.getBody().unmask();
                        }
                    });                      
                  
                }
            }
        ];
        
    },
            
    initForm: function()
    {
        var me = this;
        me.config.form =
        {
            title: me.trans('article_form'),
            fields:
            [
                me.getMainFieldset(),
                me.getERPFieldset(),
                me.getPropertiesFieldset(),
                me.getPricesFieldset(),
                me.getStockFieldset(),
                //me.getBotplusFieldset(),
                me.getAvailabilityFieldset(),
                me.getSEOFieldset(),
                me.getReviewFieldset(),
                me.getNotesFieldset()
            ]
        };
    },
    
    getGridColumns: function()
    {
        var me = this;
        var ret =  
        [
            {
                text: me.trans('brand'),
                dataIndex: 'brandName',
                width: 150,
                align: 'center'
            },           
            {
                text: me.trans('code'),
                dataIndex: 'code',
                _renderer: 'bold',
                align: 'left',
                width: 100
            },
            {
                text: me.trans('gamma'),
                dataIndex: 'gammaName',
                width: 150,
                align: 'center'
            }, 
            {
                text: me.trans('name'),
                dataIndex: 'name',
                align: 'left',
                width: 300
            },
            {
                text: 'Canonical',
                dataIndex: 'canonical',
                width: 100,
                align: 'center'
            },
            {
                text: me.trans('group_displays_by'),
                dataIndex: 'articleCode2GroupDisplays',
                width: 100,
                align: 'center'
            },
            {
                text: me.trans('family'),
                dataIndex: 'family',
                width: 100,
                align: 'center',
                renderer: function(value, meta, record) {
                    return record.get('familyName');
                }
            },
            {
                text: me.trans('article_type'),
                dataIndex: 'articleType',
                width: 100,
                align: 'center',
                renderer: function(value, meta, record) {
                    return value + ' (' + record.get('articleTypeName') + ')';
                }
            },
            {
                text: me.trans('available'),
                dataIndex: 'available',
                width: 100
            },
            {
                text: me.trans('validated'),
                dataIndex: 'validated',
                width: 100
            },
            {
                text: 'Stock?',
                dataIndex: 'anyStock',
                width: 100
            },
            {
                text: 'Stock',
                dataIndex: 'stock',
                width: 100,
                align: 'center'
            },
            {
                text: 'En Farmatic?',
                dataIndex: 'inErp',
                width: 100
            },
            {
                text: me.trans('for_sale'),
                dataIndex: 'forSale',
                width: 100
            },
            {
                text: me.trans('for_sale_and_visible'),
                dataIndex: 'forSaleAndVisible',
                width: 100
            },
            {
                text: me.trans('outstanding'),
                dataIndex: 'outstanding',
                width: 100
            },
            {
                text: 'Christmas',
                dataIndex: 'christmas',
                width: 100
            },
            {
                text: me.trans('novelty'),
                dataIndex: 'novelty',
                width: 100
            },
            {
                text: 'Pack',
                dataIndex: 'pack',
                width: 100
            },
            {
                text: me.trans('spellcheck'),
                dataIndex: 'spellcheck',
                width: 160
            },/*
            {
                text: me.trans('checked_by_pharmacist'),
                dataIndex: 'checkedByPharmacist',
                width: 160
            },*/
            {
                text: me.trans('checked_packaging'),
                dataIndex: 'checkedPackaging',
                width: 160
            },
            {
                text: me.trans('date'),
                dataIndex: 'checkedPackagingDate',
                align: 'center',
                width: 100
            },
            {
                text: me.trans('image'),
                dataIndex: 'anyImage',
                width: 160
            },
            {
                text: 'Google shopping',
                dataIndex: 'googleShopping',
                width: 100
            },
            {
                text: 'GTIN?',
                dataIndex: 'anyGtin',
                width: 100
            },
            {
                text: me.trans('code') + ' GTIN',
                dataIndex: 'gtin',
                width: 150,
                align: 'center'
            },            
            {
                text: me.trans('brand'),
                dataIndex: 'brand',
                align: 'center'
            },            
            {
                text: me.trans('sale_rate'),
                dataIndex: 'saleRate',
                align: 'center'
            },            
            {
                text: me.trans('margin'),
                dataIndex: 'useMargin',
                align: 'center'
            },            
            {
                text: me.trans('discount'),
                dataIndex: 'useDiscount',
                align: 'center'
            }
        ];
        
        return ret;
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
            
    initFilterForm: function()
    {
        var me = this;
        me.config.filterForm =
        {
            fields:
            [
                {
                    xtype: 'combo',
                    name: 'validated',
                    fieldLabel: me.trans('validated'),
                    labelAlign: 'right',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "true", "name": me.trans('yes')},
                            {"code": "false", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    value: 'false',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts)
                        {
                            if (!me.is_box_ready) return;
                            if (newValue === oldValue) return;
                            me.getMaintenanceController().refreshGrid(me.config);
                        }
                    }
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
        
        var article_type_config = {
            module_id: 'ecommerce',
            model: {
                id: 'articleType',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}
                ]
            }
        };
        
        var article_family_config = {
            module_id: 'ecommerce',
            model: {
                id: 'articleFamily',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}
                ]
            }
        };
        
        var sale_rate_config = {
            module_id: 'ecommerce',
            model: {
                id: 'saleRate',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}
                ]
            }
        };
        
        me.config.dynamicFilterForm =
        {
            title: me.trans('dynamic_filters'),
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
                },
                {
                    xtype: 'textfield',
                    name: 'gammaName',
                    fieldLabel: me.trans('gamma'),
                    anchor: '100%',
                    _filtertype: 'string' 
                },
                {
                    xtype: 'textfield',
                    name: 'canonical',
                    fieldLabel: 'Canonical',
                    maskRe: /[a-zA-Z0-9\-\_]/,
                    _filtertype: 'string'                    
                },
                {
                    xtype: 'textfield',
                    name: 'articleCode2GroupDisplays',
                    fieldLabel: me.trans('group_displays_by'),
                    maskRe: /[a-zA-Z0-9\-\_]/,
                    _filtertype: 'string'                    
                },
                {
                    xtype: 'combo',
                    name: 'family',
                    fieldLabel: me.trans('family'),
                    store: me.getMaintenanceController().getGetRecordsStore(article_family_config, true, false, 'true'),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    anchor: '100%',
                    _filtertype: 'string',
                    listConfig:{
                        minWidth: 300 // width of the list
                        //maxHeight: 400 // height of a list with scrollbar
                    }
                },
                {
                    xtype: 'combo',
                    name: 'articleType',
                    fieldLabel: me.trans('article_type'),
                    store: me.getMaintenanceController().getGetRecordsStore(article_type_config, true, false, 'true'),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    anchor: '100%',
                    _filtertype: 'string',
                    listConfig:{
                        minWidth: 300 // width of the list
                        //maxHeight: 400 // height of a list with scrollbar
                    }
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
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'anyStock',
                    fieldLabel: 'Stock',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'inErp',
                    fieldLabel: 'En farmatic',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'forSale',
                    fieldLabel: me.trans('for_sale'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'forSaleAndVisible',
                    fieldLabel: me.trans('for_sale_and_visible'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'outstanding',
                    fieldLabel: me.trans('outstanding'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'christmas',
                    fieldLabel: 'Christmas',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'novelty',
                    fieldLabel: me.trans('novelty'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'pack',
                    fieldLabel: 'Pack',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'spellcheck',
                    fieldLabel: me.trans('spellcheck'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'checkedPackaging',
                    fieldLabel: me.trans('checked_packaging'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'anyImage',
                    fieldLabel: me.trans('image'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'googleShopping',
                    fieldLabel: 'Google shopping',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
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
                    name: 'anyGtin',
                    fieldLabel: 'GTIN?',
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_male')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    _filtertype: 'boolean'
                },
                {
                    xtype: 'textfield',
                    name: 'gtin',
                    fieldLabel: me.trans('code') + ' GTIN',
                    maskRe: /[0-9]/,
                    _filtertype: 'string'                    
                },
                {
                    xtype: 'combo',
                    name: 'saleRate',
                    fieldLabel: me.trans('sale_rate'),
                    store: me.getMaintenanceController().getGetRecordsStore(sale_rate_config, true, false, 'true'),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    anchor: '100%',
                    _filtertype: 'string',
                    listConfig:{
                        minWidth: 300 // width of the list
                        //maxHeight: 400 // height of a list with scrollbar
                    }
                },
                {
                    xtype: 'combo',
                    name: 'useMargin',
                    fieldLabel: me.trans('margin'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "article", "name": me.trans('article_margin')},
                            {"code": "saleRate", "name": me.trans('sale_rate_margin')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    _filtertype: 'string',
                    anchor: '100%',
                    listConfig:{
                        minWidth: 300 // width of the list
                        //maxHeight: 400 // height of a list with scrollbar
                    }
                },
                {
                    xtype: 'combo',
                    name: 'useDiscount',
                    fieldLabel: me.trans('discount'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "article", "name": me.trans('article_discount')},
                            {"code": "saleRate", "name": me.trans('sale_rate_discount')},
                            {"code": "gamma", "name": me.trans('gamma_discount')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    _filtertype: 'string',
                    anchor: '100%',
                    listConfig:{
                        minWidth: 300 // width of the list
                        //maxHeight: 400 // height of a list with scrollbar
                    }
                }
            ]
        };
    },
    
    getMainFieldset: function()
    {
        var me = this;
        
        var items = [
            me.getCodeTextField()//,
            //me.getNameTextField()//,
            //me.getDescriptionTextField()
        ];
        
        if (Ext.isEmpty(ecommerce_only_one_delegation))
        {
            items.push(me.getDelegationsMultiselectField());
        }
        
        items.push(
            me.getArticleTypeComboField(),
            me.getBrandComboField(),
            me.getGammaComboField(),
            me.getTitlesFieldset(),
            me.getDisplaysField(),
            me.getArticleCode2GroupDisplaysField(),
            me.getFamilyComboField()
        );

        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('main'),
            anchor: '100%',
            items: items
        };
        
        return ret;
    },
    
    getCodeTextField: function()
    {   
        var me = this;  
        var ret =       
        {
            xtype: 'textfield',
            name: 'code',
            fieldLabel: '<b>' + me.trans('code') + '</b>',
            //maskRe: /[a-zA-Z0-9\-\_\.]/, // Include . for national code
            maskRe: /[a-zA-Z0-9\-\_]/,
            allowBlank: false,
            labelAlign: 'right',
            _disabledOnEdit: true,
            _setFocusOnNew: true,
            _clonable: true
        };        
        
        return ret;
    },
    
    getNameTextField: function()
    {   
        var me = this;
        var ret =    
        {
            xtype: 'textfield',
            name: 'name',
            fieldLabel: me.trans('internal_name'),
            allowBlank: false,
            labelAlign: 'right',
            anchor: '100%'
        };       
        
        return ret;
    },
    
    getDescriptionTextField: function()
    {      
        var me = this;
        var ret = 
        {
            xtype: 'textfield',
            name: 'description',
            fieldLabel: me.trans('description'),
            allowBlank: true,
            labelAlign: 'right',
            anchor: '100%'        
        };
        
        return ret;
    },
    
    getDelegationsMultiselectField: function()
    {      
        var me = this;
        
        var delegation_config = me.getMaintenanceController().cloneObject(me.config);
        delegation_config.model.fields = [
                    {name: 'code'}, 
                    {name: 'name'}
        ];
        
        /*
        var delegation_config = {
            module_id: 'admin',
            model: {
                id: 'delegation',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}
                ]
            }
        };
        */
       
        var ret = 
        {
            xtype: 'multiselect',
            name: 'delegations',
            fieldLabel: me.trans('delegations'),
            store: me.getMaintenanceController().getGetRecordsStore(delegation_config, false, false, 'true'),
            valueField: 'code',
            displayField: 'name',
            delimiter: '|',
            queryMode: 'local',
            allowBlank: true,
            msgTarget: 'side',
            minHeight: 50,
            maxHeight: 100,
            anchor: '100%',
            labelAlign: 'right',
            autoScroll: true,
            listeners: {
                render: function(field, eOpts)
                {
                    var filters = [
                        //{field: 'available', value: true},
                        {field: 'code', value: 'authorized_delegations'}
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
        };
        
        return ret;
    },
    
    getArticleTypeComboField: function()
    {      
        var me = this;
        
        var model_id = 'articleType';
        if (!Ext.isEmpty(me.config.article_model_type_id))
        {
            model_id = me.config.article_model_type_id;
        }
        
        var ret = 
        {
            xtype: 'combo',
            name: 'articleType',
            fieldLabel: me.trans('article_type'),
            _store: {
                module_id: me.config.module_id,
                model_id: model_id,
                fields: ['code', 'name', 'vat'],
                filters: [] //{field: 'available', value: true}                                
            },
            _addSubmitValues: [
                {field: 'name', as: 'articleTypeName'}
            ],
            valueField: 'code',
            displayField: 'name',
            queryMode: 'local',
            editable: true,
            typeAhead: true,
            forceSelection: true, 
            //bug//emptyText: me.trans('select_article_type'),
            allowBlank: false,
            labelAlign: 'right',
            anchor: '100%',
            listeners: {
                change: function(field, newValue, oldValue, eOpts) {
                    if (!me.is_box_ready) return;
                    if (newValue === oldValue) return;
                    var vat_field = me.down('#ecommerce_article_prices_vat');
                    if (!Ext.isEmpty(vat_field))
                    {
                        var combo_store = field.getStore();
                        var record = combo_store.findRecord('code', newValue);
                        if (!record) return;
                        vat_field.setValue(record.data.vat);
                    }
                },
                beforequery: function (record) {
                    record.query = new RegExp(record.query, 'i');
                    record.forceAll = true;
                }
            }
        };
        
        return ret;
    },
    
    getBrandComboField: function()
    {      
        var me = this;
        
        var model_id = 'brand';
        if (!Ext.isEmpty(me.config.brand_model_id))
        {
            model_id = me.config.brand_model_id;
        }
                    
        var ret = 
        {
            xtype: 'combo',
            name: 'brand',
            fieldLabel: me.trans('brand'),
            _store: {
                module_id: me.config.module_id,
                model_id: model_id,
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
            forceSelection: false, 
            //bug//emptyText: me.trans('select_brand'),
            allowBlank: false,
            labelAlign: 'right',
            anchor: '100%',
            listeners: {
                change: function(field, newValue, oldValue, eOpts)
                {
                    if (!me.is_box_ready) return false;
                    if (newValue === oldValue) return false;
                    me.updateGammaComboField(newValue, true);
                },
                beforequery: function (record) {
                    record.query = new RegExp(record.query, 'i');
                    record.forceAll = true;
                }
            }        
        };
        
        return ret;
    },
    
    getGammaComboField: function()
    {      
        var me = this;

        /*
        var gamma_config = me.getMaintenanceController().cloneObject(me.config);
        gamma_config.model.fields = [
            {name: 'code'}, 
            {name: 'name'}
        ];
        */
       
        var ret = 
        {
            xtype: 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'combo',
                    name: 'gamma',
                    fieldLabel: me.trans('gamma'),
                    //store: me.getMaintenanceController().getGetRecordsStore(gamma_config, false, false, 'true'),
                    _store: {
                        autoload: 'no',
                        module_id: me.config.module_id,
                        model_id: 'gamma',
                        fields: ['code', 'name', 'discount'],
                        filters: [] //{field: 'available', value: true}                                
                    },
                    _addSubmitValues: [
                        {field: 'name', as: 'gammaName'}
                    ],
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: true,
                    //bug//emptyText: me.trans('select_gamma'),
                    labelAlign: 'right',
                    width: '90%',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts)
                        {
                            if (!me.is_box_ready) return;
                            if (newValue === oldValue) return;
                            
                            var combo_store = field.getStore();
                            var record = combo_store.findRecord('code', newValue);
                            
                            // Set gamma discount
                            var gamma_discount = me.down('#ecommerce_article_prices_gamma_discount');
                            if (Ext.isEmpty(newValue))
                            {
                                gamma_discount.setValue('');
                            }
                            else
                            {
                                gamma_discount.setValue(record.data.discount);
                            }
                        },
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
                        var gamma_field = form.getForm().findField('gamma');                        
                        gamma_field.setValue('');
                    }
                } 
            ]
        };
        
        return ret;
    },
    
    updateGammaComboField: function(brand_value, clear)
    {
        var me = this;
        //var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(me.config);
        var form = me.getMaintenanceController().getForm(me.config);
        var gamma_field = form.getForm().findField('gamma');
                            
        if (clear || Ext.isEmpty(brand_value))
        {
            gamma_field.clearValue();
            if (Ext.isEmpty(brand_value))
            {
                return;
            }
        }
        
        var gamma_store = gamma_field.getStore();
        gamma_store.on('load', function(this_store, records, successful, eOpts)
        {             
            if(typeof brand_value == 'undefined') return false;
//            gamma_field.forceSelection = true;
//            gamma_field.typeAhead = true;
            var record = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getCurrentRecord(me.config);
            if (record)
            {
                gamma_field.setValue(record.get('gamma'));
            }
        }, this, {single: true});
       
        var filters = [
            //{field: 'available', value: true},
            {field: 'brand', value: brand_value}
        ];
        var json_filters = Ext.JSON.encode(filters);  
        gamma_store.load({
            params: {
                module_id: 'ecommerce',
                model_id: 'gamma',
                stale: 'true',                                            
                filters: json_filters
            }
        });        
    },
    
    getTitlesFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'container',
            anchor: '100%',
            padding: '0 0 10 50',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_article_title_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },         
                {
                    xtype: 'label',
                    text: me.trans('title') + ':',   
                    margin: '5 0 0 0'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'ecommerce_article_tabpanel_title',
                    listeners: {
                        tabchange: function(this_tab, newCard, oldCard, eOpts ) {
                            
                            if (me.change_tab_automatically_when_change_lang)
                            {
                                return;
                            }
                            me.change_tab_automatically_when_change_lang = true;
                            
                            var display_tab = Ext.ComponentQuery.query("#ecommerce_article_tabpanel_display")[0];
                            display_tab.setActiveTab(newCard._tabIndex);
                            
                            var task = new Ext.util.DelayedTask(function(){
                                me.change_tab_automatically_when_change_lang = false;
                            });        
                            task.delay(200);
                        }
                    }
                }
            ]
        };
        
        return ret;
    },
    
    getDisplaysField: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'container',
            anchor: '100%',
            padding: '0 0 10 50',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_article_display_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },         
                {
                    xtype: 'label',
                    text: me.trans('display') + ':',   
                    margin: '5 0 0 0'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'ecommerce_article_tabpanel_display',
                    listeners: {
                        tabchange: function(this_tab, newCard, oldCard, eOpts ) {
                            
                            if (me.change_tab_automatically_when_change_lang)
                            {
                                return;
                            }
                            me.change_tab_automatically_when_change_lang = true;
                            
                            var title_tab = Ext.ComponentQuery.query("#ecommerce_article_tabpanel_title")[0];
                            title_tab.setActiveTab(newCard._tabIndex);
                            
                            var task = new Ext.util.DelayedTask(function(){
                                me.change_tab_automatically_when_change_lang = false;
                            });        
                            task.delay(200);
                        }
                    }
                }
            ]
        };
        
        return ret;
    },
    
    getFamilyComboField: function()
    {      
        var me = this;
                    
        var ret = 
        {
            xtype: 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'combo',
                    name: 'family',
                    fieldLabel: me.trans('family'),
                    _store: {
                        module_id: me.config.module_id,
                        model_id: 'articleFamily',
                        fields: ['code', 'name'],
                        filters: [] //{field: 'available', value: true}                                
                    },
                    _addSubmitValues: [
                        {field: 'name', as: 'familyName'}
                    ],
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: true,  
                    //bug//emptyText: me.trans('select_family'),
                    allowBlank: true,
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
                        var field = form.getForm().findField('family');                        
                        field.setValue('');
                    }
                } 
            ]
        };
        
        return ret;
    },
    
    getArticleCode2GroupDisplaysField: function()
    {      
        var me = this;
       
        var ret = 
        {
            xtype: 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'combo',
                    name: 'articleCode2GroupDisplays',
                    fieldLabel: me.trans('article_to_group_displays'),
                    _store: {
                        module_id: me.config.module_id,
                        model_id: 'article',
                        get_controller: 'modules\\ecommerce\\backend\\controller\\article',
                        fields: ['code', 'name'],
                        filters: [] //{field: 'available', value: true}
                    },
                    _addSubmitValues: [
                        {field: 'name', as: 'articleName2GroupDisplays'}
                    ],
                    valueField: 'code',
                    displayField: 'code',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: false,
                    //bug//emptyText: me.trans('?'),
                    labelAlign: 'right',
                    width: 220,
                    listConfig: {
                        minWidth: 500, // width of the list
                        //maxHeight: 600 // height of a list with scrollbar
                        itemTpl: '{code} - {name}'
                    },
                    listeners: {
                        beforequery: function (record) {
                            record.query = new RegExp(record.query, 'i');
                            record.forceAll = true;
                        }
                    }       
                },
                {
                    xtype: 'button',
                    margin: '15 0 0 5',
                    text: "X",
                    width: 32,
                    handler: function()
                    {
                        var form = me.getMaintenanceController().getForm(me.config);
                        var field = form.getForm().findField('articleCode2GroupDisplays');                        
                        field.setValue('');
                    }
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
                Ext.widget('ecommerce_article_properties_grid'),
                {
                    xtype: 'checkboxfield',
                    name: 'outstanding',
                    fieldLabel: me.trans('outstanding_article'),
                    boxLabel: '(' + me.trans('special_offer') + ')',
                    labelAlign: 'right',                
                    anchor: '100%'        
                },
                {
                    xtype: 'checkboxfield',
                    name: 'christmas',
                    fieldLabel: 'Christmas',
                    boxLabel: '',
                    labelAlign: 'right',                
                    anchor: '100%'        
                },
                {
                    xtype: 'checkboxfield',
                    name: 'novelty',
                    fieldLabel: me.trans('novelty'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    anchor: '100%'        
                },
                {
                    xtype: 'checkboxfield',
                    name: 'pack',
                    fieldLabel: 'Pack',
                    boxLabel: '',
                    labelAlign: 'right',                
                    anchor: '100%'        
                }
            ]
        };     
        
        return ret;
    },
    
    getPricesFieldset: function()
    { 
        var me = this;
        return Ext.widget('ecommerce_article_prices_fieldset', {
            config: me.config
        });
    },
    
    getStockFieldset: function()
    {
        var me = this;
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Stock',
            anchor: '100%',
            items: 
            [ 
                me.getStockTextField(),
                me.getInfinityStockCheckboxField(),
                me.getVisibleIfNoStockStockField()
            ]
        };     
        
        return ret;
    },
    
    getERPFieldset: function()
    {
        var me = this;
        if (Ext.isEmpty(ecommerce_erp_interface_code))
        {
            return;
        }
        
        return Ext.widget('ecommerce_article_erp_' + ecommerce_erp_interface_code + '_fieldset', {
            config: me.config
        });
    },
    
    /*getBotplusFieldset: function()
    {
        var me = this;
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Bot plus',
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'checkboxfield',
                    name: 'botplus',
                    fieldLabel: me.trans('enabled_male'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    anchor: '100%',
                    _defaultValue: true     
                }
            ]
        };     
        
        return ret;
    },*/
    
    getStockTextField: function()
    {      
        var ret = 
        {
            xtype: 'numberfield',
            name: 'stock',
            fieldLabel: 'Stock',
            allowBlank: true,
            labelAlign: 'right',
            //minValue: 0, //prevents negative numbers                            
            width: 180//,
            //_defaultValue: 1
        };
        
        return ret;
    },
    
    getInfinityStockCheckboxField: function()
    {       
        var me = this;
        var ret = 
        {
            xtype: 'checkboxfield',
            name: 'infinityStock',
            fieldLabel: me.trans('infinity_stock'),
            boxLabel: '',
            labelAlign: 'right',                
            anchor: '100%'//,
            //_defaultValue: true  
        };
        
        return ret;
    },
    
    getVisibleIfNoStockStockField: function()
    {      
        var me = this;
        var ret = 
        {
            xtype: 'checkboxfield',
            name: 'visibleIfNoStock',
            fieldLabel: me.trans('visible_if_no_stock'),
            boxLabel: '',
            labelAlign: 'right',                
            anchor: '100%',
            _defaultValue: true  
        };
        
        return ret;
    },
    
    getAvailabilityFieldset: function()
    {
        var me = this;
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('availability'),
            anchor: '100%',
            items: 
            [
                me.getAvailableCheckboxField(),
                me.getAvailavilityDatesRangeFields()
            ]
        };     
        
        return ret;
    },
    
    getAvailableCheckboxField: function()
    {      
        var me = this;
        var ret = 
        {
            xtype: 'checkboxfield',
            name: 'available',
            fieldLabel: me.trans('available'),
            boxLabel: '',
            labelAlign: 'right',                
            anchor: '100%',
            _defaultValue: true // checked when new record        
        };
        
        return ret;
    },
    
    getAvailavilityDatesRangeFields: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            fieldLabel: '',
            combineErrors: false,
            items: 
            [
                {
                    xtype: 'datefield',
                    name: 'startDate',
                    format: app_dateformat,
                    submitFormat: app_dateformat_database,
                    fieldLabel: me.trans('start_date'),
                    labelAlign: 'right',
                    allowBlank: true,
                    width: 240,
                    fieldStyle: {
                        'text-align': 'center'
                    },
                    listeners: 
                    {
                        change: function(thisDateField, newValue, oldValue, options)
                        {
                            if (!me.is_box_ready) return;
                            if (newValue === oldValue) return;
                            var form = me.getMaintenanceController().getForm(me.config);
                            var end_date = form.getForm().findField('endDate');
                            if( (end_date.getEl()) && end_date.getValue() < newValue)
                            {
                                end_date.reset();
                                end_date.setMinValue(newValue);
                                end_date.focus();
                                end_date.getEl().highlight();
                            }
                        }
                  }
                },
                {
                    xtype: 'datefield',
                    name: 'endDate',
                    format: app_dateformat,
                    submitFormat: app_dateformat_database,
                    fieldLabel: me.trans('end_date'),
                    labelAlign: 'right',
                    allowBlank: true,
                    width: 240,
                    fieldStyle: {
                        'text-align': 'center'
                    }
                }
            ]
        };
        
        return ret;
    },
    
    getSEOFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'SEO',
            anchor: '100%',
            items: 
            [   
                me.getCanonicalField(),
                me.getUrlsField(),
                me.getKeywordsField(),
                {
                    xtype: 'textfield',
                    name: 'gtin',
                    maskRe: /[0-9]/,
                    fieldLabel: me.trans('code') + ' GTIN',
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                },
                {
                    xtype: 'checkboxfield',
                    name: 'googleShopping',
                    fieldLabel: 'Google shopping',
                    boxLabel: '',
                    labelAlign: 'right',                
                    anchor: '100%',
                    _defaultValue: true // checked when new record        
                }
            ]
        };
        
        return ret;
    },
    
    getCanonicalField: function()
    {      
        var me = this;
       
        var ret = 
        {
            xtype: 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'combo',
                    name: 'canonical',
                    fieldLabel: 'Canonical',
                    _store: {
                        module_id: me.config.module_id,
                        get_controller: 'modules\\ecommerce\\backend\\controller\\article',
                        model_id: 'article',
                        fields: ['code', 'name'],
                        filters: [] //{field: 'available', value: true}                               
                    },
                    _addSubmitValues: [
                        {field: 'name', as: 'canonicalName'}
                    ],
                    valueField: 'code',
                    displayField: 'code',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: false,
                    //bug//emptyText: me.trans('?'),
                    labelAlign: 'right',
                    width: 220,
                    listConfig: {
                        minWidth: 500, // width of the list
                        //maxHeight: 600 // height of a list with scrollbar
                        itemTpl: '{code} - {name}'
                    },
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
                        var field = form.getForm().findField('canonical');                        
                        field.setValue('');
                    }
                } 
            ]
        };
                    
        return ret;
    },
    
    getUrlsField: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'container',
            padding: '0 0 10 50',
            items:
            [
                {
                    xtype: 'label',
                    itemId: 'ecommerce_article_url_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },        
                {
                    xtype: 'label',
                    text: 'URL' + ':',   
                    margin: '5 0 0 0'
                },
                {
                    xtype: 'container',
                    layout: 'hbox',
                    items:
                    [
                        {
                            xtype: 'tabpanel',   
                            flex: 1,
                            itemId: 'ecommerce_article_tabpanel_url'/*,
                            listeners: {
                                tabchange: function(this_tab, newCard, oldCard, eOpts ) {

                                    if (me.change_tab_automatically_when_change_lang)
                                    {
                                        return;
                                    }
                                    me.change_tab_automatically_when_change_lang = true;

                                    var keywords_tab = Ext.ComponentQuery.query("#ecommerce_article_tabpanel_keywords")[0];
                                    keywords_tab.setActiveTab(newCard._tabIndex);

                                    var task = new Ext.util.DelayedTask(function(){
                                        me.change_tab_automatically_when_change_lang = false;
                                    });        
                                    task.delay(200);
                                }
                            }*/
                        },
                        {
                            xtype: 'button',
                            margin: '20 0 0 5',
                            text: "X",
                            width: 32,
                            handler: function()
                            {
                                var title_tab = Ext.ComponentQuery.query("#ecommerce_article_tabpanel_url")[0];
                                Ext.each(title_tab.items.items, function(item) {
                                    item.setValue("");
                                });
                            }
                        }                 
                    ]
                }     
            ]
        };
        
        return ret;
    },
    
    getKeywordsField: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'container',
            padding: '0 0 10 50',
            items:
            [
                {
                    xtype: 'label',
                    itemId: 'ecommerce_article_keywords_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },        
                {
                    xtype: 'label',
                    text: 'Keywords' + ':',   
                    margin: '5 0 0 0'
                }, 
                {
                    xtype: 'tabpanel',   
                    itemId: 'ecommerce_article_tabpanel_keywords'/*,
                    listeners: {
                        tabchange: function(this_tab, newCard, oldCard, eOpts ) {
                            
                            if (me.change_tab_automatically_when_change_lang)
                            {
                                return;
                            }
                            me.change_tab_automatically_when_change_lang = true;
                            
                            var urls_tab = Ext.ComponentQuery.query("#ecommerce_article_tabpanel_url")[0];
                            urls_tab.setActiveTab(newCard._tabIndex);
                            
                            var task = new Ext.util.DelayedTask(function(){
                                me.change_tab_automatically_when_change_lang = false;
                            });        
                            task.delay(200);
                        }
                    }*/
                }            
            ]
        };
        
        return ret;
    },
    
    getReviewFieldset: function()
    {
        var me = this;
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('review'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'container',
                    layout: 'hbox',
                    items:
                    [
                        {
                            xtype: 'checkboxfield',
                            name: 'validated',
                            fieldLabel: me.trans('validated'),
                            labelWidth: 120,
                            boxLabel: '',
                            labelAlign: 'right',
                            disabled: !is_super_user
                        },       
                        {
                            xtype: 'checkboxfield',
                            name: 'spellcheck',
                            fieldLabel: me.trans('spellcheck'),
                            labelWidth: 120,
                            boxLabel: '',
                            labelAlign: 'right'
                        }/*,
                        {
                            xtype: 'checkboxfield',
                            name: 'checkedByPharmacist',
                            fieldLabel: me.trans('checked_by_pharmacist'),
                            labelWidth: 120,
                            boxLabel: '',
                            labelAlign: 'right'
                        }*/
                    ]
                },
                {
                    xtype: 'datefield',
                    name: 'checkedPackagingDate',
                    format: app_dateformat,
                    submitFormat: app_dateformat_database,
                    fieldLabel: me.trans('checked_packaging'),
                    labelAlign: 'right',
                    allowBlank: true,
                    width: 230,
                    fieldStyle: {
                        'text-align': 'center'
                    }
                }
            ]
        };     
        
        return ret;
    },
    
    getNotesFieldset: function()
    {
        var me = this;
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('notes'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'textareafield',
                    name: 'notes',
                    anchor: '100%',
                    height: 60
                }
            ]
        };     
        
        return ret;
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
        me.createTabContent('display');
        me.createTabContent('title'); 
        me.createTabContent('url'); 
        me.createTabContent('keywords');        
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = me.down('#ecommerce_article_tabpanel_' + type);
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#ecommerce_article_' + type + '_msg_no_available_lang')[0];     
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
                else if (type === 'display')
                {
                    name = 'displays';
                }
                else if (type === 'url')
                {
                    name = 'url' + lang_code[0].toUpperCase() + lang_code.slice(1);
                }
                else
                {
                    name = 'keywords';
                }
                
                var object = {
                    xtype: 'textfield',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _name: name,
                    _lang_code: lang_code,
                    _tabIndex: i,
                    fieldLabel: '',
                    anchor: '100%'                    
                };
                
                if (type === 'url')
                {
                    //console.log(name);
                    object.name = name;
                    object.maskRe = /[a-z0-9\-]/;
                    /*if (!is_super_user)
                    {
                        object.fieldStyle = {
                            'background-color' : 'silver'
                        };
                        object.readOnly = true;                        
                    }*/
                }
                
                tab.add(object); 
                
                i++;
            });                 

            tab.setActiveTab(0);                                            
        }         
    },
            
    onNewRecord: function()
    {
        var view = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getMaintenanceView(this.config);
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        var tab, grid;
        
        if (!Ext.isEmpty(ecommerce_erp_interface_code))
        {
            // Clear grid
            grid = view.down('#ecommerce_article_erp_' + ecommerce_erp_interface_code + '_grid');
            if (!Ext.isEmpty(grid))
            {
                grid.getStore().removeAll();
            }            
        }
        
        // Select the first delegation by default
        if (Ext.isEmpty(ecommerce_only_one_delegation))
        {
            var delegations = form.getForm().findField('delegations');
            var delegations_store = delegations.getStore();
            if (delegations_store.getCount() > 0)
            {
                var first_record = delegations_store.first();
                var delegation_code = first_record.get('code');
                delegations.setValue(delegation_code);
            }              
        }      
        
        // Clean display tab
        tab = view.down('#ecommerce_article_tabpanel_display');
        Ext.each(tab.items.items, function(item) {
            item.setValue('');
        });
        
        // Clean title tab
        tab = view.down('#ecommerce_article_tabpanel_title');
        Ext.each(tab.items.items, function(item) {
            item.setValue('');
        });        
        
        // Clear properties grid
        grid = view.down('#ecommerce_article_properties_grid');
        grid.getStore().removeAll();
        
        // Clean urls tab
        tab = view.down('#ecommerce_article_tabpanel_url');
        Ext.each(tab.items.items, function(item) {
            item.setValue('');
        });
        
        // Clean keywords tab
        tab = view.down('#ecommerce_article_tabpanel_keywords');
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
        var tab, grid;
        
        if (!Ext.isEmpty(ecommerce_erp_interface_code))
        {
            // Set values to grid
            grid = view.down('#ecommerce_article_erp_' + ecommerce_erp_interface_code + '_grid');
            if (!Ext.isEmpty(grid))
            {
                var article_code = id.replace("ecommerce-article-", "");
                grid.getStore().load({
                    params:{
                        article_code: article_code,
                        from_db: true
                    }
                });            
            }
        }         
        
        // Set display tab
        tab = view.down('#ecommerce_article_tabpanel_display');
        Ext.each(tab.items.items, function(item) {
            var value = '';
            if (!Ext.isEmpty(record.data[item._name]) &&
                !Ext.isEmpty(record.data[item._name][item._lang_code]))
            {
                value = record.data[item._name][item._lang_code];
            }
            item.setValue(value);
        });   
        
        // Set title tab
        tab = view.down('#ecommerce_article_tabpanel_title');
        Ext.each(tab.items.items, function(item) {
            var value = '';
            if (!Ext.isEmpty(record.data[item._name]) &&
                !Ext.isEmpty(record.data[item._name][item._lang_code]))
            {
                value = record.data[item._name][item._lang_code];
            }
            item.setValue(value);
        });   

        // Set values to values grid
        grid = view.down('#ecommerce_article_properties_grid');
        var grid_store = grid.getStore();
        grid_store.removeAll();
        var properties = record.get('properties');
        if (!Ext.isEmpty(properties))
        {
            Ext.each(properties, function(item) {
                grid_store.add(item);
            });  
            grid_store.commitChanges();            
        }
        
        // Set urls tab
        tab = view.down('#ecommerce_article_tabpanel_url');
        Ext.each(tab.items.items, function(item) {
            var value = '';
            if (!Ext.isEmpty(record.data[item._name]))
            {
                value = record.data[item._name];
            }
            item.setValue(value);
        }); 
        
        // Set keywords tab
        tab = view.down('#ecommerce_article_tabpanel_keywords');
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
    
    addAdditionalDataMenu: function()
    {
        var me = this;
        var toolbar = me.getMaintenanceController().getGridToolBar(me.config);
        
        var widget = Ext.widget('ecommerce_article_additionaldata', {
            config: me.config
        });
        
        toolbar.add(
            { xtype: 'tbfill' },
            widget.getCategoriesMenu(),
            widget.getDescriptionsMenu(),
            widget
        );  
    }, 
    
    addOthersFeatures: function()
    {
        var me = this;
        var grid = me.getViewController().getGrid(me.config);
        grid.on('onItemClicked', function(record)
        {
            if (!record.get('validated') && record.get('cloned'))
            {
                Ext.MessageBox.show({
                   title: me.trans('warning'),
                   msg: me.trans('cloned_article_warning_1'),
                   buttons: Ext.MessageBox.OK,
                   icon: Ext.MessageBox.WARNING
                });       
            }
            
        }); //, this, {single: true});
        
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