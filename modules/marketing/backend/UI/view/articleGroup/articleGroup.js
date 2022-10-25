Ext.define('App.modules.marketing.backend.UI.view.articleGroup.articleGroup', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.marketing_articleGroup',
        
    explotation: 'Marketing articleGroup view',

    config: null,
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        this.config.save_controller = 'modules\\marketing\\backend\\controller\\articleGroup';
        
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
            title: me.trans('article_group_view'),
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
                me.getGroupByFieldset()
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
                }
            ]
        };
        
        return ret;
    },
    
    getGroupByFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('group_by'),
            anchor: '100%',
            items: 
            [       
                {
                    xtype: 'container',
                    padding: '20 20 0 20',
                    items:
                    [    
                        {
                            xtype: 'label',
                            html: '<b>' + me.trans('article_types') + '</b>:' 
                        },
                        Ext.widget('marketing_articleGroup_articleTypes_grid', {
                            margin: '10 0 0 0'
                        })    
                    ]
                },      
                {
                    xtype: 'container',
                    padding: '20 20 0 20',
                    items:
                    [    
                        {
                            xtype: 'label',
                            html: '<b>' + me.trans('brands') + '</b>:' 
                        },
                        Ext.widget('marketing_articleGroup_brands_grid', {
                            margin: '10 0 0 0'
                        })    
                    ]
                },       
                {
                    xtype: 'container',
                    padding: '20 20 0 20',
                    items:
                    [    
                        {
                            xtype: 'label',
                            html: '<b>' + me.trans('gammas') + '</b>:' 
                        },
                        Ext.widget('marketing_articleGroup_gammas_grid', {
                            margin: '10 0 0 0'
                        })    
                    ]
                },       
                {
                    xtype: 'container',
                    padding: '20 20 0 20',
                    items:
                    [    
                        {
                            xtype: 'label',
                            html: '<b>' + me.trans('articles') + '</b>:'
                        },
                        Ext.widget('marketing_articleGroup_articles_grid', {
                            margin: '10 0 0 0'
                        })    
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
                }
            ]
        };
    },
    
    addOthersFeatures: function()
    {
        
    },
            
    onNewRecord: function()
    {
        var view = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getMaintenanceView(this.config);
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        var grid;
        
        // Article types
        grid = view.down('#marketing_articleGroup_articleTypes_grid');
        grid.getStore().removeAll();
        
        // Brands
        grid = view.down('#marketing_articleGroup_brands_grid');
        grid.getStore().removeAll();
        
        // Gammas
        grid = view.down('#marketing_articleGroup_gammas_grid');
        grid.getStore().removeAll();
        
        // Articles
        grid = view.down('#marketing_articleGroup_articles_grid');
        grid.getStore().removeAll();
       
        // Finally.. clear form
        //extjs6 form.clearDirty();
    },
            
    onEditedRecord: function(id)
    {
        var maintenance_controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');
        var view = maintenance_controller.getMaintenanceView(this.config);
        var form = maintenance_controller.getForm(this.config);
        var record = maintenance_controller.getCurrentRecord(this.config);
        var grid;

        // Article types
        grid = view.down('#marketing_articleGroup_articleTypes_grid');
        var grid_store = grid.getStore();
        grid_store.removeAll();
        var articles = record.get('articleTypes');
        if (!Ext.isEmpty(articles))
        {
            Ext.each(articles, function(item) {
                grid_store.add(item);
            });  
            grid_store.commitChanges();            
        }

        // Brands
        grid = view.down('#marketing_articleGroup_brands_grid');
        var grid_store = grid.getStore();
        grid_store.removeAll();
        var articles = record.get('brands');
        if (!Ext.isEmpty(articles))
        {
            Ext.each(articles, function(item) {
                grid_store.add(item);
            });  
            grid_store.commitChanges();            
        }

        // Gammas
        grid = view.down('#marketing_articleGroup_gammas_grid');
        var grid_store = grid.getStore();
        grid_store.removeAll();
        var articles = record.get('gammas');
        if (!Ext.isEmpty(articles))
        {
            Ext.each(articles, function(item) {
                grid_store.add(item);
            });  
            grid_store.commitChanges();            
        }

        // Articles
        grid = view.down('#marketing_articleGroup_articles_grid');
        var grid_store = grid.getStore();
        grid_store.removeAll();
        var articles = record.get('articles');
        if (!Ext.isEmpty(articles))
        {
            Ext.each(articles, function(item) {
                grid_store.add(item);
            });  
            grid_store.commitChanges();            
        }
       
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