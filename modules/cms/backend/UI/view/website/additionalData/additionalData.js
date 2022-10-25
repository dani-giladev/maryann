Ext.define('App.modules.cms.backend.UI.view.website.additionalData.additionalData', {
    extend: 'Ext.button.Button',
    
    alias: 'widget.cms_website_additionaldata',
    itemId: 'cms_website_additionaldata',
        
    explotation: 'Additional data view for website (button menu)',
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
            me.getSocialNetworksMenu(),
            me.getImagesMenu(),
            me.getLawsMenu(),
            me.getMetatagsMenu(),
            me.getCustomerServiceMenu(),
            me.getUDataMenu(),
            me.getAnalyticsMenu()
        ];
    }, 
           
    getSocialNetworksMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: me.trans('social_networks'),
            iconCls: "x-fa fa-twitter",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: me.trans('social_networks'),
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('cms_website_additionaldata_social_networks').getForm(config);

                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
    },
    
    getImagesMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: me.trans('images'),
            iconCls: "x-fa fa-picture-o",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: me.trans('images'),
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('cms_website_additionaldata_images').getForm(config, record);

                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
    },
           
    getLawsMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: me.trans('laws'),
            iconCls: "x-fa fa-shield",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: me.trans('laws'),
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('cms_website_additionaldata_laws').getForm(config);

                me.getModalFormMaintenanceController().showForm(config, false, record);

            }
        };        
        
        return ret;
    },
           
    getMetatagsMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: 'Metatags',
            iconCls: "x-fa fa-slack",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: 'Metatags',
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('cms_website_additionaldata_metatags').getForm(config);

                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
    },
           
    getCustomerServiceMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: me.trans('customer_service'),
            iconCls: "x-fa fa-phone",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: me.trans('customer_service'),
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('cms_website_additionaldata_customerservice').getForm(config);

                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
    },
           
    getUDataMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: 'Micro-data',
            iconCls: "x-fa fa-tencent-weibo",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: 'Micro-data',
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('cms_website_additionaldata_udata').getForm(config);

                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
    },
           
    getAnalyticsMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: 'Analytics',
            iconCls: "x-fa fa-area-chart",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: 'Analytics',
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('cms_website_additionaldata_analytics').getForm(config);

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