<?php
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController\AdminController;
use App\Http\Controllers\Api\UserController\UserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\Api\AdminController\CategoryController;
use App\Http\Controllers\Api\AdminController\NewsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'loginUser'])->name('login.loginUser');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::middleware('CheckAdmin')->group(function (){
        Route::get('/getAllUser',[AdminController::class,'getAllUser']);
        Route::get('/getUserById/{id}',[AdminController::class,'getUserInfo']);
        Route::post('/createUser',[AdminController::class,'createUser']);
        Route::put('/updateUser/{id}',[AdminController::class,'updateUser']);
        Route::delete('/deleteUser/{id}',[AdminController::class,'deleteUser']);
        Route::post('/createCategory',[CategoryController::class,'createCategory']);
        Route::post('/categories/{parent_id?}',[CategoryController::class,'createCategoryparent']);
        Route::get('/getAllCate',[CategoryController::class,'getAllCate']);
        Route::get('/showSubcategories/{parent_id}',[CategoryController::class,'showSubcategories']);
        Route::get('/findBySlug/{slug}',[CategoryController::class,'findBySlug']);
        Route::put('updateCategory/{id}',[CategoryController::class,'updateCategory']);
        Route::delete('/deleteCategory/{id}',[CategoryController::class,'deleteCategory']);
        Route::post('createNews',[NewsController::class,'createNews']);
        Route::get('/getAllNews',[NewsController::class,'getAllNews']);
        Route::put('updateNews/{id}',[NewsController::class,'updateNews']);
        Route::delete('deleteNews/{id}',[NewsController::class,'deleteNews']);
        Route::get('findNewsId/{id}',[NewsController::class,'findById']);
    });
});




