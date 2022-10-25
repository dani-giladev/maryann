Ext.define('App.modules.ecommerce.backend.UI.store.article.erp.farmatic', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.ecommerce.backend.UI.model.article.erp.farmatic',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            controller: 'modules\\ecommerce\\backend\\controller\\article', 
            method: 'getArticleDataFromFarmatic'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }
});