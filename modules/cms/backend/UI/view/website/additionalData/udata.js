Ext.define('App.modules.cms.backend.UI.view.website.additionalData.udata', {
    
    alias: 'widget.cms_website_additionaldata_udata',
    explotation: 'uData for website (Micro data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: 'Micro-data',
            width: 800,
            height: 630,
            fields:
            [
                me.getMicrodataFieldset()
            ]        
        };
        
        return ret;
    },      
    
    getMicrodataFieldset:  function()
    {
        var me = this;
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'uData',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'cms_website_additionaldata_udata_udata_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'cms_website_additionaldata_udata_tabpanel_udata',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_udata_rendered = true;
                            me.createTabsContent();
                        }
                    }
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
        
        if (me.tabpanel_udata_rendered)
        {
            me.createTabContent(me.config, 'udata');
            me.tabpanel_udata_rendered = false;
        }
                            
    },
    
    canWeCreateTabs: function()
    {
        var me = this;
        return (me.tabpanel_udata_rendered);
    },
    
    createTabContent: function(config, type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = Ext.ComponentQuery.query('#cms_website_additionaldata_udata_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#cms_website_additionaldata_udata_' + type + '_msg_no_available_lang')[0];     
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
                
                var height = 400;
                name = 'udata';
                
                if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang_code]))
                {
                    value = record.data[name][lang_code];
                }
                tab.add({
                        xtype: 'textarea',
                        title: lang_name,
                        name: name + '-' + lang_code,
                        fieldLabel: '',
                        anchor: '100%',
                        height: height,
                        autoScroll: true,
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
    }

});