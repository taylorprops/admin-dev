{{-- checklist and doc review - shared --}}
<div class="modal fade draggable" id="docs_complete_modal" tabindex="-1" role="dialog" aria-labelledby="docs_complete_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header draggable-handle">
                <h4 class="modal-title" id="docs_complete_modal_title">All Documents Submitted</h4>
                <a href="javascript: void(0)" class="text-danger font-13" data-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times mt-2 fa-lg"></i>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="docs-complete-div"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-around">
                <a href="javascript: void(0);" class="btn btn-lg btn-primary email-agent-docs-complete" data-dismiss="modal"><i class="fad fa-envelope mr-2"></i> Notify Agent</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade draggable modal-shared email-modal" id="email_agent_modal" tabindex="-1" role="dialog" aria-labelledby="email_agent_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header draggable-handle">
                <h4 class="modal-title" id="email_agent_modal_title">Email Agent</h4>
                <a href="javascript: void(0)" class="text-danger font-13" data-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times mt-2 fa-lg"></i>
                </a>
            </div>
            <div class="modal-body">
                <form id="email_agent_form">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <div class="h-100 d-flex justify-content-start justify-content-sm-end align-items-center">
                                        <div class="email-heading text-gray">From:</div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-10 pl-sm-0">
                                    <input type="text" class="custom-form-element form-input" id="email_agent_from" value="{{ \Auth::user() -> name.' - '.$agent_details -> company }} <{{ \Auth::user() -> email }}>">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-sm-2">
                                    <div class="h-100 d-flex justify-content-start justify-content-sm-end align-items-center">
                                        <div class="email-heading text-gray">To:</div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-10 pl-sm-0">
                                    <input type="text" class="custom-form-element form-input" id="email_agent_to" value="{{ $agent_details -> first_name.' '.$agent_details -> last_name }} <{{ $agent_details -> email }}>">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-sm-2">
                                    <div class="h-100 d-flex justify-content-start justify-content-sm-end align-items-center">
                                        <div class="email-heading text-gray">CC:</div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-10 pl-sm-0">
                                    <input type="text" class="custom-form-element form-input" id="email_agent_cc">
                                </div>
                                <div class="col-12 col-sm-10 ml-sm-auto p-sm-0 small">
                                    Separate multiple addresses with "," or ";"
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-2">
                        <div class="col-12 col-sm-2">
                            <div class="h-100 d-flex justify-content-start justify-content-sm-end align-items-center">
                                <div class="email-heading text-gray">Subject:</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-10 pl-sm-0">
                            <input type="text" class="custom-form-element form-input" id="email_agent_subject" value="{{ $property -> FullStreetAddress }} {{ $property -> City }}, {{ $property -> StateOrProvince }} {{ $property -> PostalCode }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12 col-sm-2">
                            <div class="h-100 d-flex justify-content-start justify-content-sm-end align-items-top">
                                <div class="email-heading text-gray">Message:</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-10 pl-sm-0">
                            <div id="email_agent_message" class="text-editor font-9">
                                Hello {{ $agent_details -> first_name }},
                                <br>{!! auth() -> user() -> signature !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <div class="text-gray w-100 text-center mt-2 checklist-include hidden">A list of the checklist items and their status will be included in this email</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div id="email_agent_checklist_details"></div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer d-flex justify-content-around">
                <a class="btn btn-danger" data-dismiss="modal"><i class="fal fa-times mr-2"></i> Cancel</a>
                <a class="btn btn-primary" id="send_email_agent_button"><i class="fad fa-share mr-2"></i> Send Message</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade draggable modal-shared" id="confirm_remove_checklist_item_modal" tabindex="-1" role="dialog" aria-labelledby="remove_checklist_item_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header draggable-handle">
                <h4 class="modal-title" id="remove_checklist_item_title">Remove Checklist Item</h4>
                <a href="javascript: void(0)" class="text-danger font-13" data-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times mt-2 fa-lg"></i>
                </a>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="mr-3 text-danger"><i class="fad fa-exclamation-circle fa-2x"></i></div>
                                <div>
                                    Are you sure you want to remove this checklist item? All notes and assigned documents will also be removed.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-around">
                <a class="btn btn-danger" data-dismiss="modal"><i class="fal fa-times mr-2"></i> Cancel</a>
                <a class="btn btn-primary modal-confirm-button" id="confirm_remove_checklist_item_button"><i class="fal fa-check mr-2"></i> Confirm</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade draggable modal-shared" id="add_checklist_item_modal" tabindex="-1" role="dialog" aria-labelledby="add_checklist_item_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header draggable-handle">
                <h4 class="modal-title" id="add_checklist_item_modal_title">Add Checklist Item</h4>
                <a href="javascript: void(0)" class="text-danger font-13" data-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times mt-2 fa-lg"></i>
                </a>
            </div>
            <div class="modal-body">
                <form id="add_checklist_item_form">

                    <div class="row mt-3">

                        <div class="col-12 col-md-6">

                            <div class="h5 text-orange">Create Checklist Item</div>
                            <input type="text" class="custom-form-element form-input" id="add_checklist_item_name" data-label="Enter Item Name">

                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-12">

                            <div class="h5 text-orange mb-3">Or Select Standard Form</div>

                            <div class="card shadow-none">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="custom-form-element form-input form-search" data-label="Search">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <select class="custom-form-element form-select form-select-no-cancel form-select-no-search select-form-group mt-3" data-label="Select Form Group">
                                                <option value="all" selected>All</option>
                                                @foreach($form_groups as $form_group)
                                                <option value="{{ $form_group -> resource_id }}" {{-- @if($loop -> first) selected @endif --}}>{{ $form_group -> resource_state }} @if($form_group -> resource_state != $form_group -> resource_name) | {{ $form_group -> resource_name }} @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-groups-container mt-3">

                                                @foreach($form_groups as $form_group)

                                                    <ul class="list-group form-group-div mb-3" data-form-group-id="{{ $form_group -> resource_id }}">
                                                        <li class="list-group-header text-orange">
                                                            {{ $form_group -> resource_state }}
                                                            @if($form_group -> resource_state != $form_group -> resource_name) | {{ $form_group -> resource_name }} @endif
                                                        </li>

                                                        @php
                                                        $forms = $files -> formGroupFiles($form_group -> resource_id, null, null, '');
                                                        $forms = $forms['forms_available'];
                                                        @endphp

                                                        @foreach($forms as $form)

                                                            <li class="list-group-item list-group-item-action form-name" data-form-id="{{ $form -> file_id }}" data-form-name="{{ $form -> file_name_display }}" data-text="{{ $form -> file_name_display }}">
                                                                <div class="d-flex justify-content-between">

                                                                    <div title="{{ $form -> file_name_display }}">
                                                                        <a href="{{ $form -> file_location }}" class="btn btn-sm btn-primary mr-2 form-link" target="_blank">View</a>
                                                                        <a href="javascript: void(0)" class="btn btn-sm btn-primary mr-2">Select</a>
                                                                        <span class="d-none checked-div mr-3"><i class="fal fa-check-circle text-success"></i></span>
                                                                        <span class="text-primary form-name-display">{{ $form -> file_name_display }}</span>
                                                                    </div>
                                                                    {{-- <div>
                                                                        @php $categories = explode(',', $form -> form_categories); @endphp
                                                                        @foreach($categories as $category)
                                                                            <span class="badge badge-pill text-white ml-1 form-pill" style="background-color: {{ $resource_items -> GetCategoryColor($category) }}">{{ $resource_items -> getResourceName($category) }}</span>
                                                                        @endforeach
                                                                    </div> --}}
                                                                </div>
                                                            </li>

                                                        @endforeach

                                                    </ul>

                                                @endforeach

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <input type="hidden" id="add_checklist_item_checklist_id" value="{{ $checklist_id }}">
                    <input type="hidden" id="add_checklist_item_group_id">
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-around">
                <a class="btn btn-primary" id="save_add_checklist_item_button" data-toggle="modal"><i class="fad fa-save mr-2"></i> Save Checklist Item</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade draggable modal-shared" id="reject_document_modal" tabindex="-1" role="dialog" aria-labelledby="reject_document_modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header draggable-handle">
                <h4 class="modal-title" id="reject_document_modal_title">Reject Document</h4>
                <a href="javascript: void(0)" class="text-danger font-13" data-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times mt-2 fa-lg"></i>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="rejected-reasons-container text-gray">
                            <form id="rejected_reason_form">

                                <div class="h5 text-orange">Why is this document being rejected?</div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="my-2">Select from the list below</div>
                                    <a href="/admin/resources/resources_admin" target="_blank" class="btn btn-primary btn-sm"><i class="fal fa-plus"></i> Edit List</a>
                                </div>

                                <div class="rejected-reasons-div list-group w-100 mb-2">
                                    @foreach($rejected_reasons as $rejected_reason)
                                        <div class="list-group-item list-group-item-action rejected-reason" data-reason="{{ $rejected_reason -> resource_name }}">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="rejected-selected text-success d-none"><i class="fal fa-check-circle"></i></div>
                                                <div class="ml-3"><a href="javascript:void(0)" class="w-100">{{ $rejected_reason -> resource_name }}</a></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <span  class="mb-2">Or enter the reason<br>

                                <input type="text" class="custom-form-element form-input required" id="rejected_reason" placeholder="Enter Reason Rejected">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-around">
                <a class="btn btn-primary" id="save_reject_document_button"><i class="fad fa-save mr-2"></i> Save Rejection Reason</a>
            </div>
        </div>
    </div>
</div>






