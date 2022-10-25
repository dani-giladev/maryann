Ext.define('App.modules.ecommerce.backend.UI.store.article.properties', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.ecommerce.backend.UI.model.article.propertyValue',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            controller: 'modules\\ecommerce\\backend\\controller\\articleProperty', 
            method: 'getValues'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }
});