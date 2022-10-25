Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.banners.columns.grid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.cms_webpage_additionaldata_banners_columns_grid',
    itemId: 'cms_webpage_additionaldata_banners_columns_grid',
        
    explotation: 'Cms webpage banners columns grid view (Additional data)',
    
    border: false,
    frame: false,
    autoScroll: true,
    height: 280,
    sortableColumns: false,
    enableColumnHide : false,
            
    columns_store: null,
    config: null,
    
    initComponent: function()
    {
        var me = this;

        this.title = '';        
        this.store = me.columns_store;
        
        // Drag and drop in order to sort columns
        this.enableDrag =  false;
        this.enableDrop = true;
        this.viewConfig =
        {
            plugins:
            [
                {
                    ptype: 'gridviewdragdrop',
                    dragGroup: 'cms_webpage_banners_columns_grid_DDGroup',
                    dropGroup: 'cms_webpage_banners_columns_grid_DDGroup'
                }

            ]             
        };
            
        this.columns =
        [
            {
                text: me.trans('image'),
                align: 'center',
                width: 200,
                renderer: me.formatPreview                  
            },        
            {
                text: me.trans('title'),
                flex: 1,
                renderer: me.formatTitle
            },
            {
                text: me.trans('available'),
                dataIndex: 'available',
                align: 'center',
                width: 90,
                renderer: me.formatBoolean                   
            }
        ];
        
        this.dockedItems =
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
                            var window = Ext.widget('cms_webpage_additionaldata_banners_columns_form_window', {
                                is_new_record: true,
                                config: me.config
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
        
        me.on('itemdblclick', me.onRowDblClick, me);         
    },

    onRender: function(grid, options)
    {      
        
        this.callParent(arguments);           
    },  
    
    onRowDblClick: function(view, record, item, index, e)
    {
        var me = this;
        me.edit(record);
    },
    
    edit: function(record)
    {
        var me = this;
        
        if (Ext.isEmpty(record))
        {
            return;
        }

        var window = Ext.widget('cms_webpage_additionaldata_banners_columns_form_window', {
            is_new_record: false,
            current_record: record,
            config: me.config
        });
        window.show(); 
        
    },
    
    formatPreview: function(value, p, record)
    {
        //console.log(record);
        var relative_path = record.get('image')['es'];
        var src = '';
        if (!Ext.isEmpty(relative_path))
        {
            src = '/' + filemanager_path + '/' + relative_path;
        }
        var html = '<img src="' + src + '" width="120" height="60" border="0" />';
        return html;
    },
    
    formatBoolean: function(value)
    {
        return Ext.String.format('<img src="resources/ico/'+(value ? 'true' : 'false')+'.png" />');
    }, 
    
    formatTitle: function(value, p, record)
    {
        var title = record.get('title')['es'];
        if (Ext.isEmpty(title))
        {
            title = '';
        }
        var url = record.get('url')['es'];
        if (Ext.isEmpty(url))
        {
            url = '';
        }
        var promo = '';
        if (!Ext.isEmpty(record.get('promo')))
        {
            promo = 'Promo: ' + record.get('promo');
        }
        return Ext.String.format('<div><b>{0}</b></br>{1}</br>{2}</div>', title, url, promo); 
    },      
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }

});