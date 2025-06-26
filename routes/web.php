<?php

use App\Http\Controllers\AsteroidController;
use App\Http\Controllers\FireballController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\LLMController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SentryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */


// HomePage
Route::get('/', function () {
    return view('homepage');
});

// Informazioni di Progetto
Route::get('/info', function () {
    return view ('infoProject.info');
}) -> name('info');

// Tool Generali
Route::get('/generalTools', function () {
    return view ('tools/generalTools/generalTools');
}) -> name('generalTools');



Route::middleware('auth')->group(function () {
    // Gestione Profilo
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updatePassword'])->name('password.update');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');


    // Gestione Preferiti
    Route::post('/add-to-favorites', [UserController::class, 'addToFavorites'])->name('favorites.add');
    Route::post('/remove-from-favorites', [UserController::class, 'removeFromFavorites'])->name('favorites.remove');
    Route::get('/user/favorites', [UserController::class, 'listAllFavorites'])->name('user.favorites');

    // Notifiche
    Route::get('/check-favorite-asteroids', [NotificationController::class, 'checkFavoriteAsteroids'])->name('check.favorite.asteroids');
    Route::get('/notification/read/{id}/{asteroid_id}', [NotificationController::class, 'markAsRead'])->name('notification.read');

    // Analisi Predittiva
    // Route::get('/predictive-analysis', [SentryController::class, 'getPredictiveAnalysesView'])->name('predictiveAnalysis');
    // Route::post('/predictive-analysis', [SentryController::class, 'searchByDes'])->name('predictiveAnalysis.search');
    // Route::post('/asteroid/predictive-analysis/analyze', [SentryController::class, 'runPrediction']) ->name('predictiveAnalysis.analyze');


    // Confronto Asteroidi
    Route::get('/compare-asteroids', [AsteroidController::class, 'getCompareAsteroidsView'])->name('compareAsteroids');
    Route::post('/compare-asteroids', [AsteroidController::class, 'searchByID'])->name('compareAsteroids.search');
    Route::get('/compare-asteroids/results', function () {
        return view ('tools.asteroidComparison.results');
    })->name('compareAsteroids.results');


    // Learn
    Route::get('/CreateLearn', function () {
        return view ('tools.learn.CreateLearn');
    })->name('CreateLearn');

    Route::get('/learnList', function () {
        return view ('tools.learn.LearnList');
    })->name('LearnList');

    Route::post('/learn/generate', [LLMController::class, 'generateLearnContent'])->name('learn.generate');
    Route::get('/learn/{user}/{structure}/quiz/{content}', [LearnController::class, 'showQuiz'])->name('learn.quiz');
    Route::get('/learn/{user}/{structure}/{version?}', [LearnController::class, 'showLearnContent'])->name('learn.show');
    Route::delete('/learn/{structure}/version/{version}', [LearnController::class, 'destroy'])->name('learn.destroy');

    Route::post('/learn/feedback', [LearnController::class, 'storeFeedback'])->name('learn.feedback.store');

});


// Asteroidi
Route::get('/', [AsteroidController::class, 'index'])->name('homepage');
Route::get('/search', [AsteroidController::class, 'getSearchView'])->name('searchAsteroid');
Route::post('/search', [AsteroidController::class, 'searchAsteroid'])->name('asteroid.search');
Route::get('/search/results',[AsteroidController::class, 'searchAsteroid'])->name('searchResults');
Route::get('/asteroid/{id}', [AsteroidController::class, 'searchByID'])->name('asteroid.show');

// Close Approaches
Route::get('/CloseApproaches', [AsteroidController::class, 'getCloseApproachesView'])->name('searchACloseApproaches');
Route::post('/CloseApproaches', [AsteroidController::class, 'searchByDate'])->name('closeApproaches.search');
Route::get('/CloseApproaches/results', [AsteroidController::class, 'searchByDate'])->name('searchByDateResults');

// Fireballs
Route::get('/FireballSearch', [FireballController::class, 'searchFireballElements'])->name('searchFireball');
Route::post('/FireballSearch', [FireballController::class, 'searchFireballElements'])->name('fireball.search');

// Sentry
Route::get('/SentrySearch', [SentryController::class, 'searchSentryElements'])->name('searchSentry');
Route::post('/SentrySearch', [SentryController::class, 'searchSentryElements'])->name('sentry.search');
Route::get('/sentry/{des}', [SentryController::class, 'searchByDes'])->name('sentry.show');

// Chatbot
Route::post('/gemini/chat', [LLMController::class, 'chat'])->name('gemini.chat');
Route::get('/test-gemini-connection', [LLMController::class, 'testConnection']);
Route::post('/chat/reset', [LLMController::class, 'resetChat'])->name('chat.reset');


// Teoria
Route::prefix('theory')->name('theory.')->group(function () {
    Route::view('/glossario', 'theory.glossario')->name('glossario');
    Route::view('/teoria/overview', 'theory.teoria.overview')->name('teoria.overview');
    Route::view('/teoria/sizeEstimator', 'theory.teoria.SizeEstimator')->name('teoria.sizeEstimator');
    Route::view('/news', 'theory.news')->name('news');
});


require __DIR__ . '/auth.php';
