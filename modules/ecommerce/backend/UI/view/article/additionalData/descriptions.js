Ext.define('App.modules.ecommerce.backend.UI.view.article.additionalData.descriptions', {
    
    alias: 'widget.ecommerce_article_additionaldata_descriptions',
    explotation: 'Descriptions for E-Commerce article (Additional data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        
        var ret =       
        {
            title: me.trans('descriptions'),
            width: 1000,
            height: 800,
            fields:
            [
                {
                    xtype: 'tabpanel',
                    activeTab: 0,
                    items: 
                    [
                        me.getMainTab(),
                        me.getSecondTab(),
                        me.getThirdTab()
                    ]
                }        
            ]          
        };
        
        return ret;
    }, 
    
    getMainTab: function()
    {
        var me = this;
        
        return {
            title: me.trans('main'),
            padding: 5,
            items:
            [
                me.getShortDescriptionsFieldset(),
                me.getMetaDescriptionsFieldset(),
                me.getDescriptionsFieldset()        
            ]
        };
    },
    
    getSecondTab: function()
    {
        var me = this;
        
        return {
            title: me.trans('application_and_composition'),
            padding: 5,
            items:
            [
                me.getApplicationsFieldset(),
                me.getCompositionsFieldset() 
            ]
        };
    },
    
    getThirdTab: function()
    {
        var me = this;
        
        return {
            title: me.trans('medicines'),
            padding: 5,
            items:
            [
                me.getActiveIngredientsFieldset(),
                me.getProspectsFieldset(),
                me.getDataSheetsFieldset()     
            ]
        };
    },
    
    getShortDescriptionsFieldset:  function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_article_additionaldata_descriptions_tabpanel_short_description'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('short_description'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_short_description_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',    
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_short_description_rendered = true;
                            me.createMainTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret;    
    }, 
    
    getMetaDescriptionsFieldset:  function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_article_additionaldata_descriptions_tabpanel_meta_description'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('meta_description'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_meta_description_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',    
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_meta_description_rendered = true;
                            me.createMainTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret;    
    }, 
    
    getDescriptionsFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_article_additionaldata_descriptions_tabpanel_description'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('description'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_description_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_description_rendered = true;
                            me.createMainTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret; 
        
    },
    
    getApplicationsFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_article_additionaldata_descriptions_tabpanel_application'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('application'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_application_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_application_rendered = true;
                            me.createSecondTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret; 
        
    }, 
    
    getCompositionsFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_article_additionaldata_descriptions_tabpanel_composition';
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('composition'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_composition_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_composition_rendered = true;
                            me.createSecondTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret; 
        
    }, 
    
    getActiveIngredientsFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_article_additionaldata_descriptions_tabpanel_active_ingredient';
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('active_ingredients'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_active_ingredient_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_active_ingredient_rendered = true;
                            me.createThirdTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret; 
        
    }, 
    
    getProspectsFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('prospect'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_prospect_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: module_id + '_article_additionaldata_descriptions_tabpanel_prospect',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_prospect_rendered = true;
                            me.createThirdTabsContent();
                        }
                    }
                }
            ]
        };      
        
        return ret; 
        
    },
    
    getDataSheetsFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('datasheet'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: module_id + '_article_additionaldata_descriptions_datasheet_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: module_id + '_article_additionaldata_descriptions_tabpanel_datasheet',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_datasheet_rendered = true;
                            me.createThirdTabsContent();
                        }
                    }
                }
            ]
        };      
        
        return ret; 
        
    },
    
    createMainTabsContent: function()
    {
        var me = this;
        
        if (!me.canWeCreateMainTabs())
        {
            return;
        }

        me.createMainTabs();
    },
    
    createSecondTabsContent: function()
    {
        var me = this;
        
        if (!me.canWeCreateSecondTabs())
        {
            return;
        }

        me.createSecondTabs();
    },
    
    createThirdTabsContent: function()
    {
        var me = this;
        
        if (!me.canWeCreateThirdTabs())
        {
            return;
        }

        me.createThirdTabs();
    },
    
    canWeCreateMainTabs: function()
    {
        var me = this;
        return (me.tabpanel_short_description_rendered && 
                me.tabpanel_meta_description_rendered && 
                me.tabpanel_description_rendered);
    },
    
    canWeCreateSecondTabs: function()
    {
        var me = this;
        return (me.tabpanel_application_rendered && 
                me.tabpanel_composition_rendered);
    },
    
    canWeCreateThirdTabs: function()
    {
        var me = this;
        return (me.tabpanel_active_ingredient_rendered && 
                me.tabpanel_prospect_rendered && 
                me.tabpanel_datasheet_rendered);
    },
    
    createMainTabs: function()
    {
        var me = this;
        
        if (me.tabpanel_short_description_rendered)
        {
            me.createTabContent(me.config, 'short_description');
            me.tabpanel_short_description_rendered = false;
        }
        if (me.tabpanel_meta_description_rendered)
        {
            me.createTabContent(me.config, 'meta_description');
            me.tabpanel_meta_description_rendered = false;
        }
        if (me.tabpanel_description_rendered)
        {
            me.createTabContent(me.config, 'description');
            me.tabpanel_description_rendered = false;
        }
    },
    
    createSecondTabs: function()
    {
        var me = this;
        
        if (me.tabpanel_application_rendered)
        {
            me.createTabContent(me.config, 'application');
            me.tabpanel_application_rendered = false;
        }
        if (me.tabpanel_composition_rendered)
        {
            me.createTabContent(me.config, 'composition');
            me.tabpanel_composition_rendered = false;
        }        
    },
    
    createThirdTabs: function()
    {
        var me = this;
        
        if (me.tabpanel_active_ingredient_rendered)
        {
            me.createTabContent(me.config, 'active_ingredient');
            me.tabpanel_active_ingredient_rendered = false;
        }
        if (me.tabpanel_prospect_rendered)
        {
            me.createTabContent(me.config, 'prospect');
            me.tabpanel_prospect_rendered = false;
        }
        if (me.tabpanel_datasheet_rendered)
        {
            me.createTabContent(me.config, 'datasheet');
            me.tabpanel_datasheet_rendered = false;
        }
    },
    
    createTabContent: function(config, type)
    {
        var me = this;
        var module_id = config.module_id;
        var tab = Ext.ComponentQuery.query('#' + module_id + '_article_additionaldata_descriptions_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#' + module_id + '_article_additionaldata_descriptions_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            var form = me.getModalFormMaintenanceController().getFormView(config);
            var is_new_record = form.is_new_record;
            if (!is_new_record)
            {
                var record = form.getRecord();
            }
            
            var i = 0;
            Ext.each(langs, function(lang) {
                me.addCommonTabs(config, tab, type, is_new_record, record, lang.code, lang.name, i);
                me.addSpecificTabs(config, tab, type, is_new_record, record, lang.code, lang.name, i);
                i++;
            }); 
        
            tab.setActiveTab(0);
        }         
    },
    
    addCommonTabs: function(config, tab, type, is_new_record, record, lang_code, lang_name, i)
    {
        var value = '', name;
        var height;
        
        if (type === 'description')
        {
            name = 'descriptions';
            height =  300;            
            if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang_code]))
            {
                value = record.data[name][lang_code];
            }
            tab.add({
                    xtype: 'htmleditor',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%',
                    height: height,
                    autoScroll: true,
                    enableFont: false,
                    value: value
            });             
        }
    },
    
    addSpecificTabs: function(config, tab, type, is_new_record, record, lang_code, lang_name, i)
    {
        var value = '', name;
      
        if (type === 'short_description' || type === 'application' || type === 'active_ingredient' || type === 'composition')
        {
            var height;
            if (type === 'short_description')
            {
                name = 'shortDescriptions';
                height =  100;
            }
            else if (type === 'application')
            {
                name = 'applications';
                height =  200;
            }
            else if (type === 'active_ingredient')
            {
                name = 'activeIngredients';
                height =  100;
            }
            else
            {
                name = 'compositions';
                height =  200;
            }

            if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang_code]))
            {
                value = record.data[name][lang_code];
            }
            tab.add({
                    xtype: 'htmleditor',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%',
                    height: height,
                    autoScroll: true,
                    enableFont: false,
                    value: value
            });
        }
        else if (type === 'meta_description' || type === 'prospect' || type === 'datasheet')
        {
            if (type === 'meta_description')
            {
                name = 'metaDescriptions';
            }
            else if (type === 'prospect')
            {
                name = 'prospects';
            }
            else
            {
                name = 'dataSheets';
            }
            if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang_code]))
            {
                value = record.data[name][lang_code];
            }
            tab.add({
                    xtype: 'textfield',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%',
                    value: value
            });      
        }  
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.article');       
        return controller;
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }

});