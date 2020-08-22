<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});


// USERS

Route::name('user.index')->get('/users', 'UserController@index');
Route::name('user.show')->get('/user/{id}', 'UserController@show');
Route::name('user.store')->post('/user','UserController@store');
Route::name('user.update')->put('/user/{id}','UserController@update');
Route::name('user.send_verification')->post('/user/verify/{id}','UserController@verifyPhone');
Route::name('user.refresh_api_token')->put('/refresh_token/{id}','UserController@update');
Route::name('user.login')->post('/login','UserController@login');

Route::name('permission.index')->get('/permissions', 'PermissionsController@index');
Route::name('permission.show')->get('/permission/{id}', 'PermissionsController@show');
Route::name('permission.store')->post('/permission','PermissionsController@store');
Route::name('permission.update')->put('/permission/{id}','PermissionsController@update');

Route::name('role.index')->get('/roles', 'RolesController@index');
Route::name('role.show')->get('/role/{id}', 'RolesController@show');
Route::name('role.showrolepermissions')->get('/rolepermissions/{id}', 'RolesController@showRolePermissions');
Route::name('role.store')->post('/role','RolesController@store');
Route::name('role.update')->put('/role/{id}','RolesController@update');

Route::name('security_question.index')->get('/security_questions', 'SecurityQuestionsController@index');
Route::name('security_question.show')->get('/security_question/{id}', 'SecurityQuestionsController@show');
Route::name('security_question.store')->post('/security_question','SecurityQuestionsController@store');
Route::name('security_question.update')->put('/security_question/{id}','SecurityQuestionsController@update');
Route::name('security_question.destroy')->delete('/security_question/{id}','SecurityQuestionsController@destroy');

Route::name('security_question_user.index')->get('/security_question_users', 'SecurityQuestionUsersController@index');
Route::name('security_question_user.showQuestionAnswerUsers')->get('/users/{id}/security_questions', 'SecurityQuestionUsersController@showQuestionAnswerUsers');
Route::name('security_question_user.store')->post('/users/{id}/security_questions','SecurityQuestionUsersController@store');
Route::name('security_question_user.update')->put('/users/{id}/security_questions','SecurityQuestionUsersController@update');
Route::name('security_question_user.destroy')->delete('/security_question_user/{id}','SecurityQuestionUsersController@destroy');

Route::name('method_of_contract.index')->get('/method_of_contracts', 'MethodOfContractsController@index');
Route::name('method_of_contract.show')->get('/method_of_contract/{id}', 'MethodOfContractsController@show');
Route::name('method_of_contract.store')->post('/method_of_contract','MethodOfContractsController@store');
Route::name('method_of_contract.update')->put('/method_of_contract/{id}','MethodOfContractsController@update');
Route::name('method_of_contract.destroy')->delete('/method_of_contract/{id}','MethodOfContractsController@destroy');

Route::name('help_category.index')->get('/help_categories', 'HelpCategoriesController@index');
Route::name('help_category.show')->get('/help_category/{id}', 'HelpCategoriesController@show');
Route::name('help_category.store')->post('/help_category','HelpCategoriesController@store');
Route::name('help_category.update')->put('/help_category/{id}','HelpCategoriesController@update');
Route::name('help_category.destroy')->delete('/help_category/{id}','HelpCategoriesController@destroy');

Route::name('policy.index')->get('/policies', 'PoliciesController@index');
Route::name('policy.show')->get('/policy/{id}', 'PoliciesController@show');
Route::name('policy.store')->post('/policy','PoliciesController@store');
Route::name('policy.update')->put('/policy/{id}','PoliciesController@update');
Route::name('policy.destroy')->delete('/policy/{id}','PoliciesController@destroy');

Route::name('connection.index')->get('/connections', 'ConnectionsController@index');
Route::name('connection.show')->get('/connection/{id}', 'ConnectionsController@show');
Route::name('connection.store')->post('/connection','ConnectionsController@store');
Route::name('connection.update')->put('/connection/{id}','ConnectionsController@update');
Route::name('connection.destroy')->delete('/connection/{id}','ConnectionsController@destroy');

Route::name('ad_setting.index')->get('/ad_settings', 'AdSettingsController@index');
Route::name('ad_setting.show')->get('/ad_setting/{id}', 'AdSettingsController@show');
Route::name('ad_setting.store')->post('/ad_setting','AdSettingsController@store');
Route::name('ad_setting.update')->put('/ad_setting/{id}','AdSettingsController@update');
Route::name('ad_setting.destroy')->delete('/ad_setting/{id}','AdSettingsController@destroy');

Route::name('article_type.index')->get('/article_types', 'ArticleTypesController@index');
Route::name('article_type.show')->get('/article_type/{id}', 'ArticleTypesController@show');
Route::name('article_type.store')->post('/article_type','ArticleTypesController@store');
Route::name('article_type.update')->put('/article_type/{id}','ArticleTypesController@update');
Route::name('article_type.destroy')->delete('/article_type/{id}','ArticleTypesController@destroy');

Route::name('requirement.index')->get('/requirements', 'RequirementsController@index');
Route::name('requirement.show')->get('/requirement/{id}', 'RequirementsController@show');
Route::name('requirement.store')->post('/requirement','RequirementsController@store');
Route::name('requirement.update')->put('/requirement/{id}','RequirementsController@update');
Route::name('requirement.destroy')->delete('/requirement/{id}','RequirementsController@destroy');

Route::name('user_addresses.index')->get('/user_addresses', 'UserAddressesController@index');
Route::name('user_address.show')->get('/user_address/{id}', 'UserAddressesController@show');
Route::name('user_address.showUserAddresses')->get('/users/{id}/user_addresses', 'UserAddressesController@showUserAddresses');
Route::name('user_address.store')->post('/user_address','UserAddressesController@store');
Route::name('user_address.update')->put('/user_address/{id}','UserAddressesController@update');
Route::name('user_address.destroy')->delete('/user_address/{id}','UserAddressesController@destroy');

Route::name('size_category.index')->get('/size_categories', 'SizeCategoriesController@index');
Route::name('size_category.show')->get('/size_category/{id}', 'SizeCategoriesController@show');
Route::name('size_category.showCategorySizeTypes')->get('/size_categories/{id}/size_types', 'SizeCategoriesController@showCategorySizeTypes');
Route::name('size_category.store')->post('/size_category','SizeCategoriesController@store');
Route::name('size_category.update')->put('/size_category/{id}','SizeCategoriesController@update');
Route::name('size_category.destroy')->delete('/size_category/{id}','SizeCategoriesController@destroy');

Route::name('size_type_category.index')->get('/size_type_categories', 'SizeTypeCategoriesController@index');
Route::name('size_type_category.show')->get('/size_type_category/{id}', 'SizeTypeCategoriesController@show');
Route::name('size_type_category.store')->post('/size_type_category','SizeTypeCategoriesController@store');
Route::name('size_type_category.update')->put('/size_type_category/{id}','SizeTypeCategoriesController@update');
Route::name('size_type_category.destroy')->delete('/size_type_category/{id}','SizeTypeCategoriesController@destroy');

Route::name('size_type_category_user.index')->get('/size_type_category_user', 'SizeTypeCategoryUsersController@index');
Route::name('size_type_category_user.show')->get('/size_type_category_user/{id}', 'SizeTypeCategoryUsersController@show');
Route::name('size_type_category_user.showUserSizes')->get('/users/{id}/sizes','SizeTypeCategoryUsersController@showUserSizes');
Route::name('size_type_category_user.store')->post('/users/{id}/sizes','SizeTypeCategoryUsersController@store');
Route::name('size_type_category_user.update')->put('/users/{id}/sizes','SizeTypeCategoryUsersController@update');
Route::name('size_type_category_user.destroy')->delete('/size_type_category_user/{id}','SizeTypeCategoryUsersController@destroy');

Route::name('preference_category.index')->get('/preference_categories', 'PreferenceCategoriesController@index');
Route::name('preference_category.show')->get('/preference_category/{id}', 'PreferenceCategoriesController@show');
Route::name('preference_category.showCategorySizeTypes')->get('/preference_categories/{id}/communication_preferences', 'PreferenceCategoriesController@showCommucationPreferences');
Route::name('preference_category.store')->post('/preference_category','PreferenceCategoriesController@store');
Route::name('preference_category.update')->put('/preference_category/{id}','PreferenceCategoriesController@update');
Route::name('preference_category.destroy')->delete('/preference_category/{id}','PreferenceCategoriesController@destroy');

Route::name('communication_preference.index')->get('/communication_preferences', 'CommunicationPreferencesController@index');
Route::name('communication_preference.show')->get('/communication_preference/{id}', 'CommunicationPreferencesController@show');
Route::name('communication_preference.store')->post('/communication_preference','CommunicationPreferencesController@store');
Route::name('communication_preference.update')->put('/communication_preference/{id}','CommunicationPreferencesController@update');
Route::name('communication_preference.destroy')->delete('/communication_preference/{id}','CommunicationPreferencesController@destroy');


