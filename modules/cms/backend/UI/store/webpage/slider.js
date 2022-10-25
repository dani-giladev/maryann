Ext.define('App.modules.cms.backend.UI.store.webpage.slider', 
{
    extend: 'Ext.data.Store',
    model: 'App.modules.cms.backend.UI.model.webpage.slider',
    proxy: {
        type: 'ajax',
        url : 'index.php',
        extraParams: {
            // The controller is assigned dinamically 
            method: 'getSlider'
        },
        reader: {
            type: 'json',
            rootProperty: 'data.results',
            totalProperty: 'data.total'
        }
    }
});