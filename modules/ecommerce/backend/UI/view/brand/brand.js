Ext.define('App.modules.ecommerce.backend.UI.view.brand.brand', {
    extend: 'App.core.backend.UI.view.maintenance.type1.maintenance',
    
    alias: 'widget.ecommerce_brand',
        
    explotation: 'E-commerce brand view',

    config: null,
    
    initComponent: function() {
        this.alert();
        
        // General properties
        this.initGeneralProperties();
        // The grid
        this.initGrid();
        // The form
        this.initForm();
        // The dynamic filter form
        this.initDynamicFilterForm();

        this.callParent(arguments);
        
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        form.on('newRecord', this.onNewRecord);
        form.on('editedRecord', this.onEditedRecord);     
        
        this.addAdditionalDataMenu();
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_clone = true;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\ecommerce\\backend\\controller\\brand';
        this.config.publish_controller = this.config.save_controller;
        this.config.clone_controller = this.config.save_controller;
        this.config.delete_controller = this.config.save_controller;
        this.config.save_modal_form_method = 'saveAdditionalData';
    },
            
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: me.trans('brand_view'),
            plugins: 'bufferedrenderer',
            columns: 
            [
                {
                    text: me.trans('image'),
                    dataIndex: 'image',    
                    renderer: me.formatImage,
                    width: 80, 
                    align: 'center'        
                },
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    width: 100, 
                    align: 'left',
                    filter: {type: 'string'}
                },
                {
                    text: me.trans('name'),
                    dataIndex: 'name',
                    width: 150, 
                    align: 'left',
                    filter: {type: 'string'}
                },
                {
                    text: me.trans('available'),
                    dataIndex: 'available',
                    width: 90, 
                    filter: {type: 'boolean'}
                },
                {
                    text: me.trans('visible'),
                    dataIndex: 'visible',
                    width: 90, 
                    filter: {type: 'boolean'}
                },
                {
                    text: me.trans('outstanding_female'),
                    dataIndex: 'outstanding',
                    width: 90, 
                    filter: {type: 'boolean'}
                },
                {
                    text: me.trans('empty_female'),
                    dataIndex: 'empty',
                    width: 90, 
                    filter: {type: 'boolean'}
                },
                {
                    text: me.trans('medicines'),
                    dataIndex: 'medicines',
                    width: 100, 
                    filter: {type: 'boolean'}
                },
                {
                    text: me.trans('laboratory'),
                    dataIndex: 'laboratory',
                    width: 100, 
                    align: 'left',
                    filter: {type: 'string'}
                },
                {
                    text: me.trans('laboratory_name'),
                    dataIndex: 'laboratoryName',
                    width: 150, 
                    align: 'left',
                    filter: {type: 'string'}
                }
            ]
        };
    },
        
    formatImage: function(value, p, record)
    {
        var size = 50;
        
        if (Ext.isEmpty(value))
        {
            return  "<div style='height:" + size + "px; width:" + size + "px;' />";
        }
        
        var src = '/' + filemanager_path + '/' + value;
        return  "<img height='" + size + "' width='" + size + "' src='" + src + "' />";
    },
            
    initForm: function()
    {
        var me = this;
        
        me.config.form =
        {
            //title: me.trans('brand_form'),
            fields:
            [
                {
                    xtype: 'fieldset',
                    padding: 5,
                    title: me.trans('main'),
                    anchor: '100%',
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
                            _clonable: true
                        }                
                    ]
                },
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
                            xtype: 'textfield',
                            name: 'description',
                            fieldLabel: me.trans('description'),
                            allowBlank: true,
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
                            name: 'visible',
                            fieldLabel: me.trans('visible'),
                            boxLabel: '',
                            labelAlign: 'right',                
                            _defaultValue: true // checked when new record
                        },
                        {
                            xtype: 'checkboxfield',
                            name: 'outstanding',
                            fieldLabel: me.trans('outstanding_female'),
                            boxLabel: '',
                            labelAlign: 'right'              
                        },
                        {
                            xtype: 'checkboxfield',
                            name: 'empty',
                            fieldLabel: me.trans('empty_female'),
                            boxLabel: '',
                            labelAlign: 'right'             
                        },
                        {
                            xtype: 'checkboxfield',
                            name: 'medicines',
                            fieldLabel: me.trans('medicines'),
                            boxLabel: '',
                            labelAlign: 'right'
                        }/*,
                        {
                            xtype: 'combo',
                            name: 'brandType',
                            fieldLabel: me.trans('brand_type'),
                            _store: {
                                module_id: me.config.module_id,
                                model_id: 'brandType',
                                fields: ['code', 'name'],
                                filters: [
                                    {field: 'available', value: true}
                                ]                                
                            },
                            _addSubmitValues: [
                                {field: 'name', as: 'brandTypeName'}
                            ],
                            valueField: 'code',
                            displayField: 'name',
                            queryMode: 'local',
                            editable: false,
                            //bug//emptyText: me.trans('select_brand_type'),
                            allowBlank: false,
                            labelAlign: 'right',
                            anchor: '100%'
                        }*/,
                        {
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            items: 
                            [
                                {
                                    xtype: 'combo',
                                    name: 'laboratory',
                                    fieldLabel: me.trans('laboratory'),
                                    _store: {
                                        module_id: 'ecommerce',
                                        model_id: 'laboratory',
                                        fields: ['code', 'name'],
                                        filters: [] //{field: 'available', value: true}                                
                                    },                                  
                                    _addSubmitValues: [
                                        {field: 'name', as: 'laboratoryName'}
                                    ],
                                    valueField: 'code',
                                    displayField: 'name',
                                    queryMode: 'local',
                                    editable: true,
                                    typeAhead: true,
                                    forceSelection: true, 
                                    //bug//emptyText: me.trans('select_laboratory'),
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
                                        var form = me.getMaintenanceController().getForm(me.config);
                                        var lab_field = form.getForm().findField('laboratory');                        
                                        lab_field.setValue('');
                                    }
                                } 
                            ]
                        }
                    ]
                },/*
                {
                    xtype: 'fieldset',
                    padding: 5,
                    title: me.trans('gamma'),
                    anchor: '100%',
                    items: 
                    [
                        Ext.widget('ecommerce_brand_gamma_grid')                           
                    ]
                }*/
                {
                    xtype: 'fieldset',
                    padding: 5,
                    title: me.trans('image'),
                    anchor: '100%',
                    items: 
                    [    
                        {
                            xtype: 'panel',
                            title: '',
                            autoHeight: true,
                            items:
                            [
                                me.getImagePanel() 
                            ]
                        }
                    ]
                },                
                me.getDescriptionsFieldset(),
                me.getKeywordsFieldset(),
                me.getNotesFieldset()
            ]
        };
    },
            
    initDynamicFilterForm: function()
    {
        var me = this;
        
        var lab_config = {
            module_id: 'ecommerce',
            model: {
                id: 'laboratory',
                fields: [
                    {name: 'code'}, 
                    {name: 'name'}
                ]
            }
        };
        
        me.config.dynamicFilterForm =
        {
            //title: me.trans('brand_filter'),
            fields:
            [
                {
                    xtype: 'textfield',
                    name: 'code',
                    fieldLabel: me.trans('code'),
                    maskRe: /[a-zA-Z0-9\-\_]/,
                    _filtertype: 'string'                    
                },
                {
                    xtype: 'textfield',
                    name: 'name',
                    fieldLabel: me.trans('name'),
                    anchor: '100%',
                    _filtertype: 'string' 
                },                
                {
                    xtype: 'combo',
                    name: 'available',
                    fieldLabel: me.trans('available'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_female')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    _filtertype: 'boolean',
                    _default_value: 'yes'
                },
                {
                    xtype: 'combo',
                    name: 'visible',
                    fieldLabel: me.trans('visible'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_female')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    _filtertype: 'boolean'
                },
                {
                    xtype: 'combo',
                    name: 'outstanding',
                    fieldLabel: me.trans('outstanding_female'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_female')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    _filtertype: 'boolean'
                },
                {
                    xtype: 'combo',
                    name: 'empty',
                    fieldLabel: me.trans('empty_female'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_female')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    _filtertype: 'boolean'
                },
                {
                    xtype: 'combo',
                    name: 'medicines',
                    fieldLabel: me.trans('medicines'),
                    store: Ext.create('Ext.data.Store', {
                        fields: ['code', 'name'],
                        data : 
                        [
                            {"code": "yes", "name": me.trans('yes')},
                            {"code": "no", "name": "No"},
                            {"code": "all", "name": me.trans('all_female')}
                        ]
                    }),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    width: 200,
                    _filtertype: 'boolean'
                },
                {
                    xtype: 'combo',
                    name: 'laboratory',
                    fieldLabel: me.trans('laboratory'),
                    store: me.getMaintenanceController().getGetRecordsStore(lab_config, true, false, 'true'),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'code',
                    anchor: '100%',
                    _filtertype: 'string',
                    listConfig:{
                        minWidth: 300 // width of the list
                        //maxHeight: 400 // height of a list with scrollbar
                    }
                }                
            ]
        };
    },
    
    getImagePanel: function()
    {
        var me = this;
        
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
                                    itemId: 'ecommerce_brand_form_image',
                                    width: 150,
                                    height: 150,
                                    border: 1,
                                    style: {
                                        borderColor: '#C8C8C8',
                                        borderStyle: 'solid'
                                    }
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
                                                me.editImage();
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
                                                me.clearImage();
                                            }
                                        }                                        
                                    ]
                                }
                                
                            ]
                        },                        
                        {
                           xtype: 'textfield',
                           itemId: 'ecommerce_brand_form_image_textfield',
                           name: 'image',
                           fieldLabel: '',
                           allowBlank: true,     
                           width: '100%',
                           readOnly: true,
                           style: {
                               'text-align' : 'center'
                           },
                           fieldStyle: 'text-align: center;',
                           margin: '10 10 0 10'
                       }                                
                    ]
                };       
    },
            
    editImage: function()
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
            config.baseNode = "BRANDS";
            config.itemId = 'fileManager_ecommerce_brand_form';
            config.hideTitle = true;

            var file_manager = Ext.widget('fileManager', {
                config: config
            });         
            file_manager.on('selectedFile', function(filename, filesize, filedate, relativePath, path) {
                var textfield = Ext.ComponentQuery.query('#ecommerce_brand_form_image_textfield')[0];
                var image = Ext.ComponentQuery.query('#ecommerce_brand_form_image')[0];
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
                module_id: 'ecommerce',
                model_id: 'fileManager',
                menu_id: 'fileManager',
                start: 0,
                limit: 9999
            }
        });           
     
    },
            
    clearImage: function()
    {
        var textfield = Ext.ComponentQuery.query('#ecommerce_brand_form_image_textfield')[0];
        var image = Ext.ComponentQuery.query('#ecommerce_brand_form_image')[0];

        textfield.setValue('');
        image.setSrc('');     
    },
    
    getDescriptionsFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('meta_description') + ' (SEO)',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_brand_description_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'ecommerce_brand_tabpanel_description'
                }
            ]
        };
        
        return ret;
    },
    
    getKeywordsFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: 'Keywords' + ' (SEO)',
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_brand_keywords_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'ecommerce_brand_tabpanel_keywords'
                }
            ]
        };
        
        return ret;
    },
    
    getNotesFieldset: function()
    {
        var me = this;
        
        var ret = 
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('notes'),
            anchor: '100%',
            items: 
            [
                {
                    xtype: 'textareafield',
                    name: 'notes',
                    anchor: '100%',
                    height: 60
                }
            ]
        };     
        
        return ret;
    },
    
    onRender: function(form, eOpts)
    {
        var me = this;
        
        me.createTabsContent();                  
        
        this.callParent(arguments);
    },
    
    createTabsContent: function()
    {
        var me = this;
        me.createTabContent('description');
        me.createTabContent('keywords');        
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = me.down('#ecommerce_brand_tabpanel_' + type);
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#ecommerce_brand_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            i = 0;
            Ext.each(langs, function(lang) {
                lang_code = lang.code;
                lang_name = lang.name;
                
                var name;
                if (type === 'description')
                {
                    name = 'seoDescriptions';
                }
                else
                {
                    name = 'keywords';
                }
                tab.add({
                    xtype: 'textfield',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _name: name,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%'
                }); 
                
                i++;
            });                 

            tab.setActiveTab(0);                                            
        }         
    },
            
    onNewRecord: function()
    {
        var view = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getMaintenanceView(this.config);
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(this.config);
        var tab;
        
        /*
        // Clear gamma grid
        var gamma_grid = view.down('#ecommerce_brand_gamma_grid');
        if (!Ext.isEmpty(gamma_grid))
        {
            gamma_grid.getStore().removeAll();
        }
        */
       
       // Clean image
        view.clearImage();
        
        // Clean description tab
        tab = view.down('#ecommerce_brand_tabpanel_description');
        Ext.each(tab.items.items, function(item) {
            item.setValue('');
        }); 
        
        // Clean keywords tab
        tab = view.down('#ecommerce_brand_tabpanel_keywords');
        Ext.each(tab.items.items, function(item) {
            item.setValue('');
        });
       
        // Finally.. clear form
        //extjs6 form.clearDirty();
    },
            
    onEditedRecord: function(id)
    {
        var maintenance_controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');
        var view = maintenance_controller.getMaintenanceView(this.config);
        var form = maintenance_controller.getForm(this.config);
        var record = maintenance_controller.getCurrentRecord(this.config);
        var tab;
        
        /*
        // Set gamma to grid
        var gamma_grid = view.down('#ecommerce_brand_gamma_grid');
        if (!Ext.isEmpty(gamma_grid))
        {
            gamma_grid.getStore().load({
                params:{
                    record_id: id
                }
            });            
        }
        */
        
        // Set image
        var textfield = Ext.ComponentQuery.query('#ecommerce_brand_form_image_textfield')[0];
        var image = Ext.ComponentQuery.query('#ecommerce_brand_form_image')[0];
        var path = '', src = '';
        if (!Ext.isEmpty(record.data.image))
        {
            path = record.data.image;
            src = '/' + filemanager_path + '/' + path;
        }
        textfield.setValue(path);
        image.setSrc(src);  
        
        // Set description tab
        tab = view.down('#ecommerce_brand_tabpanel_description');
        Ext.each(tab.items.items, function(item) {
            var value = '';
            if (!Ext.isEmpty(record.data[item._name]) &&
                !Ext.isEmpty(record.data[item._name][item._lang_code]))
            {
                value = record.data[item._name][item._lang_code];
            }
            item.setValue(value);
        });  
        
        // Set keywords tab
        tab = view.down('#ecommerce_brand_tabpanel_keywords');
        Ext.each(tab.items.items, function(item) {
            var value = '';
            if (!Ext.isEmpty(record.data[item._name]) &&
                !Ext.isEmpty(record.data[item._name][item._lang_code]))
            {
                value = record.data[item._name][item._lang_code];
            }
            item.setValue(value);
        }); 
       
        // Finally.. clear form
        //extjs6 form.clearDirty();
    },
    
    addAdditionalDataMenu: function()
    {
        var me = this;
        var toolbar = me.getMaintenanceController().getGridToolBar(me.config);
        toolbar.add(
            { xtype: 'tbfill' },
            Ext.widget('ecommerce_brand_additionaldata', {
                config: me.config
            })                
        );  
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
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
});