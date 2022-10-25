Ext.define('App.modules.cms.backend.UI.view.website.additionalData.laws', {
    
    alias: 'widget.cms_website_additionaldata_laws',
    explotation: 'Laws for website (Additional data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: me.trans('laws'),
            width: 800,
            height: 800,
            fields:
            [
                me.getLegalNoticeFieldset(),
                me.getPrivacyPolicyFieldset(),
                me.getCookiesPolicyFieldset(),
                me.getConditionsOfSaleFieldset()
            ]        
        };
        
        return ret;
    },
    
    getLegalNoticeFieldset:  function()
    {
        var me = this;
        var tabItemId = 'cms_website_additionaldata_laws_tabpanel_legal_notice'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('legal_notice'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'cms_website_additionaldata_laws_legal_notice_msg_no_available_lang',
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
                            me.tabpanel_legal_notice_rendered = true;
                            me.createTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret;       
    },
    
    getPrivacyPolicyFieldset:  function()
    {
        var me = this;
        var tabItemId = 'cms_website_additionaldata_laws_tabpanel_privacy_policy'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('privacy_policy'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'cms_website_additionaldata_laws_privacy_policy_msg_no_available_lang',
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
                            me.tabpanel_privacy_policy_rendered = true;
                            me.createTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret;       
    },
    
    getCookiesPolicyFieldset:  function()
    {
        var me = this;
        var tabItemId = 'cms_website_additionaldata_laws_tabpanel_cookies_policy'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('cookies_policy'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'cms_website_additionaldata_laws_cookies_policy_msg_no_available_lang',
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
                            me.tabpanel_cookies_policy_rendered = true;
                            me.createTabsContent();
                        }
                    }
                },
                me.getViewController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };      
        
        return ret;       
    }, 
    
    getConditionsOfSaleFieldset:  function()
    {
        var me = this;
        var tabItemId = 'cms_website_additionaldata_laws_tabpanel_conditions_of_sale'; 
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('conditions_of_sale'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'cms_website_additionaldata_laws_conditions_of_sale_msg_no_available_lang',
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
                            me.tabpanel_conditions_of_sale_rendered = true;
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

        if (me.tabpanel_legal_notice_rendered)
        {
            me.createTabContent(me.config, 'legal_notice');
            me.tabpanel_legal_notice_rendered = false;
        }
        
        if (me.tabpanel_privacy_policy_rendered)
        {
            me.createTabContent(me.config, 'privacy_policy');
            me.tabpanel_privacy_policy_rendered = false;
        }
        
        if (me.tabpanel_cookies_policy_rendered)
        {
            me.createTabContent(me.config, 'cookies_policy');
            me.tabpanel_cookies_policy_rendered = false;
        }

        if (me.tabpanel_conditions_of_sale_rendered)
        {
            me.createTabContent(me.config, 'conditions_of_sale');
            me.tabpanel_conditions_of_sale_rendered = false;
        }    
                            
    },
    
    canWeCreateTabs: function()
    {
        var me = this;
        return (me.tabpanel_legal_notice_rendered && 
                me.tabpanel_privacy_policy_rendered && 
                me.tabpanel_cookies_policy_rendered && 
                me.tabpanel_conditions_of_sale_rendered);
    },
    
    createTabContent: function(config, type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = Ext.ComponentQuery.query('#cms_website_additionaldata_laws_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#cms_website_additionaldata_laws_' + type + '_msg_no_available_lang')[0];     
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
                
                var height = 300;
                if (type === 'legal_notice')
                {
                    name = 'legalNotice';
                }
                else if (type === 'privacy_policy')
                {
                    name = 'privacyPolicies';
                }
                else if (type === 'cookies_policy')
                {
                    name = 'cookiesPolicies';
                }
                else
                {
                    name = 'conditionsOfSale';
                }
                
                if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang_code]))
                {
                    value = record.data[name][lang_code];
                }
                tab.add({
                        xtype: 'htmleditor',
                        title: lang_name,
                        name: name + '-' + lang_code,
                        fieldLabel: '',
                        anchor: '100%',
                        height: height,
                        autoScroll: true,
                        enableFont: false,
                        value: value
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
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.cms.backend.UI.controller.cms');       
        return controller;
    }

});