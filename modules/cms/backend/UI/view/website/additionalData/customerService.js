Ext.define('App.modules.cms.backend.UI.view.website.additionalData.customerService', {
    
    alias: 'widget.cms_website_additionaldata_customerservice',
    explotation: 'Customer service for website (Additional data)',
    config: null,
    
    getForm: function(config)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: me.trans('customer_service'),
            width: 500,
            height: 320,
            fields:
            [
                {
                    xtype: 'textfield',
                    name: 'phone',
                    fieldLabel: me.trans('phone'),
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                },        
                {
                    xtype: 'textfield',
                    name: 'email',
                    vtype: 'email',
                    fieldLabel: 'E-mail',
                    allowBlank: true,
                    labelAlign: 'right',
                    anchor: '100%'
                },        
                me.getSchedulesFieldset()
            ]        
        };
        
        return ret;
    },      
    
    getSchedulesFieldset:  function()
    {
        var me = this;
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('schedule'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'cms_website_additionaldata_customerservice_schedule_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'cms_website_additionaldata_customerservice_tabpanel_schedule',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_schedule_rendered = true;
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
        
        if (me.tabpanel_schedule_rendered)
        {
            me.createTabContent(me.config, 'schedule');
            me.tabpanel_schedule_rendered = false;
        }  
                            
    },
    
    canWeCreateTabs: function()
    {
        var me = this;
        return (me.tabpanel_schedule_rendered);
    },
    
    createTabContent: function(config, type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = Ext.ComponentQuery.query('#cms_website_additionaldata_customerservice_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#cms_website_additionaldata_customerservice_' + type + '_msg_no_available_lang')[0];     
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
                
                if (type === 'schedule')
                {
                    name = 'schedules';
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