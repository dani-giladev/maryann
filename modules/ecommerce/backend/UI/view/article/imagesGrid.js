Ext.define('App.modules.ecommerce.backend.UI.view.article.imagesGrid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.ecommerce_article_images_grid',
    itemId: 'ecommerce_article_images_grid',
        
    explotation: 'E-commerce article images grid view',
    
    border: false,
    frame: false,
    autoScroll: true,
    height: 280,
    sortableColumns: false,
    enableColumnHide : false,
            
    config: null,
    record_id: null,
    _property: 'images',
    
    initComponent: function()
    {
        var me = this;

        this.title = '';        
        this.store = Ext.create('App.modules.ecommerce.backend.UI.store.article.images');
        
        // Drag and drop in order to sort rows
        this.enableDrag =  false;
        this.enableDrop = true;
        this.viewConfig =
        {
            plugins:
            [
                {
                    ptype: 'gridviewdragdrop',
                    dragGroup: 'ecommerce_article_images_grid_DDGroup',
                    dropGroup: 'ecommerce_article_images_grid_DDGroup'
                }

            ],
            listeners: {
                scope: me,
                itemcontextmenu: me.onContextMenu
            }             
        };
            
        this.columns =
        [
            {
                text: me.trans('image'),
                align: 'center',
                width: 100,
                renderer: me.formatPreview                  
            },        
            {
                text: me.trans('file'),
                flex: 1,
                renderer: me.formatTitle
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
                        text: me.trans('edit'),
                        handler: function()
                        {
                            // Get the model and permissions in order to show or hide update/delete buttons of file manager
                            var info_store = Ext.create('App.core.backend.UI.store.info');
                            info_store.on('load', function(this_store, records, successful, eOpts)
                            {
                                if (!records[0].data.success)
                                {
                                    Ext.MessageBox.show({
                                       title: 'Error',
                                       msg: records[0].data.message,
                                       buttons: Ext.MessageBox.OK,
                                       icon: Ext.MessageBox.ERROR
                                    });
                                    return;
                                }
            
                                var window = Ext.widget('common-window', {
                                    isFullScreen: true,
                                    title: App.app.getController('App.core.backend.UI.controller.common').trans('fileManager')
                                });
                                window.setHeight('100%');
                                window.setWidth('100%');
                                window.closable = true;

                                var imagesStore = me.getMaintenanceController().cloneStore(me.store);

                                var config = {
                                    permissions: records[0].data.permissions,
                                    enableSelectedEvent: true,
                                    enableSelectMultiImagesGrid: true,
                                    imagesStore: imagesStore
                                };
                                config.baseNode = "ARTICLES";
                                config.itemId = 'fileManager_ecommerce_brand_form';
                                config.hideTitle = true;

                                var file_manager = Ext.widget('fileManager', {
                                    config: config
                                });
                                file_manager.on('applyAssignedImagesFromMultiImage', function(store)
                                {
                                    me.getStore().removeAll();
                                    store.each(function(record)  
                                    {                                        
                                        me.getStore().add(record);
                                    });           
                
                                    // Close window
                                    var task = new Ext.util.DelayedTask(function(){
                                        window.close();
                                    });        
                                    task.delay(100);                                                            
                                }, this, {single: true}); 

                                window.add(file_manager);   
                                window.show();          

                            }, this, {single: true});  
                            info_store.load({
                                params: {
                                    module_id: me.config.module_id,
                                    model_id: 'fileManager',
                                    menu_id: 'fileManager',
                                    start: 0,
                                    limit: 9999
                                }
                            });   
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
                    },
                    '-',
                    {
                        xtype: 'button',
                        text: me.trans('download'),
                        handler: function()
                        {
                            var selection = me.getSelectionModel().getSelection();
                            if (!Ext.isEmpty(selection[0]))
                            {
                                me.download(selection[0]);
                            }
                        }
                    },
                    { xtype: 'tbfill' },
                    {
                        xtype: 'button',
                        text: 'Auto-scan',
                        handler: function()
                        {
                            
                            Ext.getBody().mask(me.trans('loading'));

                            Ext.Ajax.request({
                                type: 'ajax',
                                url : 'index.php',
                                method: 'GET',
                                params: {
                                    controller: 'modules\\ecommerce\\backend\\controller\\article', 
                                    method: 'getImageFilePaths',
                                    record_id: me.record_id
                                },
                                success: function(response, opts)
                                {
                                    Ext.getBody().unmask();
                                    var obj = Ext.JSON.decode(response.responseText);
                                    
                                    var records = obj.data.results;
                                    if (Ext.isEmpty(records))
                                    {
                                        return false;
                                    }                                       

                                    var filepath;
                                    Ext.each(records, function(rec)
                                    { 
                                        filepath = rec.relativePath + '/' + rec.filename;
                                        if (!me.exist(filepath))
                                        {
                                            me.getStore().add(rec); 
                                        }
                                    }); 
                                
                                },
                                failure: function(form, data)
                                {
                                    Ext.getBody().unmask();
                                }
                            }); 
                        }
                    }                         
                ]
            })
        ];
            
        me.callParent(arguments);
        
        me.store.on('load', me.onLoad, me);
        me.store.load({
            params:{
                controller: 'modules\\' + me.config.module_id + '\\backend\\controller\\article',
                record_id: me.record_id
            }
        });          
    },
    
    onContextMenu: function(view, record, item, index, e, eOpts)
    {
        var me = this;
        
        var menu = Ext.create('Ext.menu.Menu', 
        {
            items: 
            [
                {                       
                    text: me.trans('download'),
                    handler: function(e, t)
                    {
                        me.download(record);
                    }
                }
            ]
        });
        
        e.stopEvent();
        menu.showAt(e.getXY());
        e.preventDefault();
    },
    
    download: function(record)
    {
        var relative_path = record.get('relativePath');
        var filename = record.get('filename');
        var path = relative_path + '/' + filename;
        //console.log(path);

        var action = 
                '/?controller=core\\backend\\controller\\fileManager' + 
                '&method=downloadFile';

        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", action);
        form.setAttribute("target", "view");

        var path_field = document.createElement("input"); 
        path_field.setAttribute("type", "hidden");
        path_field.setAttribute("name", "path");
        path_field.setAttribute("value", path);
        form.appendChild(path_field);

        document.body.appendChild(form);
        //window.open('', 'view');
        form.submit();        
    },
    
    exist: function(filepath)
    {
        var me = this;
        
        var data_grid = me.store.getRange();
        if (Ext.isEmpty(data_grid))
        {
            return false;
        }
        
        var existed_filepath;
        var matched = false;
        Ext.each(data_grid, function(rec)
        { 
            existed_filepath = rec.get('relativePath') + '/' + rec.get('filename');
            if (existed_filepath === filepath)
            {
                matched = true;
                return;
            }
        });
        
        return matched;
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
    
    formatPreview: function(value, p, record)
    {
        var relative_path = record.get('relativePath');
        var filename = record.get('filename');
        var src = '/' + filemanager_path + '/' + relative_path + '/' + filename;
        var html = '<img src="' + src + '" width="60" height="60" border="0" />';
        return html;
    },
    
    formatTitle: function(value, p, record)
    {
        var relative_path = record.get('relativePath');
        var filename = record.get('filename');
        var path = relative_path;
        if (!Ext.isEmpty(path))
        {
            path += '/';
        }
        path += filename;
        return Ext.String.format('<div><b>{0}</b></br>{1}</div>', path, record.get('filesize')); 
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