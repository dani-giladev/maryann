Ext.define('App.modules.ecommerce.backend.UI.model.article.erp.farmatic', {
    extend: 'Ext.data.Model',
    fields: 
    [
        {name: 'code'},
        {name: 'description'},
        {name: 'pvp'},
        {name: 'puc'},
        {name: 'pmc'},
        {name: 'stock'},
        {name: 'updateStock'},
        {name: 'onLine'},
        {name: 'gtin'},
        {name: 'pucMargin'},
        {name: 'pmcMargin'},
        {name: 'lastReading'}
    ]
});