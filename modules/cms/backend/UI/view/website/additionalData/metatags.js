Ext.define('App.modules.cms.backend.UI.view.website.additionalData.metatags', {
    
    alias: 'widget.cms_website_additionaldata_metatags',
    explotation: 'Metatags for website (Additional data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: 'Metatags',
            width: 600,
            height: 480,
            fields:
            [
                me.getTitlesFieldset(),
                me.getDescriptionsFieldset(),
                me.getKeywordsFieldset(),
                me.getRobotsFieldset()
            ]        
        };
        
        return ret;
    },      
    
    getTitlesFieldset:  function()
    {
        var me = this;
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
                    itemId: 'cms_website_additionaldata_metatags_title_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'cms_website_additionaldata_metatags_tabpanel_title',
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
    
    getDescriptionsFieldset:  function()
    {
        var me = this;
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
                    itemId: 'cms_website_additionaldata_metatags_description_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'cms_website_additionaldata_metatags_tabpanel_description',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_description_rendered = true;
                            me.createTabsContent();
                        }
                    }
                }
            ]
        };      
        
        return ret;       
    },        
    
    getKeywordsFieldset:  function()
    {
        var me = this;
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Keywords',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'cms_website_additionaldata_metatags_keywords_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'cms_website_additionaldata_metatags_tabpanel_keywords',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_keywords_rendered = true;
                            me.createTabsContent();
                        }
                    }
                }
            ]
        };      
        
        return ret;       
    },       
    
    getRobotsFieldset:  function()
    {
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Robots',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'textfield',
                    name: 'robots',
                    fieldLabel: '',
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                }
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
        
        if (me.tabpanel_title_rendered)
        {
            me.createTabContent(me.config, 'title');
            me.tabpanel_title_rendered = false;
        }

        if (me.tabpanel_description_rendered)
        {
            me.createTabContent(me.config, 'description');
            me.tabpanel_description_rendered = false;
        }

        if (me.tabpanel_keywords_rendered)
        {
            me.createTabContent(me.config, 'keywords');
            me.tabpanel_keywords_rendered = false;
        }   
                            
    },
    
    canWeCreateTabs: function()
    {
        var me = this;
        return (me.tabpanel_title_rendered && 
                me.tabpanel_description_rendered && 
                me.tabpanel_keywords_rendered);
    },
    
    createTabContent: function(config, type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = Ext.ComponentQuery.query('#cms_website_additionaldata_metatags_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#cms_website_additionaldata_metatags_' + type + '_msg_no_available_lang')[0];     
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
                
                var max_length = Number.MAX_VALUE;
                if (type === 'title')
                {
                    name = 'titles';
                    //max_length = 70; // SEO requirements;
                }
                else if (type === 'description')
                {
                    name = 'descriptions';
                }
                else
                {
                    name = 'keywords';
                }
                
                if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang_code]))
                {
                    value = record.data[name][lang_code];
                }
                tab.add({
                        xtype: 'textfield',
                        title: lang_name,
                        name: name + '-' + lang_code,
                        fieldLabel: '',
                        anchor: '100%',
                        value: value,
                        maxLength : max_length
                });   
                
                i++;
            });

            tab.setActiveTab(0);                                            
        }         
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }

});