<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="text-right">
            <a href="#/renew" class="btn btn-info tab-link mb-1">Back</a>
        </div>
        <div class="confirm_detail">
            <h4>Domain Name: <b>{{$domain->name}}</b> </h4><br>
            <h4>Expired At: <b>{{$domain->expired_at}}</b> </h4><br>
            <h4>Renewal Duration: <b>{{$duration}} Years</b></h4><br>

            <h4>Total Price: <b>${{$price->sumPrice?? 0}}</b></h4><br>

            <button type="button" class="btn btn-success tw-bg-green-600 renewNowBtn">Renew Now</button>
        </div>
    </div>
</div>
