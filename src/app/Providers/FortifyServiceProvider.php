<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Http\Requests\AdminLoginRequest as FortifyAdminLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Laravel\Fortify\Contracts\LogoutResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Laravel\Fortify\Contracts\LoginResponse;








class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
        public function toResponse($request)
        {
            $guard = session('fortify.guard', 'web');

            return redirect($guard === 'admin' ? '/admin/attendance/list' : '/attendance');
        }
    });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {



                if ($request->session()->get('admin_logout') || strpos($request->path(), 'admin') !== false) {
                    return redirect('/admin/login');
                }

                return redirect('login');
            }
        });
    }




    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function (Request $request) {
            if ($request->is('admin/login')) {
                session(['fortify.guard' => 'admin']);
                return view('auth.admin-login');
            }

            session(['fortify.guard' => 'web']);
            return view('auth.login');
        });


        Fortify::authenticateUsing(function (Request $request) {
        $guard = session('fortify.guard', 'web');

        $model = $guard === 'admin'
        ? \App\Models\Admin::class
        : \App\Models\User::class;

        $user = $model::where('email', $request->email)->first();


        if ($user && Hash::check($request->password, $user->password)) {

            
            Auth::guard($guard)->login($user);

            config(['auth.defaults.guard' => $guard]);


            return $user;
        }


        return null;
    });



        RateLimiter::for('login', function (Request $request) {
        $email = (string) $request->email;

        return Limit::perMinute(10)->by($email . $request->ip());
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

        $this->app->bind(FortifyAdminLoginRequest::class, AdminLoginRequest::class);



    }




}
