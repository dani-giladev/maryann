Ext.define('App.modules.ecommerce.backend.UI.view.article.additionalData.images', {
    
    alias: 'widget.ecommerce_article_additionaldata_images',
    explotation: 'Images for ecommerce article (Additional data)',
    config: null,
    
    getForm: function(config, record)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: me.trans('images'),
            width: 600,
            height: 450,
            fields:
            [
                me.getImagesFieldset(record.data.id)                
            ]        
        };
        
        return ret;
    },
    
    getImageFieldset: function(image)
    { 
        var me = this;  
        var ret =              
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('showcase_image'),
            anchor: '100%',
            items: 
            [    
                {
                    xtype: 'panel',
                    title: '',
                    height: 240,
                    itemId: 'ecommerce_article_additionaldata_images_image_panel',
                    items:
                    [
                        me.createImagePanel(image) 
                    ]
                }
            ]
        };
        
        return ret;  
    },
    
    getImagesFieldset: function(record_id)
    { 
        var me = this;
        
        var ret =              
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('images'),
            anchor: '100%',
            items: 
            [    
                Ext.widget('ecommerce_article_images_grid', {
                    config: me.config,
                    record_id: record_id
                })
            ]
        };
        
        return ret;  
    },
    
    createImagePanel: function(image)
    {
        var me = this;
        if (!Ext.isEmpty(image))
        {
            image = '/' + filemanager_path + '/' + image;
        }
        
        return  {
                    xtype: 'panel',
                    title: '',
                    layout: {
                        type: 'vbox',
                        pack: 'center'
                    },
                    items:
                    [
                        {
                            xtype: 'container',
                            layout: {
                                type: 'hbox',
                                pack: 'center',
                                align: 'stretch'
                            },                               
                            width: '100%',
                            items:
                            [
                                {
                                    xtype: 'image',
                                    itemId: 'ecommerce_article_additionaldata_images_image_panel_image',
                                    width: 200,
                                    height: 200,
                                    border: 1,
                                    style: {
                                        borderColor: '#C8C8C8',
                                        borderStyle: 'solid'
                                    },
                                    src: image
                                },   
                                {
                                    xtype: 'container',
                                    layout: {
                                        type: 'vbox',
                                        pack: 'center',
                                        align: 'stretch'
                                    },                               
                                    margin: '0 0 0 15',
                                    items:
                                    [
                                        {
                                            xtype: 'button',
                                            text: me.trans('edit'),
                                            handler: function() {
                                                me.editImage(me.config);
                                            }
                                        },
                                        {
                                            xtype: 'button',
                                            text: '',
                                            icon: 'resources/ico/false.png',
//                                            padding: '0 0 0 15',
                                            style: {
                                                'background' : 'white',
                                                'padding-left': '16px',
                                                'border' : '0'
                                            },
                                            margin: '10 0 0 0',
                                            handler: function() {
                                                me.clearImage(me.config);
                                            }
                                        }                                        
                                    ]
                                }
                                
                            ]
                        },                        
                        {
                           xtype: 'textfield',
                           itemId: 'ecommerce_article_additionaldata_images_image_panel_textfield',
                           name: 'image',
                           fieldLabel: '',
                           allowBlank: false,     
                           width: '100%',
                           disabled: true,
                           style: {
                               'text-align' : 'center'
                           },
                           fieldStyle: 'text-align: center;',
                           margin: '10 10 0 10'
                       }                                
                    ]
                };       
    },
    
    editImage: function(config)
    {
        //var view = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getMaintenanceView(config);
        
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

            var file_manager = Ext.widget('fileManager', {
                config: {
                    permissions: records[0].data.permissions,
                    enableSelectedEvent: true,
                    baseNode: "ARTICLES"
                }
            });         
            file_manager.on('selectedFile', function(filename, filesize, filedate, relativePath, path) {
                var textfield = Ext.ComponentQuery.query('#ecommerce_article_additionaldata_images_image_panel_textfield')[0];
                var image = Ext.ComponentQuery.query('#ecommerce_article_additionaldata_images_image_panel_image')[0];
                textfield.setValue(path);
                var src = '/' + filemanager_path + '/' + path;
                image.setSrc(src);
            }, this, {single: true});       
            file_manager.show();                  

        }, this, {single: true});  
        info_store.load({
            params: {
                module_id: this.config.module_id,
                model_id: 'fileManager',
                menu_id: 'fileManager',
                start: 0,
                limit: 9999
            }
        });           
     
    },
            
    clearImage: function(config)
    {
        //var view = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getMaintenanceView(config);
        //var textfield = view.down('#ecommerce_article_additionaldata_images_image_panel_textfield');
        //var image = view.down('#ecommerce_article_additionaldata_images_image_panel_image');
        var textfield = Ext.ComponentQuery.query('#ecommerce_article_additionaldata_images_image_panel_textfield')[0];
        var image = Ext.ComponentQuery.query('#ecommerce_article_additionaldata_images_image_panel_image')[0];

        textfield.setValue('');
        image.setSrc('');     
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }    

});