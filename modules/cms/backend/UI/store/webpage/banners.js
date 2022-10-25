Ext.define('App.modules.cms.backend.UI.store.webpage.banners', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.cms.backend.UI.model.webpage.banners',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            // The controller is assigned dinamically 
            method: 'getBanners'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }
});