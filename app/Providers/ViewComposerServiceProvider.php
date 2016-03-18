<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // itemList
        view()->composer(array('product.bomitems.createitem', 'product.bomitems.edit', 'sales.soitems.create', 'sales.soitems.edit'), function($view) {
            $view->with('itemList', \App\Models\Product\Item::orderby('id', 'asc')->lists('item_number', 'id'));
        });
        
        // itemclassList
        view()->composer(array('items.create', 'items.edit', 'product.items.create', 'product.items.edit'), function($view) {
            $view->with('itemclassList', \App\Models\Product\Itemclass::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // itemtypeList
        view()->composer(array('items.create', 'items.edit', 'product.items.create', 'product.items.edit'), function($view) {
            $view->with('itemtypeList', \App\Models\Product\Itemtype::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // provinceList
        view()->composer(array('addr.citys.create', 'addr.citys.edit', 'addr.addrs.create', 'addr.addrs.edit'), function($view) {
            $view->with('provinceList', \App\Province::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // cityList
        view()->composer(array('addr.addrs.create', 'addr.addrs.edit'), function($view) {
            $view->with('cityList', \App\City::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // addrList
        view()->composer(array('contacts.create', 'contacts.edit', 'inventory.warehouses.create', 'inventory.warehouses.edit',
            'purchase.vendinfos.create', 'purchase.vendinfos.edit'), function($view) {
            $view->with('addrList', \App\Models\Crm\Addr::orderby('id', 'asc')->lists('line1', 'id'));
        });
        
        // contactList
        view()->composer(array('custinfos.create', 'custinfos.edit', 'inventory.warehouses.create', 'inventory.warehouses.edit',
            'purchase.vendinfos.create', 'purchase.vendinfos.edit', 'purchase.purchaseorders.create', 'purchase.purchaseorders.edit'), function($view) {
            $view->with('contactList', \App\Models\Crm\Contact::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // deptList
        view()->composer(array('system.employees.create', 'system.employees.edit'), function($view) {
            $view->with('deptList', \App\System\Dept::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // imageList
        view()->composer(array('system.employees.create', 'system.employees.edit'), function($view) {
            $view->with('imageList', \App\System\Image::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // custinfoList
        view()->composer(array('sales.salesorders.create', 'sales.salesorders.edit'), function($view) {
            $view->with('custinfoList', \App\Models\Crm\Custinfo::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // salesrepList
        view()->composer(array('sales.salesorders.create', 'sales.salesorders.edit'), function($view) {
            $view->with('salesrepList', \App\Models\Sales\Salesrep::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // termList
        view()->composer(array('purchase.vendinfos.create', 'purchase.vendinfos.edit', 'purchase.purchaseorders.create', 'purchase.purchaseorders.edit'), function($view) {
            $view->with('termList', \App\Sales\Term::orderby('id', 'asc')->lists('code', 'id'));
        });
        
        // vendtypeList
        view()->composer(array('purchase.vendinfos.create', 'purchase.vendinfos.edit'), function($view) {
            $view->with('vendtypeList', \App\Purchase\Vendtype::orderby('id', 'asc')->lists('code', 'id'));
        });
        
        // vendinfoList
        view()->composer(array('purchase.purchaseorders.create', 'purchase.purchaseorders.edit'), function($view) {
            $view->with('vendinfoList', \App\Purchase\Vendinfo::orderby('id', 'asc')->lists('number', 'id'));
        });
        
        // soheadList
        view()->composer(array('purchase.purchaseorders.create', 'purchase.purchaseorders.edit'), function($view) {
            $view->with('soheadList', \App\Sales\Salesorder::orderby('id', 'asc')->lists('number', 'id'));
        });
        
        // itemsiteList
        view()->composer(array('purchase.poitems.create', 'purchase.poitems.edit'), function($view) {
//             $items = \App\Inventory\Itemsite::orderby('itemsites.id', 'asc')->leftJoin('items', 'itemsites.item_id', '=', 'items.id')->select('item_number', 'itemsites.id')->get();
            $view->with('itemsiteList', \App\Inventory\Itemsite::orderby('itemsites.id', 'asc')->leftJoin('items', 'itemsites.item_id', '=', 'items.id')->select('item_number', 'itemsites.id')->lists('item_number', 'id'));
//             $view->with('itemsiteList', DB::table('itemsites')->leftJoin('items', 'itemsites.item_id', '=', 'items.id')->select('item_number', 'itemsites.id')->lists('item_number', 'itemsites.id'));
//             $view->with('itemsiteList', \App\Inventory\Itemsite::orderby('itemsites.id', 'asc')->lists('item_id', 'id'));
        });
        
        // roleList
        view()->composer(array('system.users.editrole'), function($view) {
            $view->with('roleList', \App\Role::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // charIList: item char list
        view()->composer(array('items.create', 'items.edit', 'product.items.create', 'product.items.edit'), function($view) {
            $view->with('charIList', \App\Models\Product\Characteristic::orderby('id', 'asc')->where('bitems', true)->lists('name', 'id'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
