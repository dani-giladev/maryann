Ext.define('App.modules.marketing.backend.UI.view.articleGroup.articleTypes.form', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.marketing_articleGroup_articleTypes_form',
    
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
                    fieldLabel: me.trans('article_type'),
                    _store: {
                        module_id: 'ecommerce',
                        model_id: 'articleType',
                        fields: [
                            'code', 'name'
                        ],
                        filters: [] //{field: 'available', value: true}                               
                    },
                    valueField: 'code',
                    displayField: 'name',
                    queryMode: 'local',
                    editable: true,
                    typeAhead: true,
                    forceSelection: false,
                    //bug//emptyText: me.trans('?'),
                    labelAlign: 'right',
                    width: 200,
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