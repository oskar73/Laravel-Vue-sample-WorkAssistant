<div class="row">
    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="firstName">First Name
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[1]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="1" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="firstName" name="firstName"
                placeholder="First Name" required>
            <div class="form-control-feedback error-firstName"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="lastName">Last Name
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[2]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="2" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="lastName" name="lastName"
                placeholder="Last Name" required>
            <div class="form-control-feedback error-lastName"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="email">Email Address
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[3]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="3" --}}
                {{--                ></i> --}}
            </label>
            <input type="email" class="form-control m-input m-input--square" id="email" name="email"
                placeholder="Email Address" required>
            <div class="form-control-feedback error-email"></div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group m-form__group">
            <label for="address1">Address Line 1
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[4]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="4" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="address1" name="address1"
                placeholder="Address Line 1" required>
            <div class="form-control-feedback error-address1"></div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group m-form__group">
            <label for="address2">Address Line 2
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[5]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="5" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="address2" name="address2"
                placeholder="Address Line 2">
            <div class="form-control-feedback error-address2"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="city">City
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[6]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="6" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="city" name="city"
                placeholder="City" required>
            <div class="form-control-feedback error-city"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="state">State / Province
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[7]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="7" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="state" name="state"
                placeholder="State / Province" required>
            <div class="form-control-feedback error-state"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="postalCode">Postal Code
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[8]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="8" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="postalCode" name="postalCode"
                placeholder="Postal Code" required>
            <div class="form-control-feedback error-postalCode"></div>
        </div>
    </div>

    @php
        $countries = App\Models\Country::query();
    @endphp

    <div class="col-md-6">
        <div class="form-group m-form__group">
            <label for="country">Country
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[9]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="9" --}}
                {{--                ></i> --}}
            </label> <br>
            <select name="country" id="country" class="country selectpicker" data-live-search="true" data-width="100%"
                required>
                @foreach ($countries->get() as $country)
                    <option value="{{ $country->iso }}" @if ($country->iso == 'US') selected @endif>
                        {{ $country->nicename }}</option>
                @endforeach
            </select>
            <div class="form-control-feedback error-country"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group m-form__group">
            <label for="phoneCode">Phone Number
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[10]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="10" --}}
                {{--                ></i> --}}
            </label>
            <select name="phoneCode" id="phoneCode" class="phoneCode selectpicker" data-live-search="true"
                data-width="100%" required>
                @foreach ($countries->distinct('phonecode')->get('phonecode') as $country)
                    <option value="{{ $country->phonecode }}" @if ($country->phonecode == 1) selected @endif>
                        +{{ $country->phonecode }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group m-form__group">
            <label for="phoneNumber">
                {{--                <i class="la la-info-circle tooltip_icon" --}}
                {{--                   title='{{$tooltip[11]}}' --}}
                {{--                   data-page="{{$view_name}}" --}}
                {{--                   data-id="11" --}}
                {{--                ></i> --}}
            </label>
            <input type="text" class="form-control m-input m-input--square" id="phoneNumber" name="phoneNumber"
                placeholder="Phone Number" required>
            <div class="form-control-feedback error-phoneNumber"></div>
        </div>
    </div>
</div>
