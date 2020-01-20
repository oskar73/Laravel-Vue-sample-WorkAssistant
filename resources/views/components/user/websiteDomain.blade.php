<div class="table-responsive">
    <table class="table table-bordered ajaxTable datatable">
        <thead>
            <tr>
                <th>Choose</th>
                <th>Domain Name</th>
                <th>Registered Date</th>
                <th>Expire Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($domains as $domain)
                <tr>
                    <td>
                        <label for="hosted_domain_{{$domain->id}}" class="h-cursor mb-0">
                            <input type="radio" name="hosted_domain" id="hosted_domain_{{$domain->id}}" value="{{$domain->id}}" data-domain="{{$domain->name}}">
                        </label>
                    </td>
                    <td>
                        <label for="hosted_domain_{{$domain->id}}" class="h-cursor mb-0">
                            {{$domain->name}}
                        </label>
                    </td>
                    <td>
                        <label for="hosted_domain_{{$domain->id}}" class="h-cursor mb-0">
                            {{$domain->created_at}}
                        </label>
                    </td>
                    <td>
                        <label for="hosted_domain_{{$domain->id}}" class="h-cursor mb-0">
                            {{$domain->expired_at}}
                        </label>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">None</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
