Ext.define('App.modules.admin.backend.UI.store.userGroup.permissions', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.admin.backend.UI.model.userGroup.permissions',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            controller: 'modules\\admin\\backend\\controller\\userGroup', 
            method: 'getPermissions'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }
});