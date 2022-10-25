Ext.define('App.modules.admin.backend.UI.model.userGroup.permissionsByModule', {
    extend: 'Ext.data.Model',
    fields: 
    [
        {name: 'menu_id'},
        {name: 'menu_text'},
        {name: 'visualize'},
        {name: 'update'},
        {name: 'delete'},
        {name: 'publish'}
    ]
});