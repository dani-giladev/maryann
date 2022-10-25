Ext.define('App.modules.ecommerce.backend.UI.view.categories.categories', {
    extend: 'App.core.backend.UI.view.maintenance.typeTree.maintenance',
    
    alias: 'widget.ecommerce_categories',
        
    explotation: 'E-Commerce categories view',
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        // The form
        this.initForm();

        this.callParent(arguments); 
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = false;               
        this.config.enable_publication = false;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\ecommerce\\backend\\controller\\categories';
        this.config.publish_controller = this.config.save_controller;
    },
    
    initForm: function()
    {      
        var me = this;
        
        me.config.form =
        {
            title: me.trans('article_category_form'),
            width: 1000,
            height: 800,    
            fields:
            [
                {
                    xtype: 'tabpanel',
                    activeTab: 0,
                    items: 
                    [
                        me.getMainTab(),
                        me.getSecondTab()
                    ],
                    listeners: {
                        afterrender: function(tab)
                        {
                            tab.setActiveTab(1); 
                            tab.setActiveTab(0);                            
                        }
                    }
                }               
            ]
        };
    },
    
    getMainTab: function()
    {
        var me = this;
        
        return {
            title: me.trans('main'),
            padding: 5,
            items:
            [
                me.getMainFieldset(),
                me.getPropertiesFieldset(),
                me.getTitlesFieldset(),
                me.getSEOFieldset()  
            ]
        };
    },
    
    getSecondTab: function()
    {
        var me = this;
        
        return {
            title: me.trans('descriptions'),
            padding: 5,
            items:
            [
                {
                    xtype: 'container',
                    layout: 'hbox',
                    items:
                    [
                        me.getImageFieldset(1),
                        me.getLongDescriptionsFieldset(1)
                    ]
                },
                me.getLongDescriptionsFieldset(2)
            ]
        };
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
//            hidden: true,
            items: 
            [
                {
                    xtype: 'textfield',
                    name: 'code',
                    fieldLabel: '<b>' + me.trans('code') + '</b>',
                    maskRe: /[a-zA-Z0-9\-\_]/,
                    allowBlank: false,
                    labelAlign: 'right',
                    _disabledOnEdit: true,
                    _setFocusOnNew: true,
                    width: 300
                }                
            ]
        };
        
        return ret;
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
                    xtype: 'textfield',
                    name: 'name',
                    fieldLabel: me.trans('name'),
                    allowBlank: false,
                    labelAlign: 'right',
                    anchor: '100%'
                },
                {
                    xtype: 'checkboxfield',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    _defaultValue: true // checked when new record
                },
                {
                    xtype: 'checkboxfield',
                    name: 'empty',
                    fieldLabel: me.trans('empty_female'),
                    boxLabel: '',
                    labelAlign: 'right',                
                    _defaultValue: false // checked when new record
                }   
            ]
        };
        
        return ret;
    },
    
    getTitlesFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('title') + ' (front-end)',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_categories_title_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: 'ecommerce_categories_tabpanel_title',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_title_rendered = true;
                            me.createTabsContent();
                        },
                        tabchange: function(this_tab, newCard, oldCard, eOpts ) {
                            //var url_tab = Ext.ComponentQuery.query('#ecommerce_categories_tabpanel_url')[0];
                            //url_tab.setActiveTab(newCard._tabIndex);       
                            var description_tab = Ext.ComponentQuery.query('#ecommerce_categories_tabpanel_description')[0];
                            description_tab.setActiveTab(newCard._tabIndex); 
                            var keywords_tab = Ext.ComponentQuery.query('#ecommerce_categories_tabpanel_keywords')[0];
                            keywords_tab.setActiveTab(newCard._tabIndex);                      
                        }
                    }
                }
            ]
        };
        
        return ret;
    },
    
    getSEOFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'SEO',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'textfield',
                    name: 'canonical',
                    fieldLabel: 'Canonical' + ' (' + me.trans('code') + ')',
                    maskRe: /[a-zA-Z0-9\-\_]/,
                    allowBlank: true,
                    labelAlign: 'right',
//                    margin: '0 0 10 0',
                    labelWidth: 150,  
                    width: 350
                },
                {
                    xtype: 'label',
                    text: 'Url:',
                    margin: '0 0 5 10'
                },          
                {
                    xtype: 'label',
                    itemId: 'ecommerce_categories_url_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: 'ecommerce_categories_tabpanel_url',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_url_rendered = true;
                            me.createTabsContent();                          
                        }
                    },
                    margin: '0 0 10 0'
                },
                me.getDescriptionsContainer(),
                me.getKeywordsContainer() 
            ]
        };
        
        return ret;
    },
    
    getDescriptionsContainer: function()
    {
        var me = this;
        var tabItemId = 'ecommerce_categories_tabpanel_description'; 
        
        var ret =  
        {
            xtype: 'container',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    text: me.trans('meta_description') + ':',
                    margin: '0 0 5 10'
                },   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_categories_description_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_description_rendered = true;
                            me.createTabsContent();                          
                        }
                    }
                },
                me.getMaintenanceTreeController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };
        
        return ret;
    },
    
    getKeywordsContainer: function()
    {
        var me = this;
        var tabItemId = 'ecommerce_categories_tabpanel_keywords'; 
        
        var ret =  
        {            
            xtype: 'container',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    text: 'Keywords' + ':',
                    margin: '0 0 5 10'
                },
                {
                    xtype: 'label',
                    itemId: 'ecommerce_categories_keywords_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: 'ecommerce_categories_tabpanel_keywords',
                    listeners: {
                        render: function(this_tab, eOpts) {
                            me.tabpanel_keywords_rendered = true;
                            me.createTabsContent();                          
                        }
                    }
                },
                me.getMaintenanceTreeController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };
        
        return ret;
    },
    
    getLongDescriptionsFieldset: function(i)
    {
        var me = this;
        var tabItemId = 'ecommerce_categories_tabpanel_longdescription' + i; 
        
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('description') + ' ' + i,
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_categories_longdescription' + i + '_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',
                    itemId: tabItemId,
                    listeners: {
                        render: function(this_tab, eOpts) {
                            if (i==1)
                            {
                                me.tabpanel_longdescription1_rendered = true;
                            }
                            else
                            {
                                me.tabpanel_longdescription2_rendered = true;
                            }
                            me.createTabsContent();                          
                        }
                    }
                },
                me.getMaintenanceTreeController().getActionMenuButtonForHtmlManagement(tabItemId)
            ]
        };
        
        if (i === 1)
        {
            ret.flex = 1;
            ret.margin = '0 0 0 5';
        }
        else
        {
            ret.anchor = '100%';
        }
        
        return ret;
    },
    
    getImageFieldset: function(i)
    {
        var me = this;
        
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('image') + ' ' + i,
            width: 200,
            height: 296,
            bodyStyle: {
                'background-color': '#f6f6f6'
            },
            items: 
            [    
                {
                    xtype: 'panel',
                    title: '',
                    autoHeight: true,
                    bodyStyle: {
                        'background-color': '#f6f6f6'
                    },
                    items:
                    [
                        me.createImagePanel(i) 
                    ]
                }
            ]
        };
        
        return ret;
    },
    
    createImagePanel: function(i)
    {
        var me = this;
        
        return  {
            xtype: 'panel',
            title: '',
            bodyStyle: {
                'background-color': '#f6f6f6'
            },
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
                            itemId: 'ecommerce_categories_image' + i,
                            width: 140,
                            height: 140,
                            border: 1,
                            style: {
                                borderColor: '#C8C8C8',
                                borderStyle: 'solid'
                            },
                            src: '',
                            listeners: {
                                render: function(image)
                                {
                                    var form = me.getMaintenanceTreeController().getEditFormView(me.config);
                                    var is_new_node = form.is_new_node;
                                    if (!is_new_node)
                                    {
                                        var tree = me.getMaintenanceTreeController().getTreeView(me.config);
                                        var selected = tree.getSelectionModel().getSelection();
                                        var selectedNode = selected[0];
                                        var record = me.getMaintenanceTreeController().getNodeRecord(selectedNode);
                                    }

                                    var name = 'image' + i;
                                    var src = '';
                                    if (!is_new_node && !Ext.isEmpty(record.data[name]))
                                    {
                                        src = '/' + filemanager_path + '/' + record.data[name];
                                    }
                                    
                                    image.setSrc(src);
                                }
                            }
                        }
                    ]
                },                        
                {
                    xtype: 'textfield',
                    itemId: 'ecommerce_categories_image' + i + '_textfield',
                    name: 'image1',
                    fieldLabel: '',
                    allowBlank: false,     
                    width: '100%',
                    disabled: true,
                    style: {
                        'text-align' : 'center'
                    },
                    fieldStyle: 'text-align: center;',
                    margin: '10 5 10 5'
               },
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
                            xtype: 'button',
                            text: me.trans('edit'),
                            width: 60,
                            handler: function() {
                                me.editImage(me.config, i);
                            }
                        },
                        {
                            xtype: 'button',
                            text: '',
                            icon: 'resources/ico/false.png',
                            style: {
                                'background' : '#f6f6f6',
                                'padding-left': '10px',
                                'border' : '0'
                            },
                            margin: '0 0 0 5',
                            handler: function() {
                                me.clearImage(me.config, i);
                            }
                        }                                        
                    ]
                }                       
            ]
        };       
    },
    
    createTabsContent: function()
    {
        var me = this;
        
        if (!me.canWeCreateTabs())
        {
            return;
        }
        
        if (me.tabpanel_title_rendered)
        {
            me.createTabContent('title');
            me.tabpanel_title_rendered = false;
        }
        if (me.tabpanel_url_rendered)
        {
            me.createTabContent('url');
            me.tabpanel_url_rendered = false;
        }
        if (me.tabpanel_description_rendered)
        {
            me.createTabContent('description');
            me.tabpanel_description_rendered = false;
        }
        if (me.tabpanel_longdescription1_rendered)
        {
            me.createTabContent('longdescription1');
            me.tabpanel_longdescription1_rendered = false;
        }
        if (me.tabpanel_longdescription2_rendered)
        {
            me.createTabContent('longdescription2');
            me.tabpanel_longdescription2_rendered = false;
        }
        if (me.tabpanel_keywords_rendered)
        {
            me.createTabContent('keywords');
            me.tabpanel_keywords_rendered = false;
        }
                            
    },
    
    canWeCreateTabs: function()
    {
        var me = this;
        return (me.tabpanel_title_rendered && 
                me.tabpanel_url_rendered && 
                me.tabpanel_description_rendered && 
                me.tabpanel_longdescription1_rendered && 
                me.tabpanel_longdescription2_rendered && 
                me.tabpanel_keywords_rendered);
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var lang_code, lang_name, i, height;
        //var tab = me.down('#ecommerce_categories_tabpanel_' + type);
        var tab = Ext.ComponentQuery.query('#ecommerce_categories_tabpanel_' + type)[0];
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#ecommerce_categories_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            var form = me.getMaintenanceTreeController().getEditFormView(me.config);
            var is_new_node = form.is_new_node;
            if (!is_new_node)
            {
                var tree = me.getMaintenanceTreeController().getTreeView(me.config);
                var selected = tree.getSelectionModel().getSelection();
                var selectedNode = selected[0];
                var record = me.getMaintenanceTreeController().getNodeRecord(selectedNode);
            }
            
            i = 0;
            Ext.each(langs, function(lang) {
                lang_code = lang.code;
                lang_name = lang.name;
                
                var value = '';
                var name;
                if (type === 'title' || type === 'description' || type === 'keywords')
                {
                    if (type === 'title')
                    {
                        name = 'titles';
                    }
                    else if (type === 'description')
                    {
                        name = 'descriptions';
                    }
                    else
                    {
                        name = 'keywords';
                    }
                    name += '-' + lang_code;
                    if (!is_new_node && !Ext.isEmpty(record.data[name]))
                    {
                        value = record.data[name];
                    }
                    tab.add({
                            xtype: 'textfield',
                            title: lang_name,
                            name: name,
                            _lang_code: lang_code,
                            _tabIndex: i,
                            fieldLabel: '',
                            anchor: '100%',
                            value: value,
                            enableKeyEvents: true,
                            listeners: {
                                keyup: function(field, e, eOpts)
                                {
                                    /*
                                    var name = field.getValue();
                                    var lang_code = field._lang_code;
                                    var url_field = Ext.ComponentQuery.query('#ecommerce_categories_tabpanel_textfield_url_' + lang_code)[0];
                                    var url = App.app.getController('App.core.backend.UI.controller.common').convert2URLText(name);
                                    url_field.setValue(url);
                                    */
                                }
                            }
                    });                      
                }
                else if (type === 'url')
                {
                    name = 'url';
                    name = name + lang_code[0].toUpperCase() + lang_code.slice(1);
                    if (!is_new_node && !Ext.isEmpty(record.data[name]))
                    {
                        value = record.data[name];
                    }
                    var object = {
                        xtype: 'textfield',
                        itemId: 'ecommerce_categories_tabpanel_textfield_'+ type +'_' + lang_code,
                        maskRe: /[a-zA-Z0-9\-]/,
                        title: lang_name,
                        name: name,
                        fieldLabel: '',
                        anchor: '100%', 
                        value: value                 
                    };     
                    /*if (!is_super_user && type === 'url')
                    {
                        object.fieldStyle = {
                            'background-color' : 'silver'
                        };
                        object.readOnly = true;                        
                    }*/
                    
                    tab.add(object);  
                }
                else if (type === 'longdescription1' || type === 'longdescription2')
                {
                    if (type === 'longdescription1')
                    {
                        name = 'longDescription1';
                        height = 150;
                    }
                    else
                    {
                        name = 'longDescription2';
                        height = 300;
                    }
                    name = name + lang_code[0].toUpperCase() + lang_code.slice(1);
                    if (!is_new_node && !Ext.isEmpty(record.data[name]))
                    {
                        value = record.data[name];
                    }
                    tab.add({
                        xtype: 'htmleditor',
                        title: lang_name,
                        name: name,
                        _lang_code: lang_code,
                        fieldLabel: '',
                        anchor: '100%',
                        height: height,
                        autoScroll: true,
                        enableFont: false,
                        value: value
                    });                    
                }
                i++;
            }); 
            
            tab.setActiveTab(0);                                            
        }         
    },
            
    editImage: function(config, i)
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

            var config = {
                permissions: records[0].data.permissions,
                enableSelectedEvent: true 
            };
            config.baseNode = "CATEGORIES";
            config.itemId = 'fileManager_ecommerce_categories_tree';
            config.hideTitle = true;

            var file_manager = Ext.widget('fileManager', {
                config: config
            });         
            file_manager.on('selectedFile', function(filename, filesize, filedate, relativePath, path) {
                var textfield = Ext.ComponentQuery.query('#ecommerce_categories_image' + i + '_textfield')[0];
                var image = Ext.ComponentQuery.query('#ecommerce_categories_image' + i)[0];
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
                module_id: this.config.module_id,
                model_id: 'fileManager',
                menu_id: 'fileManager',
                start: 0,
                limit: 9999
            }
        });           
     
    },
            
    clearImage: function(config, i)
    {
        var textfield = Ext.ComponentQuery.query('#ecommerce_categories_image' + i + '_textfield')[0];
        var image = Ext.ComponentQuery.query('#ecommerce_categories_image' + i)[0];

        textfield.setValue('');
        image.setSrc('');     
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').alertInitMaintenance(this.config);              
    },
        
    getMaintenanceTreeController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.typeTree');       
        return controller;
    }    
    
});