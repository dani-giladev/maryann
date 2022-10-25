Ext.define('App.modules.ecommerce.backend.UI.view.article.properties.grid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.ecommerce_article_properties_grid',
    itemId: 'ecommerce_article_properties_grid',
        
    border: false,
    frame: false,
    minHeight: 94,
    height: 'auto',
//    height: 200,
//    autoScroll: true,
    sortableColumns: false,
    enableColumnHide : false,
            
    config: null,
    record_id: null,
    _property: 'properties',
    
    initComponent: function()
    {
        var me = this;

        me.title = '';        
        me.store = Ext.create('Ext.data.Store', {
            model: 'App.modules.ecommerce.backend.UI.model.article.property',
            data : []
        });
        
        // Drag and drop in order to sort rows
        me.enableDrag =  false;
        me.enableDrop = true;
        me.viewConfig =
        {
            plugins:
            [
                {
                    ptype: 'gridviewdragdrop',
                    dragGroup: 'ecommerce_article_properties_grid_DDGroup',
                    dropGroup: 'ecommerce_article_properties_grid_DDGroup'
                }
            ]             
        };
            
        me.columns =
        [        
            {
                text: me.trans('property'),
                dataIndex: 'code',
                align: 'left',
                width: 200,
                renderer: me.formatCode
            },            
            {
                text: me.trans('amount'),
                dataIndex: 'amount',
                align: 'center',
                width: 120
            },            
            {
                text: me.trans('value'),
                dataIndex: 'value',
                align: 'center',
                flex: 1,
                renderer: me.formatValue
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
                            var window = Ext.widget('ecommerce_article_properties_window', {
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

        var window = Ext.widget('ecommerce_article_properties_window', {
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
    
    formatCode: function(value, meta, record)
    {
        if (Ext.isEmpty(value))
        {
            return '';
        }
        
        return value;
    },
    
    formatValue: function(value, meta, record)
    {
        if (Ext.isEmpty(value))
        {
            return '';
        }
        
        return value;
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }

});