Ext.define('App.modules.ecommerce.backend.UI.view.article.categories.tree', {
    extend: 'Ext.tree.Panel',
    
    alias: 'widget.ecommerce_article_categories_tree',
    
    explotation: 'E-Commerce article categories checktree (tree)',

    region: 'center',

    border: false,
    frame: false,
    autoScroll: true,
    
    config: null,
    
    initComponent: function()
    {    
        var me = this;
        
        me.itemId = 'ecommerce_article_categories_tree';
        
        me.xtype = 'check-tree';
        me.title = '';
        //me.useArrows = true;
        me.rootVisible = false;  
        
        var record_id = me.getMaintenanceController().getCurrentRecord(me.config).data.id;
        
        // The ajax params
        var params = {
            controller: 'core\\backend\\controller\\maintenance\\typeTree', 
            method: 'getTree',
            module_id: me.config.module_id,
            model_id: 'categories',
            record_id: record_id,
            record_model_id: me.config.model.id,
            record_property_name: 'categories'
        };    
        
        me.store = Ext.create('Ext.data.TreeStore',
        {
            autoLoad: true,
            root:
            {
                text: '/',
                expanded: true,
                loaded: true,
                draggable: false
            },
            proxy:
            {
                type: 'ajax',
                url : 'index.php',
                extraParams: params
            }
        });    
        
        me.columns =
        [
            {
                xtype: 'treecolumn', //this is so we know which column will show the tree
                text: me.trans('tree'),
                renderer: me.formatName,
                align:'left',
                flex: 1
            }
        ];    
        
        me.callParent(arguments);
        
        me.store.on('load', this.onLoad, this, {single: true});
        me.on('checkchange', this.onCheckchange, this);        
    },
    
    onLoad: function(this_store, node, records, successful, eOpts)
    {
        //var me = this;
        
        if(records.length === 0)
        {
            return;
        }
        
//        var tree_store = me.getStore();
//        var tree_root_node = tree_store.getNodeById('tree-root');
//        tree_root_node.collapse(true);

        //me.expandAll(me.onExpandedAll()); 
        //me.collapseAll(me.onCollapsedAll()); 
    }, 
    
    onExpandedAll: function()
    {
        var me = this;
        console.log('onExpandedAll');
        
//        var tree_store = me.getStore();
        
        //var root_node = me.getRootNode();
        //me.collapseAll();
        
        
//        var tree_root_node = tree_store.getNodeById('tree-root');
//        tree_root_node.collapse();
        
//        var node = tree_store.getNodeById('med');
//        console.log(node);
        //node.collapse();
        
//        tree_root_node.eachChild(function(child) {
//            console.log(child);
//            child.collapse();
//        });
        
//            root_node.cascadeBy(function (node)
//            {
//                if (node.data.id !== 'root' && node.data.id !== 'tree-root' && node.data.checked)
//                {
//                    console.log(node);
//                    node.expand();
//                }
//            });         
    }, 
    
    onCollapsedAll: function()
    {
        var me = this;
        console.log('onCollapsedAll');
        
    },
    
    onCheckchange: function(node, checked, eOpts)
    {
        if (!checked)
        {
            return;
        }
        
        var me = this;
        var parentNode, parentNodeId;
        var tree_store = me.getStore();
        
        while (true) {
            parentNode = node.parentNode;
            parentNodeId = parentNode.data.id; 
            //console.log(parentNodeId);
            if (parentNodeId === 'tree-root')
            {
                return;
            }
            node = tree_store.getNodeById(parentNodeId);
            node.set('checked', true);
        }               
    },

    formatName: function(value, metadata, record, rowIndex, colIndex, store)
    {
        return record.data.text;
    },
            
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.article');       
        return controller;
    },
        
    getMaintenanceController: function()
    {
        var controller = App.app.getController('App.core.backend.UI.controller.maintenance.type1');       
        return controller;
    }
});