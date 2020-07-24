<?php
use Illuminate\Support\Facades\Route;

/****************** COMPONENT PAGES: DOCUMENT MANAGEMENT ******************/

// ######### ADMIN ONLY ##########//
Route::middleware('admin') -> group(function () {

    /* List of uploads */
    Route::get('/doc_management/create/upload/files', 'DocManagement\Create\UploadController@get_uploaded_files') -> name('create.upload.files');
    /* Add fields page */
    Route::get('/doc_management/create/add_fields/{file_id}', 'DocManagement\Fill\FieldsController@add_fields');
    /* Resources | Add/remove associations, tags, etc. */
    Route::get('/doc_management/resources/resources', 'DocManagement\Resources\ResourcesController@resources');
    // Admin resources
    Route::get('/admin/resources/resources_admin', 'Admin\Resources\ResourceItemsAdminController@resources_admin');
    /* Checklists  */
    Route::get('/doc_management/checklists/{checklist_id?}/{checklist_location_id?}/{checklist_type?}', 'DocManagement\Checklists\ChecklistsController@checklists');

/****************** END COMPONENT PAGES: DOCUMENT MANAGEMENT ******************/



//************************** COMPONENT DATA: DOCUMENT MANAGEMENT **************************//

/**********  DATA - ADD/EDIT/DELETE /**********/


    // Upload //
    Route::post('/doc_management/upload_file', 'DocManagement\Create\UploadController@upload_file') -> name('doc_management.upload_file');
    // Edit uploaded File
    Route::post('/doc_management/save_file_edit', 'DocManagement\Create\UploadController@save_file_edit');
    // Add non form checklist item
    Route::post('/doc_management/save_add_non_form', 'DocManagement\Create\UploadController@save_add_non_form');
    // Duplicate uploaded files
    Route::post('/doc_management/duplicate_upload', 'DocManagement\Create\UploadController@duplicate_upload');
    // Activate/Deactivate uploaded files
    Route::post('/doc_management/activate_upload', 'DocManagement\Create\UploadController@activate_upload');
    // Publish upload
    Route::post('/doc_management/publish_upload', 'DocManagement\Create\UploadController@publish_upload');
    // Delete uploaded files
    Route::post('/doc_management/delete_upload', 'DocManagement\Create\UploadController@delete_upload');
    // Manage uploaded files in checklists
    Route::post('/doc_management/manage_upload', 'DocManagement\Create\UploadController@manage_upload');
    // Replace uploaded files in checklists
    Route::post('/doc_management/replace_upload', 'DocManagement\Create\UploadController@replace_upload');
    // Remove uploaded files in checklists
    Route::post('/doc_management/remove_upload', 'DocManagement\Create\UploadController@remove_upload');

    /* Add Resource  */
    Route::post('/doc_management/resources/add', 'DocManagement\Resources\ResourcesController@resources_add');
    /* Save edit Resources | Add/remove associations, tags, etc. */
    Route::post('/doc_management/resources/edit', 'DocManagement\Resources\ResourcesController@resources_edit');
    /* Delete Resources  */
    Route::post('/doc_management/resources/delete_deactivate', 'DocManagement\Resources\ResourcesController@delete_deactivate');
    /* Reorder Resources */
    Route::post('/doc_management/resources/reorder', 'DocManagement\Resources\ResourcesController@resources_reorder');

    // ADMIN RESOURCES //
    /* Add Resource  */
    Route::post('/admin/resources/add', 'Admin\Resources\ResourceItemsAdminController@resources_add');
    /* Save edit Resources | Add/remove associations, tags, etc. */
    Route::post('/admin/resources/edit', 'Admin\Resources\ResourceItemsAdminController@resources_edit');
    /* Delete Resources  */
    Route::post('/admin/resources/delete_deactivate', 'Admin\Resources\ResourceItemsAdminController@delete_deactivate');
    /* Reorder Resources */
    Route::post('/admin/resources/reorder', 'Admin\Resources\ResourceItemsAdminController@resources_reorder');

    // Fields //
    Route::post('/doc_management/save_add_fields', 'DocManagement\Fill\FieldsController@save_add_fields');
    // delete page from upload
    Route::post('/doc_management/delete_page', 'DocManagement\Fill\FieldsController@delete_page');


    /* checklists */
    /* Add Checklists */
    Route::post('/doc_management/add_checklist', 'DocManagement\Checklists\ChecklistsController@add_checklist');
    /* Add Referral Checklists */
    Route::post('/doc_management/add_checklist_referral', 'DocManagement\Checklists\ChecklistsController@add_checklist_referral');
    /* Edit Checklists */
    Route::post('/doc_management/edit_checklist', 'DocManagement\Checklists\ChecklistsController@edit_checklist');
    /* Delete Checklists */
    Route::post('/doc_management/delete_checklist', 'DocManagement\Checklists\ChecklistsController@delete_checklist');
    /* Reorder Checklists */
    Route::post('/doc_management/reorder_checklists', 'DocManagement\Checklists\ChecklistsController@reorder_checklists');
    /* Add Checklist Items */
    Route::post('/doc_management/add_checklist_items', 'DocManagement\Checklists\ChecklistsController@add_checklist_items');
    /* Add Form to Checklists  */
    Route::post('/doc_management/save_add_to_checklists', 'DocManagement\Create\UploadController@save_add_to_checklists');
    /* Duplicate Checklist  */
    Route::post('/doc_management/duplicate_checklist', 'DocManagement\Checklists\ChecklistsController@duplicate_checklist');
    /* Save Copy Checklists  */
    Route::post('/doc_management/save_copy_checklists', 'DocManagement\Checklists\ChecklistsController@save_copy_checklists');


    /**********  DATA - GET /**********/


    // get updated list of form_group files after adding a new one
    Route::get('/doc_management/get_form_group_files', 'DocManagement\Create\UploadController@get_form_group_files') -> name('doc_management.get_form_group_files');
    // get upload details for edit
    Route::get('/doc_management/get_upload_details', 'DocManagement\Create\UploadController@get_upload_details');
    // get checklist after adding
    Route::get('/doc_management/get_checklists', 'DocManagement\Checklists\ChecklistsController@get_checklists');
    /* Copy Checklists */
    Route::get('/doc_management/get_copy_checklists', 'DocManagement\Checklists\ChecklistsController@get_copy_checklists');
    // get checklist items
    Route::get('/doc_management/get_checklist_items', 'DocManagement\Checklists\ChecklistsController@get_checklist_items');
    // get checklist item details
    Route::get('/doc_management/get_checklist_item_details', 'DocManagement\Checklists\ChecklistsController@get_checklist_item_details');
    // get details to manage form in checklist
    Route::get('/doc_management/get_manage_upload_details', 'DocManagement\Create\UploadController@get_manage_upload_details');
    // get checklist items for add form to checklists
    Route::get('/doc_management/add_form_get_checklist_items', 'DocManagement\Create\UploadController@add_form_get_checklist_items');
    // get details to add to checklists
    Route::get('/doc_management/get_add_to_checklists_details', 'DocManagement\Create\UploadController@get_add_to_checklists_details');


    Route::get('/doc_management/common_fields', 'DocManagement\Fill\FieldsController@get_common_fields');



    /********* Document Review ************/
    // doc review page
    Route::get('/doc_management/document_review', 'DocManagement\Review\DocumentReviewController@document_review');

});

//************************** END COMPONENT: DOCUMENT MANAGEMENT **************************//
