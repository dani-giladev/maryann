Ext.define('App.modules.admin.backend.UI.controller.admin', {
    extend: 'App.core.backend.UI.controller.common',

    views: [
        'App.modules.admin.backend.UI.view.delegation.delegation',
        'App.modules.admin.backend.UI.view.language.language',
        'App.modules.admin.backend.UI.view.userGroup.userGroup',
        'App.modules.admin.backend.UI.view.userGroup.permissions',
        'App.modules.admin.backend.UI.view.user.user'
    ],
    
    models: [
        'App.modules.admin.backend.UI.model.language.language',
        'App.modules.admin.backend.UI.model.userGroup.permissions',
        'App.modules.admin.backend.UI.model.userGroup.permissionsByModule'
    ],
    
    stores: [
        'App.modules.admin.backend.UI.store.language.languages',
        'App.modules.admin.backend.UI.store.userGroup.permissions',
        'App.modules.admin.backend.UI.store.userGroup.permissionsByModule'
    ],

    refs: [
        
    ],
    
    init: function() 
    {
        this.control({

        }); 
    }
});