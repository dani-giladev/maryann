Ext.define('App.modules.ecommerce.backend.UI.view.gamma.additionalData.additionalData', {
    extend: 'Ext.button.Button',
    
    alias: 'widget.ecommerce_gamma_additionaldata',
    itemId: 'ecommerce_gamma_additionaldata',
        
    explotation: 'E-commerce additional data gamma view (button menu)',
    iconCls: "x-fa fa-bars",
            
    config: null,
    
    initComponent: function()
    {
        // General properties
        this.initGeneralProperties();
        // The button menu
        this.initMenu();
            
        this.callParent(arguments);         
    },
    
    initGeneralProperties: function()
    {
        var me = this;
        me.text = me.trans('additional_data');
    },
    
    initMenu: function()
    {
        var me = this;
        me.menu =
        [
            me.getDescriptionsMenu()     
        ];
    },
           
    getDescriptionsMenu: function()
    {   
        var me = this;  
        var ret =       
        {
            text: me.trans('descriptions'),
            iconCls: "x-fa fa-text-height",
            handler: function()
            {
                var grid = me.getMaintenanceController().getGrid(me.config);
                var selected = grid.getSelectionModel().getSelection(); 
                if (!selected[0])
                {
                    Ext.MessageBox.show({
                       title: me.trans('descriptions'),
                       msg: me.trans('select_register_previously'),
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.INFO
                    });     
                    return;
                }
                var record = selected[0];
                
                // New config
                var config = me.getModalFormMaintenanceController().cloneConfig(me.config);
                config.form = Ext.widget('ecommerce_gamma_additionaldata_descriptions').getForm(config);

                me.getModalFormMaintenanceController().showForm(config, false, record);

            }
        };        
        
        return ret;
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
            
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    },
            
    getModalFormMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1ModalForm');       
        return controller;
    }

});