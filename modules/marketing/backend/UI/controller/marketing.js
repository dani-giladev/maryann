Ext.define('App.modules.marketing.backend.UI.controller.marketing', {
    extend: 'App.core.backend.UI.controller.common',

    views: [
        'App.modules.marketing.backend.UI.view.promo.promo',
        
        'App.modules.marketing.backend.UI.view.articleGroup.articleGroup',
        // Group by articles
        'App.modules.marketing.backend.UI.view.articleGroup.articles.grid',
        'App.modules.marketing.backend.UI.view.articleGroup.articles.window',
        'App.modules.marketing.backend.UI.view.articleGroup.articles.toolbar',
        'App.modules.marketing.backend.UI.view.articleGroup.articles.form',
        // Group by article types
        'App.modules.marketing.backend.UI.view.articleGroup.articleTypes.grid',
        'App.modules.marketing.backend.UI.view.articleGroup.articleTypes.window',
        'App.modules.marketing.backend.UI.view.articleGroup.articleTypes.toolbar',
        'App.modules.marketing.backend.UI.view.articleGroup.articleTypes.form',
        // Group by brands
        'App.modules.marketing.backend.UI.view.articleGroup.brands.grid',
        'App.modules.marketing.backend.UI.view.articleGroup.brands.window',
        'App.modules.marketing.backend.UI.view.articleGroup.brands.toolbar',
        'App.modules.marketing.backend.UI.view.articleGroup.brands.form',
        // Group by gammas
        'App.modules.marketing.backend.UI.view.articleGroup.gammas.grid',
        'App.modules.marketing.backend.UI.view.articleGroup.gammas.window',
        'App.modules.marketing.backend.UI.view.articleGroup.gammas.toolbar',
        'App.modules.marketing.backend.UI.view.articleGroup.gammas.form',
        
        'App.modules.marketing.backend.UI.view.voucher.voucher'
    ],
    
    models: [

    ],
    
    stores: [

    ],

    refs: [
        
    ],
    
    init: function() 
    {
        this.control({

        }); 
    }
});