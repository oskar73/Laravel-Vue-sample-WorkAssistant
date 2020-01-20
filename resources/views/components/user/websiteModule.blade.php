<div class="table-responsive module_container_area custom-scroll-h">
    <table class="table table-hover table-bordered table-item-center table-item-padding-0">
        <thead>
            <tr>
                <td class="p-2">Action</td>
                <td class="p-2">Featured</td>
                <td class="p-2">Module Name</td>
                <td class="p-2">Learn more</td>
            </tr>
        </thead>
        <tbody>
            @forelse($modules as $key=>$module)
                <tr>
                    <td>
                        <a href="#" class="btn btn-outline-success btn-sm m-1 py-1 module_select_btn"
                           data-id="{{$module->id}}"
                           data-featured="{{$module->featured}}"
                           data-name="{{$module->name}}"
                           data-slug="{{$module->slug}}"
                        >Select</a>
                    </td>
                    <td>
                        @if($module->featured)
                            <span class="c-badge c-badge-success">F</span>
                        @endif
                    </td>
                    <td>
                        {{$module->name}} @if($module->new) <span class="c-badge c-badge-danger h-default" title="New">N</span> @endif
                    </td>
                    <td><a href="#" class="m-1 underline learn_more_btn" data-id="{{$module->id}}">Learn more</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-2">No items</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
