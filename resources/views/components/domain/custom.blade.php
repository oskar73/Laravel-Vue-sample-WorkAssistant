<div class="table-responsive">
    <table class="table table-bordered ajaxTable datatable">
        <thead>
            <tr>
                <th>Choose</th>
                <th>Domain Name</th>
                <th>Connected Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customs as $custom)
                <tr>
                    <td>
                        <label for="connected_domain_{{$custom->id}}" class="h-cursor mb-0">
                            <input type="radio" name="connected_domain" id="connected_domain_{{$custom->id}}" value="{{$custom->id}}" data-domain="{{$custom->name}}">
                        </label>
                    </td>
                    <td>
                        <label for="connected_domain_{{$custom->id}}" class="h-cursor mb-0">
                            {{$custom->name}}
                        </label>
                    </td>
                    <td>
                        <label for="connected_domain_{{$custom->id}}" class="h-cursor mb-0">
                            {{$custom->created_at}}
                        </label>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">None</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
