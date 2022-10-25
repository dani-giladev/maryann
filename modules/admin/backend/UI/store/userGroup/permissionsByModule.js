Ext.define('App.modules.admin.backend.UI.store.userGroup.permissionsByModule', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.admin.backend.UI.model.userGroup.permissionsByModule',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            controller: 'modules\\admin\\backend\\controller\\userGroup', 
            method: 'getPermissionsByModule'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }
});