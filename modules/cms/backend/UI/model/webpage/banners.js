Ext.define('App.modules.cms.backend.UI.model.webpage.banners', {
    extend: 'Ext.data.Model',
    fields: 
    [
        // Row
        {name: 'available'},
        {name: 'promo'},
        
        {name: 'width'},
        {name: 'height'},
        // Margin
        {name: 'marginTop'},
        {name: 'marginRight'},
        {name: 'marginBottom'},
        {name: 'marginLeft'},
        // Padding
        {name: 'paddingTop'},
        {name: 'paddingRight'},
        {name: 'paddingBottom'},
        {name: 'paddingLeft'},
        
        // Columns
        {name: 'columns'}
    ]
});