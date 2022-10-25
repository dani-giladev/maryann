Ext.define('App.modules.cms.backend.UI.view.website.additionalData.images', {
    
    alias: 'widget.cms_website_additionaldata_images',
    explotation: 'Images for website (Additional data)',
    config: null,
    
    getForm: function(config, record)
    {    
        var me = this;
        me.config = config;
        var ret =       
        {
            title: me.trans('images'),
            width: 700,
            height: 350,
            fields:
            [
                me.getLogoFieldset(record.data.logo)
            ]        
        };
        
        return ret;
    },      
    
    getLogoFieldset: function(image)
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Logo',
            anchor: '100%',
            items: 
            [    
                {
                    xtype: 'panel',
                    title: '',
                    autoHeight: true,
                    itemId: 'cms_website_additionaldata_images_logo_panel',
                    bodyStyle: {
                        'background-color': '#f6f6f6'
                    },
                    items:
                    [
                        me.createImagePanel(image) 
                    ]
                }
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
                    xtype: 'container',
                    width: '100%',
                    items:
                    [
                        {
                            xtype: 'container',
                            layout: {
                                type: 'hbox',
                                align: 'center',
                                pack: 'center'
                            }, 
                            width: '100%',
                            items:
                            [
                                {
                                    xtype: 'image',
                                    itemId: 'cms_website_additionaldata_images_logo_image',
                                    width: 300,
                                    height: 100,
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
                                        pack: 'center'
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
                                            style: {
                                                'background' : '#f6f6f6',
                                                'border' : '0'
                                            },
                                            margin: '10 0 0 15',
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
                           itemId: 'cms_website_additionaldata_images_logo_textfield',
                           name: 'logo',
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
                    baseNode: "LOGOS"
                }
            });         
            file_manager.on('selectedFile', function(filename, filesize, filedate, relativePath, path){
                var textfield = Ext.ComponentQuery.query('#cms_website_additionaldata_images_logo_textfield')[0];
                var image = Ext.ComponentQuery.query('#cms_website_additionaldata_images_logo_image')[0];
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
        //var textfield = view.down('#cms_website_additionaldata_images_logo_textfield');
        //var image = view.down('#cms_website_additionaldata_images_logo_image');
        var textfield = Ext.ComponentQuery.query('#cms_website_additionaldata_images_logo_textfield')[0];
        var image = Ext.ComponentQuery.query('#cms_website_additionaldata_images_logo_image')[0];

        textfield.setValue('');
        image.setSrc('');     
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }

});