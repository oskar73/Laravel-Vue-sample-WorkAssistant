<table class="tw-table-auto tw-border-separate tw-border-spacing-0 tw-w-full tw-text-center">
    <thead>
    <tr>
        <th class="tw-border tw-border-x tw-border-gray-300">#</th>
        <th class="tw-border tw-border-x tw-border-gray-300">Name</th>
        <th class="tw-border tw-border-x tw-border-gray-300">Delete</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($items as $key => $item)
        <tr>
            <td class="tw-border tw-border-x tw-border-gray-300 tw-p-2">{{$key+1}}</td>
            <td class="tw-border tw-border-x tw-border-gray-300 tw-p-2">{{$item->name}}</td>
            <td class="tw-border tw-border-x tw-border-gray-300 tw-p-2">
                <button data-module="{{ $item->slug }}" class="tw-text-red-400 tw-p-2 hover:tw-text-red-700 del_module_btn">
                        <span>
                            <i class="fa fa-trash"></i>
                        </span>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
