<?php

    // ++++++ Doc Management ++++++ //

    // Global functions
    Route::get('/agents/doc_management/global_functions/get_location_details', 'Agents\DocManagement\Functions\GlobalFunctionsController@get_location_details');

    // Add new listing
    Route::get('/agents/doc_management/transactions/listings/listing_add', 'Agents\DocManagement\Transactions\Listings\ListingAddController@add_listing_page');
    // Add listing details if existing
    Route::get('/agents/doc_management/transactions/listings/listing_add_details_existing/{state?}/{tax_id?}/{bright_type?}/{bright_id?}', 'Agents\DocManagement\Transactions\Listings\ListingAddController@add_listing_details_existing');
    // Add listing details if new
    Route::get('/agents/doc_management/transactions/listings/listing_add_details_new/{street_number?}/{street_name?}/{city?}/{state?}/{zip?}/{county?}/{street_dir?}/{unit_number?}', 'Agents\DocManagement\Transactions\Listings\ListingAddController@add_listing_details_new');
    // Required Details page
    Route::get('/agents/doc_management/transactions/listings/listing_required_details/{id}', 'Agents\DocManagement\Transactions\Listings\ListingAddController@listing_required_details');



    // axios calls
    // save add listing
    Route::post('/agents/doc_management/transactions/save_add_listing', 'Agents\DocManagement\Transactions\Listings\ListingAddController@save_add_listing');
    // save required details
    Route::post('/agents/doc_management/transactions/save_listing_required_details', 'Agents\DocManagement\Transactions\Listings\ListingAddController@save_listing_required_details');

    // listing details page
    Route::get('/agents/doc_management/transactions/listings/listing_details/{Listing_ID}', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@listing_details');
    // get header for listing details page
    Route::get('/agents/doc_management/transactions/listings/listing_details_header', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@listing_details_header');
    // get details, members, checklist, etc for listing page
    Route::get('/agents/doc_management/transactions/listings/get_details', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@get_details');
    Route::get('/agents/doc_management/transactions/listings/get_members', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@get_members');
    Route::get('/agents/doc_management/transactions/listings/get_checklist', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@get_checklist');
    Route::get('/agents/doc_management/transactions/listings/get_documents', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@get_documents');
    Route::get('/agents/doc_management/transactions/listings/get_contracts', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@get_contracts');
    // get mls details
    Route::get('/agents/doc_management/transactions/listings/mls_search', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@mls_search');
    Route::get('/agents/doc_management/transactions/listings/save_mls_search', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@save_mls_search');
    // get add contact html
    Route::get('/agents/doc_management/transactions/listings/add_member_html', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@add_member_html');
    // save details
    Route::post('/agents/doc_management/transactions/listings/save_details', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@save_details');
    // save member
    Route::post('/agents/doc_management/transactions/listings/save_member', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@save_member');
    // delete member
    Route::post('/agents/doc_management/transactions/listings/delete_member', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@delete_member');
    // add documents folder
    Route::post('/agents/doc_management/transactions/listings/add_folder', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@add_folder');
    // delete documents folder
    Route::post('/agents/doc_management/transactions/listings/delete_folder', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@delete_folder');
    // upload documents
    Route::post('/agents/doc_management/transactions/listings/upload_documents', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@upload_documents');
    // save add template documents
    Route::post('/agents/doc_management/transactions/listings/save_add_template_documents', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@save_add_template_documents');
    // move documents to trash
    Route::post('/agents/doc_management/transactions/listings/move_documents_to_trash', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@move_documents_to_trash');
    // move documents to different folder
    Route::post('/agents/doc_management/transactions/listings/move_documents_to_folder', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@move_documents_to_folder');
    // reorder documents
    Route::post('/agents/doc_management/transactions/listings/reorder_documents', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@reorder_documents');
    // get add document to checklist html
    Route::get('/agents/doc_management/transactions/listings/add_document_to_checklist_item_html', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@add_document_to_checklist_item_html');

    // delete document from checklist item
    Route::post('/agents/doc_management/transactions/listings/remove_document_from_checklist_item', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@remove_document_from_checklist_item');
    // add notes checklist item
    Route::post('/agents/doc_management/transactions/listings/add_notes_to_checklist_item', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@add_notes_to_checklist_item');
    // mark note read
    Route::post('/agents/doc_management/transactions/listings/mark_note_read', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@mark_note_read');

    // add one document to checklist item from checklist
    Route::post('/agents/doc_management/transactions/listings/add_document_to_checklist_item', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@add_document_to_checklist_item');
    // save assign items to checklist from documents
    Route::post('/agents/doc_management/transactions/listings/save_assign_documents_to_checklist', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@save_assign_documents_to_checklist');
    // save rename document
    Route::post('/agents/doc_management/transactions/listings/save_rename_document', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@save_rename_document');
    // get split document html
    Route::get('/agents/doc_management/transactions/listings/get_split_document_html', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@get_split_document_html');
    // save add split document to documents
    Route::post('/agents/doc_management/transactions/listings/save_split_document', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@save_split_document');
    // duplicate document
    Route::post('/agents/doc_management/transactions/listings/duplicate_document', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@duplicate_document');
    // get email documents
    Route::post('/agents/doc_management/transactions/listings/email_get_documents', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@email_get_documents');
    // email documents
    Route::post('/agents/doc_management/transactions/listings/email_documents', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@email_documents');
    // merge documents
    Route::post('/agents/doc_management/transactions/listings/merge_documents', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@merge_documents');




    // Add new contract
    /* Route::get('/agents/doc_management/transactions/add_contract/add_contract', 'Agents\DocManagement\Transactions\ContractAddController@add_contract'); */

    // TODO this is shared by add listing and add contract
    // Add listing and contract
    Route::get('/agents/doc_management/transactions/get_property_info', 'Agents\DocManagement\Transactions\Listings\ListingAddController@get_property_info');
    Route::get('/agents/doc_management/transactions/update_county_select', 'Agents\DocManagement\Transactions\Listings\ListingAddController@update_county_select');


    // all listings page
    Route::get('/agents/doc_management/transactions/listings/listings_all', 'Agents\DocManagement\Transactions\Listings\ListingDetailsController@listings_all');






    // ** FILL FIELDS

    // fill fields
    Route::get('/agents/doc_management/transactions/edit_files/{document_id}/{saved?}', 'Agents\DocManagement\Transactions\EditFiles\EditFilesController@file_view');

    // rotate document
    Route::post('/agents/doc_management/transactions/edit_files/rotate_document', 'Agents\DocManagement\Transactions\EditFiles\EditFilesController@rotate_document');

    Route::post('/agents/doc_management/transactions/edit_files/save_field_input_values', 'Agents\DocManagement\Transactions\EditFiles\EditFilesController@save_field_input_values');
    // Export filled fields to pdf
    Route::post('/agents/doc_management/transactions/edit_files/convert_to_pdf', 'Agents\DocManagement\Transactions\EditFiles\EditFilesController@convert_to_pdf') -> name('convert_to_pdf');
    // save editing form - user added text, highlighting, etc
    Route::post('/agents/doc_management/transactions/edit_files/save_edit_options', 'Agents\DocManagement\Transactions\EditFiles\EditFilesController@save_edit_options');
    // get user fields
    Route::post('/agents/doc_management/transactions/edit_files/get_user_fields', 'Agents\DocManagement\Transactions\EditFiles\EditFilesController@get_user_fields');







