Ext.define('App.modules.admin.backend.UI.view.userGroup.permissions', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.admin_user_group_permissions',
        
    explotation: 'Common module permissions grid of user groups view',
    
    border: false,
    frame: false,
    autoScroll: true,
    margin: '0 0 12 0',
    
    initComponent: function()
    {
        var me = this;
        
        this.title = '';        
        this.store = Ext.create('App.modules.admin.backend.UI.store.userGroup.permissionsByModule');
        this.columns =
        [
            {
                text: me.trans('menu'),
                dataIndex: 'menu_text',
                width: 200
            },
            {
                text: me.trans('visualize'),
                dataIndex: 'visualize',
                align:'center',
                renderer: this.formatBoolean,
                editor: {
                    xtype: 'checkbox',
                    selectOnFocus: false
                },
                flex: 1
            },
            {
                text: me.trans('update_insert'),
                dataIndex: 'update',
                align:'center',
                renderer: this.formatBoolean,
                editor: {
                    xtype: 'checkbox',
                    selectOnFocus: false
                },
                flex: 1
            },
            {
                text: me.trans('delete'),
                dataIndex: 'delete',
                align:'center',
                renderer: this.formatBoolean,
                editor: {
                    xtype: 'checkbox',
                    selectOnFocus: false
                },
                flex: 1
            },
            {
                text: me.trans('publish'),
                dataIndex: 'publish',
                align:'center',
                renderer: this.formatBoolean,
                editor: {
                    xtype: 'checkbox',
                    selectOnFocus: false
                },
                flex: 1
            }
        ];
        
        this.plugins = 
        [
            Ext.create('Ext.grid.plugin.CellEditing', {
                clicksToEdit: 1
            })
        ];
            
        this.callParent(arguments);
    },
    
    formatBoolean: function(value)
    {
        return Ext.String.format('<img src="resources/ico/'+(value ? 'true' : 'false')+'.png" />');
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.admin.backend.UI.controller.admin').getLangStore();
        return App.app.trans(id, lang_store);
    }
         

});