Ext.define('App.modules.reporting.backend.UI.view.farmaticArticles.farmaticArticles', {
    extend: 'Ext.panel.Panel',
    
    alias: 'widget.reporting_farmaticArticles',
        
    layout: 'border',
    
    border: false,
    frame: false,
    title: '',

    config: {},
    
    initComponent: function() {
        this.alert();
        
        var me = this;
        
        // General properties
        this.initGeneralProperties();
        // The grid
        this.initGrid();
        // The form
        //this.initForm();
        // The dynamic filter form
        this.initDynamicFilterForm();
        
        // Create the getRecords Store (main store of maintenance)
        me.config.store = me.getGetRecordsStore();
        
        var items = 
        [
            Ext.widget('reporting_farmatic_articles_gridpanel', {
                config: me.config
            }),
            {
                xtype: 'panel',
                region: 'west',
                layout: 'border',
                width: 300,
                height: '100%',
                split: true,
                collapsible: true,
                title: me.trans('filters'),
                items: 
                [
                    Ext.widget('reporting_farmatic_articles_filterform', {
                        config: me.config
                    }),           
                    Ext.widget('reporting_farmatic_articles_dynamicfilterform', {
                        config: me.config
                    })
                ]        
            }
        ];
            
        me.items = items;
        
        me.callParent(arguments);
        
        me.on('boxready', this.onBoxready, this);
    },
    
    onBoxready: function(this_panel, width, height, eOpts)
    {
        var me = this;
        
//        var task = new Ext.util.DelayedTask(function(){
//            
//        });        
//        task.delay(500);        
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_clone = false;
        this.config.enable_deletion = false;
        this.config.get_controller = 'modules\\reporting\\backend\\controller\\farmaticArticles';
    },
    
    getGetRecordsStore: function()
    {
        var me = this;
        return me.getViewController().getGetRecordsStore(me.config, false, false, false); 
    },
            
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: me.trans('farmaticArticles_view'),
            features: me.getGridFeatures(),
            plugins: 'bufferedrenderer',
            groupField: me.getGroupFieldGrid(),
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    width: 100
                },
                {
                    text: me.trans('name'),
                    dataIndex: 'name',
                    //flex: 1,
                    width: 400
                },
                {
                    text: me.ecommerceTrans('brand'),
                    dataIndex: 'brandName',
                    width: 150,
                    align: 'center'
                },
                /*{
                    text: 'Efp?',
                    dataIndex: 'efp',
                    width: 100
                },*/
                {
                    text: 'Pvp',
                    dataIndex: 'pvp',
                    width: 100,
                    align: 'center'
                },
                {
                    text: 'En db?',
                    dataIndex: 'inDb',
                    width: 100
                },
                /*{
                    text: 'Stock?',
                    dataIndex: 'anyStock',
                    width: 100
                },*/
                {
                    text: 'Stock',
                    dataIndex: 'stock',
                    width: 100,
                    align: 'center'
                },
                {
                    text: 'Stock en db',
                    dataIndex: 'stockInDb',
                    width: 100,
                    align: 'center'
                },                
                {
                    text: me.ecommerceTrans('for_sale'),
                    dataIndex: 'forSale',
                    width: 100
                },
                {
                    text: me.ecommerceTrans('for_sale_and_visible'),
                    dataIndex: 'forSaleAndVisible',
                    width: 100
                },
                {
                    text: me.ecommerceTrans('article_type'),
                    dataIndex: 'articleType',
                    width: 150,
                    align: 'center',
                    renderer: function(value, meta, record) {
                        if (Ext.isEmpty(value)) return '';
                        return value + ' (' + record.get('articleTypeName') + ')';
                    }
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
            
    initDynamicFilterForm: function()
    {
        var me = this;
        
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
                    name: 'inDb',
                    fieldLabel: 'En db',
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
                    xtype: 'numberfield',
                    name: 'stock',
                    fieldLabel: 'Stock',
                    labelAlign: 'right',
                    minValue: 1, //prevents negative numbers                            
                    decimalPrecision: 0,
                    width: 180,
                    _filtertype: 'integer'
                },  
                {
                    xtype: 'numberfield',
                    name: 'stockInDb',
                    fieldLabel: 'Stock en db',
                    labelAlign: 'right',
                    minValue: 1, //prevents negative numbers                            
                    decimalPrecision: 0,
                    width: 180,
                    _filtertype: 'integer'
                },               
                {
                    xtype: 'combo',
                    name: 'forSale',
                    fieldLabel: me.ecommerceTrans('for_sale'),
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
                    fieldLabel: me.ecommerceTrans('for_sale_and_visible'),
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
                }
            ]
        };
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.reporting.backend.UI.controller.reporting').getLangStore();
        return App.app.trans(id, lang_store);
    },
    
    ecommerceTrans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.reporting.backend.UI.controller.reporting').alertInitMaintenance(this.config);              
    },
            
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.reporting.backend.UI.controller.farmaticArticles');       
        return controller;
    },
        
    getDynamicFilterController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1DynamicFilterForm');
        return controller;
    }
});