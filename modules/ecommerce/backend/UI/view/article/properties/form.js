Ext.define('App.modules.ecommerce.backend.UI.view.article.properties.form', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.ecommerce_article_properties_form',
    

    border: false,
    frame: false,
    bodyPadding: 10,
    autoScroll: true,
    
    is_new_record: true,
    current_record: null,
    value_combo_initialized: false,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = ''; 

        me.items = 
        [   
            me.getMainFieldset()
        ];
        
        me.dockedItems = [
            Ext.widget('ecommerce_article_properties_toolbar')   
        ];
        
        me.callParent(arguments);
        
        // Add custom listeners
        me.getMaintenanceController().addListeners(me);        
        
        // set combos stores dinamically
        me.getMaintenanceController().setComboStores(me);  
        if (!me.is_new_record)
        {
            var code_field = me.getForm().findField("code");
            code_field.getStore().on('load', function(this_store, records, successful, eOpts) {
                code_field.setValue(me.current_record.get('code'));
            });            
        }
        
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
                    xtype: 'combo',
                    name: 'code',
                    fieldLabel: me.trans('code'),
                    _store: {
                        module_id: 'ecommerce',
                        model_id: 'articleProperty',
                        fields: ['code', 'name']                                
                    },
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: true, 
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts)
                        {
                            if (!me.is_box_ready) return false;
                            if (newValue === oldValue) return false;
                            me.updateValueComboField(newValue, true);
                        },
                        beforequery: function (record) {
                            record.query = new RegExp(record.query, 'i');
                            record.forceAll = true;
                        }
                    }
                },
                {
                    xtype: 'numberfield',
                    name: 'amount',
                    fieldLabel: me.trans('amount'),
                    labelAlign: 'right',
                    minValue: 0, //prevents negative numbers                            
                    decimalPrecision: 2,
                    decimalSeparator: app_decimal_separator,
                    width: 200/*,
                    // Remove spinner buttons, and arrow key and mouse wheel listeners
                    hideTrigger: true,
                    keyNavEnabled: false,
                    mouseWheelEnabled: false*/
                },
                {
                    xtype: 'combo',
                    name: 'value',
                    fieldLabel: me.trans('value'),
                    store: Ext.create('App.modules.ecommerce.backend.UI.store.article.properties'),
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: true, 
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%',
                    listeners: {
                        beforequery: function (record) {
                            record.query = new RegExp(record.query, 'i');
                            record.forceAll = true;
                        }
                    }  
                }                
            ]
        };
        
        return ret;
    },
    
    updateValueComboField: function(code, clear)
    {
        var me = this;
        var values_field = me.getForm().findField('value');
        
        if (clear || Ext.isEmpty(code))
        {
            values_field.clearValue();
            if (Ext.isEmpty(code))
            {
                return;
            }
        }
        
        var values_store = values_field.getStore();
        values_store.on('load', function(this_store, records, successful, eOpts)
        {             
            if (!me.is_new_record && !me.value_combo_initialized)
            {
                values_field.setValue(me.current_record.get('value'));
            }
            
            me.value_combo_initialized = true;
            
        }, this, {single: true});
       
//        var filters = [
//            {field: 'available', value: true},
//        ];
//        var json_filters = Ext.JSON.encode(filters);  
        values_store.load({
            params: {
                code: code
            }
        });        
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