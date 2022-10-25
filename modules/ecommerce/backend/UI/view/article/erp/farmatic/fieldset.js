Ext.define('App.modules.ecommerce.backend.UI.view.article.erp.farmatic.fieldset', {
    extend: 'Ext.form.FieldSet',
    
    alias: 'widget.ecommerce_article_erp_farmatic_fieldset',
        
    explotation: 'E-Commerce interface fieldset view',

    config: null,
    
    padding: 5,
    anchor: '100%',
    
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
        me.title = app_erp_interface_description;
    },
    
    initFieldset: function()
    {
        var me = this;
        me.items =
        [
            Ext.widget('ecommerce_article_erp_farmatic_grid', {
                config: me.config
            }),
            {
                xtype: 'container',
                layout: 'hbox',
                items: 
                [

                    {
                        xtype: 'checkboxfield',
                        name: 'syncStock',
                        fieldLabel: 'Sync stock',
                        boxLabel: '',
                        labelAlign: 'right',
                        _defaultValue: true
                    },        
                    {
                        xtype: 'checkboxfield',
                        name: 'syncCostPrice',
                        fieldLabel: 'Sync ' + me.trans('cost_price').toLowerCase(),
                        boxLabel: '',
                        labelAlign: 'right',
                        _defaultValue: true
                    },        
                    {
                        xtype: 'checkboxfield',
                        name: 'syncMargin',
                        fieldLabel: 'Sync ' + me.trans('margin').toLowerCase(),
                        boxLabel: '',
                        labelAlign: 'right',
                        _defaultValue: true
                    }
                ]
            }            
        ];
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }
     
});