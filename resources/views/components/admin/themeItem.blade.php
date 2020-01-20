<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{ $selector }}">
        <thead>
            <tr>
                <th class="no-sort">
                    <input name="select_all" class="selectAll checkbox" value="1" type="checkbox"
                        data-area="{{ $selector }}">
                </th>
                <th>
                    Name
                </th>
                <th>
                    Category
                </th>
                <th>
                    Status
                </th>
                <th>
                    Main Palette
                </th>
                <th class="no-sort">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($themes as $key => $theme)
                <tr>
                    <td><input type="checkbox" class="checkbox" data-id="{{ $theme->id }}"></td>
                    <td>
                        {{ $theme->name }}
                    </td>
                    <td>
                        {{ $theme->category->name }}
                    </td>
                    <td>
                        @if ($theme->status === 1)
                            <span class="c-badge c-badge-success hover-handle">Active</span>
                            <a href="javascript:void(0);"
                                class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne"
                                data-action="inactive">Inactive?</a>
                        @else
                            <span class="c-badge c-badge-danger hover-handle">InActive</span>
                            <a href="javascript:void(0);"
                                class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne"
                                data-action="active">Active?</a>
                        @endif
                    </td>
                    <td>
                        @php
                            $mainPalette = collect($theme->data['palettes'])->where('appliedTo', 'website')->first()
                        @endphp
                        <div class="tw-h-8 tw-grid tw-grid-cols-6">
                            @foreach($mainPalette['colors'] as $key => $color)
                                @if($key !== 'primaryColor')
                                    <div class="tw-h-full" style="background-color: {{$color}}"></div>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-info btn-sm m-1 p-2 m-btn m-btn--icon palettes" data-id="{{ $theme->id }}" data-palettes="{{ json_encode($theme->data['palettes']) }}">
                            <span>View All Palettes</span>
                        </a>
                        <a href="javascript:void(0);"
                            class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne"
                            data-action="delete">
                            <span>
                                <span>Delete</span>
                            </span>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
