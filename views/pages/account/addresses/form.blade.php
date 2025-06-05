@template_extends('views.layouts.account')

@php
    $address ??= null;

    if ($address) {
        $title = 'ویرایش آدرس';
        $buttonText = 'ویرایش';
        $formAction = route('client.account.addresses.edit', $address);
    } else {
        $title = 'افزودن آدرس';
        $buttonText = 'افزودن';
        $formAction = route('client.account.addresses.create');
    }
@endphp
@title($title, false)

@section('page-content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">{{ $title }}</span>
        </div>
        <form action="{{ $formAction }}" method="POST" class="card-content form">
            @csrf
            @if ($address)
                @method('PUT')
            @endif

            <div class="form-row mt-0">
                <div class="form-control" data-danger="{{ as_string($errors->has('province_id')) }}">
                    <label for="province_id">استان *</label>
                    <select id="province_id" class="default" name="province_id"></select>
                    <x-input-error :messages="$errors->get('province_id')" class="mt-2" />
                </div>

                <div class="form-control" data-danger="{{ as_string($errors->has('city_id')) }}">
                    <label for="city_id">شهر *</label>
                    <select id="city_id" class="default" name="city_id"></select>
                    <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                </div>

                <div class="form-control" data-danger="{{ as_string($errors->has('postal_code')) }}">
                    <label for="postal_code">کد پستی</label>
                    <input id="postal_code" type="text" maxlength="10" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? null) }}" class="default ltr-direction text-center">
                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                </div>
            </div>

            <div class="form-control" data-danger="{{ as_string($errors->has('address')) }}">
                <label for="address">آدرس *</label>
                <input id="address" type="text" name="address" value="{{ old('address', $address->address ?? null) }}" class="default">
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="form-group">
                <label class="inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" id="is_self" name="is_self" value="1" @checked(
                        $address ? ($errors->any() ? old('is_self') : $address->is_self) : old('is_self')
                    )>
                    <span class="mr-2 text-sm">گیرنده خودم هستم</span>
                </label>
            </div>

            <div class="form-row">
                <div class="form-control" data-danger="{{ as_string($errors->has('first_name')) }}">
                    <label for="first_name">نام *</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $address->first_name ?? null) }}" class="default">
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <div class="form-control" data-danger="{{ as_string($errors->has('last_name')) }}">
                    <label for="last_name">نام خانوادگی *</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $address->last_name ?? null) }}" class="default">
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <div class="form-control" data-danger="{{ as_string($errors->has('number')) }}">
                    <label for="number">شماره موبایل *</label>
                    <input id="number" type="text" name="number" value="{{ old('number', $address->number ?? null) }}" class="default ltr-direction text-center">
                    <x-input-error :messages="$errors->get('number')" class="mt-2" />
                </div>
            </div>

            <button class="btn btn-success mt-5" data-click-once>{{ $buttonText }}</button>
        </form>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        function populateAddressSelf(isChecked) {
            if (isChecked) {
                $('#first_name, #last_name').prop('readonly', window.user.full_name.length > 0);

                $('#first_name').val(window.user.first_name);
                $('#last_name').val(window.user.last_name);
                $('#number').prop('readonly', true).val(window.user.number);
            } else {
                $('#first_name, #last_name, #number').prop('readonly', false).val('');
            }
        }

        $('#is_self').on('click', function () {
            populateAddressSelf($(this).prop('checked'));
        });

        populateAddressSelf($('#is_self').prop('checked'));

        function initProvince(initialId) {
            const $selector = $('#province_id');
            const provinces = locationProvider.all().map(element => {
                return '<option value="'+ element.id +'" '+ (initialId == element.id ? 'selected' : '') +'>'+ element.name +'</option>';
            });

            $selector.html(provinces.join(''));
            $selector.selectbox();

            // Event
            $selector.on('change', function () {
                initCity();
            });
        }

        function initCity(initialId) {
            const $selector = $('#city_id');
            const province = $('#province_id').val();

            if (province) {
                const cities = locationProvider.province(province).cities.map(element => {
                    return '<option value="'+ element.id +'" '+ (initialId == element.id ? 'selected' : '') +'>'+ element.name +'</option>';
                });

                $selector.html(cities);
            }

            $selector.selectbox();
        }

        initProvince({{ old('province_id', $address->province_id ?? null) }});
        initCity({{ old('city_id', $address->city_id ?? null) }});
    </script>
@endpush
