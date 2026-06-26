<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\AdminController; 


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin.index');

    Route::resource('transactions', TransactionController::class);
    Route::resource('budgets', BudgetController::class);
    Route::resource('saving-goals', SavingGoalController::class);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index'); 
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); 
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/family', [FamilyMemberController::class, 'index'])->name('family.index');
    Route::get('/family/create', [FamilyMemberController::class, 'create'])->name('family.create');
    Route::post('/family', [FamilyMemberController::class, 'store'])->name('family.store');
    Route::delete('/family/{user}', [FamilyMemberController::class, 'destroy'])->name('family.destroy');
    
    Route::post('/saving-goals/{id}/add-funds', [SavingGoalController::class, 'addFunds'])->name('saving-goals.add-funds');
    Route::post('/saving-goals/{id}/withdraw-funds', [SavingGoalController::class, 'withdrawFunds'])->name('saving-goals.withdraw-funds');
    Route::post('/saving-goals/withdraw-global', [SavingGoalController::class, 'withdrawGlobal'])->name('saving-goals.withdraw-global');
});