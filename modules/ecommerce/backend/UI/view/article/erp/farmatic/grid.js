Ext.define('App.modules.ecommerce.backend.UI.view.article.erp.farmatic.grid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.ecommerce_article_erp_farmatic_grid',
    itemId: 'ecommerce_article_erp_farmatic_grid',
        
    explotation: 'E-Commerce interface grid view',
    
    border: false,
    frame: false,
    autoScroll: true,
    minHeight: 130,
    
    config: null,
    _property: 'erp',
    
    initComponent: function()
    {
        var me = this;
        
        me.title = '';        
        me.store = Ext.create('App.modules.ecommerce.backend.UI.store.article.erp.farmatic');

        me.viewConfig =  {
            enableTextSelection : true
        };

        me.columns =
        [
            {
                text: me.trans('last_reading'),
                dataIndex: 'lastReading',
                align: 'center',
                width: 150                  
            },    
            {
                text: me.trans('code'),
                dataIndex: 'code',
                width: 100              
            },
            {
                text: me.trans('description'),
                dataIndex: 'description',
                flex: 1,
                minWidth: 200
            },
            {
                text: 'On-line',
                dataIndex: 'onLine',
                align: 'center',
                width: 90,
                renderer: me.formatBoolean                   
            },
            {
                text: 'Stock',
                dataIndex: 'stock',
                align: 'center',
                width: 100                 
            },
            {
                text: me.trans('update_stock'),
                dataIndex: 'updateStock',
                align: 'center',
                width: 140,
                renderer: me.formatBoolean                   
            },
            {
                text: 'PUC' + ' \u20ac',
                dataIndex: 'puc',
                align: 'center',               
                width: 80,
                renderer: me.formatPrice                   
            },
            {
                text: 'PVP' + ' \u20ac',
                dataIndex: 'pvp',
                align: 'center',               
                width: 80,
                renderer: me.formatPrice                   
            },
            {
                text: 'PMC' + ' \u20ac',
                dataIndex: 'pmc',
                align: 'center',               
                width: 80,
                hidden: true,
                renderer: me.formatPrice                   
            },
            {
                text:  me.trans('margin') + ' %',
                dataIndex: 'pucMargin',
                align: 'center',               
                width: 120,
                renderer: me.formatPrice                   
            },  
            {
                text: 'GTIN',
                dataIndex: 'gtin',
                width: 150              
            }
        ];
        
        me.dockedItems =
        [
            Ext.create('Ext.toolbar.Toolbar', 
            {
                dock: 'bottom', //'top',
                items: 
                [
                    {
                        xtype: 'button',
                        text:  me.trans('get_farmatic_data'),
                        handler: function()
                        {
                            var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(me.config);
                            var code = form.getForm().findField('code').getValue();
                            
                            if (Ext.isEmpty(code))
                            {
                                return true;
                            }
                            
                            // The ajax params
                            var params = {
                                controller: 'modules\\ecommerce\\backend\\controller\\article', 
                                method: 'getArticleDataFromFarmatic',
                                article_code: code,
                                from_db: false
                            };        

                            // Set mask
                            me.getEl().mask(me.trans('getting_farmatic_data') + '...'); 
        
                            Ext.Ajax.request(
                            {
                                type: 'ajax',
                                url : 'index.php',
                                method: 'GET',
                                params: params,
                                success: function(response, opts)
                                {
                                    me.getEl().unmask();
                                    var obj = Ext.JSON.decode(response.responseText);
                                    //console.log(obj);
                                    if(!Ext.isEmpty(obj.meta) && !Ext.isEmpty(obj.meta.success) && obj.meta.success)
                                    {
                                        var record = obj.data.results[0];
                                        //console.log(record);
                                        me.getStore().removeAll();
                                        me.getStore().insert(0, record);
                                        
                                        // Set some fields if is new record
                                        var is_new_record = App.app.getController('App.core.backend.UI.controller.maintenance.type1').isNewRecord(me.config);
                                        if (is_new_record)
                                        {
                                            var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(me.config);
                                            form.getForm().findField('saleRate').setValue('parafarmacia');
                                            form.getForm().findField('costPrice').setValue(record.puc);
                                            form.getForm().findField('margin').setValue(record.pucMargin);
                                            form.getForm().findField('useDiscount').setValue('saleRate');
                                            form.getForm().findField('gtin').setValue(record.gtin);
                                            form.getForm().findField('stock').setValue(record.stock);
                                            var title_tab = Ext.ComponentQuery.query("#ecommerce_article_tabpanel_title")[0];
                                            /*Ext.each(title_tab.items.items, function(item) {
                                                item.setValue(record.description);
                                            });*/
                                            
                                            var description = App.app.getController('App.core.backend.UI.controller.common').capitalizeFirstLetter(record.description);
                                            title_tab.items.items[0].setValue(description);
                                        }
                                    }
                                    else
                                    {
                                        Ext.MessageBox.show({
                                           title: 'Error Farmatic',
                                           msg: obj.data.result,
                                           buttons: Ext.MessageBox.OK,
                                           icon: Ext.MessageBox.ERROR
                                        });
                                    }
                                },
                                failure: function(form, data)
                                {
                                    me.getEl().unmask();
                                    var obj = Ext.JSON.decode(data.response.responseText);
                                    Ext.MessageBox.show({
                                       title: 'Error Farmatic',
                                       msg: obj.data.result,
                                       buttons: Ext.MessageBox.OK,
                                       icon: Ext.MessageBox.ERROR
                                    });
                                }
                            });                            

                        }
                    },
                    {
                        xtype: 'button',
                        text: me.trans('mark_as') + ' On-line',
                        handler: me.markOffAsOnline
                    },
                    {
                        xtype: 'button',
                        text: me.trans('mark_as') + ' Off-line',
                        handler: me.markOffAsOffline
                    }                        
                ]
            })
        ];
            
        me.callParent(arguments);
    },

    onRender: function(grid, options)
    {      
        
        this.callParent(arguments);           
    },
    
    markOffAsOnline: function(button, eventObject)
    {
        var me = button.up('gridpanel');
        me.markOffAsOnlineOrOffline(true);
    },
    
    markOffAsOffline: function(button, eventObject)
    {
        var me = button.up('gridpanel');
        me.markOffAsOnlineOrOffline(false);
    },
    
    markOffAsOnlineOrOffline: function(online)
    {
        var me = this;
        var form = App.app.getController('App.core.backend.UI.controller.maintenance.type1').getForm(me.config);
        var code = form.getForm().findField('code').getValue();

        if (Ext.isEmpty(code))
        {
            return true;
        }

        // The ajax params
        var params = {
            controller: 'modules\\ecommerce\\backend\\controller\\article', 
            method: 'markOffArticleAsOnlineOrOfflineToFarmatic',
            article_code: code,
            online: online
        };        

        // Set mask
        me.getEl().mask(me.trans('sending_data_to_farmatic') + '...'); 

        Ext.Ajax.request(
        {
            type: 'ajax',
            url : 'index.php',
            method: 'GET',
            params: params,
            success: function(response, opts)
            {
                me.getEl().unmask();
                var obj = Ext.JSON.decode(response.responseText);
                //console.log(obj);
                if(!Ext.isEmpty(obj.meta) && !Ext.isEmpty(obj.meta.success) && obj.meta.success)
                {
                    var record = obj.data.results[0];
                    //console.log(record);
                    me.getStore().removeAll();
                    me.getStore().insert(0, record);
                }
                else
                {
                    Ext.MessageBox.show({
                       title: 'Error Farmatic',
                       msg: obj.data.result,
                       buttons: Ext.MessageBox.OK,
                       icon: Ext.MessageBox.ERROR
                    });
                }
            },
            failure: function(form, data)
            {
                me.getEl().unmask();
                var obj = Ext.JSON.decode(data.response.responseText);
                Ext.MessageBox.show({
                   title: 'Error Farmatic',
                   msg: obj.data.result,
                   buttons: Ext.MessageBox.OK,
                   icon: Ext.MessageBox.ERROR
                });
            }
        });                            

    },
    
    formatBoolean: function(value)
    {
        return Ext.String.format('<img src="resources/ico/'+(value ? 'true' : 'false')+'.png" />');
    },        
        
    formatPrice: function(value)
    {
        //var new_price = Ext.util.Format.number(value, '0.00');   
        var new_price = parseFloat(value).toFixed(2);
        return new_price;                                 
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    }

});