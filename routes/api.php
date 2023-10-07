<?php
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController\AdminController;
use App\Http\Controllers\Api\UserController\UserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\Api\AdminController\CategoryController;
use App\Http\Controllers\Api\AdminController\NewsController;
use App\Http\Controllers\Api\ReaderController\ReaderController;
use App\Http\Controllers\Api\CommentsController\CommentsController;
use App\Http\Controllers\Api\ReaderController\ReaderAccountController;
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
Route::post('/createReader',[ReaderController::class,'createReader']);

Route::put('/updateReader/{id}',[ReaderController::class,'updateReader']);
Route::post('/loginReader',[ReaderAccountController::class,'login']);
Route::middleware('can.read.comments')->group(function () {
        Route::post('/createCmt',[CommentsController::class,'createCmt']);
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
        Route::get('/getAll',[AdminController::class,'getAll']);
        Route::get('searchUser',[AdminController::class,'searchUser']);


        Route::delete('/deleteUser/{id}',[AdminController::class,'deleteUser']);
        Route::post('/createCategory',[CategoryController::class,'createCategory']);
        Route::get('/getAllCate',[CategoryController::class,'getAllCate']);
        Route::get('/showSubcategories/{parent_id}',[CategoryController::class,'showSubcategories']);
        Route::get('/findBySlug/{slug}',[CategoryController::class,'findBySlug']);
        Route::put('updateCategory/{id}',[CategoryController::class,'updateCategory']);
        Route::delete('/deleteCategory/{id}',[CategoryController::class,'deleteCategory']);
        Route::get('/searchCate',[CategoryController::class,'searchCate']);

        Route::post('createNews',[NewsController::class,'createNews']);
        Route::get('/getAllNews',[NewsController::class,'getAllNews']);
        Route::put('updateNews/{id}',[NewsController::class,'updateNews']);
        Route::put('/updateSlugNews/{id}',[NewsController::class,'updateSlugNews']);
        Route::delete('deleteNews/{id}',[NewsController::class,'deleteNews']);
        Route::get('findNewsId/{id}',[NewsController::class,'findById']);
        Route::post('/addChildCategory/{parent_id}/children',[CategoryController::class,'addChildCategory']);
        Route::get('showCategories/{id}',[CategoryController::class,'showCategory']);
//        Route::get('showChildren/{id}/children',[CategoryController::class,'showChildren']);
        Route::get('/getAllReader',[ReaderController::class,'getAllReader']);
        Route::delete('/deleteReader/{id}',[ReaderController::class,'deleteReader']);
        Route::put('/updateReaderByAdmin/{id}',[ReaderController::class,'updateReaderByAdmin']);
        Route::get('/getReaderInfo/{id}',[ReaderController::class,'getReaderInfo']);
        Route::get('getAllCmt',[CommentsController::class,'getAllCmt']);
    });
});




