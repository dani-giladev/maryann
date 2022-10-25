Ext.define('App.modules.ecommerce.backend.UI.view.brand.additionalData.descriptions', {
    
    alias: 'widget.ecommerce_brand_additionaldata_descriptions',
    explotation: 'Descriptions for ecommerce brand (Additional data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: me.trans('descriptions'),
            width: 700,
            height: 420,
            fields:
            [
                //me.getTitlesFieldset(),
                me.getDescriptionsFieldset()
            ]          
        };
        
        return ret;
    },
    
    getTitlesFieldset:  function()
    {
        var me = this;
        var module_id = me.config.module_id;
        
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
                    itemId: module_id + '_brand_additionaldata_descriptions_title_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: module_id + '_brand_additionaldata_descriptions_tabpanel_title',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_title_rendered = true;
                            me.createTabsContent();
                        }
                    }
                }
            ]
        };      
        
        return ret;       
    },
    
    getDescriptionsFieldset: function()
    {
        var me = this;
        var module_id = me.config.module_id;
        var tabItemId = module_id + '_brand_additionaldata_descriptions_tabpanel_description'; 
        
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
                    itemId: module_id + '_brand_additionaldata_descriptions_description_msg_no_available_lang',
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
                            me.createTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret; 
        
    },
    
    createTabsContent: function()
    {
        var me = this;
        
        if (!me.canWeCreateTabs())
        {
            return;
        }
        
        /*
        if (me.tabpanel_title_rendered)
        {
            me.createTabContent(me.config, 'title');
            me.tabpanel_title_rendered = false;
        }
        */
       
        if (me.tabpanel_description_rendered)
        {
            me.createTabContent(me.config, 'description');
            me.tabpanel_description_rendered = false;
        }  
                            
    },
    
    canWeCreateTabs: function()
    {
        var me = this;
        return (//me.tabpanel_title_rendered && 
                me.tabpanel_description_rendered);
    },
    
    createTabContent: function(config, type)
    {
        var me = this;
        var module_id = config.module_id;
        var lang_code, lang_name, i;
        var tab = Ext.ComponentQuery.query('#' + module_id + '_brand_additionaldata_descriptions_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#' + module_id + '_brand_additionaldata_descriptions_' + type + '_msg_no_available_lang')[0];     
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
            
            var value, name;
            i = 0;
            Ext.each(langs, function(lang) {
                lang_code = lang.code;
                lang_name = lang.name; 
                value = '';
                
                if (type === 'title')
                {
                    name = 'titles';
                    if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang_code]))
                    {
                        value = record.data[name][lang_code];
                    }
                    tab.add({
                            xtype: 'textfield',
                            title: lang_name,
                            name: name + '-' + lang_code,
                            _lang_code: lang_code,
                            _tabIndex: i,
                            fieldLabel: '',
                            anchor: '100%',
                            value: value
                    });                      
                }
                else
                {
                    name = 'descriptions';
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
                            height: 200,
                            autoScroll: true,
                            enableFont: false,
                            value: value
                    });
                }
                
                i++;
            });

            tab.setActiveTab(0);
        }         
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
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce');       
        return controller;
    }

});