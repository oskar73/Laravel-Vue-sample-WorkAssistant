<link href="{{asset('assets/css/global/pagination.css')}}" rel="stylesheet"/>

<div class="pagination-root">
    <select class="form-control pagination-per-page" x-model="pagination.perPage">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>

    <div class="pagination-container">
        <div class="pagination-item" @click="pagination.current = 1" :class="{ disabled: pagination.current === 1 }">
            <i class="fas fa-angle-double-left"></i>
        </div>
        <div class="pagination-item" @click="pagination.current = Math.max(--pagination.current, 1)" :class="{ disabled: pagination.current === 1  }">
            <i class="fa fa-chevron-left"></i>
        </div>
        <template x-for="(_, index) in Array.from({ length: pagination.total })">
            <div class="pagination-item" @click="pagination.current = index + 1"  :class="{active: index + 1 === pagination.current}">
                <span x-text="index + 1"></span>
            </div>
        </template>
        <div class="pagination-item" @click="pagination.current = Math.min(++pagination.current, pagination.total)"  :class="{ disabled: pagination.current === pagination.total }">
            <i class="fa fa-chevron-right"></i>
        </div>
        <div class="pagination-item" @click="pagination.current = pagination.total"  :class="{ disabled: pagination.current === pagination.total }">
            <i class="fas fa-angle-double-right"></i>
        </div>
    </div>
</div>
