Ext.define('App.modules.ecommerce.backend.UI.controller.ecommerce', {
    extend: 'App.core.backend.UI.controller.common',

    views: [

        // Labs, brands and gammas
        'App.modules.ecommerce.backend.UI.view.laboratory.laboratory',
        'App.modules.ecommerce.backend.UI.view.brandType.brandType',
        'App.modules.ecommerce.backend.UI.view.brand.brand',
        'App.modules.ecommerce.backend.UI.view.brand.additionalData.additionalData',
        'App.modules.ecommerce.backend.UI.view.brand.additionalData.descriptions',
        'App.modules.ecommerce.backend.UI.view.gamma.gamma',
        'App.modules.ecommerce.backend.UI.view.gamma.additionalData.additionalData',
        'App.modules.ecommerce.backend.UI.view.gamma.additionalData.descriptions',
        
        // Categories
        'App.modules.ecommerce.backend.UI.view.categories.categories',
        
        // Articles
        'App.modules.ecommerce.backend.UI.view.articleType.articleType',
        'App.modules.ecommerce.backend.UI.view.articleFamily.articleFamily',
        'App.modules.ecommerce.backend.UI.view.articleProperty.articleProperty',
        'App.modules.ecommerce.backend.UI.view.articleProperty.values.grid',
        'App.modules.ecommerce.backend.UI.view.articleProperty.values.window',
        'App.modules.ecommerce.backend.UI.view.articleProperty.values.form',
        'App.modules.ecommerce.backend.UI.view.articleProperty.values.toolbar',
        
        // Users
        'App.modules.ecommerce.backend.UI.view.user.user',
        
        // Sales
        'App.modules.ecommerce.backend.UI.view.saleRate.saleRate'
    ],
    
    models: [
        'App.modules.ecommerce.backend.UI.model.articleProperty.value'
    ],
    
    stores: [
        
    ],

    refs: [
        
    ],
    
    module_id: 'ecommerce'
    
});