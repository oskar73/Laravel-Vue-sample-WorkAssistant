<table class="tw-table-auto tw-border-separate tw-border-spacing-0 tw-w-full tw-text-center" id="dataTable">
    <thead>
    <tr>
        <th class="tw-border tw-border-x tw-border-gray-300 tw-text-center dt-head-center">#</th>
        <th class="tw-border tw-border-x tw-border-gray-300 tw-text-center dt-head-center">Name</th>
        <th class="tw-border tw-border-x tw-border-gray-300 tw-text-center dt-head-center">Add</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($items as $key => $item)
        <tr>
            <td class="tw-border tw-border-x tw-border-gray-300 tw-p-2 tw-text-center">{{$key+1}}</td>
            <td class="tw-border tw-border-x tw-border-gray-300 tw-p-2 tw-text-center">{{$item->name}}</td>
            <td class="tw-border tw-border-x tw-border-gray-300 tw-p-2 tw-text-center">
                <button data-id="{{ $item->id }}" data-slug="{{ $item->slug }}"
                        class="tw-text-green-700 tw-p-2 hover:tw-text-green-400 add_module_btn choose_module_btn">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                    </span>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
