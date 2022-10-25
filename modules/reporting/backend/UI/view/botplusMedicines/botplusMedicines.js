Ext.define('App.modules.reporting.backend.UI.view.botplusMedicines.botplusMedicines', {
    extend: 'Ext.panel.Panel',
    
    alias: 'widget.reporting_botplusMedicines',
    
    border: false,
    frame: false,
    layout: 'fit',
    title: '',

    config: {},
    
    initComponent: function() {
        this.alert();
        
        var me = this;
        
        // General properties
        this.initGeneralProperties();
        // The grid
        this.initGrid();
        // The form
        //this.initForm();
        // The dynamic filter form
        this.initDynamicFilterForm();
        
        // Create the getRecords Store (main store of maintenance)
        me.config.store = me.getGetRecordsStore();
        
        var dynamicfilterform =  Ext.widget('reporting_botplus_medicines_dynamicfilterform', {
            config: me.config
        });
        dynamicfilterform.setTitle('');
        
        var items = 
        [
            {
                xtype: 'panel',
                border: false,
                frame: false,
                title: me.config.breadscrumb,
                layout: 'border',
                items:
                [
                    {
                        xtype: 'panel',
                        region: 'west',
                        layout: 'border',
                        width: 300,
                        height: '100%',
                        split: true,
                        collapsible: true,
                        title: me.trans('filters'),
                        items: 
                        [
                            Ext.widget('reporting_botplus_medicines_filterform', {
                                config: me.config
                            })
                        ]        
                    },
                    {
                        xtype: 'panel',
                        region: 'center',
                        layout: 'border',
                        border: false,
                        frame: false,
                        flex: 2,
                        items:
                        [
                            {
                                xtype: 'panel',
                                region: 'west',
                                layout: 'border',
                                width: 300,
                                height: '100%',
                                split: true,
                                collapsible: true,
                                collapsed: true,
                                title: me.trans('dynamic_filters'),
                                items: 
                                [         
                                    dynamicfilterform
                                ]        
                            },
                            Ext.widget('reporting_botplus_medicines_gridpanel', {
                                config: me.config
                            })                      
                        ]
                    }            
                ]
            }
        ];
            
        me.items = items;
        
        me.callParent(arguments);
        
        me.on('boxready', this.onBoxready, this);
    },
    
    onBoxready: function(this_panel, width, height, eOpts)
    {
        var me = this;
        
//        var task = new Ext.util.DelayedTask(function(){
//            
//        });        
//        task.delay(500);        
    },
    
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_clone = false;
        this.config.enable_deletion = false;
        this.config.get_controller = 'modules\\reporting\\backend\\controller\\botplusMedicines';
    },
    
    getGetRecordsStore: function()
    {
        var me = this;
        return me.getViewController().getGetRecordsStore(me.config, false, false, false); 
    },
            
    initGrid: function()
    {
        var me = this;
        me.config.grid = 
        {
            title: me.trans('botplusMedicines_view'),
            plugins: 'bufferedrenderer',
            groupField: me.getGroupFieldGrid(),
            columns: 
            [
                {
                    text: me.trans('code'),
                    dataIndex: 'code',
                    _renderer: 'bold',
                    width: 80
                },
                {
                    text: 'ESPENOM',
                    dataIndex: 'ESPENOM',
                    width: 100
                },
                {
                    text: 'ESPEDES',
                    dataIndex: 'ESPEDES',
                    width: 100
                },
                {
                    text: 'CODESTADO',
                    dataIndex: 'CODESTADO',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'DESCESTADO',
                    dataIndex: 'DESCRIPCION',
                    width: 120
                },
                {
                    text: 'CODVIA',
                    dataIndex: 'CODVIA',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'DESCRIPCION_VIA',
                    dataIndex: 'DESCRIPCION_VIA',
                    width: 100
                },
                {
                    text: 'FFARCOD',
                    dataIndex: 'FFARCOD',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'FFARDESC',
                    dataIndex: 'FFARDESC',
                    width: 100
                },
                {
                    text: 'ESPUNIE1',
                    dataIndex: 'ESPUNIE1',
                    width: 100
                },
                {
                    text: 'ESPUNIE',
                    dataIndex: 'ESPUNIE',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'ESPELABEU',
                    dataIndex: 'ESPELABEU',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'ESPEIVAEU',
                    dataIndex: 'ESPEIVAEU',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'LABCOD',
                    dataIndex: 'LABCOD',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'LABNOM',
                    dataIndex: 'LABNOM',
                    width: 100
                },
                {
                    text: 'CODCONJUNTO',
                    dataIndex: 'CODCONJUNTO',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'NOMBRE',
                    dataIndex: 'NOMBRE',
                    width: 100
                },
                {
                    text: 'ESPFEA',
                    dataIndex: 'ESPFEA',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'ESPEDESHAS',
                    dataIndex: 'ESPEDESHAS',
                    width: 100
                },
                {
                    text: 'ESPFEE',
                    dataIndex: 'ESPFEE',
                    width: 100
                },
                {
                    text: 'GTVMPCOD',
                    dataIndex: 'GTVMPCOD',
                    align: 'center',
                    width: 100
                },
                {
                    text: 'GTVMPDES',
                    dataIndex: 'GTVMPDES',
                    width: 300
                },
                {
                    text: 'COMP1',
                    dataIndex: 'COMP1',
                    width: 200
                },
                {
                    text: 'COMP2',
                    dataIndex: 'COMP2',
                    width: 200
                },
                {
                    text: 'COMP3',
                    dataIndex: 'COMP3',
                    width: 200
                },
                {
                    text: 'COMP4',
                    dataIndex: 'COMP4',
                    width: 200
                },
                {
                    text: 'COMP5',
                    dataIndex: 'COMP5',
                    width: 200
                },
                {
                    text: 'COMP6',
                    dataIndex: 'COMP6',
                    width: 200
                },
                {
                    text: 'COMP7',
                    dataIndex: 'COMP7',
                    width: 200
                },
                {
                    text: 'COMP8',
                    dataIndex: 'COMP8',
                    width: 200
                },
                {
                    text: 'COMP9',
                    dataIndex: 'COMP9',
                    width: 200
                },
                {
                    text: 'COMP10',
                    dataIndex: 'COMP10',
                    width: 200
                },
                {
                    text: 'DF1',
                    dataIndex: 'DF1',
                    width: 200
                },
                {
                    text: 'DF2',
                    dataIndex: 'DF2',
                    width: 200
                },
                {
                    text: 'DF3',
                    dataIndex: 'DF3',
                    width: 200
                },
                {
                    text: 'DF4',
                    dataIndex: 'DF4',
                    width: 200
                },
                {
                    text: 'DF5',
                    dataIndex: 'DF5',
                    width: 200
                },
                {
                    text: 'DF6',
                    dataIndex: 'DF6',
                    width: 200
                },
                {
                    text: 'DF7',
                    dataIndex: 'DF7',
                    width: 200
                },
                {
                    text: 'DF8',
                    dataIndex: 'DF8',
                    width: 200
                },
                {
                    text: 'DF9',
                    dataIndex: 'DF9',
                    width: 200
                },
                {
                    text: 'DF10',
                    dataIndex: 'DF10',
                    width: 200
                }
            ]
        };
    },
    
    getGroupFieldGrid: function()
    {
        return null;
    },
    
    initDynamicFilterForm: function()
    {
        var me = this;
        
        me.config.dynamicFilterForm =
        {
            //title: me.trans('dynamic_filters'),
            fields:
            [
                {
                    xtype: 'textfield',
                    name: 'code',
                    fieldLabel: me.trans('code'),
                    maskRe: /[0-9]/,
                    _filtertype: 'string'                    
                },
                {
                    xtype: 'textfield',
                    name: 'ESPENOM',
                    fieldLabel: 'ESPENOM',
                    _filtertype: 'string'
                },
                {
                    xtype: 'textfield',
                    name: 'CODESTADO',
                    fieldLabel: 'CODESTADO',
                    _filtertype: 'string'
                },
                {
                    xtype: 'textfield',
                    name: 'DESCRIPCION',
                    fieldLabel: 'DESCESTADO',
                    _filtertype: 'string'
                },
                {
                    xtype: 'textfield',
                    name: 'LABCOD',
                    fieldLabel: 'LABCOD',
                    _filtertype: 'string'
                },
                {
                    xtype: 'textfield',
                    name: 'LABNOM',
                    fieldLabel: 'LABNOM',
                    _filtertype: 'string'
                },
                {
                    xtype: 'textfield',
                    name: 'GTVMPDES',
                    fieldLabel: 'GTVMPDES',
                    _filtertype: 'string'
                }
            ]
        };
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.reporting.backend.UI.controller.reporting').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    ecommerceTrans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    alert: function()
    {
        App.app.getController('App.modules.reporting.backend.UI.controller.reporting').alertInitMaintenance(this.config);              
    },
            
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.reporting.backend.UI.controller.botplusMedicines');       
        return controller;
    },
        
    getDynamicFilterController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1DynamicFilterForm');
        return controller;
    }
});