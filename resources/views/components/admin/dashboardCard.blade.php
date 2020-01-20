<div class="row mb-3">
    <div class="col-sm-6 col-md-3 mb-2">
        <div class="card card-body"
             style="background-color: rgb(108, 178, 235); color: rgb(255, 255, 255);box-shadow:1px 5px 8px #3333;">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{$totalUsers > 0 ? $totalUsers - 1 : $totalUsers}}</h3>
                    <small class="text-uppercase font-size-xs">Registered Users</small>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="fa fa-users fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-2">
        <div class="card card-body"
             style="background-color: rgb(23, 197, 203); color: rgb(255, 255, 255);box-shadow:1px 5px 8px #3333;">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{$verifiedUsers > 0 ? $verifiedUsers - 1 : $verifiedUsers }}</h3>
                    <small class="text-uppercase font-size-xs">Verified Users</small>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="fa fa-user-check fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-2">
        <div class="card card-body"
             style="background-color: rgb(81, 216, 138); color: rgb(255, 255, 255);box-shadow:1px 5px 8px #3333;">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{$todayUsers}}</h3>
                    <small class="text-uppercase font-size-xs">Today New Users</small>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="fa fa-user-plus fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-2">
        <div class="card card-body"
             style="background-color: rgb(250, 173, 99); color: rgb(255, 255, 255);box-shadow:1px 5px 8px #3333;">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{$totalSubscribers}}</h3>
                    <small class="text-uppercase font-size-xs">Total Verified Subscribers</small>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="fa fa-users fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>
