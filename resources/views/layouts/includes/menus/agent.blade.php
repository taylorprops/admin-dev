<li class="nav-item mx-2">
    <a href="/dashboard_agent" class="nav-link"> Dashboard</a>
</li>

<li class="nav-item dropdown mx-2">

    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="transactions_dropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        Transactions
    </a>
    <ul class="dropdown-menu" aria-labelledby="transactions_dropdown">

        <li><a href="/agents/doc_management/transactions" class="dropdown-item">View Transactions</a></li>

        <li>
            <a href="/agents/doc_management/transactions/add/listing" class="dropdown-item">Add Listing</a>
        </li>
        <li>
            <a href="/agents/doc_management/transactions/add/contract" class="dropdown-item">Add Contract/Lease</a>
        </li>
        <li>
            <a href="/agents/doc_management/transactions/add/referral" class="dropdown-item">Add Referral</a>
        </li>

    </ul>

</li>

<li class="nav-item mx-2">
    <a href="/documents" class="nav-link"> Documents</a>
</li>

@if(auth() -> user() -> group == 'agent')
<li class="nav-item mx-2">
    <a href="/esign" class="nav-link"> E-Sign</a>
</li>

<li class="nav-item mx-2">
    <a href="/contacts" class="nav-link"> Contacts</a>
</li>
@endif
