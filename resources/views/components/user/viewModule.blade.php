<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Module Detail</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">X</span>
    </button>
</div>
<div class="modal-body">
    <div class="tw-flex tw-gap-5 tw-p-2">
        <div class="tw-w-60" style="--ratio: 1.5">
            <div>
                <figure data-href="{{$item->getFirstMediaUrl("thumbnail")}}" class="w-100 progressive replace m-0">
                    <img src="{{$item->getFirstMediaUrl("thumbnail","thumb")}}" alt="{{$item->title}}" class="preview img-full" />
                </figure>
            </div>
        </div>
        
        <table class="tw-table-auto tw-border-separate tw-border-spacing-0 tw-w-full">
            <tbody>
                <tr>
                    <th class="tw-border-t tw-border-gray-300 tw-px-2">Name</th>
                    <td class="tw-text-right tw-border-t tw-border-gray-300 tw-px-2">{{ $item->name }}</td>
                </tr>
                <tr>
                    <th class="tw-border-t tw-border-gray-300 tw-px-2">Description</th>
                    <td class="tw-text-right tw-border-t tw-border-gray-300 tw-px-2">{{ $item->description }}</td>
                </tr>
                <tr>
                    <th class="tw-border-t tw-border-gray-300 tw-px-2">Price</th>
                    <td class="tw-text-right tw-border-t tw-border-gray-300 tw-px-2">
                        @if($item->standardPrice->slashed_price!==null)
                            <span class="slashed_price_text ml-1 font-size16">
                                ${{formatNumber($item->standardPrice->slashed_price)}}
                            </span>
                        @endif
                        ${{formatNumber($item->standardPrice->price)}}
                        @if($item->standardPrice->recurrent) / {{periodName($item->standardPrice->period, $item->standardPrice->period_unit)}} @endif
                    </td>
                </tr>
                <tr>
                    <th class="tw-border-t tw-border-gray-300 tw-px-2">Category</th>
                    <td class="tw-text-right tw-border-t tw-border-gray-300 tw-px-2">{{ $item->category->name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>