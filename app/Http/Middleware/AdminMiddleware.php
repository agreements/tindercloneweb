<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;

class AdminMiddleware
{
   
    public function handle($request, Closure $next)
    {
        if(session()->has('role'))
        {  
            if(url('admin/login') === $request->url())
                return redirect('/admin/dashboard');  

            if(session('role') === 'admin')
                return $this->nocache($next($request));
                     
        }
        else
        {
            return redirect()->guest('admin/login');
        }
        
    }


    protected function nocache($response)
    {
        $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        $response->headers->set('Pragma','no-cache');

        return $response;
    }
}
