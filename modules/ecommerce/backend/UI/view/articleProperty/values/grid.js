Ext.define('App.modules.ecommerce.backend.UI.view.articleProperty.values.grid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.ecommerce_article_property_values_grid',
    itemId: 'ecommerce_article_property_values_grid',
        
    border: false,
    frame: false,
    minHeight: 200,
    height: 'auto',
//    height: 200,
//    autoScroll: true,
    sortableColumns: false,
    enableColumnHide : false,
            
    config: null,
    record_id: null,
    _property: 'values',
    
    initComponent: function()
    {
        var me = this;

        me.title = '';        
        me.store = Ext.create('Ext.data.Store', {
            model: 'App.modules.ecommerce.backend.UI.model.articleProperty.value',
            data : [],
            sorters: [{
                property: 'name',
                direction: 'ASC'
            }]
        });
        
        // Drag and drop in order to sort rows
        me.enableDrag =  false;
        me.enableDrop = true;
        me.viewConfig =
        {
           
        };
            
        me.columns =
        [        
            {
                text: me.trans('code'),
                dataIndex: 'code',
                width: 100
            },       
            {
                text: me.trans('name'),
                dataIndex: 'name',
                flex: 1
            },
            {
                text: me.trans('text'),
                dataIndex: 'texts',
                align: 'center',
                width: 200,
                renderer: me.formatText   
            },
            {
                text: me.trans('available'),
                dataIndex: 'available',
                align: 'center',
                width: 120,
                renderer: me.formatBoolean                   
            }
        ];
        
        me.dockedItems =
        [
            Ext.create('Ext.toolbar.Toolbar', 
            {
                dock: 'bottom', //'top',
                items: 
                [
                    {
                        xtype: 'button',
                        text: me.trans('add'),
                        handler: function()
                        {
                            var window = Ext.widget('ecommerce_article_property_values_window', {
                                is_new_record: true
                            });
                            window.show();
                        }
                    },
                    {
                        xtype: 'button',
                        text: me.trans('edit'),
                        handler: function()
                        {
                            var selection = me.getSelectionModel().getSelection();
                            if (Ext.isEmpty(selection[0]))
                            {
                                return;
                            }
                            me.edit(selection[0]);
                            
                        }
                    },
                    {
                        xtype: 'button',
                        text: me.trans('remove'),
                        handler: function()
                        {
                            var selection = me.getSelectionModel().getSelection();
                            if (!Ext.isEmpty(selection[0]))
                            {
                                me.getStore().remove(selection[0]); 
                            }
                        }
                    }                        
                ]
            })
        ];
            
        me.callParent(arguments);
        
        this.on('itemdblclick', this.onRowDblClick, this);
    },
    
    edit: function(record)
    {
        if (Ext.isEmpty(record))
        {
            return;
        }

        var window = Ext.widget('ecommerce_article_property_values_window', {
            is_new_record: false,
            current_record: record
        });
        window.show();
        
    },
    
    onRowDblClick: function(view, record, item, index, e)
    {
        var me = this;
        me.edit(record);
    },
    
    formatText: function(texts, meta, record)
    {
        if (Ext.isEmpty(texts))
        {
            return '';
        }
        
        var amount = '';
        if (!Ext.isEmpty(record.get('amount')))
        {
            amount = record.get('amount') + ' ';
        }
        
        for (var lang in texts) {
            var value = texts[lang];
            if (!Ext.isEmpty(value))
            {
                return '<b>' + amount + value + '</b>';
            }
        }
        
        return '';
    },
    
    formatBoolean: function(value)
    {
        return Ext.String.format('<img src="resources/ico/'+(value ? 'true' : 'false')+'.png" />');
    }, 
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }

});