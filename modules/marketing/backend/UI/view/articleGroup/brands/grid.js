Ext.define('App.modules.marketing.backend.UI.view.articleGroup.brands.grid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.marketing_articleGroup_brands_grid',
    itemId: 'marketing_articleGroup_brands_grid',
        
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
    _property: 'brands',
    
    initComponent: function()
    {
        var me = this;

        me.title = '';
        
        var fields = [
            'code', 'name'
        ];
        
        me.store = Ext.create('Ext.data.Store', {
            fields: fields,
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
                    dragGroup: 'marketing_articleGroup_brands_grid_DDGroup',
                    dropGroup: 'marketing_articleGroup_brands_grid_DDGroup'
                }
            ]             
        };
            
        me.columns =
        [        
            {
                text: me.trans('code'),
                dataIndex: 'code',
                width: 90
            },            
            {
                text: me.trans('name'),
                dataIndex: 'name',
                align: 'left',
                flex: 1
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
                            var window = Ext.widget('marketing_articleGroup_brands_window', {
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

        var window = Ext.widget('marketing_articleGroup_brands_window', {
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
        var lang_store = App.app.getController('App.modules.marketing.backend.UI.controller.marketing').getLangStore();
        return App.app.trans(id, lang_store);
    }

});