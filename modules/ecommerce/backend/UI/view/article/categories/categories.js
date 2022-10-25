Ext.define('App.modules.ecommerce.backend.UI.view.article.categories.categories', {
    extend: 'Ext.window.Window',
    
    alias: 'widget.ecommerce_article_categories',
        
    explotation: 'E-Commerce article checktree (parent window)',

    modal: true,
    closable: true,
    resizable: false,    
    header: true,
    frame: false,
    border: true,
    
    layout: 'border',
    
    config: null,
    
    initComponent: function() 
    {
        var me = this;
        
        me.itemId = 'ecommerce_article_categories';
        
        var size = me.getViewController().getSize();
        
        me.title = me.trans('categories');
        me.width = 650;
        me.height = 800;
        me.maxHeight  = size.height - 20;
            
        me.items = 
        [ 
            Ext.widget('ecommerce_article_categories_tree', {
                config: me.config
            }),
            Ext.widget('ecommerce_article_categories_toolbar', {
                config: me.config
            })
        ];

        me.callParent(arguments);
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
    }

});