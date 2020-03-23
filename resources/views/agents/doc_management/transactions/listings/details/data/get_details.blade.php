<div class="container mt-0">
    <div class="row">
        <div class="col-12">
            <form id="listing_details_form">

                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="listing-details-div my-2 z-depth-1">
                            <div class="h5 m-2 mb-4 text-default">
                                <i class="fad fa-file-signature mr-3"></i> Listing Details
                            </div>
                            <div class="row">
                                <div class="col-12 col-xl-8">
                                    <div class="row d-flex align-items-center">
                                        {{-- TODO: this needs to be dynamic if MLS ID is changed --}}
                                        <div class="col-1">
                                            @if($listing -> MLS_Verified)
                                                <i class="fal fa-check fa-2x text-success mls-verified" data-toggle="tooltip" title="MLS ID Verified"></i>
                                            @endif
                                        </div>
                                        <div class="col-6 pr-0">
                                            <input type="text" class="custom-form-element form-input" data-label="MLS ID" name="ListingId" id="ListingId" value="{{ $listing -> ListingId }}">
                                        </div>
                                        <div class="col-2 pl-0">
                                            <a href="javascript: void(0)" class="btn btn-primary" id="search_mls_button">Search</a>
                                        </div>
                                        <div class="col-2">
                                            <a href="javascript: void(0)" class="float-left" role="button" data-toggle="popover" data-html="true" data-trigger="focus" title="Bright MLS ID" data-content="If the MLS ID is found, data from BrightMLS will be imported and auto-populated.<br><br><i class='fad fa-exclamation-triangle mr-2'></i> If the County is changed a new checklist will be provided. Any relevant forms will be kept in the checklist but some may need to be added or replaced."><i class="fad fa-question-circle ml-4  ml-sm-1 ml-md-3 ml-lg-2 ml-xl-3 fa-lg"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <input type="text" class="custom-form-element form-input required" data-label="List Price" name="ListPrice" id="ListPrice" value="{{ $listing -> ListPrice }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="custom-form-element form-input datepicker required" data-label="List Date" name="MLSListDate" id="MLSListDate" value="{{ $listing -> MLSListDate }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="custom-form-element form-input datepicker required" data-label="Expiration Date" name="ExpirationDate" id="ExpirationDate" value="{{ $listing -> ExpirationDate }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="custom-form-element form-input required" data-label="Year Built" name="YearBuilt" id="YearBuilt" value="{{ $listing -> YearBuilt }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="custom-form-element form-input" data-label="Source" name="Source" id="Source" value="{{ $listing -> Source }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="listing-details-div my-2 z-depth-1">
                            <div class="h5 m-2 mb-4 text-default">
                                <i class="fad fa-users mr-3"></i> Listing Agent(s)
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <select class="custom-form-element form-select required" @if(Auth::user() -> group == 'agent') disabled @endif data-label="Listing Agent" name="Agent_ID" id="Agent_ID">
                                        <option value=""></option>
                                        @foreach($agents as $agent)
                                        <option value="{{ $agent -> id }}" @if($listing -> Agent_ID == $agent -> id) selected @endif>{{ $agent -> last_name . ', ' . $agent -> first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <select class="custom-form-element form-select" data-label="Co-Listing Agent" name="CoAgent_ID" id="CoAgent_ID">
                                        <option value=""></option>
                                        @foreach($agents as $agent)
                                        <option value="{{ $agent -> id }}" @if($listing -> CoAgent_ID == $agent -> id) selected @endif>{{ $agent -> last_name . ', ' . $agent -> first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <select class="custom-form-element form-select" data-label="Transaction Coordinator" name="TransCoordinator_ID" id="TransCoordinator_ID">
                                        <option value=""></option>
                                        @foreach($trans_coords as $trans_coord)
                                        <option value="{{ $trans_coord -> id }}" @if($listing -> TransCoordinator_ID == $trans_coord -> id) selected @endif>{{ $trans_coord -> last_name . ', ' . $trans_coord -> first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <select class="custom-form-element form-select" data-label="Team" name="Team_ID" id="Team_ID">
                                        <option value=""></option>
                                        @foreach($teams as $team)
                                        <option value="{{ $team -> id }}" @if($listing -> Team_ID == $team -> id) selected @endif>{{ $team -> team_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="listing-details-div mt-4 mb-2 z-depth-1">
                            <div class="row d-flex align-items-center">
                                <div class="col-12 col-xl-3">
                                    <div class="h5 m-2 mb-2 mb-xl-4 text-default">
                                        <i class="fad fa-location mr-3"></i> Location Details
                                    </div>
                                </div>
                                <div class="col-12 col-xl-9">
                                    @if($listing -> MLS_Verified)
                                        <div class="text-success mb-3"><i class="fal fa-check fa-lg mr-3 mls-verified"></i> Location Details were verified by BrightMLS <a href="javascript: void(0)" role="button" data-toggle="popover" data-html="true" data-trigger="focus" title="How To Change The Address" data-content="To change the address you must remove the MLS ID or enter a different MLS ID."><i class="fad fa-question-circle ml-2 fa-lg"></i></a></div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 col-xl-2">
                                    <input type="text" class="custom-form-element form-input required" data-label="Street Number" name="StreetNumber" id="StreetNumber" value="{{ $listing -> StreetNumber += 0 }}">
                                </div>
                                <div class="col-12 col-lg-6 col-xl-4">
                                    <input type="text" class="custom-form-element form-input required" data-label="Street Name" name="StreetName" id="StreetName" value="{{ $listing -> StreetName }}">
                                </div>
                                <div class="col-12 col-lg-3 col-xl-2">
                                    <select class="custom-form-element form-select" data-label="Street Suffix" name="StreetSuffix" id="StreetSuffix">
                                        <option value=""></option>
                                        @foreach($street_suffixes as $street_suffix)
                                        <option value="{{ $street_suffix }}" @if($listing -> StreetSuffix == $street_suffix) selected @endif>{{ $street_suffix }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 col-xl-2">
                                    <select class="custom-form-element form-select" data-label="Street Dir" name="StreetDirSuffix" id="StreetDirSuffix">
                                        <option value=""></option>
                                        @foreach($street_dir_suffixes as $street_dir_suffix)
                                        <option value="{{ $street_dir_suffix }}" @if($listing -> StreetDirSuffix == $street_dir_suffix) selected @endif>{{ $street_dir_suffix }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 col-xl-2">
                                    <input type="text" class="custom-form-element form-input" data-label="Unit" name="UnitNumber" id="UnitNumber" value="{{ $listing -> UnitNumber }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-8 col-xl-4">
                                    <input type="text" class="custom-form-element form-input required" data-label="City" name="City" id="City" value="{{ $listing -> City }}">
                                </div>
                                <div class="col-12 col-lg-4 col-xl-2">
                                    <select class="custom-form-element form-select form-select-no-cancel required" data-label="State" name="StateOrProvince" id="StateOrProvince">
                                        <option value=""></option>
                                        @foreach($states as $state)
                                        <option value="{{ $state }}" @if($listing -> StateOrProvince == $state) selected @endif>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 col-xl-2">
                                    <input type="text" class="custom-form-element form-input required" data-label="Postal Code" name="PostalCode" id="PostalCode" value="{{ $listing -> PostalCode }}">
                                </div>
                                <div class="col-12 col-lg-8 col-xl-4">
                                    <div class="row">
                                        <div class="col-11 pr-0">
                                            <select class="custom-form-element form-select form-select-no-cancel required" disabled data-label="County" name="County" id="County">
                                                <option value=""></option>
                                                @foreach($counties as $county)
                                                <option value="{{ $county -> county }}" @if(strtolower($listing -> County) == strtolower($county -> county)) selected @endif>{{ $county -> county }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1 pl-0 pt-4">
                                            <a href="javascript: void(0)" role="button" data-toggle="popover" data-html="true" data-trigger="focus" title="County" data-content="You cannot change the County here because it will change the checklist requirements.<br><br>To change the County you must click on the <strong>Checklist</strong> tab or click <a href='javascript: void(0)' class='btn btn-sm btn-primary' id='open_checklist_button'>Here</a>."><i class="fad fa-question-circle ml-2 fa-lg"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-4 mt-3 text-center text-xl-left">
                        <a href="javascript: void(0)" class="btn btn-lg btn-success save-details-button"><span class="h4"><i class="fad fa-save mr-2"></i> Save Details</span></a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
