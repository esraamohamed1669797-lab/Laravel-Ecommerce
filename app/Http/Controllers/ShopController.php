<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\product;
use App\Models\Slide;
use Illuminate\Http\Request;

use function GuzzleHttp\default_ca_bundle;

class ShopController extends Controller
{
    public function index(Request $request){
        $slides =Slide::where('status',1)->get()->take(3);
        $size= $request->query('size',12) ;
        $o_column="";
        $o_order="";
        $order =$request->query('order') ? $request->query('order') : -1;
        $f_brands=$request->query('brands');
        $f_categories=$request->query('categories');
       $minPrice = Product::min('regular_price');
       $maxPrice = Product::max('regular_price');
       $min_price = $request->query('min', $minPrice);
       $max_price = $request->query('max', $maxPrice);       
        switch($order){
            case 1: 
                $o_column = 'created_at';
                $o_order ='DESC';
                break;
                case 2 :
                $o_column ='created_at';
                $o_order ='ASC';
                break;
                case 3:
                $o_column ='regular_price';
                $o_order ='ASC';
                break;
                case 4:
                $o_column='regular_price';
                $o_order = 'DESC';
                break;
                default:
                $o_column ='id';
                $o_order = 'DESC';


        } 
        $brands =Brand::withCount('products')->orderBy('name','ASC')->get();
        $categories = Category::with(['children' => function($query){ $query->withCount('products');
         }
       ])
          ->withCount('products')
          ->whereNull('parent_id')
          ->orderBy('name','ASC')
          ->get();
       foreach ($categories as $category) {
        $category->products_count =
        $category->products_count +
        $category->children->sum('products_count');
                }
        $products =product::query();
        if($f_brands != ''){
             $products->whereIn('brand_id',explode(',',$f_brands));
        }
        if($f_categories != '') {

    $category_ids = explode(',', $f_categories);

    $all_categories = [];

    foreach($category_ids as $category_id){

        $all_categories[] = $category_id;

        $children = Category::where('parent_id', $category_id)
                    ->pluck('id')
                    ->toArray();

        $all_categories = array_merge($all_categories, $children);
    }

    $products->whereIn('category_id', array_unique($all_categories));
}
        $products ->where(function($query)use($min_price,$max_price){
            $query->whereBetween('regular_price',[$min_price,$max_price])
            ->orWhereBetween('sale_price',[$min_price,$max_price]);
        });
        if ($order == -1 && $f_brands == '' && $f_categories == '') {
    $products = $products->inRandomOrder()->paginate($size);
} else {
    $products = $products->orderBy($o_column, $o_order)->paginate($size);
}
if ($request->ajax()) {
    return view('shop-products', compact('products'))->render();
}

return view('shop', compact(
    'slides',
    'products',
    'size',
    'order',
    'brands',
    'f_brands',
    'categories',
    'f_categories',
    'min_price',
    'max_price',
    'minPrice',
    'maxPrice'
));
    }




   public function product_details($product_slug){
    $product = Product::where('slug', $product_slug)->first();

    $rproducts = Product::where('id', '!=', $product->id)
        ->where(function ($q) use ($product) {
            $q->where('category_id', $product->category_id);
              
        })
        ->inRandomOrder()
        ->take(6)
        ->get();

    return view('details', compact('product', 'rproducts'));
}

}
