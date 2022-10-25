Ext.define('App.modules.reporting.backend.UI.controller.reporting', {
    extend: 'App.core.backend.UI.controller.common',

    views: [
        'App.modules.reporting.backend.UI.view.articles.articles',
        'App.modules.reporting.backend.UI.view.articles.toolbar',
        'App.modules.reporting.backend.UI.view.brandsAndGammas.brandsAndGammas',
        'App.modules.reporting.backend.UI.view.brandsAndGammas.toolbar',
        
        'App.modules.reporting.backend.UI.view.seoOnPage.seoOnPage',
        'App.modules.reporting.backend.UI.view.seoOnPage.toolbar'
    ],
    
    models: [

    ],
    
    stores: [

    ],

    refs: [
        
    ],
    
    module_id: 'reporting'
    
});