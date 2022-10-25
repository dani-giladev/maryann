Ext.define('App.modules.ecommerce.backend.UI.view.article.categories.toolBar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.ecommerce_article_categories_toolbar',
        
    explotation: 'E-Commerce article categories checktree (toolbar)',

    region: 'north',
                
    border: true,
    frame: false,
    
    config: null,
    
//    ui: 'footer',
    
    initComponent: function() {
        
        var me = this;
        
        this.title = '';
        
        this.items = 
        [     
            {
                text: me.trans('save'),
                disabled: !me.config.permissions.update,
                handler: me.save
            },      
            {
                text: me.trans('save_and_publish'),
                disabled: (!me.config.permissions.update || !me.config.permissions.publish),
                hidden: !me.config.enable_publication,
                handler: me.saveAndPublish
            },             
            {
                text: me.trans('undo'),
                handler: me.refresh,
                disabled: !me.config.permissions.update
            }       
        ];
            
        this.callParent(arguments);
    },
            
    save: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.getViewController().saveCategoriesTree(me.config, false);
    },
            
    saveAndPublish: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.getViewController().saveCategoriesTree(me.config, true);
    },
            
    refresh: function(button, eventObject)
    {
        var me = button.up('toolbar');
        me.getViewController().refreshCategoriesTree();
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