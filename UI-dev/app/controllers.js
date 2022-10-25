Ext.define('App.controllers', {
    extend: 'Ext.app.Controller', 

    requires: [
        'App.core.backend.UI.controller.init',
        
        // Core
        'App.core.backend.UI.controller.common',
        'App.core.backend.UI.controller.fileManager',
        'App.core.backend.UI.controller.maintenance.type1',
        'App.core.backend.UI.controller.maintenance.typeTree',
        'App.core.backend.UI.controller.maintenance.type1Windowed',
        'App.core.backend.UI.controller.maintenance.type1ModalForm',
        'App.core.backend.UI.controller.maintenance.type1DynamicFilterForm',
        'App.core.backend.UI.controller.maintenance.cloneForm',
        'App.core.backend.UI.controller.maintenance.basic',

        // Admin module
        'App.modules.admin.backend.UI.controller.admin',

        // CMS module
        'App.modules.cms.backend.UI.controller.cms',

        // E-commerce module
        'App.modules.ecommerce.backend.UI.controller.ecommerce',
        'App.modules.ecommerce.backend.UI.controller.sales',
        'App.modules.ecommerce.backend.UI.controller.article',

        // Reporting module
        'App.modules.reporting.backend.UI.controller.reporting',
        'App.modules.reporting.backend.UI.controller.farmaticArticles',
        'App.modules.reporting.backend.UI.controller.botplusMedicines',

        // SEO module
        'App.modules.seo.backend.UI.controller.seo',

        // Marketing module
        'App.modules.marketing.backend.UI.controller.marketing'
    ]
        
});