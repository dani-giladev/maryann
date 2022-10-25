Ext.define('App.modules.ecommerce.backend.UI.view.articleType.articleType', {
    extend: 'App.core.backend.UI.view.maintenance.basic.basic',
    
    alias: 'widget.ecommerce_articleType',
        
    explotation: 'E-Commerce article type view',
    
    // Overwritten
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\ecommerce\\backend\\controller\\articleType';
    },
            
    // Overwritten
    initGrid: function()
    {
        var me = this;
        this.config.grid = 
        {
            title: me.trans('article_type_view'),
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
                    flex: 1,
                    align: 'left',
                    minWidth: 180
                },
                {
                    text: me.trans('available'),
                    dataIndex: 'available',
                    width: 90
                },
                {
                    text: me.trans('vat') + ' %',
                    dataIndex: 'vat',
                    align: 'center',
                    width: 120
                }
            ]
        };
    },
    
    // Overwritten            
    initForm: function()
    {
        var me = this;
        
        this.config.form =
        {
            title: me.trans('article_type_form'),
            fields:
            [
                me.getMainFieldset(),
                me.getPropertiesFieldset(),
                me.getVatFieldset()
            ]
        };
    },
    
    getVatFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('vat'),
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
                            name: 'vat',
                            fieldLabel: me.trans('vat'),
                            allowBlank: false,
                            labelAlign: 'right',
                            minValue: 0, //prevents negative numbers                            
                            decimalPrecision: 2,
                            decimalSeparator: app_decimal_separator,
                            width: 200,
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