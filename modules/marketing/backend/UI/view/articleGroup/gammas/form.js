Ext.define('App.modules.marketing.backend.UI.view.articleGroup.gammas.form', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.marketing_articleGroup_gammas_form',
    
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
            me.getMainFieldset()
        ];
        
        me.callParent(arguments);
        
        // set combos stores dinamically
        me.getMaintenanceController().setComboStores(me);  
        if (!me.is_new_record)
        {
            var brand_field = me.getForm().findField("brand");
            brand_field.getStore().on('load', function(this_store, records, successful, eOpts) {

                brand_field.setValue(me.current_record.get('brand'));
                me.updateGammaComboField(me.current_record.get('brand'), false, me.current_record.get('code'));
                
            }, this, {single: true});     
        }
        
        me.on('boxready', this.onBoxready, this);
    },
    
    onBoxready: function(this_panel, width, height, eOpts)
    {
        var me = this;
        var task = new Ext.util.DelayedTask(function(){
            me.is_box_ready = true;
        });        
        task.delay(200);        
        
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
                    name: 'brand',
                    fieldLabel: me.trans('brand'),
                    _store: {
                        module_id: 'ecommerce',
                        model_id: 'brand',
                        fields: ['code', 'name'],
                        filters: [] //{field: 'available', value: true}                                
                    },
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: false, 
                    //bug//emptyText: me.trans('select_brand'),
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%',
                    listeners: {
                        change: function(field, newValue, oldValue, eOpts)
                        {
                            if (!me.is_box_ready) return false;
                            if (newValue === oldValue) return false;
                            me.updateGammaComboField(newValue, true);
                        },
                        beforequery: function (record) {
                            record.query = new RegExp(record.query, 'i');
                            record.forceAll = true;
                        }
                    }        
                },
                {
                    xtype: 'combo',
                    name: 'code',
                    fieldLabel: me.trans('gamma'),
                    _store: {
                        autoload: 'no',
                        module_id: 'ecommerce',
                        model_id: 'gamma',
                        fields: ['code', 'name'],
                        filters: [] //{field: 'available', value: true}                                
                    },
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: true,
                    //bug//emptyText: me.trans('select_gamma'),
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
    
    updateGammaComboField: function(brand_value, clear, value)
    {
        var me = this;
        var gamma_field = me.getForm().findField('code');
                            
        if (clear || Ext.isEmpty(brand_value))
        {
            gamma_field.clearValue();
            if (Ext.isEmpty(brand_value))
            {
                return;
            }
        }
        
        var gamma_store = gamma_field.getStore();
        gamma_store.on('load', function(this_store, records, successful, eOpts)
        {             
            if (value)
            {
                gamma_field.setValue(value);
            }

        }, this, {single: true});
       
        var filters = [
            //{field: 'available', value: true},
            {field: 'brand', value: brand_value}
        ];
        var json_filters = Ext.JSON.encode(filters);  
        gamma_store.load({
            params: {
                module_id: 'ecommerce',
                model_id: 'gamma',
                stale: 'true',                                            
                filters: json_filters
            }
        });        
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.marketing.backend.UI.controller.marketing').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
    
});