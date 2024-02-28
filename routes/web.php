<?php

use App\Mail\ReminderEmail;
use App\Models\Todo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;


Route::get('/', function () {
    return redirect()->route('filament.admin.home');
});

// FOR TESTING
// Route::get('/mail', function () {
//     $todos = Todo::where('due_date', '<', Carbon::now()->subMinute(1))->get();
//     foreach ($todos as $todo) {
//         Mail::to($todo->user->email)->send(new ReminderEmail($todo));
//     }
//     dd('done');

//     $todos = Todo::where('due_date', '<', Carbon::now()->subMinute(1))->get();
//     dd($todos[0]->user->email);
//     Mail::to('omar6647@gmail.com')->send(new ReminderEmail());
//     dd('hi');
// });
