@php

$sale_rent = 'For Sale';
$for_sale = true;
if($property -> SaleRent == 'rental') {
    $sale_rent = 'Rental';
    $for_sale = false;
} else if($property -> SaleRent == 'both' && $transaction_type == 'listing') {
    $sale_rent = 'For Sale And Rent';
}

if($transaction_type == 'listing') {
    $header_transaction_type = '<i class="fad fa-sign mr-2"></i> Listing Agreement';
    $transaction_type_bg = 'bg-orange';
} else if($transaction_type == 'contract') {
    $header_transaction_type = '<i class="fad fa-file-signature mr-2"></i> Lease Agreement';
    if($for_sale) {
        $header_transaction_type = '<i class="fad fa-file-signature mr-2"></i> Sales Contract';
    }
    $transaction_type_bg = 'bg-success';
} else if($transaction_type == 'referral') {
    $header_transaction_type = '<i class="fad fa-handshake mr-2"></i> Referral Agreement';
    $transaction_type_bg = 'bg-orange';
}

$status = $resource_items -> GetResourceName($property -> Status);

$settle_date = '';
if($property -> CloseDate != '') {
    $settle_date = date_mdy($property -> CloseDate);
}

@endphp
<div class="row mt-1 mt-sm-5">

    <div class="col-12 col-lg-7">

        <div class="d-flex justify-content-start">

            @if($property -> ListPictureURL)
                <div class="d-none d-sm-block ml-2 mr-3">
                    <div class="property-image-div">
                        <img loading="lazy" src="{{ str_replace('http:', 'https:', $property -> ListPictureURL) }}" class="img-fluid shadow">
                    </div>
                </div>
            @endif

            <div class="w-100">

                <div class="h3 mt-2 mt-sm-0 mb-2 ml-2 text-gray">
                    {!! $property -> FullStreetAddress.' '.$property -> Street.' '.$property -> City.', '.$property -> StateOrProvince.' '.$property -> PostalCode !!}
                </div>

                <div class="d-flex justify-content-start align-items-center flex-wrap mb-1 mb-md-3">

                    <div class="{{ $transaction_type_bg }} my-1 p-1 p-sm-2 rounded no-wrap">
                        <span class="font-12 text-white">{!! $header_transaction_type !!}</span>
                    </div>

                    @if($transaction_type != 'referral')

                        <div>

                            <div class="d-flex justify-content-start align-items-center flex-wrap">

                                <div class="font-12 text-gray ml-sm-3">
                                    @if($transaction_type == 'listing') ${{ number_format($property -> ListPrice) }} @else ${{ number_format($property -> ContractPrice) }} @endif
                                </div>

                                <span class="font-12 text-primary mx-3">|</span>

                                <span class="text-gray">{{ $sale_rent }}</span>

                                <span class="font-12 text-primary mx-3">|</span>

                                <span class="text-gray">{{ $resource_items -> GetResourceName($property -> PropertyType) }}</span>

                                <span class="font-12 text-primary mx-3">|</span>

                                @if($sale_rent != 'Rental' && $property -> PropertySubType > '0')
                                    <span class="text-gray">{{ $resource_items -> GetResourceName($property -> PropertySubType) }}</span>
                                @endif

                            </div>

                            <div class="d-flex justify-content-start align-items-center flex-wrap">

                                <span class="font-11 text-gray ml-sm-3">{{ $status }}</span>

                                <span class="font-12 text-primary mx-3">|</span>

                                <div data-toggle="tooltip" title="@if($transaction_type == 'listing') List Date @else Contract Date @endif">
                                    <span class="text-gray">@if($transaction_type == 'listing') LD - {{ date_mdy($property -> MLSListDate) }} @else CD - {{ date_mdy($property -> ContractDate) }} @endif</span>
                                </div>

                                <span class="font-12 text-primary mx-3">|</span>

                                <div data-toggle="tooltip" title="@if($transaction_type == 'listing') Expiration Date @else Settle Date @endif">
                                    <span class="text-gray">@if($transaction_type == 'listing') EX - {{ date_mdy($property -> ExpirationDate) }} @else SD - {{ $settle_date }}  @endif</span>
                                </div>

                            </div>

                        </div>

                    @endif

                </div>
            </div>

        </div>

    </div>
    <div class="col-12 col-lg-5 mt-3 mt-lg-0">

        @if($transaction_type == 'listing')

            @php $action = $listing_accepted ? 'Withdraw' : 'Cancel'; @endphp

            <div class="d-flex flex-wrap justify-content-end align-items-center">
                @if(in_array($property -> Status, $resource_items -> GetActiveListingStatuses('no', 'yes', 'yes') -> toArray()))

                    <div>
                        <a href="javascript: void(0);" class="btn btn-success mt-2 d-block d-sm-inline-block" id="accept_contract_button"><i class="fal fa-plus mr-2"></i> Accept {{ $for_sale ? 'Contract' : 'Lease' }}</a>
                    </div>
                    <div>
                        <a href="javascript: void(0);" class="btn btn-danger mt-2 d-block d-sm-inline-block" id="cancel_listing_button"><i class="fal fa-minus mr-2"></i> {{ $action }} Listing</a>
                    </div>

                @else

                    @php
                    $header_status = $status;
                    if($header_status == 'Under Contract') {
                        $header_status = $for_sale ? 'Under Contract' : 'Lease Accepted';
                    }
                    $header_status_class = 'bg-orange';
                    $success_statuses = ['Under Contract', 'Lease Accepted', 'Closed'];
                    $danger_statuses = ['Canceled', 'Withdrawn', 'Expired'];
                    if(in_array($header_status, $success_statuses)) {
                        $header_status_class = 'bg-success';
                        $header_fa = 'fa-check-circle';
                    } elseif(in_array($header_status, $danger_statuses)) {
                        $header_status_class = 'bg-danger';
                        $header_fa = 'fa-ban';
                    }
                    @endphp
                    <div>
                        <span class="{{ $header_status_class }} text-white mr-2 font-13 rounded px-3 py-2"><i class="fal {{ $header_fa }} mr-2"></i> {{ $header_status }}!</span>
                    </div>

                    @if($property -> Status == $resource_items -> GetResourceID('Canceled', 'listing_status') || $property -> Status == $resource_items -> GetResourceID('Withdrawn', 'listing_status'))

                        <div class="mx-3 mt-1">
                            <a href="javascript: void(0)"class="undo-cancel-listing-button" data-listing-id="{{ $property -> Listing_ID }}"><i class="fal fa-undo mr-1"></i> Undo</a>
                        </div>

                    @endif

                @endif
            </div>

        @elseif($transaction_type == 'contract')

            @php

            $docs_submitted = $upload -> DocsSubmitted('', $Contract_ID);
            $action = $docs_submitted['contract_submitted'] ? 'Release' : 'Cancel';
            @endphp

            <div class="row">

                <div class="col-12">

                    <div class="d-flex flex-wrap justify-content-end align-items-center">

                        @if($property -> Status == $resource_items -> GetResourceID('Active', 'contract_status'))

                            <div>
                                <a href="javascript: void(0);" class="btn btn-danger mt-2" id="cancel_contract_button" data-for-sale="{{ $for_sale ? 'yes' : 'no' }}" data-listing-expiration-date="{{ $listing_expiration_date }}"><i class="fal fa-minus mr-2"></i> {{ $for_sale ? $action.' Contract' : 'Cancel Lease' }}</a>
                            </div>

                        @elseif($property -> Status == $resource_items -> GetResourceID('Cancel Pending', 'contract_status'))

                            <span class="bg-orange text-white mr-2 font-13 rounded px-3 py-2"><i class="fad fa-hourglass-start mr-2 text-white"></i> {{ $status }}</span>
                            <div class="mx-3 mt-1">
                                <a href="javascript: void(0)"class="undo-cancel-contract-button" data-contract-id="{{ $property -> Contract_ID }}"><i class="fal fa-undo mr-1"></i> Undo</a>
                            </div>
                            @if(auth() -> user() -> group == 'admin')
                                <div>
                                    <button class="btn btn-danger process-cancellation-button" data-contract-id="{{ $property -> Contract_ID }}"><i class="fad fa-cogs mr-2 text-white"></i> Process</button>
                                </div>
                            @endif

                        @elseif($property -> Status == $resource_items -> GetResourceID('Released', 'contract_status') || $property -> Status == $resource_items -> GetResourceID('Canceled', 'contract_status'))

                            <span class="bg-danger text-white mr-2 font-13 rounded px-3 py-2"><i class="fal fa-ban mr-2 text-white"></i> {{ $status }}</span>
                            <div class="mx-3 mt-1">
                                <a href="javascript: void(0)"class="undo-cancel-contract-button" data-contract-id="{{ $property -> Contract_ID }}"><i class="fal fa-undo mr-1"></i> Undo</a>
                            </div>

                        @elseif($property -> Status == $resource_items -> GetResourceID('Closed', 'contract_status'))

                            <span class="bg-success text-white mr-2 font-13 rounded px-3 py-2"><i class="fal fa-check mr-2 text-white"></i> {{ $status }}</span>

                        @else
                            <span class="bg-primary text-white mr-2 font-13 rounded px-3 py-2"><i class="fad fa-exclamation-circle mr-2 text-white"></i> {{ $status }}</span>
                        @endif

                        @if($property -> Listing_ID > 0 && $property -> Contract_ID > 0)

                            <div>
                                <a href="/agents/doc_management/transactions/transaction_details/{{ $property -> Listing_ID }}/listing" class="btn btn-primary mt-2"><i class="fad fa-sign mr-2"></i> View Listing</a>
                            </div>

                        @endif

                    </div>

                    @if(!$property -> Listing_ID > 0 && $listings_count > 0)

                        <div class="d-flex justify-content-end mt-3">
                            <a href="javascript:void(0)" id="merge_with_listing_button"><i class="fad fa-exchange-alt mr-2"></i> Merge with Listing</a>
                        </div>

                    @endif

                    @if($property -> Merged == 'yes')

                        <div class="d-flex justify-content-end mt-3">
                            <a href="javascript:void(0)" id="undo_merge_with_listing_button" data-listing-id="{{ $property -> Listing_ID }}"><i class="fad fa-exchange-alt mr-2"></i> Undo Merge with Listing</a>
                        </div>

                    @endif



                </div>

            </div>

        @elseif($transaction_type == 'referral')

            <div class="d-flex flex-wrap justify-content-end align-items-center">

                    @php
                    $header_status = $status;
                    $header_status_class = 'bg-orange';
                    $header_fa = 'fa-check';
                    if($status == 'Closed') {
                        $header_status_class = 'bg-success';
                        $header_fa = 'fa-check-circle';
                    } elseif($status == 'Canceled') {
                        $header_status_class = 'bg-danger';
                        $header_fa = 'fa-ban';
                    }
                    @endphp


                    @if($property -> Status == $resource_items -> GetResourceID('Active', 'referral_status'))

                        <div>
                            <a href="javascript: void(0);" class="btn btn-danger" id="cancel_referral_button"><i class="fal fa-minus mr-2"></i> Cancel Referral</a>
                        </div>

                    @else

                        <div>
                            <span class="{{ $header_status_class }} text-white mr-2 font-13 rounded px-3 py-2"><i class="fal {{ $header_fa }} mr-2"></i> {{ $header_status }}!</span>
                        </div>

                        @if($property -> Status == $resource_items -> GetResourceID('Canceled', 'referral_status'))

                            <div class="mx-3">
                                <a href="javascript: void(0)"class="undo-cancel-referral-button" data-referral-id="{{ $property -> Referral_ID }}"><i class="fal fa-undo mr-1"></i> Undo</a>
                            </div>

                        @endif

                    @endif


            </div>

        @endif
    </div>

</div>

@if($transaction_type != 'referral')
<div class="row my-4 listing-header-details">

    <div class="col-12">

        <div class="row">

            <div class="col-12 col-sm-6 col-xl-4 h-100">

                <div class="bg-blue-light text-gray rounded p-2 h-100">

                    <div class="row">

                        @if($resource_items -> GetResourceId('For Sale By Owner', 'checklist_property_sub_types') != $property -> PropertySubType)

                            @php
                            $contact_details = '<i class=\'fad fa-phone-alt mr-2 text-primary\'></i> <a href=\'tel:'.format_phone($property -> ListAgentPreferredPhone).'\'>'.format_phone($property -> ListAgentPreferredPhone).'</a><br>
                            <i class=\'fad fa-at mr-2 text-primary\'></i> <a href=\'mailto:'.$property -> ListAgentEmail.'\'>'.$property -> ListAgentEmail.'</a>';
                            @endphp

                            <div class="col-6 border-right">

                                <div class="agent-section header-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="font-weight-bold">List Agent</span>
                                        </div>
                                        <div>
                                            <a href="javascript: void(0)" class="btn btn-sm btn-primary ml-0 my-2" role="button" data-toggle="popover" data-html="true" data-trigger="focus" title="Contact Details" data-content="{!! $contact_details !!}"><i class="fad fa-address-book mr-sm-1"></i> <span class="d-none d-sm-inline-block">Contact</span></a>
                                        </div>
                                    </div>
                                    <div>
                                        {{ $property -> ListAgentFirstName . ' ' . $property -> ListAgentLastName }}
                                        <br>
                                        {{ shorten_text($property -> ListOfficeName, 28) }}
                                    </div>
                                </div>

                            </div>

                        @endif

                        <div class="col-6">

                            <div class="header-section">
                                <span class="font-weight-bold">{{ $for_sale ? 'Sellers' : 'Owners' }}</span>
                                <br>
                                @if($sellers)
                                    @foreach($sellers as $seller)
                                        <div>
                                            {{ $seller }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>


                    </div>

                </div>

            </div>

            @if($transaction_type == 'contract' && $property -> BuyerRepresentedBy != 'none')

                <div class="col-12 col-sm-6 col-xl-4 h-100">
                    @php
                    $contact_details = '<i class=\'fad fa-phone-alt mr-2 text-primary\'></i> <a href=\'tel:'.format_phone($property -> BuyerAgentPreferredPhone).'\'>'.format_phone($property -> BuyerAgentPreferredPhone).'</a><br>
                    <i class=\'fad fa-at mr-2 text-primary\'></i> <a href=\'mailto:'.$property -> BuyerAgentEmail.'\'>'.$property -> BuyerAgentEmail.'</a>';
                    @endphp

                    <div class="bg-blue-light text-gray rounded p-2 h-100">

                        <div class="row">

                            <div class="col-6 border-right">
                                <div class="agent-section header-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="font-weight-bold">{{ $for_sale ? 'Buyer' : 'Renter' }}'s Agent</span>
                                        </div>
                                        <div>
                                            <a href="javascript: void(0)" class="btn btn-sm btn-primary ml-0 my-2" role="button" data-toggle="popover" data-html="true" data-trigger="focus" title="Contact Details" data-content="{!! $contact_details !!}"><i class="fad fa-address-book mr-sm-1"></i> <span class="d-none d-sm-inline-block">Contact</span></a>
                                        </div>
                                    </div>
                                    <div>
                                        {{ $property -> BuyerAgentFirstName . ' ' . $property -> BuyerAgentLastName }}
                                        <br>
                                        {{ shorten_text($property -> BuyerOfficeName, 28) }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="header-section">
                                    <span class="font-weight-bold">{{ $for_sale ? 'Buyers' : 'Renters' }}</span>
                                    <br>
                                    @if(count($buyers) > 0)
                                        @foreach($buyers as $buyer)
                                            <div>
                                                {{ $buyer }}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- <div class="col-12 col-sm-6 col-xl-3 h-100">

                <div class="bg-blue-light text-gray rounded h-100  header-status-div">

                    @if($for_sale || $transaction_type == 'listing')

                        <div class="container pt-2 pr-5">
                            <div class="row">
                                <div class="col-6 text-right pr-0">
                                    <span class="text-primary text-nowrap">Status</span>
                                </div>
                                <div class="col-6 text-left text-nowrap">
                                    {{ $status }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right pr-0">
                                    <span class="text-primary text-nowrap">@if($transaction_type == 'listing') List Date @else Contract Date @endif</span>
                                </div>
                                <div class="col-6 text-left">
                                    @if($transaction_type == 'listing') {{ date_mdy($property -> MLSListDate) }} @else {{ date_mdy($property -> ContractDate) }} @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right pr-0">
                                    <span class="text-primary text-nowrap">@if($transaction_type == 'listing') Expires Date @else Settle Date @endif</span>
                                </div>
                                <div class="col-6 text-left">
                                    @php
                                    $settle_date = '';
                                    if($property -> CloseDate != '') {
                                        $settle_date = date_mdy($property -> CloseDate);
                                    }
                                    @endphp
                                    @if($transaction_type == 'listing') {{ date_mdy($property -> ExpirationDate) }} @else {{ date_mdy($settle_date) }} @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right pr-0 text-nowrap text-nowrap">
                                    <span class="text-primary">@if($transaction_type == 'listing') {{ $for_sale ? 'List Price' : 'Lease Amount' }} @else Sale Price @endif</span>
                                </div>
                                <div class="col-6 text-left text-nowrap">
                                    @if($transaction_type == 'listing') ${{ number_format($property -> ListPrice) }} @else ${{ number_format($property -> ContractPrice) }} @endif
                                </div>
                            </div>
                        </div>

                    @else

                        <div class="container pr-5">
                            <div class="row">
                                <div class="col-6 text-right pr-0">
                                    <span class="font-weight-bold text-nowrap">Lease Date</span>
                                </div>
                                <div class="col-6 text-left">
                                    {{ date_mdy($property -> CloseDate) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right pr-0 text-nowrap text-nowrap">
                                    <span class="font-weight-bold">Lease Price</span>
                                </div>
                                <div class="col-6 text-left text-nowrap">
                                    @if($transaction_type == 'listing') ${{ number_format($property -> ListPrice) }} @else ${{ number_format($property -> LeaseAmount) }}  @endif
                                </div>
                            </div>
                        </div>

                    @endif

                </div>

            </div> --}}

        </div>

    </div>

</div>
@endif
