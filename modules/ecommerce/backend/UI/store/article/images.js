Ext.define('App.modules.ecommerce.backend.UI.store.article.images', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.ecommerce.backend.UI.model.article.image',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            // The controller is assigned dinamically 
            method: 'getImages'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }
});