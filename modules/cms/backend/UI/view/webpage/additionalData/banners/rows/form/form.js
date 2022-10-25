Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.banners.rows.form.form', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.cms_webpage_additionaldata_banners_rows_form_form',
    
    region: 'center',

    border: false,
    frame: false,
    bodyPadding: 10,
    autoScroll: true,
    
    config: null,
    is_new_record: true,
    current_record: null,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = ''; 

        me.items = 
        [   
            me.getPropertiesFieldset(),
            me.getColumnsFieldset()
        ];
        
        me.callParent(arguments);
        
        me.on('boxready', this.onBoxready, this);
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
                    xtype: 'checkboxfield',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    checked: me.getPropertyValue('available', true)
                },
                {
                    xtype: 'textfield',
                    name: 'width',
                    fieldLabel: 'Width',
                    allowBlank: true,
                    labelAlign: 'right',
                    width: 200,
                    value: me.getPropertyValue('width', "auto")
                },   
                {
                    xtype: 'textfield',
                    name: 'height',
                    fieldLabel: 'Height',
                    allowBlank: true,
                    labelAlign: 'right',
                    width: 200,
                    value: me.getPropertyValue('height', "auto")
                }, 
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    items: 
                    [
                        {
                            xtype: 'textfield',
                            name: 'marginTop',
                            fieldLabel: 'Margin',
                            allowBlank: true,
                            labelAlign: 'right',
                            width: 150,
                            value: me.getPropertyValue('marginTop', "0px")
                        }, 
                        {
                            xtype: 'textfield',
                            name: 'marginRight',
                            fieldLabel: '',
                            allowBlank: true,
                            width: 50,
                            value: me.getPropertyValue('marginRight', "0px")
                        },
                        {
                            xtype: 'textfield',
                            name: 'marginBottom',
                            fieldLabel: '',
                            allowBlank: true,
                            width: 50,
                            value: me.getPropertyValue('marginBottom', "0px")
                        },
                        {
                            xtype: 'textfield',
                            name: 'marginLeft',
                            fieldLabel: '',
                            allowBlank: true,
                            width: 50,
                            value: me.getPropertyValue('marginLeft', "0px")
                        }                   
                    ]                    
                },
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    items: 
                    [
                        {
                            xtype: 'textfield',
                            name: 'paddingTop',
                            fieldLabel: 'Padding',
                            allowBlank: true,
                            labelAlign: 'right',
                            width: 150,
                            value: me.getPropertyValue('paddingTop', "0px")
                        }, 
                        {
                            xtype: 'textfield',
                            name: 'paddingRight',
                            fieldLabel: '',
                            allowBlank: true,
                            width: 50,
                            value: me.getPropertyValue('paddingRight', "0px")
                        },
                        {
                            xtype: 'textfield',
                            name: 'paddingBottom',
                            fieldLabel: '',
                            allowBlank: true,
                            width: 50,
                            value: me.getPropertyValue('paddingBottom', "0px")
                        },
                        {
                            xtype: 'textfield',
                            name: 'paddingLeft',
                            fieldLabel: '',
                            allowBlank: true,
                            width: 50,
                            value: me.getPropertyValue('paddingLeft', "0px")
                        }                   
                    ]                    
                } 
            ]
        };
        
        return ret;
    },
    
    getColumnsFieldset: function()
    { 
        var me = this;
        
        var fields = [
            'available',
            'promo',
            
            'width',
            'height',
            // Margin
            'marginTop',
            'marginRight',
            'marginBottom',
            'marginLeft',
            // Padding
            'paddingTop',
            'paddingRight',
            'paddingBottom',
            'paddingLeft',
        
            'image', 
            'title', 
            'url'
        ];
        
        var data = me.getPropertyValue('columns', []);

        var object = {
            fields: fields,
            data : data
        };
        
        var columns_store = Ext.create('Ext.data.Store', object);
        
        var ret =              
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('columns'),
            anchor: '100%',
            items: 
            [    
                Ext.widget('cms_webpage_additionaldata_banners_columns_grid', {
                    config: me.config,
                    columns_store: columns_store
                })
            ]
        };
        
        return ret;  
    },
    
    getPropertyValue: function(name, default_value)
    {
        var me = this;
        var value = default_value;
        
        if (!me.is_new_record && !Ext.isEmpty(me.current_record.data[name]))
        {
            value = me.current_record.data[name];
        }
        
        return value;
    },
    
    onBoxready: function(this_panel, width, height, eOpts)
    {
        var me = this;
        me.is_box_ready = true;
    }, 
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    },
    
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
    
});