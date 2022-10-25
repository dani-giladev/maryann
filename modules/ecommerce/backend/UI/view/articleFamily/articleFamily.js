Ext.define('App.modules.ecommerce.backend.UI.view.articleFamily.articleFamily', {
    extend: 'App.core.backend.UI.view.maintenance.basic.basic',
    
    alias: 'widget.ecommerce_articleFamily',
        
    explotation: 'E-Commerce article family view',
            
    // Overwritten
    setTitles: function()
    {
        this.config.grid.title = this.trans('article_family_view');
        this.config.form.title = this.trans('article_family_form');
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});