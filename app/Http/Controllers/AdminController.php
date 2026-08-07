<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\product;
use App\Models\Slide;
use App\Models\Contact;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
     public function index(){
      $orders =Order::orderBy('created_at','DESC')->get()->take(10);
      $dashboardDatas = DB::select("Select sum(total) As TotalAmount,
      sum(if(status='ordered',total,0))As TotalOrderedAmount,
      sum(if(status='delivered',total,0))As TotalDeliveredAmount,
      sum(if(status='canceled',total,0))As TotalCanceledAmount,
      count(*) As Total,
      sum(if(status='ordered',1,0))As TotalOrdered,
      sum(if(status='delivered',1,0))As TotalDelivered,
      sum(if(status='canceled',1,0))As TotalCanceled
      From orders
      ");
      $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
      IFNULL(D.TotalAmount,0) As TotalAmount,
      IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
      IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
      IFNULL(D.TotalCanceledAmount,0) As TotalCanceledlAmount
      FROM month_names M
      LEFT JOIN (Select DATE_FORMAT(created_at,'%b') As MonthName,
      MONTH(created_at)As MonthNo,
      sum(total) As TotalAmount,
      sum(if(status='ordered',total,0))As TotalOrderedAmount,
      sum(if(status='delivered',total,0))As TotalDeliveredAmount,
      sum(if(status='canceled',total,0))As TotalCanceledAmount
      From Orders WHERE YEAR(created_at)=Year(NOW()) GROUP BY YEAR(created_at),MONTH(created_at), DATE_FORMAT(created_at, '%b')
      Order By MONTH(created_at)) D On D.MonthNo=M.id");

$AmountM =implode(',',collect($monthlyDatas)->pluck('TotalAmount')->toArray());
$OrderAmountM =implode(',',collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
$DeliveredAmountM =implode(',',collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
$CanceledAmountM =implode(',',collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());
       
$TotalAmount =collect($monthlyDatas)->sum('TotalAmount');
$TotalOrderedAmount =collect($monthlyDatas)->sum('TotalOrderedAmount');
$TotalDeliveredAmount =collect($monthlyDatas)->sum('TotalDeliveredAmount');
$TotalCanceledAmount =collect($monthlyDatas)->sum('TotalCanceledAmount');

      return view('admin.index',compact('orders','dashboardDatas','AmountM','OrderAmountM','DeliveredAmountM','CanceledAmountM','TotalAmount'
      ,'TotalOrderedAmount','TotalDeliveredAmount','TotalCanceledAmount'));
   }
 //////////////brands/////////   
   public function brands(){
      $brands =Brand::orderBy('id','DESC')->withCount('products')->paginate(10);
      return view('admin.brands',compact('brands'));
   }
   //////add///
   public function add_brand(){
      return view('admin.brand-add');
   }
   ////store////
   public function brand_store(Request $request){
      $request->validate([
         'name'=> 'required',
         'slug'=> 'required|unique:brands,slug',
         'image'=> 'mimes:png,jpg,jpeg|max:2048'
         ]);
    $brand =new Brand();
    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);
    $image= $request->file('image');
    $file_extension=$request->file('image')->extension();
    $file_name=Carbon::now()->timestamp.'.'. $file_extension;
    $this->GenerateBrandThumbailsImage($image, $file_name);
    $brand->image=$file_name;
    $brand->save();
    return redirect()->route('admin.brands')->with('status','Brand has been added succesfully');
   }
   /////edit////
   public function brand_edit($id){
      $brand= Brand::find($id);
      return view ('admin.brand-edit',compact('brand'));
   }
        ///update//
   public function brand_update(Request $request){
      $request->validate([
         'name'=> 'required',
         'slug'=> 'required|unique:brands,slug,'.$request->id,
         'image'=> 'mimes:png,jpg,jpeg|max:2048'
         ]);
         $brand =Brand::find($request->id);
         $brand->name = $request->name;
         $brand->slug = Str::slug($request->name);
         if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
               File::delete(public_path('uploads/brands'));
            }
             $image= $request->file('image');
             $file_extension=$request->file('image')->extension();
             $file_name=Carbon::now()->timestamp.'.'. $file_extension;
             $this->GenerateBrandThumbailsImage($image, $file_name);
             $brand->image=$file_name;
         }
        
         $brand->save();
         return redirect()->route('admin.brands')->with('status','Brand has been update succesfully');
   }

public function GenerateBrandThumbailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/brands');

  $img =Image::read($image->path());
  $img->cover(124,124,"top");
  $img->resize(124,124,function($constraint){
   $constraint->aspectRatio();
  })->save($destinationPath.'/'.$imageName);
   
}
       // //delete////
public function brand_delete($id){
$brand =Brand::find($id);
  if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
       File::delete(public_path('uploads/brands').'/'.$brand->image);
     }
     $brand->delete();
     return redirect()->route('admin.brands')->with('status','brand has been deleted successfully');
}


///////////////////categories/////////////

public function categories(){
   $categories = category::orderBy('id','DESC')->withCount('products')->paginate(10);
   return view('admin.categories',compact('categories'));
}
public function category_add(){
   $parentcategories=Category::where('parent_id',null)->orderBy('name','ASC')->get();
   return view('admin.category-add',compact('parentcategories'));
}
public function category_store(Request $request){
     $request->validate([
         'name'=> 'required',
         'slug'=> 'required|unique:categories,slug',
         'parent_id'=>' nullable|exists:categories,id',
         'image'=> 'mimes:png,jpg,jpeg,webp|max:2048'
         ]);
    $category =new category();
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->parent_id = $request->parent_id;
    $image= $request->file('image');
    $file_extension=$request->file('image')->extension();
    $file_name=Carbon::now()->timestamp.'.'. $file_extension;
    $this->GenerateCategoryThumbailsImage($image, $file_name);
    $category->image=$file_name;
    $category->save();
    return redirect()->route('admin.categories')->with('status','category has been added succesfully');
   }
 public function GenerateCategoryThumbailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/categories');

  $img =Image::read($image->path());
  $img->cover(124,124,"top");
  $img->resize(124,124,function($constraint){
   $constraint->aspectRatio();
  })->save($destinationPath.'/'.$imageName);
   
}
public function category_edit($id){
   $category =Category::find($id);
   $parentcategories=Category::where('parent_id',null)->where('id','!=',$category->id)->orderBy('name','ASC')->get();
   return view('admin.category-edit',compact('category','parentcategories'));
}
public function  category_update(Request $request){
    $request->validate([
         'name'=> 'required',
         'slug'=> 'required|unique:categories,slug,'.$request->id,
         'parent_id'=>' nullable|exists:categories,id',
         'image'=> 'mimes:png,jpg,jpeg,webp|max:2048'
         ]);
         $category =category::find($request->id);
         $category->name = $request->name;
         $category->slug = Str::slug($request->name);
         $category->parent_id = $request->parent_id;
         if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
               File::delete(public_path('uploads/categories'));
            }
             $image= $request->file('image');
             $file_extension=$request->file('image')->extension();
             $file_name=Carbon::now()->timestamp.'.'. $file_extension;
             $this->GenerateCategoryThumbailsImage($image, $file_name);
             $category->image=$file_name;
         }
        
         $category->save();
         return redirect()->route('admin.categories')->with('status','Category has been update succesfully');
   }
        public function category_delete($id){
         $category =Category::find($id);
         if(File::exists(public_path('uploads/categories').'/'.$category->image)){
         File::delete(public_path('uploads/categories').'/'.$category->image);
       }
       $category->delete();
       return redirect()->route('admin.categories')->with('status','category has been deleted successfully');
}  
////////////////////Products///////////////////////////////////////////////
public function products(){
   $products =product::orderBy('created_at','DESC')->paginate(10);
   return view('admin.products',compact('products'));
}
////////////////add////////
public function product_add(){
   $categories =Category::select('id','name')->orderBy('name')->get();
   $brands= Brand::select('id','name')->orderBy('name')->get();
   return view('admin.product-add',compact('categories','brands'));

}
/////////////////////store//////
public function product_store(Request $request){
   $request->validate([
       'name'=> 'required' , 
       'slug'=> 'required|unique:products,slug' ,
       'short_description'=> 'required' ,
       'description'=> 'required' ,
       'regular_price'=> 'required' ,
       'sale_price'=> 'nullable|numeric|min:0|max:100' ,
       'SKU'=> 'nullable' ,
       'stock_status'=> 'required' ,
       'featured'=> 'required' ,
       'quantity'=> 'required' ,
       'image'=> 'required|mimes:png,jpg,jpeg,avif|max:2048' ,
       'category_id'=> 'required' ,
       'brand_id'=> 'required' ,
   ]);
   $product =new product();
     $product->name =$request->name;
     $product->slug =str::slug($request->name);
     $product->short_description =$request ->short_description;
     $product->description =$request ->description;
     $product->regular_price =$request ->regular_price;
     $product->sale_price =$request->sale_price ?: null;
     $product->SKU =$request->SKU ?: null;
     $product->stock_status =$request ->stock_status;
     $product->featured =$request ->featured;
     $product->quantity =$request ->quantity;
     $product->category_id =$request ->category_id;
     $product->brand_id =$request ->brand_id;

     $current_timestamp = Carbon::now()->timestamp;
      
     if($request-> hasFile('image')){
      $image = $request->file('image');
      $imageName = $current_timestamp .'.'.$image->extension();
      $this->GenerateProductThumbnailImage($image,$imageName);
      $product->image = $imageName;
     }
     $gallery_arr = array();
     $gallery_images ="";
     $counter =1;
     if($request->hasFile('images')){
      $allowedfileExtion = ['jpg','png','jpeg','avif'];
      $files =$request->file('images');
      foreach($files as $file){
         $gextension = $file->getClientOriginalExtension();
         $gcheck = in_array($gextension,$allowedfileExtion);
         if($gcheck){
            $gfileName =$current_timestamp ."-". $counter . ".". $gextension;
            $this->GenerateProductThumbnailImage($file,$gfileName);
            array_push($gallery_arr,$gfileName);
            $counter = $counter+1;


         }

      }
      $gallery_images = implode(',',$gallery_arr);
     }
     $product->images = $gallery_images;
     $product->save();
     return redirect()->route('admin.products')->with('status','product has been added successfully!');
}
 public function GenerateProductThumbnailImage($image, $imageName)
{
    $destinationPath = public_path('uploads/products');
    $destinationPathThumbnails = public_path('uploads/products/thumbnails');

$img = Image::read($image->path());

$img->scaleDown(width: 540, height: 689);

$img->pad(540, 689, '#ffffff', position: 'center');

$img->save($destinationPath.'/'.$imageName);

// الصورة المصغرة
$thumb = Image::read($image->path());

$thumb->scaleDown(width: 104, height: 104);

$thumb->pad(104, 104, '#ffffff', position: 'center');

$thumb->save($destinationPathThumbnails.'/'.$imageName);
}
/////////////////////edit//////
public function product_edit($id){
   $product =Product::find($id);
    $categories =Category::select('id','name')->orderBy('name')->get();
   $brands= Brand::select('id','name')->orderBy('name')->get();
   return view('admin.product-edit',compact('product','categories','brands'));

}
////////////////////////update/////////////////
public function product_update(Request $request){
   $request->validate([
       'name'=> 'required' , 
       'slug'=> 'required|unique:products,slug,' .$request->id,
       'short_description'=> 'required' ,
       'description'=> 'required' ,
       'regular_price'=> 'required' ,
       'sale_price'=> 'nullable|numeric|min:0|max:100' ,
       'SKU'=> 'nullable' ,
       'stock_status'=> 'required' ,
       'featured'=> 'required' ,
       'quantity'=> 'required' ,
       'image'=> 'mimes:png,jpg,jpeg,avif|max:2048' ,
       'category_id'=> 'required' ,
       'brand_id'=> 'required' ,
   ]);
     $product = Product::find($request->id);
     $product->name =$request->name;
     $product->slug =str::slug($request->name);
     $product->short_description =$request ->short_description;
     $product->description =$request ->description;
     $product->regular_price =$request ->regular_price;
     $product->sale_price =$request->sale_price ?: null;
     $product->SKU =$request->SKU ?: null;
     $product->stock_status =$request ->stock_status;
     $product->featured =$request ->featured;
     $product->quantity =$request ->quantity;
     $product->category_id =$request ->category_id;
     $product->brand_id =$request ->brand_id;

     $current_timestamp = Carbon::now()->timestamp;
      if($request-> hasFile('image')){
         if(File::exists(public_path('uploads/products').'/'.$product->image))
            {
            File::delete(public_path('uploads/producst').'/'.$product->image);
         }
          if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
            {
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
         }
      $image = $request->file('image');
      $imageName = $current_timestamp .'.'.$image->extension();
      $this->GenerateProductThumbnailImage($image,$imageName);
      $product->image = $imageName;
     }
     $gallery_arr = array();
     $gallery_images ="";
     $counter =1;
     if($request->hasFile('images')){
      foreach(explode(',',$product->images) as $ofile){
          if(File::exists(public_path('uploads/products').'/'.$ofile))
            {
            File::delete(public_path('uploads/producst').'/'.$ofile);
         }
          if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile))
            {
            File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
         }
      }
      $allowedfileExtion = ['jpg','png','jpeg','avif'];
      $files =$request->file('images');
      foreach($files as $file){
         $gextension = $file->getClientOriginalExtension();
         $gcheck = in_array($gextension,$allowedfileExtion);
         if($gcheck){
            $gfileName =$current_timestamp ."-". $counter . ".". $gextension;
            $this->GenerateProductThumbnailImage($file,$gfileName);
            array_push($gallery_arr,$gfileName);
            $counter = $counter+1;


         }

      }
      $gallery_images = implode(',',$gallery_arr);
     $product->images = $gallery_images;

     }
     $product->save();
     return redirect()->route('admin.products')->with('status','product has been update successfully!');
     }
     //////////////////////////delete//////////
     public function product_delete($id){
        $product =Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image)){
            File::delete(public_path('uploads/producst').'/'.$product->image);
        }
         if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
            {
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
         }

          foreach(explode(',',$product->images) as $ofile){
          if(File::exists(public_path('uploads/products').'/'.$ofile))
            {
            File::delete(public_path('uploads/producst').'/'.$ofile);
         }
          if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile))
            {
            File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
         }
      }
        $product->delete();
        return redirect()->route('admin.products')->with('status','product has been deleted successfully!');
      

     }
     ////////////////////////coupon///////////////////////
     public function coupons(){
      $coupons =coupon::orderBy('expiry_date','DESC')->paginate(12);
      return view('admin.coupons',compact('coupons'));
     }
     ///////////////////ADD////////////
     public function coupon_add(){
       return view('admin.coupon-add');
     }
     public function coupon_store(Request $request){
         $request->validate([
            'code'=> 'required |unique:coupons,code',
            'type'=> 'required',
            'value'=> 'required|numeric',
            'cart_value'=> 'required|numeric',
            'expiry_date'=> 'required|date',
         ]);
         $coupon =new coupon();
         $coupon->code =$request->code;
         $coupon->type =$request->type;
         $coupon->value =$request->value;
         $coupon->cart_value =$request->cart_value;
         $coupon->expiry_date =$request->expiry_date;
         $coupon->save();
         return redirect()->route('admin.coupons')->with('status','coupon has been added successfully');
     }
     public function coupon_edit($id){
      $coupon =Coupon::find($id);
      return view('admin.coupon-edit',compact('coupon'));
     }
     public function coupon_update(Request $request){
       $request->validate([
            'code'=> 'required|unique:coupons,code,'. $request->id,
            'type'=> 'required',
            'value'=> 'required|numeric',
            'cart_value'=> 'required|numeric',
            'expiry_date'=> 'required|date',
         ]);
         $coupon =Coupon::find($request->id);
         $coupon->code =$request->code;
         $coupon->type =$request->type;
         $coupon->value =$request->value;
         $coupon->cart_value =$request->cart_value;
         $coupon->expiry_date =$request->expiry_date;
         $coupon->save();
         return redirect()->route('admin.coupons')->with('status','coupon has been update successfully');
     }
     public function coupon_delete($id){
      $coupon =Coupon::find($id);
      $coupon->delete();
      return redirect()->route('admin.coupons')->with('status','coupon has been deleted successfully');
     }
     public function orders(){
      $orders= Order::orderBy('created_at','DESC')->paginate(12);
      return view('admin.orders',compact('orders'));
     }
     public function order_details($order_id){
      $order= Order::find($order_id);
      $orderItems=OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
      $transaction =Transaction::where('order_id',$order_id)->first();
      return view('admin.order-details',compact('order','orderItems','transaction'));
     }
     public function update_order_status(Request $request){
           $order =Order::find($request->order_id);
           $order->status =$request->order_status;
           if($request->order_status == 'delivered'){
            $order->delivered_date= Carbon::now();
           }elseif($request->order_status == 'canceled')
           {
            $order->canceled_date= Carbon::now();
            }
            $order->save();
            if($request->order_status == 'delivered'){
               $transaction =Transaction::where('order_id',$request->order_id)->first();
               $transaction->status = 'approved';
               $transaction->save();

            }
            return back()->with("status","status changed successfully");

     }
     public function slides(){
      $slides = Slide::orderBy('id','DESC')->paginate(12);
      return view('admin.slides',compact('slides'));

     }
     public function slide_add(){
          return view('admin.slide-add');
     }
     public function slide_store(Request $request){
      $request->validate([
         'tagline'=> 'required',
         'title'=> 'required',
         'subtitle'=> 'required',
         'link'=> 'required',
         'image'=> 'required|mimes:png,jpg,jpeg,avif,webp|max:2048',
         'status'=> 'required',

      ]);
      $slide = new Slide();
      $slide->tagline =$request->tagline;
      $slide->title =$request->title;
      $slide->subtitle =$request->subtitle;
      $slide->link =$request->link;
      $slide->status =$request->status;


      $image= $request->file('image');
      $file_extension=$request->file('image')->extension();
      $file_name=Carbon::now()->timestamp.'.'. $file_extension;
      $this->GenerateSlideThumbailsImage($image, $file_name);
      $slide->image=$file_name;
      $slide->save();
      return redirect()->route('admin.slides')->with('status','Slide added successfully!');
     }
      public function GenerateSlideThumbailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/slides');

    // إنشاء الفولدر لو مش موجود
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
    }

    // حفظ الصورة مباشرة
    $image->move($destinationPath, $imageName);
   
}
public function slide_edit($id){
  $slide = Slide::find($id);
  return view('admin.slide-edit',compact('slide'));
}
public function slide_update(Request $request){
    $request->validate([
         'tagline'=> 'required',
         'title'=> 'required',
         'subtitle'=> 'required',
         'link'=> 'required',
         'image'=> 'mimes:png,jpg,jpeg,avif,webp|max:2048',
         'status'=> 'required',

      ]);
      $slide = Slide::find($request->id);
      $slide->tagline =$request->tagline;
      $slide->title =$request->title;
      $slide->subtitle =$request->subtitle;
      $slide->link =$request->link;
      $slide->status =$request->status;

      if($request->hasFile('image')){
       if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
         File::delete(public_path('uploads/slides').'/'.$slide->image);
       }
      $image= $request->file('image');
      $file_extension=$request->file('image')->extension();
      $file_name=Carbon::now()->timestamp.'.'. $file_extension;
      $this->GenerateSlideThumbailsImage($image, $file_name);
      $slide->image=$file_name;
      }
      $slide->save();
      return redirect()->route('admin.slides')->with('status','Slide updated successfully!');
}
public function slide_delete($id){
   $slide =Slide::find($id);
    if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
       File::delete(public_path('uploads/slides').'/'.$slide->image);
    }
    $slide->delete();
    return redirect()->route('admin.slides')->with('status','Slide deleted successfully!');
}
public function contacts(){
   $contacts =Contact::orderBy('created_at','DESC')->paginate(10);
   return view('admin.contacts',compact('contacts'));
}
public function contact_delete($id){
   $contact =Contact::find($id);
   $contact->delete();
   return redirect()->route('admin.contacts')->with('status','Contact deleted successfully');
}
public function users(){
   $users = User::orderBy('created_at','DESC')->paginate(10);
   return view('admin.users',compact('users'));

}
public function user_delete($id){
   $user =User::find($id);
   $user->delete();
   return redirect()->route('admin.users')->with('status','User deleted successfully');
   }
      public function search(Request $request){
        $query = $request->input('query');
        $results =product::where('name','LIKE',"%{$query}%")->take(8)->get();
        return response()->json($results);
        
    }

}
