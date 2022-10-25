Ext.define('App.modules.ecommerce.backend.UI.view.saleRate.saleRate', {
    extend: 'App.core.backend.UI.view.maintenance.basic.basic',
    
    alias: 'widget.ecommerce_saleRate',
        
    explotation: 'E-Commerce sale rates view',
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\ecommerce\\backend\\controller\\saleRate';
        this.config.delete_controller = this.config.save_controller;
    },
            
    // Overwritten
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: me.trans('sale_rate_view'),
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    align: 'left',
                    width: 100
                },
                {
                    text: me.trans('name'),
                    dataIndex: 'name',
                    align: 'left',
                    width: 200
                },
                {
                    text: me.trans('available'),
                    dataIndex: 'available',
                    width: 90
                },
                {
                    text: me.trans('profit_margin') + ' %',
                    dataIndex: 'profitMargin',
                    align: 'center',
                    width: 140
                },
                {
                    text: me.trans('discount') + ' %',
                    dataIndex: 'discount',
                    align: 'center',
                    width: 120
                },
                {
                    text: me.trans('hide_discount'),
                    dataIndex: 'hideDiscount',
                    width: 90
                },
                {
                    text: me.trans('hide_discount_badge'),
                    dataIndex: 'hideDiscountBadge',
                    width: 90
                }
            ]
        };
    },
            
    initForm: function()
    {
        var me = this;
        me.config.form =
        {
            title: me.trans('sale_rate_form'),
            fields:
            [
                me.getMainFieldset(),
                me.getPropertiesFieldset(),
                me.getMarginsAndDiscountsFieldset()
            ]
        };
    },
    
    getMarginsAndDiscountsFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('margins_and_discounts'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    items: 
                    [
                        {
                            xtype: 'numberfield',
                            name: 'profitMargin',
                            fieldLabel: me.trans('profit_margin'),
                            allowBlank: true,
                            labelAlign: 'right',
                            minValue: 0, //prevents negative numbers                            
                            decimalPrecision: 2,
                            decimalSeparator: app_decimal_separator,
                            width: 160,
                            // Remove spinner buttons, and arrow key and mouse wheel listeners
                            hideTrigger: true,
                            keyNavEnabled: false,
                            mouseWheelEnabled: false
                        },
                        {
                            xtype: 'label',
                            margin: '5 0 0 5',
                            text: '%'
                        }   
                    ]
                },
                {
                    xtype : 'fieldcontainer',
                    layout: 'hbox',
                    items: 
                    [              
                        {
                            xtype: 'numberfield',
                            name: 'discount',
                            fieldLabel: me.trans('discount'),
                            allowBlank: true,
                            labelAlign: 'right',
                            minValue: 0, //prevents negative numbers                                    
                            decimalPrecision: 0,                   
                            width: 160,
                            // Remove spinner buttons, and arrow key and mouse wheel listeners
                            hideTrigger: true,
                            keyNavEnabled: false,
                            mouseWheelEnabled: false
                        },
                        {
                            xtype: 'label',
                            margin: '5 0 0 5',
                            text: '%'
                        }
                    ]
                },
                {
                    xtype: 'checkboxfield',
                    name: 'hideDiscount',
                    fieldLabel:  me.trans('hide_discount'),
                    boxLabel: '',
                    labelAlign: 'right'              
                },
                {
                    xtype: 'checkboxfield',
                    name: 'hideDiscountBadge',
                    fieldLabel:  me.trans('hide_discount_badge'),
                    boxLabel: '',
                    labelAlign: 'right'              
                }
            ]
        };
        
        return ret;
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});