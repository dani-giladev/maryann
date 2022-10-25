Ext.define('App.modules.ecommerce.backend.UI.view.articleProperty.articleProperty', {
    extend: 'App.core.backend.UI.view.maintenance.basic.basic',
    
    alias: 'widget.ecommerce_articleProperty',
        
    explotation: 'E-Commerce article property view',
    
    // Overwritten
    initGeneralProperties: function()
    {
        this.config.hide_datapanel_title = true;               
        this.config.enable_publication = false;
        this.config.enable_deletion = true;
        this.config.save_controller = 'modules\\ecommerce\\backend\\controller\\articleProperty';
        this.config.delete_controller = this.config.save_controller;
    },
            
    // Overwritten
    initForm: function()
    {
        var me = this;
        
        me.config.form =
        {
            title: '',
            fields:
            [
                me.getMainFieldset(),
                me.getPropertiesFieldset(),
                me.getTitlesFieldset(),
                me.getValuesFieldset()
            ]
        };
    },
            
    // Overwritten
    setTitles: function()
    {
        this.config.grid.title = this.trans('article_property_view');
        this.config.form.title = this.trans('article_property_form');
    },
    
    getTitlesFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('title'),
            anchor: '100%',
            items: 
            [   
                {
                    xtype: 'label',
                    itemId: 'ecommerce_article_property_title_msg_no_available_lang',
                    text: me.trans('no_available_language'),
                    style: {
                        color: 'red'
                    },
                    hidden: true,   
                    margin: '0 0 0 10'
                },                     
                {
                    xtype: 'tabpanel',   
                    itemId: 'ecommerce_article_property_tabpanel_title'
                }
            ]
        };
        
        return ret;
    },
    
    getValuesFieldset: function()
    {
        var me = this;
        var ret =  
        {
            xtype: 'fieldset',
            padding: 5,
            title: me.trans('values'),
            anchor: '100%',
            items: 
            [    
                Ext.widget('ecommerce_article_property_values_grid')
            ]     
        };
        
        return ret;
    },
    
    onRender: function(form, eOpts)
    {
        var me = this;
        
        me.createTabsContent();                  
        
        this.callParent(arguments);
    },
    
    createTabsContent: function()
    {
        var me = this;
        me.createTabContent('title');
    },
    
    createTabContent: function(type)
    {
        var me = this;
        var lang_code, lang_name, i;
        var tab = me.down('#ecommerce_article_property_tabpanel_' + type);
        var langs = App.app.getController('App.core.backend.UI.controller.common').getAvailableLangs();
        
        if (Ext.isEmpty(langs))
        {
            tab.hide();
            var label = Ext.ComponentQuery.query('#ecommerce_article_property_' + type + '_msg_no_available_lang')[0];     
            label.show();
        }
        else
        {
            i = 0;
            Ext.each(langs, function(lang) {
                lang_code = lang.code;
                lang_name = lang.name;
                
                var name;
                if (type === 'title')
                {
                    name = 'titles';
                }
                tab.add({
                    xtype: 'textfield',
                    title: lang_name,
                    name: name + '-' + lang_code,
                    _name: name,
                    _lang_code: lang_code,
                    fieldLabel: '',
                    anchor: '100%'
                }); 
                
                i++;
            });                 

            tab.setActiveTab(0);                                            
        }         
    },
            
    // Overwritten            
    onNewRecord: function()
    {
        var view = App.app.getController('App.core.backend.UI.controller.maintenance.basic').getMaintenanceView(this.config);
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.basic').getForm(this.config);
        var tab;
        
        // Clean title tab
        tab = view.down('#ecommerce_article_property_tabpanel_title');
        Ext.each(tab.items.items, function(item) {
            item.setValue('');
        }); 
        
        // Clear values grid
        var grid = view.down('#ecommerce_article_property_values_grid');
        grid.getStore().removeAll();
        
        // Finally.. clear form
        //extjs6 form.clearDirty();
    },
    
    // Overwritten    
    onEditedRecord: function(id)
    {
        var maintenance_controller = App.app.getController('App.core.backend.UI.controller.maintenance.basic');
        var view = maintenance_controller.getMaintenanceView(this.config);
        var form = maintenance_controller.getForm(this.config);
        var record = maintenance_controller.getCurrentRecord(this.config);
        var tab;
        
        // Set title tab
        tab = view.down('#ecommerce_article_property_tabpanel_title');
        Ext.each(tab.items.items, function(item) {
            var value = '';
            if (!Ext.isEmpty(record.data[item._name]) &&
                !Ext.isEmpty(record.data[item._name][item._lang_code]))
            {
                value = record.data[item._name][item._lang_code];
            }
            item.setValue(value);
        }); 

        // Set values to values grid
        var grid = view.down('#ecommerce_article_property_values_grid');
        var grid_store = grid.getStore();
        grid_store.removeAll();
        var values = record.get('values');
        Ext.each(values, function(item) {
            grid_store.add(item);
        });  
        grid_store.commitChanges();
        
        // Finally.. clear form
        //extjs6 form.clearDirty();
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }
    
});