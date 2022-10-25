Ext.define('App.modules.cms.backend.UI.view.webpage.additionalData.slider.form.toolbar', {
    extend: 'Ext.toolbar.Toolbar',
    
    alias: 'widget.cms_webpage_additionaldata_slider_toolbar',

    region: 'south',
                
    border: true,
    frame: false,
    
//    ui: 'footer',
    
    config: null,
    
    initComponent: function() {
        
        var me = this;
        
        this.title = '';
        
        this.items = 
        [     
            {xtype: 'tbfill'},
            {
                text: me.trans('accept'),
                handler: me.accept
            },      
            {
                text: me.trans('cancel'),
                handler: me.cancel
            }            
        ];
            
        this.callParent(arguments);
    },
            
    accept: function(button, eventObject)
    {
        var me = this;
        var window = button.up('window');
        var grid = Ext.ComponentQuery.query('#cms_webpage_additionaldata_slider_grid')[0];
        var store = grid.getStore();
        var form = button.up('window').down('form');
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (!form.getForm().isValid())
        {
            return;
        }
        
        // Get values
        var form_values = form.getValues();
        var available = (form_values.available === 'on');
        var promo = form_values.promo;
            
        // Set values on store
        if (form.is_new_record)
        {
            var new_record = 
            {
                available : available,
                promo : promo,
                image: {},
                title: {},
                url: {}
            };
            
            Ext.each(langs, function(lang)
            {
                new_record.image[lang.code] = form_values['image-' + lang.code];
                new_record.title[lang.code] = form_values['title-' + lang.code];
                new_record.url[lang.code] = form_values['url-' + lang.code];
            }); 

            //var count_rows = store.getCount();
            //store.insert(count_rows, new_record);
            store.add(new_record);            
        }
        else
        {
            //var record = store.findRecord('code', code);
            var index = store.indexOf(form.current_record);
            var record = store.getAt(index);
            
            record.set('available', available);
            record.set('promo', promo);
            
            var image = {}, title = {}, url = {};
            Ext.each(langs, function(lang)
            {
               image[lang.code] = form_values['image-' + lang.code];
               title[lang.code] = form_values['title-' + lang.code];
               url[lang.code] = form_values['url-' + lang.code];
            });            
            
            record.set('image', image); 
            record.set('title', title);    
            record.set('url', url);      
        }

        window.close();
    },
            
    cancel: function(button, eventObject)
    {
        var window = button.up('window');
        window.close();
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.cms.backend.UI.controller.cms').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});