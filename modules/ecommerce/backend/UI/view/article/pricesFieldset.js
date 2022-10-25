Ext.define('App.modules.ecommerce.backend.UI.view.article.pricesFieldset', {
    extend: 'Ext.form.FieldSet',
    
    alias: 'widget.ecommerce_article_prices_fieldset',
        
    explotation: 'E-Commerce article prices view (fieldset)',
            
    padding: 5,
    anchor: '100%',
    
    config: null,
    
    initComponent: function()
    {
        // General properties
        this.initGeneralProperties();
        // The fieldset
        this.initFieldset();
        
        this.callParent(arguments);         
    },
    
    initGeneralProperties: function()
    {
        var me = this;
        me.title = me.trans('prices');
    },
    
    initFieldset: function()
    {
        var me = this;
        me.items =
        [
            me.getSaleRateComboField(),
            me.getCostPriceFieldcontainer(),
            me.getMarginFieldcontainer(),
            me.getSaleRateMarginFieldcontainer(),
            me.getBasePriceFieldcontainer(),
            me.getBasePriceForCostPrice0Fieldcontainer(),
            me.getVatFieldcontainer(),
            me.getRetailPriceFieldcontainer(),
            me.getRecommendedRetailPriceFieldcontainer(),
            me.getDiscountFieldcontainer(),
            me.getSaleRateDiscountFieldcontainer(),
            me.getGammaDiscountFieldcontainer(),
            {
                xtype : 'fieldcontainer',
                layout: 'hbox',
                items: 
                [
                    {
                        xtype: 'textfield',
                        itemId: 'ecommerce_article_prices_final_retail_price_manual',
                        name: 'finalRetailPrice',
                        fieldLabel: me.trans('frp'),
                        labelAlign: 'right',
                        width: 200,
                        listeners: {
                            change: function(field, newValue, oldValue, eOpts) {
                                if (newValue !== oldValue)
                                {
                                    me.updateFinalPrices(null);
                                }                    
                            }
                        }
                    },
                    {
                        xtype: 'label',
                        margin: '5 0 0 5',
                        text: '\u20ac'
                    }
                ]
            },            
            me.getFinalRetailPriceFieldcontainer(),
            me.getMinMarginWarningField(),   
            {
                xtype : 'fieldcontainer',
                layout: 'hbox',
                items: 
                [
                    {
                        xtype: 'numberfield',
                        name: 'secondUnitDiscount',
                        fieldLabel: me.trans('2nd_unit_to'),
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
            }
        ];
    },
    
    getSaleRateComboField: function()
    {      
        var me = this;
                    
        var ret = 
        {
            xtype: 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'combo',
                    name: 'saleRate',
                    fieldLabel: me.trans('sale_rate'),
                    _store: {
                        module_id: me.config.module_id,
                        model_id: 'saleRate',
                        fields: ['code', 'name', 'profitMargin', 'discount'],
                        filters: [
                            {field: 'available', value: true}
                        ]                                
                    },
                    _addSubmitValues: [
                        {field: 'name', as: 'saleRateName'}
                    ],
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: true,  
                    //bug//emptyText: me.trans('select_sale_rate'),
                    allowBlank: true,
                    labelAlign: 'right',
                    width: '90%',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts)
                        {
                            if (newValue === oldValue)
                            {
                                return;
                            }
                            
                            var combo_store = field.getStore();
                            var record = combo_store.findRecord('code', newValue);
                            
                            // Set sale rate margin
                            var sale_rate_margin = me.down('#ecommerce_article_prices_sale_rate_margin');
                            if (Ext.isEmpty(newValue))
                            {
                                sale_rate_margin.setValue('');
                            }
                            else
                            {
                                sale_rate_margin.setValue(record.data.profitMargin);
                            }
                            
                            // Set sale rate discount
                            var sale_rate_discount = me.down('#ecommerce_article_prices_sale_rate_discount');
                            if (Ext.isEmpty(newValue))
                            {
                                sale_rate_discount.setValue('');
                            }
                            else
                            {
                                sale_rate_discount.setValue(record.data.discount);
                            }
                        },
                        beforequery: function (record) {
                            record.query = new RegExp(record.query, 'i');
                            record.forceAll = true;
                        }
                    }
                },
                {
                    xtype: 'button',
                    margin: '0 0 0 5',
                    text: "X",
                    width: 32,
                    handler: function()
                    {
                        var form = me.getMaintenanceController().getForm(me.config);
                        var field = form.getForm().findField('saleRate');                        
                        field.setValue('');
                    }
                } 
            ]
        };
        
        return ret;
    },
    
    getCostPriceFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                me.getCostPriceField(),
                me.getIncludedVatField()
            ]
        };
        
        return ret;
    },
    
    getCostPriceField: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'numberfield',
                    itemId: 'ecommerce_article_prices_cost_price',
                    name: 'costPrice',
                    fieldLabel: me.trans('cost_price'),
                    allowBlank: true,
                    labelAlign: 'right',
                    minValue: 0, //prevents negative numbers                            
                    decimalPrecision: 4,
                    decimalSeparator: app_decimal_separator,
                    width: 200,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                var radio_button = me.up('form').getForm().findField('useMargin');                                  
                                var use_margin = radio_button.getGroupValue();
                                me.updateBasePrice(use_margin);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '\u20ac'
                }
            ]
        };
        
        return ret;
    },
    
    getIncludedVatField: function()
    {      
        var me = this;
        var ret = 
        {
            xtype: 'checkboxfield',
            itemId: 'ecommerce_article_prices_included_vat',
            name: 'includedVat',
            fieldLabel: '',
            boxLabel: me.trans('included_vat'),
            labelAlign: 'right',
            padding: '0 0 0 10',
            anchor: '100%',
            disabled: ecommerce_vat_is_always_inclued_to_cost_price,
            _defaultValue: ecommerce_vat_is_always_inclued_to_cost_price, // checked when new record 
            listeners: {
                change: function(field, newValue, oldValue, eOpts) {
                    if (newValue !== oldValue)
                    {
                        me.updateFinalPrices(null);
                    }                    
                }
            }      
        };
        
        return ret;
    },
    
    getMarginFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'numberfield',
                    itemId: 'ecommerce_article_prices_required_margin',
                    name: 'margin',
                    fieldLabel: me.trans('margin'),
                    allowBlank: true,
                    labelAlign: 'right',
                    minValue: -100, //prevents negative numbers     
                    maxValue: 100,                         
                    decimalPrecision: 2,
                    decimalSeparator: app_decimal_separator,
                    width: 160,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                var radio_button = me.up('form').getForm().findField('useMargin');                                  
                                var use_margin = radio_button.getGroupValue();
                                me.updateBasePrice(use_margin);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                },
                {
                    xtype: 'radio',
                    name: 'useMargin',
                    fieldLabel: '',
                    boxLabel: me.trans('article_margin'),
                    margin: '0 0 0 10',
                    inputValue: 'article',
                    checked: true,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue)
                            {
                                me.updateBasePrice('');
                            }                    
                        }
                    }
                }                
            ]
        };
        
        return ret;
    },
    
    getSaleRateMarginFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'textfield',
                    itemId: 'ecommerce_article_prices_sale_rate_margin',
                    fieldLabel: '',
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    //width: 160,
                    width: 56,
                    margin: '0 0 0 104',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                var radio_button = me.up('form').getForm().findField('useMargin');                                  
                                var use_margin = radio_button.getGroupValue();
                                me.updateBasePrice(use_margin);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                },
                {
                    xtype: 'radio',
                    itemId: 'ecommerce_article_prices_sale_rate_margin_radiobutton',
                    name: 'useMargin',
                    fieldLabel: '',
                    boxLabel: me.trans('sale_rate_margin'),
                    margin: '0 0 0 10',
                    inputValue: 'saleRate',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue)
                            {
                                me.updateBasePrice('saleRate');
                            }                    
                        }
                    }
                }                
            ]
        };
        
        return ret;
    },
    
    getBasePriceFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            itemId: 'ecommerce_article_prices_base_price_fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'numberfield',
                    itemId: 'ecommerce_article_prices_base_price',
                    fieldLabel: me.trans('sale_price'),
                    allowBlank: true,
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    minValue: 0, //prevents negative numbers                          
                    decimalPrecision: 2,
                    decimalSeparator: app_decimal_separator,
                    width: 200,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                me.updateFinalPrices(null);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '\u20ac'
                }    
            ]
        };
        
        return ret;
    },
    
    getBasePriceForCostPrice0Fieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            itemId: 'ecommerce_article_prices_base_price_for_cost_price_0_fieldcontainer',
            layout: 'hbox',
            visible: false,
            items: 
            [
                {
                    xtype: 'numberfield',
                    itemId: 'ecommerce_article_prices_base_price_for_cost_price_0',
                    name: 'basePriceForCostPrice0',
                    fieldLabel: me.trans('sale_price'),
                    allowBlank: true,
                    labelAlign: 'right',
                    minValue: 0, //prevents negative numbers                          
                    decimalPrecision: 2,
                    decimalSeparator: app_decimal_separator,
                    width: 200,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                me.updateFinalPrices(null);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '\u20ac'
                }    
            ]
        };
        
        return ret;
    },
    
    getVatFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'textfield',
                    itemId: 'ecommerce_article_prices_vat',
                    fieldLabel: me.trans('vat'),
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    width: 160,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                me.updateFinalPrices(null);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                }                    
            ]
        };
        
        return ret;
    },
    
    getRetailPriceFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'textfield',
                    itemId: 'ecommerce_article_prices_retail_price',
                    fieldLabel: me.trans('rp'),
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    width: 200
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '\u20ac'
                }
            ]
        };
        
        return ret;
    },
    
    getRecommendedRetailPriceFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'textfield',
                    itemId: 'ecommerce_article_prices_recommended_retail_price',
                    name: 'recommendedRetailPrice',
                    fieldLabel: me.trans('rrp'),
                    labelAlign: 'right',
                    width: 200,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                me.updateFinalPrices(null);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '\u20ac'
                }
            ]
        };
        
        return ret;
    },
    
    getDiscountFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [              
                {
                    xtype: 'numberfield',
                    itemId: 'ecommerce_article_prices_discount',
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
                    mouseWheelEnabled: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                me.updateFinalPrices(null);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                },
                {
                    xtype: 'radio',
                    name: 'useDiscount',
                    fieldLabel: '',
                    boxLabel: me.trans('article_discount'),
                    margin: '0 0 0 10',
                    inputValue: 'article',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue)
                            {
                                me.updateFinalPrices('article');
                            }                    
                        }
                    }
                }                
            ]
        };
        
        return ret;
    },
    
    getSaleRateDiscountFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [              
                {
                    xtype: 'numberfield',
                    itemId: 'ecommerce_article_prices_sale_rate_discount',
                    fieldLabel: '',
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    //width: 160,
                    width: 56,
                    margin: '0 0 0 104',
                    minValue: 0, //prevents negative numbers                                    
                    decimalPrecision: 0,                   
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                me.updateFinalPrices(null);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                },
                {
                    xtype: 'radio',
                    name: 'useDiscount',
                    fieldLabel: '',
                    boxLabel: me.trans('sale_rate_discount'),
                    margin: '0 0 0 10',
                    inputValue: 'saleRate',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue)
                            {
                                me.updateFinalPrices('saleRate');
                            }                    
                        }
                    }
                }                
            ]
        };
        
        return ret;
    },
    
    getGammaDiscountFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [              
                {
                    xtype: 'numberfield',
                    itemId: 'ecommerce_article_prices_gamma_discount',
                    fieldLabel: '',
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    //width: 160,
                    width: 56,
                    margin: '0 0 0 104',
                    minValue: 0, //prevents negative numbers                                    
                    decimalPrecision: 0,                   
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false,
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue !== oldValue)
                            {
                                me.updateFinalPrices(null);
                            }                    
                        }
                    }
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                },
                {
                    xtype: 'radio',
                    name: 'useDiscount',
                    fieldLabel: '',
                    boxLabel: me.trans('gamma_discount'),
                    margin: '0 0 0 10',
                    inputValue: 'gamma',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts) {
                            if (newValue)
                            {
                                me.updateFinalPrices('gamma');
                            }                    
                        }
                    }
                }                
            ]
        };
        
        return ret;
    },
    
    getFinalRetailPriceFieldcontainer: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                me.getFinalRetailPriceField(),
                me.getFinalMarginField()
            ]
        };
        
        return ret;
    },
    
    getFinalRetailPriceField: function()
    {     
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'textfield',
                    itemId: 'ecommerce_article_prices_final_retail_price',
                    fieldLabel: '<b>' + me.trans('frp') + '</b>',
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    width: 200
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '\u20ac'
                }     
            ]
        };
        
        return ret;
    },
    
    getFinalMarginField: function()
    {     
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'textfield',
                    itemId: 'ecommerce_article_prices_final_margin',
                    fieldLabel: me.trans('final_margin'),
                    labelAlign: 'right',
                    readOnly: true,
                    fieldStyle: {
                        'background-color' : 'silver'
                    },
                    labelWidth: 80,
                    width: 150
                },
                {
                    xtype: 'label',
                    margin: '5 0 0 5',
                    text: '%'
                }    
            ]
        };
        
        return ret;
    },
    
    getMinMarginWarningField: function()
    {      
        var me = this;
        var ret = 
        {
            xtype : 'fieldcontainer',
            layout: 'hbox',
            items: 
            [
                {
                    xtype: 'numberfield',
                    name: 'minMarginWarning',
                    fieldLabel: me.trans('min_margin_warning'),
                    allowBlank: true,
                    labelAlign: 'right',
                    minValue: 0, //prevents negative numbers                          
                    decimalPrecision: 2,
                    decimalSeparator: app_decimal_separator,
                    width: 220,
                    margin: '0 0 0 142',
                    labelWidth: 150,
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
        };
        
        return ret;
    },
    
    updateBasePrice: function(use_margin)
    {      
        var me = this;
        
        // Cost price
        var cost_price = me.down('#ecommerce_article_prices_cost_price').getValue();
        
        // Margin
        var margin;
        var required_margin_field = me.down('#ecommerce_article_prices_required_margin');
        if (use_margin === 'saleRate')
        {
            margin = me.down('#ecommerce_article_prices_sale_rate_margin').getValue();
        }
        else
        {
            margin = required_margin_field.getValue();
        }
        
        // Base price (sale price)
        var base_price_fieldcontainer = me.down('#ecommerce_article_prices_base_price_fieldcontainer');
        var base_price_field = me.down('#ecommerce_article_prices_base_price');
        var base_price_for_cost_price_0_fieldcontainer = me.down('#ecommerce_article_prices_base_price_for_cost_price_0_fieldcontainer');
        var base_price_for_cost_price_0_field = me.down('#ecommerce_article_prices_base_price_for_cost_price_0');
        var sale_rate_margin_radiobutton = me.down('#ecommerce_article_prices_sale_rate_margin_radiobutton');
        var base_price_value;
        if (cost_price == 0)
        {
            required_margin_field.setReadOnly(true);
            required_margin_field.setFieldStyle({
                'background-color' : 'silver'
            });
            required_margin_field.setValue('100');
            margin = 100;
            
            var radio_button = me.up('form').getForm().findField('useMargin');                                  
            radio_button.setValue('article');
            sale_rate_margin_radiobutton.setDisabled(true);
            
            base_price_fieldcontainer.setVisible(false);
            base_price_for_cost_price_0_fieldcontainer.setVisible(true);
            
            // Set base price
            base_price_field.setValue('');
            //base_price_for_cost_price_0_field.setValue('');
        }
        else
        {
            required_margin_field.setReadOnly(false);
            required_margin_field.setFieldStyle({
                'background-color' : 'white'
            });
            
            sale_rate_margin_radiobutton.setDisabled(false);
            
            base_price_fieldcontainer.setVisible(true);
            base_price_for_cost_price_0_fieldcontainer.setVisible(false);
            
            // Set base price
            base_price_value = me.getBasePrice(cost_price, margin);
            var value = parseFloat(Ext.util.Format.round(base_price_value, 2).toFixed(2));
            //console.log(base_price_value, value);
            base_price_field.setValue(value);
            base_price_for_cost_price_0_field.setValue('');
        }

    },
    
    updateFinalPrices: function(use_discount)
    {
        var me = this;
        
        if (!use_discount)
        {
            var radio_button = me.up('form').getForm().findField('useDiscount');                                  
            use_discount = radio_button.getGroupValue();
        }
        
        // Vat
        var vat = me.down('#ecommerce_article_prices_vat').getValue();
        var included_vat = me.down('#ecommerce_article_prices_included_vat').getValue();
        if (included_vat)
        {
            vat = 0;
        }
        
        // Cost price
        var cost_price = me.down('#ecommerce_article_prices_cost_price').getValue();
        var base_price;
        if (cost_price == 0)
        {
            base_price = me.down('#ecommerce_article_prices_base_price_for_cost_price_0').getValue();
        }
        else
        {
            base_price = me.down('#ecommerce_article_prices_base_price').getValue();
        }
        
        // Retail Price
        var vat_tax = (base_price * vat) / 100;
        var calculated_rp = base_price + vat_tax;
        var rp;
        
        // Recommended Retail Price
        var rrp = me.down('#ecommerce_article_prices_recommended_retail_price').getValue();
        if (rrp == 0)
        {
            rp = calculated_rp;
        }
        else
        {
            rp = rrp;
        }
        
        // Discount
        var discount;
        if (use_discount === 'saleRate')
        {
            discount = me.down('#ecommerce_article_prices_sale_rate_discount').getValue();
        }
        else if (use_discount === 'gamma')
        {
            discount = me.down('#ecommerce_article_prices_gamma_discount').getValue();
        }
        else
        {
            discount = me.down('#ecommerce_article_prices_discount').getValue();
        }
        
        // Final Retail Price (with discount)
        var discount_tax = (rp * discount) / 100;
        var final_rp = rp - discount_tax;        
        var final_rp_manual = me.down('#ecommerce_article_prices_final_retail_price_manual').getValue();
        if (final_rp_manual > 0)
        {
            final_rp = final_rp_manual;
        }
        
        // Final margin
        var final_margin = 0;
        var rp_aux;
        if (included_vat)
        {
            rp_aux = final_rp;
        }
        else
        {
            var discount_over_base_price_tax = (base_price * discount) / 100;
            rp_aux = base_price - discount_over_base_price_tax;
        }
        if (rp_aux > 0)
        {
            final_margin = me.getMargin(cost_price, rp_aux);
        }
        
        // Set calculated values
        var value = parseFloat(Ext.util.Format.round(calculated_rp, 2).toFixed(2));
        //console.log(calculated_rp, value);
        me.down('#ecommerce_article_prices_retail_price').setValue(value);
        
        value = parseFloat(Ext.util.Format.round(final_rp, 2).toFixed(2));
        //console.log(final_rp, value);
        me.down('#ecommerce_article_prices_final_retail_price').setValue(value);
        
        var final_margin_field = me.down('#ecommerce_article_prices_final_margin');
        if (!Ext.isEmpty(final_margin_field))
        {
            value = parseFloat(Ext.util.Format.round(final_margin, 2).toFixed(2));
            //console.log(final_margin, value);            
            final_margin_field.setValue(value);
        }        
    },
    
    getBasePrice: function(cost_price, margin)
    {      
        // Precio = Coste / (1 – %margen)
        // http://manueldelgado.com/como-calcular-el-precio-de-venta-coste-margen/
        var base_price = cost_price / (1 - ( margin / 100));
        return base_price;
    },
    
    getMargin: function(cost_price, sale_price)
    {      
        var margin = 100 - ((cost_price * 100) / sale_price);
        //var margin = ((100 * sale_price) - (100 * cost_price)) / sale_price;
        return margin;
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
     
});