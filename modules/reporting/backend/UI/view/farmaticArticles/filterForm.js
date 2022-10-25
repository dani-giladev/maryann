Ext.define('App.modules.reporting.backend.UI.view.farmaticArticles.filterForm', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.reporting_farmatic_articles_filterform',
    itemId: 'reporting_farmatic_articles_filterform',
    
    region: 'north',
    border: false,
    frame: false,
    autoWidth: true,
    bodyPadding: 10,
    anchor: '100%',
    
    config: null,
    initialized: false,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = ''; 
        
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
        
        // Set default value after loading combo
        var article_type_store = me.getViewController().getGetRecordsStore(article_type_config, true, false, 'true');
        article_type_store.on('load', function(this_store, records, successful, eOpts){
            var article_type_field = me.getForm().findField('articleType');                        
            article_type_field.setValue('1');
        });

        this.items = 
        [    
            {
                xtype: 'combo',
                name: 'articleType',
                fieldLabel: me.ecommerceTrans('article_type'),
                labelAlign: 'right',
                store: me.getViewController().getGetRecordsStore(article_type_config, true, false, 'true'),
                queryMode: 'local',
                displayField: 'name',
                valueField: 'code',
                anchor: '100%',
                allowBlank: false,
                listConfig:{
                    minWidth: 300 // width of the list
                    //maxHeight: 400 // height of a list with scrollbar
                },
                listeners: {
                    change: function(field, newValue, oldValue, eOpts)
                    {
                        if (newValue == oldValue) return;
                        if (!me.initialized) me.checkRefreshGrid();
                    }
                }
            },
            {
                xtype: 'textfield',
                name: 'name',
                fieldLabel: me.ecommerceTrans('name'),
                labelAlign: 'right',
                anchor: '100%',
                value: 'APIVITA',
                listeners: {
                    change: function(field, newValue, oldValue, eOpts)
                    {
                        if (newValue == oldValue) return;
                        if (!me.initialized) me.checkRefreshGrid();
                    }
                }
            },
            {
                xtype: 'numberfield',
                name: 'stock',
                fieldLabel: 'Stock',
                labelSeparator: ' >',
                labelAlign: 'right',
                minValue: 0, //prevents negative numbers                            
                decimalPrecision: 0,
                width: 180,
                value: 0,
                listeners: {
                    change: function(field, newValue, oldValue, eOpts)
                    {
                        if (newValue == oldValue) return;
                        if (!me.initialized) me.checkRefreshGrid();
                    }
                }
            }
        ];
        
        me.callParent(arguments);
        
        // Add listeners
        me.addListeners();        
    },
    
    addListeners: function()
    {   
        var me = this;
        
        // Add custom listeners
        me.getViewController().addListeners(me);
        // Update several properties
        me.getViewController().updateFormProperties(me);
        // set combos stores dinamically
        me.getViewController().setComboStores(me);            
    },
    
    onRender: function(form, eOpts)
    {
        
        this.callParent(arguments);
    },
    
    checkRefreshGrid: function()
    {
        var me = this;
        
        var formValues = me.getValues();
            
        var all_fields_with_value = true;
        for (var key in formValues) {
            var value = formValues[key];
            if (value.length == 0)
            {
                all_fields_with_value = false;
            }
        }
        
        if (all_fields_with_value)
        {
            me.initialized = true;
            me.getViewController().refreshGrid(me.config);
        }
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.reporting.backend.UI.controller.farmaticArticles').getLangStore();
        return App.app.trans(id, lang_store);
    },
    
    ecommerceTrans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.reporting.backend.UI.controller.farmaticArticles');       
        return controller;
    }
    
});