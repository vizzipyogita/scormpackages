<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\RatingsController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\StripePaymentController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\PlansController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// index
//Route::get('/', 'Home\HomeController@index');
//Route::get('/home', [HomeworkController::class, 'index'])->name('index');

//Route::get('/test','test\TestController@getTest');

//Login
Route::get('/', [LoginController::class, 'index'])->name('index');
Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('loginpost', [LoginController::class, 'loginpost'])->name('loginpost');
Route::get('forgotpassword', [LoginController::class, 'forgotpassword'])->name('forgotpassword');    
Route::post('forgotpasswordpost', [LoginController::class, 'forgotpasswordpost'])->name('forgotpasswordpost');    
Route::get('loginasorganization/{organizationId}', [LoginController::class, 'loginasorganization'])->name('loginasorganization');
Route::get('signup', [LoginController::class, 'signup'])->name('signup');
Route::post('signuppost', [LoginController::class, 'signuppost'])->name('signuppost');

//Middleware
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    //Organizations
    Route::get('organizations', [OrganizationController::class, 'organizations'])->name('organizations');
    Route::get('organizations/list', [OrganizationController::class, 'list'])->name('listOrganization');
    Route::get('organization/create/{id?}', [OrganizationController::class, 'create'])->name('createOrganization');
    Route::post('organization/save/{id?}', [OrganizationController::class, 'save'])->name('saveOrganization');
    
    //Campus
    Route::get('campus', [CampusController::class, 'campus'])->name('campus');
    Route::get('campus/list', [CampusController::class, 'list'])->name('listCampus');
    Route::get('campus/create/{id?}', [CampusController::class, 'create'])->name('createCampus');
    Route::post('campus/save/{id?}', [CampusController::class, 'save'])->name('saveCampus');
    Route::delete('campus/delete/{id}', [CampusController::class, 'delete'])->name('deleteCampus');

    //User
    Route::get('users', [UsersController::class, 'users'])->name('users');
    Route::get('users/list', [UsersController::class, 'list'])->name('listUsers');
    Route::get('users/create/{id?}', [UsersController::class, 'create'])->name('createUser');
    Route::post('users/save/{id?}', [UsersController::class, 'save'])->name('saveUser');
    Route::delete('users/delete/{id}', [UsersController::class, 'delete'])->name('deleteUser');
    Route::get('users/changepassword/{id?}', [UsersController::class, 'changePassword'])->name('changePassword');
    Route::post('users/changepassword/save/{id?}', [UsersController::class, 'savechangepassword'])->name('saveChangePassword');
    Route::post('users/sendlogindetailsemail/{id}', [UsersController::class, 'sendLoginDetailsEmail'])->name('sendLoginDetailsEmail');
    Route::post('users/bulksendlogindetails', [UsersController::class, 'bulkSendLoginDetails'])->name('bulkSendLoginDetails');

    Route::get('admin/profile/{user_type?}', [UsersController::class, 'userprofile'])->name('adminProfile');
    Route::post('admin/profile/update', [UsersController::class, 'userprofileupdate'])->name('adminProfileUpdate');

    //Import Users
    Route::get('users/import', [UsersController::class, 'import'])->name('importUser');
    Route::post('users/importpost', [UsersController::class, 'userImportPost'])->name('userImportPost');
    Route::get('users/import/{user_type}/list/{organization_id?}', [UsersController::class, 'importUserList'])->name('importUserList');
    Route::post('users/{user_type}/importlist/{organization_id?}', [UsersController::class, 'postImportUserList'])->name('postImportUserList');

    //Roles
    Route::get('roles', [RoleController::class, 'roles'])->name('roles');
    Route::get('role/list', [RoleController::class, 'list'])->name('listRoles');
    Route::get('role/create/{id?}', [RoleController::class, 'create'])->name('createRole');
    Route::post('role/save/{id?}', [RoleController::class, 'save'])->name('saveRole');
    Route::delete('role/delete/{id}', [RoleController::class, 'delete'])->name('deleteRole');

    //Courses
    Route::get('courses', [CoursesController::class, 'courses'])->name('courses');
    Route::get('courses/list', [CoursesController::class, 'list'])->name('listCourses');
    Route::get('courses/create/{id?}', [CoursesController::class, 'create'])->name('createCourse');
    Route::post('courses/save/{id?}', [CoursesController::class, 'save'])->name('saveCourse');

    //Course Category
    Route::get('coursecategory', [CourseCategoryController::class, 'coursecategory'])->name('coursecategory');
    Route::get('coursecategory/list', [CourseCategoryController::class, 'list'])->name('listCourseCategory');
    Route::get('coursecategory/create/{id?}', [CourseCategoryController::class, 'create'])->name('createCourseCategory');
    Route::post('coursecategory/save/{id?}', [CourseCategoryController::class, 'save'])->name('saveCourseCategory');

    //Ratings
    Route::get('ratings', [RatingsController::class, 'ratings'])->name('ratings');
    Route::get('ratings/list', [RatingsController::class, 'list'])->name('listRatings');
    Route::post('ratings/save/{id?}', [RatingsController::class, 'save'])->name('saveRatings');

    //License
    Route::get('license', [OrganizationController::class, 'license'])->name('license');
    Route::get('license/list', [OrganizationController::class, 'listLicense'])->name('listLicense');
    Route::post('license/save', [OrganizationController::class, 'saveLicense'])->name('saveLicense');

    //Coupon
    Route::get('coupons', [CouponsController::class, 'coupons'])->name('coupons');
    Route::get('coupons/list', [CouponsController::class, 'list'])->name('listCoupons');
    Route::get('coupons/create/{id?}', [CouponsController::class, 'create'])->name('createCoupons');
    Route::post('coupons/save/{id?}', [CouponsController::class, 'save'])->name('saveCoupons');
    Route::delete('coupons/delete/{id}', [CouponsController::class, 'delete'])->name('deleteCoupons');
    Route::get('coupons/details/{code?}', [CouponsController::class, 'couponDetails'])->name('couponDetails');

    //Payment
    Route::get('organization/upgrade', [StripePaymentController::class, 'upgrade'])->name('upgrade');
    Route::get('organization/payment/{plan_id}', [StripePaymentController::class, 'payment'])->name('payment');
    Route::post('organization/paymentpost', [StripePaymentController::class, 'paymentPost'])->name('paymentPost');
    Route::get('organization/cancelsubscription', [StripePaymentController::class, 'cancelSubscription'])->name('cancelSubscription');

    //Get States by country
    Route::get('masters/states/{countryId}', [MasterController::class, 'states'])->name('states');

    //Settings
    Route::get('plans', [PlansController::class, 'plans'])->name('plans');
    Route::get('plans/create/{id?}', [PlansController::class, 'create'])->name('createPlan');
    Route::post('plans/save/{id?}', [PlansController::class, 'save'])->name('savePlan');
    Route::get('plans/view/{country_id?}', [PlansController::class, 'view'])->name('viewPlan');

    //Logout
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');  
});

    //User Login
    Route::get('user/login', [UserLoginController::class, 'userlogin'])->name('userlogin');
    // Route::post('user/loginpost', [UserLoginController::class, 'userloginpost'])->name('userloginpost');
    Route::match(['get', 'post'],'user/loginpost', [UserLoginController::class, 'userloginpost'])->name('userloginpost');
    Route::get('user/forgotpassword', [UserLoginController::class, 'userforgotpassword'])->name('userforgotpassword');    
    Route::post('user/forgotpasswordpost', [UserLoginController::class, 'userforgotpasswordpost'])->name('userforgotpasswordpost');  
    Route::get('user/signup', [UserLoginController::class, 'userSignup'])->name('userSignup');
    Route::post('user/signuppost', [UserLoginController::class, 'userSignuppost'])->name('userSignuppost');
    
    Route::middleware(['auth:user'])->group(function () {
        Route::get('user/dashboard', [DashboardController::class, 'userdashboard'])->name('userdashboard');
        Route::get('user/course/{id}/play', [CoursesController::class, 'courseplay'])->name('courseplay');
        Route::get('user/profile/{user_type?}', [UsersController::class, 'userprofile'])->name('userprofile');
        Route::post('user/profile/update', [UsersController::class, 'userprofileupdate'])->name('userProfileUpdate');
        Route::get('course/{id}/ratings', [CoursesController::class, 'courseRatings'])->name('courseRatings');
        Route::post('course/{id}/postratings', [CoursesController::class, 'postRatings'])->name('postRatings');
        Route::get('user/category/{id}/viewall', [CoursesController::class, 'userAllCourses'])->name('userAllCourses');
        Route::get('user/course/{id}/ratings/viewall', [CoursesController::class, 'userAllCourseRatings'])->name('userAllCourseRatings');
        Route::post('user/course/{id}/favorite', [CoursesController::class, 'courseFavorite'])->name('courseFavorite');
        Route::post('user/course/{id}/finish', [CoursesController::class, 'finishCourse'])->name('finishCourse');
        Route::get('user/history', [CoursesController::class, 'userHistory'])->name('userHistory');
        Route::get('user/changepassword/{id?}', [UsersController::class, 'changePassword'])->name('userChangePassword');
        Route::post('user/changepassword/save/{id?}', [UsersController::class, 'savechangepassword'])->name('saveUserChangePassword');
        Route::get('user/checkout', [StripePaymentController::class, 'userCheckout'])->name('userCheckout');
        Route::get('user/payment', [StripePaymentController::class, 'userPayment'])->name('userPayment');
        Route::post('user/paymentpost', [StripePaymentController::class, 'userPaymentPost'])->name('userPaymentPost');
        Route::get('coupons/details/{code?}', [CouponsController::class, 'couponDetails'])->name('couponDetails');
    });
    //Logout
    Route::get('user/logout', [UserLoginController::class, 'userlogout'])->name('userlogout');  