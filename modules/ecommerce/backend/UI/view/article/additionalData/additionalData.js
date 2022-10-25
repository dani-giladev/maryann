Ext.define('App.modules.ecommerce.backend.UI.view.article.additionalData.additionalData', {
    extend: 'Ext.button.Button',
    
    alias: 'widget.ecommerce_article_additionaldata',
    itemId: 'ecommerce_article_additionaldata',
        
    explotation: 'E-Commerce additional data article view (button menu)',
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
        me.text = me.trans('more_data');
    },
    
    initMenu: function()
    {
        var me = this;
        me.menu =
        [
            //me.getCategoriesMenu(),            
            //me.getDescriptionsMenu(),
            me.getImagesMenu(),
            me.getBotplusMenu()//,
            //me.getGoogleMenu() 
        ];
    },
    
    getCategoriesMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: me.trans('categories'),
            iconCls: "x-fa fa-tree",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: me.trans('categories'),
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                
                var categoriesCheckTreeWindow = Ext.create('App.modules.ecommerce.backend.UI.view.article.categories.categories', {
                    config: me.config
                });                
                categoriesCheckTreeWindow.show();
            }
        };        
        
        return ret;
    },
    
    getDescriptionsMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: me.trans('descriptions'),
            iconCls: "x-fa fa-text-height",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: me.trans('descriptions'),
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                            
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                
                me.getMaintenanceController().on('getRecord', function(success, record)
                {
                    if (!success)
                    {
                        return;
                    }
                    
                    config.form = Ext.widget('ecommerce_article_additionaldata_descriptions').getForm(config);
                    me.getModalFormMaintenanceController().showForm(config, false, record);   
                    
                }, this, {single: true});                        
                me.getMaintenanceController().getRecord(config);

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
                config.form = Ext.widget('ecommerce_article_additionaldata_images').getForm(config, record);
                
                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
    },
    
    getBotplusMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: 'Bot plus',
            iconCls: "x-fa fa-book",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: 'Bot plus',
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // Get bot plus data
                me.getBotplusData(record);
            }
        };        
        
        return ret;
    },
    
    getBotplusData: function(record)
    { 
        var me = this; 
        
        var code = record.get('code');
        
        Ext.getBody().mask(me.trans('loading'));

        Ext.Ajax.request({
            type: 'ajax',
            url : 'index.php',
            method: 'GET',
            params: {
                controller: 'modules\\ecommerce\\backend\\controller\\botplus', 
                method: 'getBotplusData',
                code: code              
            },
            success: function(response, opts)
            {
                Ext.getBody().unmask();
                var obj = Ext.JSON.decode(response.responseText);
                if(!obj.success)
                {
                    Ext.MessageBox.show({
                       title: 'Bot plus',
                       msg: obj.data.result,
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });
                    return;
                }

                //console.log(obj.data.result);
                var data = obj.data.result.data;
                /*var model = obj.data.result.model;
                
                var model_name = 'BotplusModel' + '-' + me.getModalFormMaintenanceController().getRandom(1, 100);
                
                Ext.define(model_name, {
                    extend: 'Ext.data.Model',
                    fields: model
                });

                var store = Ext.create('Ext.data.Store', {
                    model: model_name,
                    data : data
                });                    

                var rec = store.data.items[0];*/
                var rec = Ext.data.Record.create(data);
                rec.data.id = record.data.id;    
                    
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                
                // Set params
                config.enable_publication = false;
                config.save_controller = 'modules\\ecommerce\\backend\\controller\\botplus';
                config.save_modal_form_method = 'saveBotplusData'; 
                config.form = Ext.widget('ecommerce_article_additionaldata_botplus').getForm(config, rec);
                
                me.getModalFormMaintenanceController().showForm(config, false, rec);
            },
            failure: function(form, data)
            {
                Ext.getBody().unmask();
            }
        });          
    },
    
    getGoogleMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: 'Google',
            iconCls: "x-fa fa-google",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: 'Google',
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('ecommerce_article_additionaldata_google').getForm(config);
                
                me.getModalFormMaintenanceController().showForm(config, false, record);
            }
        };        
        
        return ret;
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.article');       
        return controller;
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