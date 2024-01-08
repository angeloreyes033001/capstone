<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\MinorController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearLevelController;
use App\Http\Controllers\OfficialTimeController;
use App\Http\Controllers\publicController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'departments'],function(){
     Route::post('create', [DepartmentController::class, 'create']);
    Route::get('read', [DepartmentController::class, 'read']);
    Route::get('delete/{department}', [DepartmentController::class, 'delete']);
});

Route::group(['prefix'=>'users'], function(){
    Route::post('login',  [UserController::class, 'login']);
    Route::get('session/{tokens}', [UserController::class , 'sessionLogin' ]);
    Route::get('read/{tokens}', [UserController::class , 'read' ]);
    Route::post('changeStatus', [UserController::class , 'changeStatus' ]);
    Route::post('create-accounts', [UserController::class, 'createAccounts']);
    Route::post('create-deans', [UserController::class, 'createAccountDean']);
    
    Route::post('update-email', [UserController::class,'updateEmail']);
    Route::post('update-password',[UserController::class, 'updatePassword']);
    
    Route::get('passwordGet',[UserController::class,'passwordGet']);
});

Route::group(['prefix'=>'yearlevels'], function(){
    Route::get('read/{tokens}', [YearLevelController::class, 'read']);
    Route::post('create', [YearLevelController::class, 'create']);
    Route::post('update', [YearLevelController::class, 'update']);
    Route::get('delete/{id}', [YearLevelController::class, 'delete']);
});

Route::group(['prefix'=>'ranks'],function(){
    Route::post('create', [RankController::class,'create'] );
    Route::get('read/{tokens}', [RankController::class,'read'] );
    Route::post('update', [RankController::class,'update'] );
    Route::get('delete/{id}', [RankController::class,'delete'] );
});

Route::group(['prefix'=>'designations'], function(){

    Route::post('create', [DesignationController::class , 'create']);
    Route::get('read', [DesignationController::class , 'read']);
    Route::post('update', [DesignationController::class , 'update'] );
    Route::get('delete/{position}', [DesignationController::class , 'delete']);
});

Route::group(['prefix'=>'specialization'], function(){

    Route::get('autocreatecommon/{tokens}', [ SpecializationController::class , 'AutoCreateCommon' ]);
    Route::post('create', [ SpecializationController::class , 'create' ]);
    Route::get('read/{tokens}',[SpecializationController::class , 'read' ]);
    Route::post('update',[SpecializationController::class,'update']);
    Route::post('delete', [ SpecializationController::class , 'delete' ]);
});

Route::group(["prefix"=>"sections"], function(){
    Route::post('read_slot',[SectionController::class , 'read_slot']);
    Route::post('update_slot',[SectionController::class,'update_slot']);
    Route::get('auto_generated_slot/{tokens}', [SectionController::class , 'auto_generated_slot']);
    Route::post('create_section', [SectionController::class,'create_section']);
    Route::post('read_section', [SectionController::class,'read_section']);
    Route::post('update_section', [SectionController::class,'update_section']);
    Route::get('delete_section/{section}',[SectionController::class, 'delete_section']);
});

Route::group(["prefix"=>'classrooms'], function(){
    Route::post('create',[ClassroomController::class, 'create']);
    Route::get('read/{tokens}',[ClassroomController::class, 'read']);
    Route::post('update',[ClassroomController::class, 'update']);
    Route::get('delete/{id}',[ClassroomController::class, 'delete']);
});

Route::group(['prefix'=>'subjects'],function(){
    Route::post('read_slot', [SubjectController::class,'read_slot']);
    Route::post('create',[SubjectController::class, 'create']);
    Route::get('read/{tokens}', [SubjectController::class , 'read'] );
    Route::post('update',[SubjectController::class, 'update']);
    Route::get('delete/{code}', [SubjectController::class, 'delete']);
});

Route::group(['prefix'=>'professors'], function(){
    Route::post('create',[ProfessorController::class , 'create']);
    Route::get('read/{tokens}',[ProfessorController::class , 'read']);
    Route::post('update',[ProfessorController::class , 'update']);
    Route::get('delete/{id}',[ProfessorController::class , 'delete']);
    Route::get('count/{tokens}',[ProfessorController::class , 'countProfessor']);
});

Route::group(['prefix'=>"loads"], function(){
    // Route::post('read_load',[LoadController::class, 'read_load']);
    Route::get('read_professor_details/{professor}',[LoadController::class,'read_professor_details']);
    Route::post('read_all_load',[LoadController::class,'read_all_load']);
    Route::get('delete_load/{id}',[LoadController::class, 'delete_load']);
    Route::post('create_load',[LoadController::class,'create_load']);
    Route::post('summary',[LoadController::class,'summary']);
    Route::post('professor_summary',[LoadController::class,'professor_summary']);
});

Route::group(['prefix'=>'officialtime'],function(){
    Route::post('create',[OfficialTimeController::class, 'create']);
    Route::post('read',[OfficialTimeController::class, 'read']);
    Route::post('update',[OfficialTimeController::class, 'update']);
    Route::get('delete/{id}',[OfficialTimeController::class, 'delete']);
    Route::post('showDelete',[OfficialTimeController::class, 'showDelete']);
});

Route::group(['prefix'=>'schedules'],function(){
    Route::get('loads/{professor}',[ScheduleController::class,'showLoad']);
    Route::post('showClassroom',[ScheduleController::class,'showClassroom']);
    Route::post('showSection',[ScheduleController::class,'showSection']);
    Route::post('create_schedule',[ScheduleController::class,'create_schedule']);
    Route::get('showAvailableDelete/{professor}',[ScheduleController::class,'showAvailableDelete']);

    Route::get('professorSchedule/{professor}',[ScheduleController::class,'professorSchedule']);
    Route::get('sectionSchedule/{section}',[ScheduleController::class,'sectionSchedule']);
    Route::get('classroomSchedule/{classroom}',[ScheduleController::class,'classroomSchedule']);
    Route::get('delete_schedule/{id}',[ScheduleController::class,'delete_schedule']);
});

Route::group(['prefix'=>"public"],function(){
    Route::get('read_professor/{department}', [publicController::class, 'read_professor']);
    Route::post('read_professor_schedule', [publicController::class, 'read_professor_schedule']);

    Route::get('read_classroom/{department}',[publicController::class,'read_classroom']);
    Route::post('read_classroom_schedule',[publicController::class,'read_classroom_schedule']);

    Route::get('read_section/{department}',[publicController::class, 'read_section']);
    Route::post('read_section_schedule',[publicController::class,'read_section_schedule']);

    Route::post('read_professor_schedule_dean',[publicController::class,'read_professor_schedule_dean']);

    Route::post('read_print',[publicController::class,'read_print']);

});

Route::group(['prefix'=>"minors"],function(){
    Route::get('read_professor/{tokens}',[ MinorController::class,'read_professor' ]);
    Route::post('read_classroom',[MinorController::class,'read_classroom']);
    Route::post('read_section',[MinorController::class,'read_section']);
});

Route::group(['prefix'=>"generals"],function(){
    Route::get('total_subject/{tokens}', [ GeneralController::class, 'total_subjects' ]);
    Route::get('total_classroom/{tokens}', [ GeneralController::class, 'total_classrooms' ]);
    Route::get('total_section/{tokens}', [ GeneralController::class, 'total_sections' ]);

    Route::get('total_admin/{tokens}', [ GeneralController::class, 'total_admins' ]);

    Route::get('show_public_schedule/{tokens}',[GeneralController::class,'show_schedule_public']);
    Route::get('hidden_public_schedule/{tokens}',[GeneralController::class,'hidden_public_schedule']);
    Route::post('dean_to_otherDepartment',[GeneralController::class,'dean_to_otherDepartment']);
    Route::post('minor_to_otherDepartment',[GeneralController::class,'minor_to_otherDepartment']);
});