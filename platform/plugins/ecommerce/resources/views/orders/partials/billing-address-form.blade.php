<div class="customer-billing-address-form">
    @php
        $oldSessionAddressId = null;
        $billingAddressSameAsShippingAddress = old('billing_address_same_as_shipping_address', Arr::get($sessionCheckoutData, 'billing_address_same_as_shipping_address', true));
    @endphp
    <div class="mb-3 form-group">
        <input
            name="billing_address_same_as_shipping_address"
            type="hidden"
            value="0"
        >
        @if ($isShowAddressForm)
            <input
                id="billing_address_same_as_shipping_address"
                name="billing_address_same_as_shipping_address"
                type="checkbox"
                value="1"
                @checked ($billingAddressSameAsShippingAddress)
            >
            <label
                class="form-label"
                for="billing_address_same_as_shipping_address"
            >{{ __('Same as shipping information') }}</label>
        @elseif (auth('customer')->check() && $isAvailableAddress)
            <input
                name="billing_address_same_as_shipping_address"
                type="hidden"
                value="1"
            >
            @php
                $oldSessionAddressId = old('address.address_id', $sessionAddressId);
            @endphp
            <div class="select--arrow form-input-wrapper">
                <select
                    class="form-control"
                    id="billing_address_id"
                    name="address[address_id]"
                >
                    <option value="">{{ __('Select billing address...') }}</option>
                    @foreach ($addresses as $address)
                        <option
                            value="{{ $address->id }}"
                            @selected($oldSessionAddressId == $address->id)
                        >{{ $address->full_address }}</option>
                    @endforeach
                </select>
                <x-core::icon name="ti ti-chevron-down" />
            </div>
            <br>
        @endif
    </div>

    <div
        class="billing-address-form-wrapper"
        @if (
            ($oldSessionAddressId && $oldSessionAddressId != 'new') ||
                ($isShowAddressForm && $billingAddressSameAsShippingAddress)) style="display: none" @endif
    >
        <div class="form-group mb-3 @error('billing_address.name') has-error @enderror">
            <div class="form-input-wrapper">
                <input
                    class="form-control"
                    id="billing-address-name"
                    name="billing_address[name]"
                    autocomplete="family-name"
                    type="text"
                    value="{{ old('billing_address.name', Arr::get($sessionCheckoutData, 'billing_address.name')) ?: (auth('customer')->check() ? auth('customer')->user()->name : null) }}"
                >
                <label for='billing-address-name'>{{ __('Full Name') }}</label>
            </div>
            {!! Form::error('billing_address.name', $errors) !!}
        </div>

        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="form-group  @error('billing_address.email') has-error @enderror">
                    <div class="form-input-wrapper">
                        <input
                            class="form-control"
                            id="billing-address-email"
                            name="billing_address[email]"
                            autocomplete="email"
                            type="email"
                            value="{{ old('billing_address.email', Arr::get($sessionCheckoutData, 'billing_address.email')) ?: (auth('customer')->check() ? auth('customer')->user()->email : null) }}"
                        >
                        <label for='billing-address-email'>{{ __('Email') }}</label>
                    </div>
                    {!! Form::error('billing_address.email', $errors) !!}
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="form-group  @error('billing_address.phone') has-error @enderror">
                    <div class="form-input-wrapper">
                        {!! Form::text(
                            'billing_address[phone]',
                            old('billing_address.phone', Arr::get($sessionCheckoutData, 'billing_address.phone')) ?:
                            (auth('customer')->check()
                                ? auth('customer')->user()->phone
                                : null),
                            ['id' => 'billing-address-phone', 'class' => 'form-control', 'autocomplete' => 'phone'],
                        ) !!}
                        <label>{{ __('Phone') }}</label>
                    </div>
                    {!! Form::error('billing_address.phone', $errors) !!}
                </div>
            </div>
        </div>

        <div class="form-group mb-3 @error('billing_address.country') has-error @enderror">
            @if (EcommerceHelper::isUsingInMultipleCountries())
                <div class="select--arrow form-input-wrapper">
                    <select
                        class="form-control"
                        id="billing-address-country"
                        name="billing_address[country]"
                        data-form-parent=".customer-billing-address-form"
                        data-type="country"
                        autocomplete="country"
                    >
                        @foreach (EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                            <option
                                value="{{ $countryCode }}"
                                @if (old('billing_address.country', Arr::get($sessionCheckoutData, 'billing_address.country')) == $countryCode) selected @endif
                            >{{ $countryName }}</option>
                        @endforeach
                    </select>
                    <x-core::icon name="ti ti-chevron-down" />
                    <label for='billing-address-country'>{{ __('Country') }}</label>
                </div>
            @else
                <input
                    id="billing-address-country"
                    name="billing_address[country]"
                    type="hidden"
                    value="{{ EcommerceHelper::getFirstCountryId() }}"
                >
            @endif
            {!! Form::error('billing_address.country', $errors) !!}
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group  @error('billing_address.city') has-error @enderror">
                    @if (EcommerceHelper::useCityFieldAsTextField())
                        <div class="form-input-wrapper">
                            <input
                                class="form-control"
                                id="billing-address-city"
                                name="billing_address[city]"
                                type="text"
                                autocomplete="city"
                                value="{{ old('billing_address.city', Arr::get($sessionCheckoutData, 'billing_address.city')) }}"
                            >
                            <label for='billing-address-city'>{{ __('City') }}</label>
                        </div>
                    @else
                        <div class="select--arrow form-input-wrapper">
                            <select
                                class="form-control"
                                id="billing-address-city"
                                name="billing_address[city]"
                                data-type="city"
                                autocomplete="city"
                                data-using-select2="false"
                                data-url="{{ route('ajax.cities-by-state') }}"
                            >
                                <option value="">{{ __('Select city...') }}</option>
                                @if (old('billing_address.state', Arr::get($sessionCheckoutData, 'billing_address.state')))
                                    @foreach (EcommerceHelper::getAvailableCitiesByState(old('billing_address.state', Arr::get($sessionCheckoutData, 'billing_address.state'))) as $cityId => $cityName)
                                        <option
                                            value="{{ $cityId }}"
                                            @if (old('billing_address.city', Arr::get($sessionCheckoutData, 'billing_address.city')) == $cityId) selected @endif
                                        >{{ $cityName }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <x-core::icon name="ti ti-chevron-down" />
                            <label for='billing-address-city'>{{ __('City') }}</label>
                        </div>
                    @endif
                    {!! Form::error('billing_address.city', $errors) !!}
                </div>
            </div>
        </div>

        <div class="form-group mb-3 @error('billing_address.address') has-error @enderror">
            <div class="form-input-wrapper">
                <input
                    class="form-control"
                    id="billing-address-address"
                    name="billing_address[address]"
                    autocomplete="address"
                    type="text"
                    value="{{ old('billing_address.address', Arr::get($sessionCheckoutData, 'billing_address.address')) }}"
                >
                <label for='billing-address-address'>{{ __('Address') }}</label>
            </div>
            {!! Form::error('billing_address.address', $errors) !!}
        </div>

        @if (EcommerceHelper::isZipCodeEnabled())
            <div class="form-group mb-3 @error('billing_address.zip_code') has-error @enderror">
                <div class="form-input-wrapper">
                    <input
                        class="form-control"
                        id="billing-address-zip-code"
                        name="billing_address[zip_code]"
                        autocomplete="postal-code"
                        type="text"
                        value="{{ old('billing_address.zip_code', Arr::get($sessionCheckoutData, 'billing_address.zip_code')) }}"
                    >
                    <label for='billing-address-zip-code'>{{ __('Zip code') }}</label>
                </div>
                {!! Form::error('billing_address.zip_code', $errors) !!}
            </div>
        @endif
    </div>
</div>
