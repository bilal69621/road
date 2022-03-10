<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
class guestusercheck
{
  public function handle($request, Closure $next)
    {

      $user = Auth::user();
      if(isset($user))
      {
       if($user->name == "Guest User" && $user->user_type == "guest")
      {
         return redirect('/rescue');
      }else{

        return $next($request);
      }
      }else{
         return redirect('userlogin');
      }
    }
}

