Ext.define('App.modules.reporting.backend.UI.view.botplusMedicines.filterForm', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.reporting_botplus_medicines_filterform',
    itemId: 'reporting_botplus_medicines_filterform',
    
    region: 'north',
    border: false,
    frame: false,
    autoWidth: true,
    bodyPadding: 10,
    anchor: '100%',
    height: '100%',
                
    config: null,
    initialized: false,
    keywords_1_initialized: false,
    keywords_2_initialized: false,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = ''; 

        this.items = 
        [    
            {
                xtype: 'combo',
                name: 'authorized',
                fieldLabel: 'Autoritzats',
                labelAlign: 'right',
                store: Ext.create('Ext.data.Store', {
                    fields: ['code', 'name'],
                    data : 
                    [
                        {"code": "yes", "name": "Si"},
                        {"code": "no", "name": "No"},
                        {"code": "all", "name": "Tots"}
                    ]
                }),
                queryMode: 'local',
                displayField: 'name',
                valueField: 'code',
                width: 200,
                value: 'yes'
            },/*
            {
                xtype: 'radio',
                name: 'option',
                fieldLabel: '',
                boxLabel: 'Tots',
                margin: '0 0 0 30',
                inputValue: 'all',
                checked: true
            }*/
            {
                xtype: 'fieldset',
                padding: 5,
                title: 'Que continguin les següents KEYWORDS...',
                anchor: '100%',
                items: 
                [
                    {
                        xtype: 'textarea',
                        name: 'keywords',
                        fieldLabel: '',
                        allowBlank: true,
                        labelAlign: 'right',
                        height: 250,
                        anchor: '100%',
                        listeners: {
                            render: function(field)
                            {
                                me.getKeywords('keywords', field);
                            }                            
                        }
                    },
                    {
                         xtype: 'container',
                         layout: {
                             type: 'hbox',
                             pack: 'center',
                             align: 'stretch'
                         },                                        
                         width: '100%',          
                         items:
                         [
                            {
                                xtype: 'button',
                                text: 'Desar',
                                width: 60,
                                handler: function() 
                                {
                                    me.saveKeywords('keywords');
                                }
                            }
                         ]
                     }    
                ]
            },
            {
                xtype: 'fieldset',
                padding: 5,
                title: 'Que <b>NO</b> continguin les següents KEYWORDS...',
                anchor: '100%',
                items: 
                [
                    {
                        xtype: 'textarea',
                        name: 'nokeywords',
                        fieldLabel: '',
                        allowBlank: true,
                        labelAlign: 'right',
                        height: 150,
                        anchor: '100%',
                        listeners: {
                            render: function(field)
                            {
                                me.getKeywords('nokeywords', field); 
                            }                            
                        }
                    },
                    {
                         xtype: 'container',
                         layout: {
                             type: 'hbox',
                             pack: 'center',
                             align: 'stretch'
                         },                                        
                         width: '100%',          
                         items:
                         [
                            {
                                xtype: 'button',
                                text: 'Desar',
                                width: 60,
                                handler: function() 
                                {
                                    me.saveKeywords('nokeywords');
                                }
                            }                                       
                         ]
                     }    
                ]
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
    
    getKeywords: function(key, field)
    {
        var me = this;
        
        Ext.Ajax.request(
        {
            type: 'ajax',
            url : 'index.php',
            method: 'GET',
            params: {
                controller: 'modules\\reporting\\backend\\controller\\botplusMedicines', 
                method: 'getKeywords',
                key: key
            },
            success: function(response, opts)
            {
                var value = response.responseText;
                field.setValue(value);
                
                if (key === 'keywords')
                {
                    me.keywords_1_initialized = true;
                }
                else
                {
                    me.keywords_2_initialized = true;
                }
                
                if (me.keywords_1_initialized && me.keywords_2_initialized)
                {
                    me.getViewController().refreshGrid(me.config);                    
                }
            }
        });          
    },
    
    saveKeywords: function(key)
    {
        var me = this;
        var keywords_field = me.getForm().findField(key);
        var keywords_value = keywords_field.getValue();

        Ext.Ajax.request(
        {
            type: 'ajax',
            url : 'index.php',
            method: 'GET',
            params: {
                controller: 'modules\\reporting\\backend\\controller\\botplusMedicines', 
                method: 'saveKeywords',
                key: key,
                keywords: keywords_value
            },
            success: function(response, opts)
            {
                Ext.MessageBox.show({
                   title: 'KEYWORDS desades',
                   msg: 'Les KEYWORDS han estat desades correctament',
                   buttons: Ext.MessageBox.OK,
                   icon: Ext.MessageBox.OK
                });                                            
            }
        }); 
    },

    onRender: function(form, eOpts)
    {
//        var me = this;
//        me.getViewController().refreshGrid(me.config);
        
        this.callParent(arguments);
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.reporting.backend.UI.controller.botplusMedicines').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    ecommerceTrans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.reporting.backend.UI.controller.botplusMedicines');       
        return controller;
    }
    
});