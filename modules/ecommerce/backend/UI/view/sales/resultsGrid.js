Ext.define('App.modules.ecommerce.backend.UI.view.sales.resultsGrid', {
    extend: 'Ext.grid.Panel',
    
    alias: 'widget.ecommerce_sales_resultsgrid',
    itemId: 'ecommerce_sales_resultsgrid',
    
    explotation: 'Sales grid view for E-commerce module',

    region: 'center',

    border: false,
    frame: false,
    autoScroll: true,

    config: null,
    
    initComponent: function()
    {
        var me = this;
        
        me.title = '';        
        me.store = me.config.store;
        me.viewConfig = {
            emptyText: me.trans('there_are_not_records_to_show'),
            deferEmptyText: false,
            listeners: {
                scope: me,
                itemcontextmenu: me.onContextMenu
            }
        };
        me.features = [
            {
                ftype: "summary"
            }           
        ];      
        me.columns = me.getColumns();
    
        me.callParent(arguments);
        //me.store.on('load', this.onLoad, this);
        //me.on('selectionchange', this.onSelect, this);
    },
    
    getColumns: function()
    {
        var me = this;
        
        var items = [
            {
                text: me.trans('order_number'),
                dataIndex: 'code',
                width: 170,
                filterable: true, 
                filter: {type: 'string'}
            }
        ];
        
        if (Ext.isEmpty(ecommerce_only_one_delegation))
        {
            items.push(
                {
                    text: me.trans('delegation'),
                    dataIndex: 'delegationName',
                    width: 180
                }
            );
        }
        
        items.push(
            {
                text: me.trans('date'),
                dataIndex: 'date',
                renderer: me.formatDate,
                align: 'center',
                width: 100
            },
            {
                text: me.trans('customer'),
                dataIndex: 'lastName',
                renderer: me.formatCustomer,
                flex: 1,
                minWidth: 180,
                filterable: true, 
                filter: {type: 'string'}
            },
            {
                text: me.trans('total_price'),
                dataIndex: 'totalPrice',
                align: 'center',
                width: 100,
                summaryType: 'sum'
            },
            {
                text: me.trans('shipping_cost'),
                dataIndex: 'shippingCost',
                align: 'center',
                width: 100
            },
            {
                text: me.trans('voucher_discount'),
                dataIndex: 'voucherDiscount',
                align: 'center',
                width: 100
            },
            {
                text: me.trans('2nd_unit_discount'),
                dataIndex: 'secondUnitDiscount',
                align: 'center',
                width: 100
            },
            {
                text: me.trans('final_total_price'),
                dataIndex: 'finalTotalPrice',
                align: 'center',
                width: 100,
                summaryType: 'sum'
            },
            {
                text: me.trans('payment'),
                dataIndex: 'paymentWay',
                align: 'center',
                width: 90
            },
            {
                text: me.trans('articles'),
                dataIndex: 'shoppingcart',
                renderer: me.formatShoppingcart,
                flex: 1,
                minWidth: 200
            },
            {
                text: me.trans('comments'),
                dataIndex: 'comments',
                flex: 1,
                minWidth: 100
            },
            {
                text: me.trans('cancelled_female'),
                dataIndex: 'cancelled',
                renderer: me.formatBoolean,
                align: 'center',
                width: 100,
                filterable: true, 
                filter: {type: 'boolean'}//, active:true, value: false}
            },
            {
                text: 'Mobile',
                dataIndex: 'mobile',
                renderer: me.formatBoolean,
                align: 'center',
                width: 100,
                filterable: true, 
                filter: {type: 'boolean'}
            }
        );
        
        return items;
    },
    
    onContextMenu: function(view, record, item, index, e, eOpts)
    {
        var me = this;
        
        var menu = Ext.create('Ext.menu.Menu', 
        {
            items:
            [
                {                       
                    text: me.trans('view_order_confirmation'),
                    icon: 'resources/ico/blue-eye.png',
                    handler: function(e, t)
                    {
                        var record = me.getSelectionModel().getSelection()[0];
                        //console.log(record);

                        Ext.getBody().mask(me.trans('loading'));

                        Ext.Ajax.request({
                            type: 'ajax',
                            url : 'index.php',
                            method: 'GET',
                            params: {
                                controller: 'modules\\ecommerce\\backend\\controller\\sales', 
                                method: 'getUrl',
                                code: record.get('code')              
                            },
                            success: function(response, opts)
                            {
                                Ext.getBody().unmask();
                                var obj = Ext.JSON.decode(response.responseText);
                                if(!obj.success)
                                {
                                    Ext.MessageBox.show({
                                       title: 'Error',
                                       msg: obj.data.result,
                                       buttons: Ext.MessageBox.OK,
                                       icon: Ext.MessageBox.INFO
                                    });
                                }

                                var url = obj.data.result;
                                //console.log(url);
                                window.open(url, '_blank');
                            },
                            failure: function(form, data)
                            {
                                Ext.getBody().unmask();
                            }
                        });                      

                    }
                }
            ]
        });
        
        e.stopEvent();
        menu.showAt(e.getXY());
        e.preventDefault();
    },
    
    onRender: function(grid, eOpts)
    {
        this.getViewController().refreshGrid(this.config);
        
        this.callParent(arguments);
    },
    
    onLoad: function(store, records, successful, eOpts)
    {
//        console.log(record);
    },
    
    onSelect: function(grid, record, index, eOpts)
    {
//        console.log(record);
    },
            
    formatBoolean: function(value)
    {
        return Ext.String.format('<img src="resources/ico/'+(value ? 'true' : 'false')+'.png" />');
    },
            
    formatBold: function(value)
    {
        return '<b>' + value + '</b>';
    },
            
    formatDate: function(value)
    {
        return value;
        //return Ext.Date.format(value, app_dateformat);
    },

    formatCustomer: function(value, metadata, record, rowIndex, colIndex, store)
    {
        var html = 
                '<b>' + value + ', ' + record.data.firstName + '</b>' + '</br>' +
                record.data.email + '</br>' +
                record.data.phone;
        
        return html;
    },
    
    formatShoppingcart: function(value, metadata, record, rowIndex, colIndex, store)
    {
        var html = '';     
        
        Ext.iterate(value, function(article_code, values) {
            //console.log(values);
            var displays = values.article.displays;
            var display = displays[logged_lang];
            var titles = values.article.titles;
            var title = titles[logged_lang];

            var article_name = title;
            if (!Ext.isEmpty(display))
            {
                article_name = article_name + ' - ' + display;
            }
            if (!Ext.isEmpty(values.article.gammaName))
            {
                article_name = values.article.gammaName + ' - ' + article_name;
            }

            html += 
                    '<b>' + article_name + '</b>' +
                    ' (' + values.amount + ' X ' + values.price + '&euro;)' +
                    '</br>' +
                    '';            
        });
        
        return html;
    },
    
    trans: function(id)
    {
        var lang_store = App.app.getController('App.modules.ecommerce.backend.UI.controller.ecommerce').getLangStore();
        return App.app.trans(id, lang_store);
    },
        
    getViewController: function()
    {
        var controller = App.app.getController('App.modules.ecommerce.backend.UI.controller.sales');       
        return controller;
    }
    
});