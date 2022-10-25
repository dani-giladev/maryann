Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.banners.rows.grid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.cms_webpage_additionaldata_banners_rows_grid',
    itemId: 'cms_webpage_additionaldata_banners_rows_grid',
        
    explotation: 'Cms webpage banners rows grid view (Additional data)',
    
    border: false,
    frame: false,
    autoScroll: true,
    height: 650,
    sortableColumns: false,
    enableColumnHide : false,
            
    config: null,
    record_id: null,
    _property: 'banners',
    
    initComponent: function()
    {
        var me = this;

        this.title = '';        
        this.store = Ext.create('App.modules.cms.backend.UI.store.webpage.banners');
        
        // Drag and drop in order to sort rows
        this.enableDrag =  false;
        this.enableDrop = true;
        this.viewConfig =
        {
            plugins:
            [
                {
                    ptype: 'gridviewdragdrop',
                    dragGroup: 'cms_webpage_banners_rows_grid_DDGroup',
                    dropGroup: 'cms_webpage_banners_rows_grid_DDGroup'
                }

            ]             
        };
            
        this.columns =
        [
            {
                text: me.trans('image'),
                align: 'center',
                flex: 1,
                renderer: me.formatPreview                  
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
                            var window = Ext.widget('cms_webpage_additionaldata_banners_rows_form_window', {
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
        
        me.store.on('load', me.onLoad, me);
        me.store.load({
            params:{
                controller: 'modules\\' + me.config.module_id + '\\backend\\controller\\webpage',
                record_id: me.record_id
            }
        });          
    },

    onRender: function(grid, options)
    {      
        
        this.callParent(arguments);           
    },

    onLoad: function(this_store, records, successful, eOpts)
    {
//        if(this_store.getCount() > 0)
//        {
//            this.getSelectionModel().select(0);
//        }
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

        var window = Ext.widget('cms_webpage_additionaldata_banners_rows_form_window', {
            is_new_record: false,
            current_record: record,
            config: me.config
        });
        window.show(); 
        
    },
    
    formatPreview: function(value, p, record)
    {
        //console.log(record);
        // Row
        var r_width = record.get('width');
        var r_height = record.get('height');
        // Margin
        var r_marginTop = record.get('marginTop');
        var r_marginRight = record.get('marginRight');
        var r_marginBottom = record.get('marginBottom');
        var r_marginLeft = record.get('marginLeft');
        // Padding
        var r_paddingTop = record.get('paddingTop');
        var r_paddingRight = record.get('paddingRight');
        var r_paddingBottom = record.get('paddingBottom');
        var r_paddingLeft = record.get('paddingLeft');
        
        var html = 
                '<table ' + 
                    'width="' + r_width + '" ' + 
                    'height="' + r_height + '" ' + 
                    'style="' + 
                        'margin-top=' + r_marginTop + '; margin-right=' + r_marginRight + '; margin-bottom=' + r_marginBottom + '; margin-left=' + r_marginLeft + 
                        '; padding-top=' + r_paddingTop + '; padding-right=' + r_paddingRight + '; padding-bottom=' + r_paddingBottom + '; padding-left=' + r_paddingLeft + 
                    ';" ' + 
                '>';
        
        var columns = record.get('columns');
        if (Ext.isEmpty(columns))
        {
            html += '<td>This row doesn\'t have any column</td>'
        }
        else
        {
            Ext.each(columns, function(column)
            { 
                //console.log(column);
                // Column
                var c_width = column.width;
                var c_height = '100px'; //column.height;
                // Margin
                var c_marginTop = column.marginTop;
                var c_marginRight = column.marginRight;
                var c_marginBottom = column.marginBottom;
                var c_marginLeft = column.marginLeft;
                // Padding
                var c_paddingTop = column.paddingTop;
                var c_paddingRight = column.paddingRight;
                var c_paddingBottom = column.paddingBottom;
                var c_paddingLeft = column.paddingLeft;
        
                html += 
                    '<td ' + 
//                        'width="' + c_width + '" ' + 
//                        'height="' + c_height + '" ' + 
                        'style="' + 
                            'margin-top=' + c_marginTop + '; margin-right=' + c_marginRight + '; margin-bottom=' + c_marginBottom + '; margin-left=' + c_marginLeft + 
                            '; padding-top=' + c_paddingTop + '; padding-right=' + c_paddingRight + '; padding-bottom=' + c_paddingBottom + '; padding-left=' + c_paddingLeft + 
                        ';" ' + 
                    '>';
            
                var relative_path = column.image['es'];
                var src = '';
                if (!Ext.isEmpty(relative_path))
                {
                    src = '/' + filemanager_path + '/' + relative_path;
                }
                html += '<img src="' + src + '" width="100%" height="auto" border="0" />';                
            
                html += '</td>';
            });
            
        }
        
        html += '</table>';
        //console.log(html);
        
        return html;
    },
    
    formatBoolean: function(value)
    {
        return Ext.String.format('<img src="resources/ico/'+(value ? 'true' : 'false')+'.png" />');
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