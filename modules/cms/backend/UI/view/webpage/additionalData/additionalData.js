Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.additionalData', {
    extend: 'Ext.button.Button',
    
    alias: 'widget.cms_webpage_additionaldata',
    itemId: 'cms_webpage_additionaldata',
        
    explotation: 'Additional data view for webpage (button menu)',
    iconCls: "x-fa fa-bars",
            
    config: null,
    
    initComponent: function()
    {
        // General properties
        this.initGeneralProperties();
        // The button menu
        this.initMenu();
            
        this.callParent(arguments);         
    },
    
    initGeneralProperties: function()
    {
        var me = this;
        me.text = me.trans('additional_data');
    },
    
    initMenu: function()
    {
        var me = this;
        me.menu =
        [
            me.getMenu('Slider', 'slider', 'x-fa fa-exchange'),
            me.getMenu('Banners', 'banners', 'x-fa fa-angle-double-down')
        ];
    }, 
           
    getMenu: function(title, menu, iconCls)
    {   
        var me = this;  
        var ret =       
        {
            text: title,
            iconCls: iconCls,
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: title,
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.title = title;
                config.form = Ext.widget('cms_webpage_additionaldata_' + menu).getForm(config, record);

                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
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
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }

});