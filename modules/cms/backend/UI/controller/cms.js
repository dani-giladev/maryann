Ext.define('App.modules.cms.backend.UI.controller.cms', {
    extend: 'App.core.backend.UI.controller.common',

    controllers: [

    ],

    views: [
        'App.modules.cms.backend.UI.view.website.website',
        'App.modules.cms.backend.UI.view.website.additionalData.additionalData',
        'App.modules.cms.backend.UI.view.website.additionalData.socialNetworks', 
        'App.modules.cms.backend.UI.view.website.additionalData.images',
        'App.modules.cms.backend.UI.view.website.additionalData.laws',
        'App.modules.cms.backend.UI.view.website.additionalData.metatags',
        'App.modules.cms.backend.UI.view.website.additionalData.customerService',
        'App.modules.cms.backend.UI.view.website.additionalData.udata',
        'App.modules.cms.backend.UI.view.website.additionalData.analytics',
        
        'App.modules.cms.backend.UI.view.webpage.webpage',
        'App.modules.cms.backend.UI.view.webpage.additionalData.additionalData',
        // Slider
        'App.modules.cms.backend.UI.view.webpage.additionalData.slider.slider',
        'App.modules.cms.backend.UI.view.webpage.additionalData.slider.sliderGrid',
        'App.modules.cms.backend.UI.view.webpage.additionalData.slider.form.window',
        'App.modules.cms.backend.UI.view.webpage.additionalData.slider.form.toolbar',
        'App.modules.cms.backend.UI.view.webpage.additionalData.slider.form.form',
        // Banners
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.banners',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.rows.grid',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.rows.form.window',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.rows.form.toolbar',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.rows.form.form',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.columns.grid',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.columns.form.window',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.columns.form.toolbar',
        'App.modules.cms.backend.UI.view.webpage.additionalData.banners.columns.form.form'
    ],
    
    models: [
        'App.modules.cms.backend.UI.model.webpage.slider',
        'App.modules.cms.backend.UI.model.webpage.banners'
    ],
    
    stores: [
        'App.modules.cms.backend.UI.store.webpage.slider',
        'App.modules.cms.backend.UI.store.webpage.banners'
    ],

    refs: [
        
    ],
    
    init: function() 
    {
        this.control({

        }); 
    },
        
    getWebpageDesignController: function()
    {
        var controller = App.app.getController('App.modules.cms.backend.UI.controller.webpage.design');       
        return controller;
    },
            
    getWebpageDesignView: function()
    {
        // Find view by itemId
        var itemId = 'cms_webpage_design';
        var view = Ext.ComponentQuery.query('#' + itemId)[0];
        return view;
    },
        
    getWebpageCanvasController: function()
    {
        var controller = App.app.getController('App.modules.cms.backend.UI.controller.webpage.canvas');       
        return controller;
    },
            
    getWebpageCanvasView: function()
    {
        // Find view by itemId
        var itemId = 'cms_webpage_design_canvas';
        var view = Ext.ComponentQuery.query('#' + itemId)[0];
        return view;
    },
        
    getWebpageWidgetController: function()
    {
        var controller = App.app.getController('App.modules.cms.backend.UI.controller.webpage.widget');       
        return controller;
    }
});