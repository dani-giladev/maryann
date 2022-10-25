Ext.define('App.modules.ecommerce.backend.UI.controller.article', {
    extend: 'App.modules.ecommerce.backend.UI.controller.ecommerce',

    views: [
        'App.modules.ecommerce.backend.UI.view.article.article',
        'App.modules.ecommerce.backend.UI.view.article.additionalData.additionalData',
        'App.modules.ecommerce.backend.UI.view.article.additionalData.descriptions',
        'App.modules.ecommerce.backend.UI.view.article.additionalData.images',
        'App.modules.ecommerce.backend.UI.view.article.additionalData.botplus',
        'App.modules.ecommerce.backend.UI.view.article.additionalData.google',
        'App.modules.ecommerce.backend.UI.view.article.categories.categories',
        'App.modules.ecommerce.backend.UI.view.article.categories.tree',
        'App.modules.ecommerce.backend.UI.view.article.categories.toolBar',
        'App.modules.ecommerce.backend.UI.view.article.imagesGrid',
        'App.modules.ecommerce.backend.UI.view.article.pricesFieldset',
        'App.modules.ecommerce.backend.UI.view.article.erp.farmatic.fieldset',
        'App.modules.ecommerce.backend.UI.view.article.erp.farmatic.grid',
        'App.modules.ecommerce.backend.UI.view.article.properties.grid',
        'App.modules.ecommerce.backend.UI.view.article.properties.window',
        'App.modules.ecommerce.backend.UI.view.article.properties.form',
        'App.modules.ecommerce.backend.UI.view.article.properties.toolbar'
    ],
    
    models: [
        'App.modules.ecommerce.backend.UI.model.article.image',
        'App.modules.ecommerce.backend.UI.model.article.erp.farmatic',
        'App.modules.ecommerce.backend.UI.model.article.property',
        'App.modules.ecommerce.backend.UI.model.article.propertyValue'
    ],
    
    stores: [
        'App.modules.ecommerce.backend.UI.store.article.images',
        'App.modules.ecommerce.backend.UI.store.article.erp.farmatic',
        'App.modules.ecommerce.backend.UI.store.article.properties'
    ],

    refs: [

    ],
    
    init: function() 
    {
        this.control({

        }); 
    },
            
    getCategoriesTreeWindow: function()
    {
        // Find form by itemId
        var itemId = 'ecommerce_article_categories';
        var form = Ext.ComponentQuery.query('#' + itemId)[0];
        return form;
    },
            
    getCategoriesTree: function()
    {
        // Find form by itemId
        var itemId = 'ecommerce_article_categories_tree';
        var form = Ext.ComponentQuery.query('#' + itemId)[0];
        return form;
    },
    
    saveCategoriesTree: function(config, publish)
    {
        var me = this;
        
        if (publish)
        {
            Ext.MessageBox.show({
                title: me.trans('publish_record'),
                msg: me.trans('are_you_sure_to_publish'),
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.QUESTION,
                fn: function(btn, text)
                {
                    if(btn === 'yes')
                    {
                        me.savingCategoriesTree(config, true);
                    } 
                    else
                    {
                        me.savingCategoriesTree(config, false);
                    } 
                }
            });
        }
        else
        {
            me.savingCategoriesTree(config, false);
        }
    },
            
    savingCategoriesTree: function(config, publish)
    {
        var me = this;
        var params;
        var record_id = me.getMaintenanceController().getCurrentRecord(config).data.id;
        var tree = me.getCategoriesTree();
        var save_controller = 'core\\backend\\controller\\maintenance\\type1';

        // Get checked categories
        var categories = '';
        var records = tree.getChecked();
        var first_time = true;
        Ext.Array.each(records, function(rec){
            if (!first_time)
            {
                categories += '|';
            }
            first_time = false;
            categories += rec.raw._data.code;
        });
        //console.log(categories);
        
        // Check for overrided savecontroller definition
        if(config.save_controller)
        {
            save_controller = config.save_controller;
        }
        
        // The ajax params
        params = {
            controller: save_controller, 
            method: 'saveProperty',
            module_id: config.module_id,
            model_id: config.model.id,
            record_id: record_id,
            property_name: 'categories',
            property_value: categories,
            publish: publish
        };
        
        Ext.getBody().mask(me.trans('saving_tree'));

        Ext.Ajax.request({
            type: 'ajax',
            url : 'index.php',
            method: 'GET',
            params: params,
            //waitMsg : me.trans('saving_tree'),
            success: function(response, opts)
            {
                Ext.getBody().unmask();
                var obj = Ext.JSON.decode(response.responseText);
                if(!obj.success)
                {
                    Ext.MessageBox.show({
                       title: me.trans('saving_tree_failed'),
                       msg: obj.data.result,
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.ERROR
                    });
                }
                
                // Close window
                var tree_window = me.getCategoriesTreeWindow();
                tree_window.close();
            },
            failure: function(form, data)
            {
                Ext.getBody().unmask();
                var obj = Ext.JSON.decode(data.response.responseText);
                Ext.MessageBox.show({
                   title: me.trans('saving_tree_failed'),
                   msg: obj.data.result,
                   buttons: Ext.MessageBox.OK,
                   icon: Ext.MessageBox.ERROR
                });
            }
        });

    },
            
    refreshCategoriesTree: function()
    {
        var me = this;
        var tree = me.getCategoriesTree();
        var tree_store = tree.getStore();
        tree_store.reload();
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