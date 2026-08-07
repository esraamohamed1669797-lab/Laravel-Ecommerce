<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Contact;
use App\Models\product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   

   
    public function index(Request $request)
    { 
        $slides =Slide::limit(1)->get();
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $sproducts=product::whereNotNull('sale_price')->where('sale_price','<>','')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured',1)->inRandomOrder()->paginate(8, ['*'], 'featured_page');
        if ($request->ajax() && $request->has('featured_page')) {
        return view('featured-products', compact('fproducts'))->render();
                 }
        return view('index',compact('slides','categories','sproducts','fproducts'));
    }
    public function contact(){
        return view('contact');
    }
    public function contact_store(Request $request){
        $request->validate(
            [
                'name'=>'required|max:100',
                'email'=>'required|email',
                'phone'=>'required|numeric|digits:11',
                'comment'=>'required',
            ]);
            $contact =new Contact();
            $contact->name =$request->name;
            $contact->email =$request->email;
            $contact->phone =$request->phone;
            $contact->comment =$request->comment;
            $contact->save();
            return redirect()->back()->with('success','Your message has been sent successfully');
        
    }
    public function search(Request $request){
        $query = $request->input('query');
        $results =product::where('name','LIKE',"%{$query}%")->take(8)->get();
        return response()->json($results);
        
    }
}
