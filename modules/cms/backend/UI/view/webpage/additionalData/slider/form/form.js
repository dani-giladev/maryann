Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.slider.form.form', {
    extend: 'Ext.form.Panel',
    
    alias: 'widget.cms_webpage_additionaldata_slider_form',
    
    region: 'center',

    border: false,
    frame: false,
    bodyPadding: 10,
    autoScroll: true,
    
    config: null,
    is_new_record: true,
    current_record: null,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = ''; 

        me.items = 
        [   
            me.getPropertiesFieldset(),
            me.getImagesFieldset()
        ];
        
        me.callParent(arguments);
        
        // set combos stores dinamically
        me.getMaintenanceController().setComboStores(me); 
        if (!me.is_new_record && !Ext.isEmpty(me.current_record.data["promo"]))
        {
            var promo_field = me.getForm().findField("promo");
            promo_field.getStore().on('load', function(this_store, records, successful, eOpts) {
                promo_field.setValue(me.current_record.data["promo"]);
            });            
        }
        
        me.on('boxready', this.onBoxready, this);
    },  
    
    getPropertiesFieldset: function()
    {
        var me = this;
        
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('properties'),
            anchor: '100%',
            items:
            [
                {
                    xtype: 'checkboxfield',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    checked: me.getPropertyValue('available', true)
                },                        
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    items: 
                    [
                        {
                            xtype: 'combo',
                            name: 'promo',
                            fieldLabel: 'Promo',
                            _store: {
                                module_id: 'marketing',
                                model_id: 'promo',
                                fields: ['code', 'name'],
                                filters: [] //{field: 'available', value: true}                                
                            },
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: true,
                            typeAhead: true,
                            forceSelection: true, 
                            //bug//emptyText: me.trans('select_promo'),
                            allowBlank: true,
                            labelAlign: 'right',
                            width: '90%',
                            listeners: {
                                beforequery: function (record) {
                                    record.query = new RegExp(record.query, 'i');
                                    record.forceAll = true;
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            margin: '0 0 0 5',
                            text: "X",
                            width: 32,
                            handler: function()
                            {
                                var field = me.getForm().findField('promo');                        
                                field.setValue('');
                            }
                        } 
                    ]
                }
            ]
        };
        
        return ret;
    },
    
    getPropertyValue: function(name, default_value)
    {
        var me = this;
        var value = default_value;
        
        if (!me.is_new_record && !Ext.isEmpty(me.current_record.data[name]))
        {
            value = me.current_record.data[name];
        }
        
        return value;
    },
    
    getImagesFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('image'),
            anchor: '100%',
            items: 
            [    
                {
                    xtype: 'label',
                    itemId: 'cms_webpage_additionaldata_slider_form_images_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'cms_webpage_additionaldata_slider_form_tabpanel_images',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.createTabContent('images');
                        }
                    }
                }
            ]
        };
        
        return ret;
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var tab = Ext.ComponentQuery.query('#cms_webpage_additionaldata_slider_form_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        var value, name;
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#cms_webpage_additionaldata_slider_form_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            var i = 0;
            Ext.each(langs, function(lang)
            {
                if (type === 'images')
                {
                    tab.add({
                        xtype: 'panel',
                        title: lang.name,
                        autoHeight: true,
                        items:
                        [
                            me.getImagePanel(lang.code),
                            me.getTitleField(lang.code),
                            me.getUrlField(lang.code)

                        ]                        
                    });                 
                }
                else
                {
                    value = '';
                    name = type;
                    var form = me;
                    var is_new_record = form.is_new_record;
                    if (!is_new_record)
                    {
                        var record = me.current_record;
                    }                    
                    if (!is_new_record && !Ext.isEmpty(record.data[name]) && !Ext.isEmpty(record.data[name][lang.code]))
                    {
                        value = record.data[name][lang.code];
                    }                    
                    tab.add({
                            xtype: 'textfield',
                            title: lang.name,
                            name: name + '-' + lang.code,
                            fieldLabel: '',
                            anchor: '100%',
                            value: value
                    });                    
                }
                
                i++;
            }); 
        
            tab.setActiveTab(0);
        }         
    },
    
    getImagePanel: function(lang_code)
    {
        var me = this;
        
        var image = '';
        var value = '';
        var name = 'image';
        if (!me.is_new_record && !Ext.isEmpty(me.current_record.data[name]) && !Ext.isEmpty(me.current_record.data[name][lang_code]))
        {
            value = me.current_record.data[name][lang_code];
            image = '/' + filemanager_path + '/' + value;
        }    
        
        var width = 300;
        
        return  {
                    xtype: 'panel',
                    title: '',
                    layout: {
                        type: 'vbox',
                        pack: 'center'
                    },
                    margin: '20 0 20 0',
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
                                    itemId: 'cms_website_additionaldata_slider_form_image_image_' + lang_code,
                                    width: width,
                                    height: '150',
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
                                                me.editImage(lang_code);
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
                                                me.clearImage(lang_code);
                                            }
                                        }                                        
                                    ]
                                }
                                
                            ]
                        },                        
                        {
                           xtype: 'textfield',
                           itemId: 'cms_website_additionaldata_slider_form_image_textfield_' + lang_code,
                           name: 'image-' + lang_code,
                           fieldLabel: '',
                           allowBlank: true,     
                           width: '100%',
                           readOnly: true,
                           style: {
                               'text-align' : 'center'
                           },
                           fieldStyle: 'text-align: center;',
                           margin: '10 10 0 10',  
                           value: value
                       }                                
                    ]
                };       
    },
            
    editImage: function(lang_code)
    {
        var me = this;
        
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

            var config = {
                permissions: records[0].data.permissions,
                enableSelectedEvent: true 
            };
            config.baseNode = "SLIDERS";
            config.itemId = 'fileManager_cms_webpage_sliders_form';
            config.hideTitle = true;

            var file_manager = Ext.widget('fileManager', {
                config: config
            });         
            file_manager.on('selectedFile', function(filename, filesize, filedate, relativePath, path){
                var textfield = Ext.ComponentQuery.query('#cms_website_additionaldata_slider_form_image_textfield_' + lang_code)[0];
                var image = Ext.ComponentQuery.query('#cms_website_additionaldata_slider_form_image_image_' + lang_code)[0];
                textfield.setValue(path);
                var src = '/' + filemanager_path + '/' + path;
                image.setSrc(src);
                
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
                module_id: 'cms',
                model_id: 'fileManager',
                menu_id: 'fileManager',
                start: 0,
                limit: 9999
            }
        });           
     
    },
            
    clearImage: function(lang_code)
    {
        var textfield = Ext.ComponentQuery.query('#cms_website_additionaldata_slider_form_image_textfield_' + lang_code)[0];
        var image = Ext.ComponentQuery.query('#cms_website_additionaldata_slider_form_image_image_' + lang_code)[0];

        textfield.setValue('');
        image.setSrc('');     
    },
    
    getTitleField: function(lang_code)
    {
        var me = this;
        
        var value = '';
        var name = 'title';
        if (!me.is_new_record && !Ext.isEmpty(me.current_record.data[name]) && !Ext.isEmpty(me.current_record.data[name][lang_code]))
        {
            value = me.current_record.data[name][lang_code];
        } 
        
        var ret =  
        {
            xtype: 'textfield',
            name: name + '-' + lang_code,
            fieldLabel: 'Title',
            labelAlign: 'right',
            width: 650,
            labelWidth: 50,  
            value: value
        };
        
        return ret;
    },
    
    getUrlField: function(lang_code)
    {
        var me = this;
        
        var value = '';
        var name = 'url';
        if (!me.is_new_record && !Ext.isEmpty(me.current_record.data[name]) && !Ext.isEmpty(me.current_record.data[name][lang_code]))
        {
            value = me.current_record.data[name][lang_code];
        } 
        
        var ret =  
        {
            xtype: 'textfield',
            name: name + '-' + lang_code,
            fieldLabel: 'Url',
            labelAlign: 'right',
            width: 650,
            labelWidth: 50,  
            vtype: 'url',
            value: value
        };
        
        return ret;
    },
    
    onBoxready: function(this_panel, width, height, eOpts)
    {
        var me = this;
        me.is_box_ready = true;
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