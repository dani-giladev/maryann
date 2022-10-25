Ext.define('App.modules.admin.backend.UI.store.language.languages', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.admin.backend.UI.model.language.language',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            controller: 'modules\\admin\\backend\\controller\\language',
            method: 'getLanguages'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }/*,
    sorters: [{
        property: 'order',
        direction: 'ASC'
    }]*/
});