<div class="mb-5">
    <h5 class="font-weight-bold">Primary domain
    </h5>
    <div class="table-responsive">
        <table class="table table-bordered ajaxTable datatable">
            <thead>
                <tr>
                    <th>Domain Name</th>
                    <th>Date added</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{$website->primaryDomain->name}}

                        @if($website->domain_type==='subdomain')
                            <a href="javascript:void(0);" data-domain="{{$website->primaryDomain->subdomain}}"
                               data-id="{{$website->primaryDomain->id}}"
                               class="float-right underline custom_domain_edit">Edit</a>
                        @endif
                    </td>
                    <td>{{$website->primaryDomain->created_at}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<hr>
<div class="mt-5">
    <h5 class="mt-3 font-weight-bold">Bizinabox-managed domains
    </h5>
    <a href="{{route('user.domain.search')}}" class="btn btn-primary float-right p-2 mb-2">Purchase New</a>
    <div class="table-responsive">
        <table class="table table-bordered ajaxTable datatable">
            <thead>
                <tr>
                    <th>Domain Name</th>
                    <th>Expires Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach(user()->domains()->whereNull('web_id')->get() as $domain)
                    <tr>
                        <td>{{$domain->name}}</td>
                        <td>{{$domain->expired_at}}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-sm btn-success setPrimary"  data-id="{{$domain->id}}" data-type="hosted">Set as Primary</a>
                        </td>
                    </tr>
                @endforeach
                @foreach(user()->domainCustoms()->whereNull('web_id')->get() as $domain)
                    <tr>
                        <td>
                            {{$domain->name}}
                            <a href="javascript:void(0);" data-domain="{{$domain->subdomain}}" data-id="{{$domain->id}}" class="float-right underline custom_domain_edit">Edit</a>
                        </td>
                        <td>Never Expires</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-sm btn-success setPrimary" data-id="{{$domain->id}}" data-type="subdomain">Set as Primary</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">
    <h5 class="font-weight-bold">Third Party domains
    </h5>
    <a href="javascript:void(0);" class="btn btn-primary float-right p-2 mb-2 connectDomain">Connect Existing Domain</a>
    <div class="table-responsive">
        <table class="table table-bordered ajaxTable datatable">
            <thead>
            <tr>
                <th>Domain Name</th>
                <th>Date added</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @forelse(user()->domainConnects()->whereNull('web_id')->get() as $domain)
                    <tr>
                        <td>{{$domain->name}}</td>
                        <td>{{$domain->created_at}}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-sm btn-success setPrimary" data-id="{{$domain->id}}" data-type="connected">Set as Primary</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">None</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
