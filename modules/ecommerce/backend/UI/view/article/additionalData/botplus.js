Ext.define('App.modules.ecommerce.backend.UI.view.article.additionalData.botplus', {
    
    alias: 'widget.ecommerce_article_additionaldata_botplus',
    explotation: 'Bot plus data',
    config: null,
    
    getForm: function(config, record)
    {    
        var me = this;
        me.config = config;
        //console.log(config);
        
        var items = [];
        
        //console.log(record);
        if (!Ext.isEmpty(record))
        {
            var maindata = record.get('maindata');
            var epigraphs = record.get('epigraphs');
            var messages = record.get('messages');

            if (!Ext.isEmpty(maindata))
            {
                items.push({
                    title: me.trans('main'),
                    padding: 5,
                    items: me.getMainTab(maindata)
                });            
            }

            items.push({
                title: 'Epigraphs',
                padding: 5,
                items: me.getEpigraphsTab(epigraphs)
            });  

            if (!Ext.isEmpty(messages))
            {
                items.push({
                    title: 'Messages',
                    padding: 5,
                    items: me.getMessagesTab(messages)
                });
            } 
        }
            
        var ret =       
        {
            title: 'Bot plus',
            width: 700,
            height: 700,
            fields:
            [
                {
                    xtype: 'tabpanel',
                    activeTab: 0,
                    items: items
                }        
            ]/*,
            listeners: {
                render: function(this_panel, eOpts)
                {
                    // Hide toolbar
                    var view = me.getModalFormMaintenanceController().getWindowView(me.config);
                    var toolbar = view.down('toolbar');
                    toolbar.setVisible(false);
                }
            }*/
        };
        
        return ret;
    },
    
    getMainTab: function(maindata)
    {
        var ret =  
        {
            xtype: 'panel',
            html: maindata
        };
        
        return ret;
    },
    
    getMessagesTab: function(messages)
    {
        var ret =  
        {
            xtype: 'panel',
            html: messages
        };
        
        return ret;
    },
    
    getEpigraphsTab: function(epigraphs)
    {
        var me = this;
        
        var module_id = me.config.module_id;
        
        var ret =  
        {
            xtype: 'panel',
            itemId: module_id + '_article_additionaldata_botplus_epigraphs_form',
            layout: 'fit',
            width: '100%',
            height: '100%',
            items:
            [             
                {
                    xtype: 'panel',
                    defaults: {
                        // applied to each contained panel
                        bodyStyle: 'padding:15px'
                    },
                    layout: {
                        // layout-specific configs go here
                        type: 'accordion',
                        titleCollapse: true,
                        //multi: true,
                        animate: true
                    },
                    width: '100%',
                    height: '100%',
                    items: 
                    [
                        {
                            // The first accordion or item must exist and being hidden (sencha bug)
                            hidden: true
                        }
                    ],
                    listeners: {
                        render: function(this_panel, eOpts)
                        {
                            var baseTabItemId = module_id + '_article_additionaldata_botplus_tabpanel';                     

                            // Add accordions
                            for (var key in epigraphs) {
                                var epigraph = epigraphs[key];
                                var texts = epigraph['es'];
                                //console.log(key);
                                //console.log(epigraph);
                                //console.log(texts);

                                var nameTabItemId = me.getModalFormMaintenanceController().convert2URLText('name-' + baseTabItemId + '_' + key);
                                var textTabItemId = me.getModalFormMaintenanceController().convert2URLText('text-' + baseTabItemId + '_' + key);

                                this_panel.add({
                                    title: texts.name,
                                    collapsed: true,
                                    //layout: 'fit',
                                    items: 
                                    [
                                        {
                                            xtype: 'checkboxfield',
                                            name: key + '-enabled',
                                            fieldLabel: me.trans('available'),
                                            boxLabel: '',
                                            labelAlign: 'right',                
                                            anchor: '100%',
                                            checked: epigraph.enabled
                                        },
                                        
                                        {   
                                            xtype: 'tabpanel',
                                            itemId: nameTabItemId,
                                            _key: key,
                                            _epigraph: epigraph,
                                            listeners: {
                                                render: function(this_tab, eOpts) {
                                                    me.createTabContent(this_tab, 'name');
                                                }
                                            }
                                        },
                                        me.getModalFormMaintenanceController().getActionMenuButtonForHtmlManagement(nameTabItemId),
                                        
                                        {   
                                            xtype: 'tabpanel',
                                            itemId: textTabItemId,
                                            _key: key,
                                            _epigraph: epigraph,
                                            listeners: {
                                                render: function(this_tab, eOpts) {
                                                    me.createTabContent(this_tab, 'text');
                                                }
                                            }
                                        },
                                        me.getModalFormMaintenanceController().getActionMenuButtonForHtmlManagement(textTabItemId)
                                    ]
                                });
                            };
                        }
                    }
                }                
            ]
        };
        
        return ret;
    },
    
    createTabContent: function(tab, type)
    {
        var me = this;
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        var key, epigraph, name, value, lang_code;
                
        key = tab._key;
        epigraph = tab._epigraph;

        var i = 0;
        Ext.each(langs, function(lang) 
        {
            lang_code = lang.code; 

            value = '';
            if (!Ext.isEmpty(epigraph[lang_code]))
            {
                name = epigraph[lang_code].name;
                value = epigraph[lang_code].text;
            }
            else
            {
                name = epigraph['es'].name;
            }

            if (type === 'name')
            {
                tab.add({
                    
                    xtype: 'textfield',
                    title: lang.name,
                    name: key + '-' + type + '-' + lang_code,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%',
                    value: name                                       
                }); 
            }
            else
            {
                tab.add({
                    xtype: 'htmleditor',
                    title: lang.name,
                    name: key + '-' + type + '-' + lang_code,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%',
                    height: 300,
                    autoScroll: true,
                    enableFont: false,
                    value: value                                         
                }); 
            }    

            i++;
        }); 

        tab.setActiveTab(0);    
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }


});