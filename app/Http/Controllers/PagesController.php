<?php

namespace App\Http\Controllers;

use App\blog;
use App\Category;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function viewHome(Request $request){
        $data['description']='Emergency roadside assistance services available 24/7 and with immediate quotes. No hidden fees. Try Drive Roadside Assistance and learn why people choose us.';
        $data['ref'] = ($request->has('ref')) ? '?ref='.$request->get('ref') : '';
        return view('home')->with($data);

    }

    public function viewAbout(Request $request){
        $data['description']= 'Drive is an emergency roadside assistance company that has your best interest in mind. Our goal it to support you with our wide range of roadside assistance services anywhere and anytime.';
        $data['ref'] = ($request->has('ref')) ? '?ref='.$request->get('ref') : '';

        return view('about')->with($data);

    }

    public function viewMembership(Request $request){
        $data['description']='Find the emergency roadside assistance plan that best fits your needs starting at $9.99. Learn more about our all inclusive roadside assistance memberships and contact us if you have any questions.';
        $data['title'] = 'MEMBERSHIP | DRIVE | Roadside Assistance Plans';
        $data['ref'] = ($request->has('ref')) ? '?ref='.$request->get('ref') : '';
        return view('membership')->with($data);

    }

    public function viewPartnership(Request $request){
        $data['description']='';
        $data['ref'] = ($request->has('ref')) ? '?ref='.$request->get('ref') : '';
        return view('partnership')->with($data);

    }

    public function viewCareers(Request $request){
        $data['description']='';
        $data['ref'] = ($request->has('ref')) ? '?ref='.$request->get('ref') : '';
        return view('careers')->with($data);

    }

    public function viewPrivacy(Request $request){
        $data['description']='';
        $data['ref'] = ($request->has('ref')) ? '?ref='.$request->get('ref') : '';

        return view('privacy')->with($data);

    }
    public function viewblogSingle1(Request $request){
        $data['description']='blog';
        $data['title']='blog';
        return view('blogSingle1')->with($data);

    }
    public function news(Request $request,$id){
        $blogs = blog::all();
        $categories = Category::all();
        if ($id == 'all'){
            $data['blogs'] = $blogs;
        }else{
            $data['blogs'] = $blogs->where('category_id',$id);
        }

        $data['categories']=$categories;
        $data['description']='blog';
        $data['title']='blog';
        return view('news')->with($data);

    }
    public function viewblogSingle($id){
        $blog = blog::where('id', $id)->first();
        $data['blog'] = $blog;
        $data['description']='blog';
        $data['title']='blog';
        return view('blogSingle2')->with($data);

    }
    public function viewblogSingle3(Request $request){
        $data['description']='blog';
        $data['title']='blog';
        return view('blogSingle3')->with($data);

    }
    public function viewblogSingle4(Request $request){
        $data['description']='blog';
        $data['title']='blog';
        return view('blogSingle4')->with($data);

    }

    public function viewTerms(Request $request){
        $data['description']='';
        $data['ref'] = ($request->has('ref')) ? '?ref='.$request->get('ref') : '';
        return view('terms')->with($data);

    }

    public function viewblogSingle5()
    {
        $data['description']='blog';
        $data['title']='blog';
        return view('blogSingle5')->with($data);
    }


}
