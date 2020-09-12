@extends('layouts.main')
@section('title', $property -> FullStreetAddress.' '.$property -> City.' '.$property -> StateOrProvince.' '.$property -> PostalCode )

@section('content')

<div class="container page-transaction-details mb-5">

    <div id="details_header"></div>

    <span id="scroll_to"></span>

    <div class="row">
        <div class="col-md-12 px-1 px-sm-3 mt-3 details-tabs">
            <ul id="tabs" class="nav nav-tabs details-list-group">

                <li class="nav-item"><a href="javascript: void(0)" data-tab="details" data-target="#details_tab" data-toggle="tab" class="nav-link active"><i class="fad fa-home-lg-alt mr-2 d-none d-md-inline-block"></i> Details</a></li>

                @if($transaction_type != 'referral')
                    <li class="nav-item"><a href="javascript: void(0)" data-tab="members" id="open_members_tab" data-target="#members_tab" data-toggle="tab" class="nav-link"><i class="fad fa-user-friends mr-2 d-none d-md-inline-block"></i> Members</a></li>
                @endif

                <li class="nav-item"><a href="javascript: void(0)" data-tab="documents" id="open_documents_tab" data-target="#documents_tab" data-toggle="tab" class="nav-link"><i class="fad fa-folder-open mr-2 d-none d-md-inline-block"></i> Documents</a></li>

                <li class="nav-item"><a href="javascript: void(0)" data-tab="checklist" id="open_checklist_tab" data-target="#checklist_tab" data-toggle="tab" class="nav-link"><i class="fad fa-tasks mr-2 d-none d-md-inline-block"></i> Checklist</a></li>

                @if($transaction_type == 'listing')

                    <li class="nav-item"><a href="javascript: void(0)" data-tab="contracts" id="open_contracts_tab" data-target="#contracts_tab" data-toggle="tab" class="nav-link"><i class="fad fa-file-signature mr-2 d-none d-md-inline-block"></i> {{ $for_sale ? 'Contracts' : 'Leases' }}</a></li>

                @else

                    @php
                    // agent and admin have different commission tabs
                    $commission = 'commission';
                    if(auth() -> user() -> group == 'agent') {
                        $commission = 'agent_commission';
                    } else if(auth() -> user() -> group == 'referral') {
                        $commission = 'referral_commission';
                    }
                    @endphp

                    {{-- show listing link if exists --}}
                    <li class="nav-item"><a href="javascript: void(0)" data-tab="{{ $commission }}" id="open_{{ $commission }}_tab" data-target="#{{ $commission }}_tab" data-toggle="tab" class="nav-link"><i class="fad fa-sack-dollar mr-2 d-none d-md-inline-block"></i> Commission</a></li>

                    @if($for_sale && auth() -> user() -> group == 'admin')
                        <li class="nav-item"><a href="javascript: void(0)" data-tab="earnest" id="open_earnest_tab" data-target="#earnest_tab" data-toggle="tab" class="nav-link"><i class="fad fa-envelope-open-dollar mr-2 d-none d-md-inline-block"></i> Earnest</a></li>
                    @endif

                @endif
            </ul>

            <div id="details_tabs" class="tab-content details-main-tabs">
                <div id="details_tab" class="tab-pane fade active show">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                <div id="members_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                <div id="documents_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                <div id="checklist_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                <div id="contracts_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                @if(auth() -> user() -> group == 'admin')
                <div id="commission_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                <div id="earnest_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                @elseif(auth() -> user() -> group == 'agent')
                <div id="agent_commission_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                @elseif(auth() -> user() -> group == 'referral')
                <div id="referral_commission_tab" class="tab-pane fade">
                    <div class="w-100 my-5 text-center">
                        {!! config('global.vars.loader') !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <input type="hidden" id="Listing_ID" value="{{ $property -> Listing_ID }}">
    <input type="hidden" id="Contract_ID" value="{{ $property -> Contract_ID }}">
    <input type="hidden" id="Referral_ID" value="{{ $property -> Referral_ID }}">
    <input type="hidden" id="Agent_ID" value="{{ $property -> Agent_ID }}">
    <input type="hidden" id="transaction_type" value="{{ $transaction_type }}">
    <input type="hidden" id="questions_confirmed" value="{{ $questions_confirmed }}">
    <input type="hidden" id="for_sale" value="{{ $for_sale == true ? 'yes' : 'no' }}">


    <div class="modal fade draggable" id="confirm_undo_cancel_modal" tabindex="-1" role="dialog" aria-labelledby="undo_cancel_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary draggable-handle">
                    <h4 class="modal-title" id="undo_cancel_title">Undo Release/Cancellation</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times mt-2"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                This will reactivate the {{ $for_sale ? 'Sales Contract' : 'Lease Agreement' }}.<br>
                                Are you sure you want to UNDO this Release/Cancellation?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-around">
                    <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-2"></i> Cancel</a>
                    <a class="btn btn-success modal-confirm-button" id="undo_cancel_button"><i class="fad fa-check mr-2"></i> Confirm</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade draggable" id="cancel_contract_modal" tabindex="-1" role="dialog" aria-labelledby="cancel_contract_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary draggable-handle">
                    <h4 class="modal-title" id="cancel_contract_modal_title">Cancel {{ $for_sale ? 'Contract' : 'Lease' }}</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times mt-2"></i>
                    </button>
                </div>
                <div class="modal-body pt-3">

                    <div class="list-group cancel-alerts">

                        {{-- Contracts --}}
                        <div class="list-group-item cancel-contract docs-submitted d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                By submitting this form your cancellation request will be sent to the office for approval.
                            </div>
                        </div>
                        <div class="list-group-item cancel-contract has-listing docs-submitted d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                Your listing will remain active and you will be able to accept a new {{ $for_sale ? 'Sales Contract' : 'Lease Agreement' }} once the cancellation is approved.
                            </div>
                        </div>

                        <div class="list-group-item cancel-contract docs-not-submitted d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                Since we have not reviewed and approved a Sales Contract for this property the Contract will be instantly canceled.
                            </div>
                        </div>
                        <div class="list-group-item cancel-contract has-listing docs-not-submitted d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                Your listing will remain active and you will be able to accept a new {{ $for_sale ? 'Sales Contract' : 'Lease Agreement' }} immediately.
                            </div>
                        </div>

                        {{-- Leases --}}
                        <div class="list-group-item cancel-lease docs-submitted d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                By submitting this form your cancellation request will be sent to the office for approval.
                            </div>
                        </div>
                        <div class="list-group-item cancel-lease docs-not-submitted d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                Since we have not reviewed and approved a Lease Agreement for this property the Lease Agreement will be instantly canceled.
                            </div>
                        </div>

                        <div class="list-group-item cancel-lease has-listing docs-not-submitted d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                Your listing will remain active and you will be able to accept a new {{ $for_sale ? 'Sales Contract' : 'Lease Agreement' }} immediately.
                            </div>
                        </div>
                        <div class="list-group-item expired-listing d-flex justify-content-start align-items-center">
                            <div class="pr-3">
                                <i class="fa fa-exclamation-circle text-danger fa-2x"></i>
                            </div>
                            <div>
                                Your Listing Agreement is past its expiration date, please submit an extension or Withdraw it.
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer d-flex justify-content-around">
                    <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-2"></i> Do Not Cancel</a>
                    <a class="btn btn-success" id="save_cancel_contract_button"><i class="fad fa-check mr-2"></i> Submit Cancellation {{ $for_sale ? 'Request' : '' }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade draggable" id="required_fields_modal" tabindex="-1" role="dialog" aria-labelledby="required_fields_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="required_fields_form">
                    <div class="modal-header draggable-handle">
                        <h4 class="modal-title" id="required_fields_modal_title">Add Required Fields</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <i class="fal fa-times mt-2"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="h5 text-orange mt-2 mb-3">Please enter the following required details before submitting any documents.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        Are the Buyer's using Heritage Title?
                                        <br>
                                        <select class="custom-form-element form-select form-select-no-search form-select-no-cancel required" id="required_fields_using_heritage" name="required_fields_using_heritage" data-label="Using Heritage">
                                            <option value=""></option>
                                            <option value="yes" @if($property -> UsingHeritage == "yes") selected @endif>Yes</option>
                                            <option value="no" @if($property -> UsingHeritage == "no") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="not-using-heritage">
                                            <input type="text" class="custom-form-element form-input required" id="required_fields_title_company" name="required_fields_title_company" value="{{ $property -> TitleCompany }}" data-label="Title Company">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" class="custom-form-element form-input money-decimal numbers-only required" id="required_fields_earnest_amount" name="required_fields_earnest_amount" value="{{ $property -> EarnestAmount > 0 ? $property -> EarnestAmount : '' }}" data-label="Earnest Deposit Amount">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <select class="custom-form-element form-select form-select-no-search form-select-no-cancel required" id="required_fields_earnest_held_by" name="required_fields_earnest_held_by" data-label="Earnest Deposit Held By">
                                            <option value=""></option>
                                            <option value="us" @if($property -> TitleCompany == "us") selected @endif>Taylor/Anne Arundel Properties</option>
                                            <option value="other_company" @if($property -> TitleCompany == "other_company") selected @endif>Other Real Estate Company</option>
                                            <option value="title" @if($property -> TitleCompany == "title") selected @endif>Title Company/Attorney</option>
                                            <option value="heritage_title" @if($property -> TitleCompany == "heritage_title") selected @endif>Heritage Title</option>
                                            <option value="builder" @if($property -> TitleCompany == "builder") selected @endif>Builder</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-around">
                        <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-2"></i> Cancel</a>
                        <a class="btn btn-success" id="save_required_fields_button"><i class="fad fa-check mr-2"></i> Save</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade draggable disable-scrollbars" id="accept_contract_modal" tabindex="-1" role="dialog" aria-labelledby="accept_contract_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header draggable-handle">
                    <h4 class="modal-title" id="accept_contract_modal_title">Accept {{ $for_sale ? 'Contract' : 'Lease' }}</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times mt-2"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="h5 text-primary pb-3 border-bottom">Enter the required details <a href="javascript: void(0)" role="button" data-toggle="popover" data-html="true" data-trigger="focus" title="Required Details" data-content="This information will be used to autopopulate your forms"><i class="fad fa-question-circle ml-2"></i></a></div>

                    <form id="accept_contract_form" autocomplete="off">

                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="h5 text-orange">{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent Details</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="text-primary mr-2">
                                        Who is representing the {{ $for_sale ? 'Buyer' : 'Renter' }}(s)?
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <select class="custom-form-element form-select form-select-no-search form-select-no-cancel required" id="accept_contract_BuyerRepresentedBy" data-label="Select One">
                                        <option value=""></option>
                                        <option value="other_agent">Agent From Other Company</option>
                                        <option value="our_agent">Agent From Taylor or Anne Arundel Properties</option>
                                        <option value="none">Not Represented</option>
                                        @if($property -> StateOrProvince != 'MD')
                                            <option value="agent">You Represent Both {{ $for_sale ? 'Seller' : 'Owner' }} and {{ $for_sale ? 'Buyer' : 'Renter' }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="our-agent-div">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <select class="custom-form-element form-select" id="accept_contract_our_agent" data-label="Select Agent">
                                            <option value=""></option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent -> id }}" data-id="{{ $agent -> id }}" data-first="{{ $agent -> first_name }}" data-last="{{ $agent -> last_name }}" data-email="{{ $agent -> email }}" data-phone="{{ $agent -> cell_phone }}" data-company="{{ $agent -> company }}">{{ $agent -> first_name . ' ' . $agent -> last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="buyer-agent-details">

                                <div class="row bright-search-row">
                                    <div class="col-12">
                                        <a class="btn btn-primary btn-sm my-3" data-toggle="collapse" href="#agent_search_div" role="button" aria-expanded="false" aria-controls="agent_search_div">
                                            <i class="fad fa-search mr-2"></i> Search Agents in Bright MLS
                                        </a>
                                        <div class="collapse border" id="agent_search_div">
                                            <div class="p-2 mb-4">
                                                <div class="mb-4">Type the Agent's Name, Email or BrightMLS ID</div>
                                                <input type="text" class="custom-form-element form-input" id="agent_search" data-label="Enter Agent's Name, Email or ID" autocomplete="agentsearch">
                                                <div class="search-results-container">
                                                    <div class="list-group search-results bg-white p-2 border z-depth-1 w-100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input agent-details agent-details-required required" id="accept_contract_buyer_agent_first" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent First Name" data-agent-detail="{{ $agent_details -> first_name }}">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input agent-details agent-details-required required" id="accept_contract_buyer_agent_last" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent Last Name" data-agent-detail="{{ $agent_details -> last_name }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input agent-details agent-details-required required" id="accept_contract_buyer_agent_company" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent Company" data-agent-detail="{{ $agent_details -> company }}">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input agent-details" id="accept_contract_buyer_agent_mls_id" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent BrightMLS ID">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input phone agent-details" id="accept_contract_buyer_agent_phone" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent Phone" data-agent-detail="{{ $agent_details -> cell_phone }}">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="email" class="custom-form-element form-input agent-details" id="accept_contract_buyer_agent_email" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent Email" data-agent-detail="{{ $agent_details -> email }}">
                                    </div>
                                    <input type="hidden" id="accept_contract_buyer_agent_street" class="agent-details" data-agent-detail="175 Admiral Cochrane Dr., Suite 111">
                                    <input type="hidden" id="accept_contract_buyer_agent_city" class="agent-details" data-agent-detail="Annapolis">
                                    <input type="hidden" id="accept_contract_buyer_agent_state" class="agent-details" data-agent-detail="MD">
                                    <input type="hidden" id="accept_contract_buyer_agent_zip" class="agent-details" data-agent-detail="21401">
                                    <input type="hidden" id="accept_contract_OtherAgent_ID" class="agent-details">
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="h5 text-orange">{{ $for_sale ? 'Buyer' : 'Renter' }} Details</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="custom-form-element form-input required" id="accept_contract_buyer_one_first" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }} One First Name">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="custom-form-element form-input required" id="accept_contract_buyer_one_last" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }} One Last Name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="custom-form-element form-input" id="accept_contract_buyer_two_first" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }} Two First Name">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="custom-form-element form-input" id="accept_contract_buyer_two_last" data-label="{{ $for_sale ? 'Buyer' : 'Renter' }} Two Last Name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>

                            @if($for_sale)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="h5 text-orange">Title and Earnest Details</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-start flex-wrap align-items-center">
                                            <div class="text-primary mr-2">
                                                Are the Buyer's using Heritage Title?
                                            </div>
                                            <div class="mr-2 using-heritage">
                                                <select class="custom-form-element form-select form-select-no-search form-select-no-cancel required" id="accept_contract_using_heritage" data-label="Using Heritage">
                                                    <option value=""></option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                                            <div class="not-using-heritage">
                                                <input type="text" class="custom-form-element form-input" id="accept_contract_title_company" data-label="Title Company">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input money-decimal numbers-only required" id="accept_contract_earnest_amount" data-label="Earnest Deposit Amount">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <select class="custom-form-element form-select form-select-no-search form-select-no-cancel required" id="accept_contract_earnest_held_by" data-label="Earnest Deposit Held By">
                                            <option value=""></option>
                                            <option value="us">Taylor/Anne Arundel Properties</option>
                                            <option value="other_company">Other Real Estate Company</option>
                                            <option value="title">Title Company/Attorney</option>
                                            <option value="heritage_title">Heritage Title</option>
                                            <option value="builder">Builder</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12">
                                    <div class="h5 text-orange">{{ $for_sale ? 'Contract' : 'Lease' }} Details</div>
                                </div>
                            </div>

                            @if($for_sale)
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input datepicker required" id="accept_contract_contract_date" data-label="Contract Date">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input datepicker required" id="accept_contract_close_date" data-label="Settle Date">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input money-decimal numbers-only required" id="accept_contract_contract_price" data-label="Sales Price">
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input datepicker required" id="accept_contract_close_date" data-label="Lease Date">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <input type="text" class="custom-form-element form-input money-decimal numbers-only required" id="accept_contract_lease_amount" data-label="Lease Price">
                                    </div>
                                </div>
                            @endif
                        </div>

                    </form>

                </div>
                <div class="modal-footer d-flex justify-content-around mb-5 pb-5">
                    <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-2"></i> Cancel</a>
                    <a class="btn btn-success" id="save_accept_contract_button"><i class="fad fa-check mr-2"></i> Save</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade draggable" id="confirm_import_modal" tabindex="-1" role="dialog" aria-labelledby="import_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header draggable-handle">
                    <h4 class="modal-title" id="import_title">Confirm Import</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times mt-2"></i>
                    </button>
                </div>
                <div class="modal-body"> </div>
                <div class="modal-footer d-flex justify-content-around">
                    <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-2"></i> Cancel</a>
                    <a class="btn btn-success modal-confirm-button" id="confirm_import_button"><i class="fad fa-check mr-2"></i> Confirm</a>
                </div>
            </div>
        </div>
    </div>


</div>


@endsection
