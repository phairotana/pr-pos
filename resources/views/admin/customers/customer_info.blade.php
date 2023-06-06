<table class="table" aria-hidden="true">
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Customer Name</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">
            @if (empty($entry->customer_profile))
                <a class="text-decoration-none" href="{{ asset(config('const.filePath.default_image')) }}" data-lightbox="lightbox">
                    <img class="avatar_small" src="{{ asset(config('const.filePath.default_image')) }}" style="cursor:pointer" alt="No Profile" />
                </a>
                <span> {{ $entry->customer_name }}</span>
            @else
                <a class="text-decoration-none" href="{{ asset($entry->customer_profile) }}" data-lightbox="lightbox">
                    <img class="avatar_small" src="{{ asset($entry->customer_profile) }}" style="cursor:pointer" alt="Profile" />
                </a>
                <span>{{ $entry->customer_name }}</span>
            @endif
        </td>
        <td class="border-0 font-weight-bold" style="width:20%">Customer Code</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->customer_code }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Gender</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->customer_gender }}</td>
        <td class="border-0 font-weight-bold" style="width:20%">Date of Birth</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">
            {{ $entry->customer_dob ? Carbon\Carbon::parse($entry->customer_dob)->format('d-m-Y') : '' }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Phone</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->customer_phone }}</td>
        <td class="border-0 font-weight-bold" style="width:20%">Email</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->customer_email }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Address</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->customer_address }}</td>
        <td class="border-0 font-weight-bold" style="width:20%">Branch</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ optional($entry->branch)->branch_name }}
        </td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Created By</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ optional($entry->createBy)->name }}</td>
        <td class="border-0 font-weight-bold" style="width:20%">Updated By</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ optional($entry->updateBy)->name }}</td>
    </tr>
</table>
