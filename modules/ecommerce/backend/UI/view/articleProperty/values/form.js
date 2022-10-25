Ext.define('App.modules.ecommerce.backend.UI.view.articleProperty.values.form', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.ecommerce_article_property_values_form',
    
    region: 'center',

    border: false,
    frame: false,
    bodyPadding: 10,
    autoScroll: true,
    
    is_new_record: true,
    current_record: null,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = ''; 

        me.items = 
        [   
            me.getMainFieldset(),
            me.getPropertiesFieldset(),
            me.getTextsFieldset()
        ];
        
        me.callParent(arguments);
        
        me.on('boxready', this.onBoxready, this);
    },
    
    onBoxready: function(this_panel, width, height, eOpts)
    {
        var me = this;
        me.is_box_ready = true;
        
        if (me.is_new_record)
        {
            var task = new Ext.util.DelayedTask(function(){
                me.getForm().findField("code").focus();
            });        
            task.delay(200);            
        }
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
                    disabled: !me.is_new_record
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
                    fieldLabel: me.trans('name'),
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%'
                },        
                {
                    xtype: 'checkboxfield',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    anchor: '100%',
                    checked: true
                }/*,
                {
                    xtype: 'numberfield',
                    name: 'amount',
                    fieldLabel: me.trans('amount'),
                    labelAlign: 'right',
                    minValue: 0, //prevents negative numbers                            
                    decimalPrecision: 2,
                    decimalSeparator: app_decimal_separator,
                    width: 200,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false
                }*/
            ]
        };
        
        return ret;
    },
    
    getTextsFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('texts'),
            anchor: '100%',
            items: 
            [  
                {
                    xtype: 'label',
                    itemId: 'ecommerce_article_property_values_form_texts_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'ecommerce_article_property_values_form_tabpanel_texts',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.createTabContent('texts');
                        }
                    }
                }                          
            ]
        };
        
        return ret;
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var tab = Ext.ComponentQuery.query('#ecommerce_article_property_values_form_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        var value, name;
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#ecommerce_article_property_values_form_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            var form = me;
            var is_new_record = form.is_new_record;
            if (!is_new_record)
            {
                var record = form.getRecord();
            }
            
            var i = 0;
            Ext.each(langs, function(lang) {
                
                value = '';
                name = 'texts';
                if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang.code]))
                {
                    value = record.data[name][lang.code];
                }
                tab.add({
                        xtype: 'textfield',
                        title: lang.name,
                        name: name + '-' + lang.code,
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
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});