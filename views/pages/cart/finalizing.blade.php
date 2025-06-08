@template_extends('views.layouts.default')

@title('ثبت سفارش', false)
@php
    $walletBalance = auth()->user()->wallet->balance ?? 0;
@endphp

@section('content')
    <div class="main">
        <div class="container">
            <div data-role="loading">
                <x-tpl::loading />
            </div>
            <form id="finalizing-form" action="{{ route('client.cart.finalizing.finalize') }}" method="POST" data-role="container" class="hidden">
                @csrf
                <input data-role="invoice-key" type="hidden" name="invoice_key" value="">

                <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row md:space-x-3 md:space-x-reverse">
                    <div class="flex-1 space-y-3">
                        @if (auth()->check() && feature('discount') && settings('cart')['discount'])
                            <!-- Apply discount -->
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title">کد تخفیف</h2>
                                    <div class="text-sm font-light mt-1">پس از اعمال کد تخفیف، درصورت هرگونه تغییر در سبد کالا، کد تخفیف حذف خواهد شد.</div>
                                </div>
                                <div class="card-content space-y-3">
                                    <div class="form" data-role="discount-form">
                                        <div class="form-control" data-form-field-id="code">
                                            <div class="flex">
                                                <input type="text" class="input default w-auto text-center ltr-direction uppercase flex-1 rounded-l-none">
                                                <button type="button" id="apply-discount" class="btn btn-light border border-neutral-300 rounded-r-none text-sm -mr-px">اعمال</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div data-role="discount-applied" class="text-green-600">کد تخفیف اعمال شده است.</div>
                                </div>
                            </div>
                        @endif

                        <!-- Address -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">آدرس</h2>
                            </div>
                            <div class="card-content">
                                @if ($addresses->isNotEmpty())
                                    <div class="space-y-3">
                                        @foreach ($addresses as $key => $address)
                                            <label class="flex border rounded-md p-3 cursor-pointer">
                                                <input type="radio" name="address_id" value="{{ $address->id }}" data-province="{{ $address->province_id }}" data-city="{{ $address->city_id }}" @checked($key === 0)>

                                                <div class="flex-1 mr-4">
                                                    <div>{{ $address->address }}</div>

                                                    <div class="flex items-center text-sm font-medium mt-2">
                                                        <i class="fi fi-rr-marker flex"></i>
                                                        <span class="mr-3">{{ $address->province->name }}، {{ $address->city->name }}</span>
                                                    </div>
                                                    <div class="flex items-center text-sm mt-1">
                                                        <i class="fi fi-rr-phone-flip flex"></i>
                                                        <span class="mr-3">{{ $address->is_self ? auth()->user()->number : $address->number }}</span>
                                                    </div>
                                                    <div class="flex items-center text-sm mt-1">
                                                        <i class="fi fi-rr-user flex"></i>
                                                        <span class="mr-3">{{ $address->is_self ? auth()->user()->full_name : $address->full_name }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach

                                        <label class="flex items-center bg-slate-100 rounded-md cursor-pointer px-3 py-2">
                                            <input type="radio" name="address_id" value="">
                                            <div class="flex-1 mr-4">آدرس جدید وارد می کنم</div>
                                        </label>
                                    </div>
                                @endif

                                <!-- Manual Address -->
                                <div data-role="manual-address" class="@if ($addresses->isNotEmpty()) hidden p-3 border rounded-md mt-3 @endif form">
                                    <div class="form-row">
                                        <div class="form-control" data-form-field-id="address.province_id">
                                            <label for="province_id">استان *</label>
                                            <select id="province_id" class="default" name="address[province_id]"></select>
                                        </div>

                                        <div class="form-control" data-form-field-id="address.city_id">
                                            <label for="city_id">شهر *</label>
                                            <select id="city_id" class="default" name="address[city_id]"></select>
                                        </div>

                                        <div class="form-control" data-form-field-id="address.postal_code">
                                            <label for="postal_code">کد پستی</label>
                                            <input id="postal_code" type="text" maxlength="10" name="address[postal_code]" class="default ltr-direction text-center">
                                        </div>
                                    </div>

                                    <div class="form-control" data-form-field-id="address.address">
                                        <label for="address">آدرس *</label>
                                        <input id="address" type="text" name="address[address]" class="default">
                                    </div>

                                    @if (auth()->check())
                                        <div class="form-group">
                                            <div>
                                                <label class="inline-flex items-center cursor-pointer select-none">
                                                    <input type="checkbox" id="is_self" name="address[is_self]" value="1">
                                                    <span class="mr-2 text-sm">گیرنده خودم هستم</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-row">
                                        <div class="form-control" data-form-field-id="address.first_name">
                                            <label for="first_name">نام *</label>
                                            <input id="first_name" type="text" name="address[first_name]" class="default">
                                        </div>

                                        <div class="form-control" data-form-field-id="address.last_name">
                                            <label for="last_name">نام خانوادگی *</label>
                                            <input id="last_name" type="text" name="address[last_name]" class="default">
                                        </div>

                                        <div class="form-control" data-form-field-id="address.number">
                                            <label for="number">شماره موبایل *</label>
                                            <input id="number" type="text" name="address[number]" class="default ltr-direction">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">توضیحات اضافی</h2>
                                <div class="text-sm font-light mt-1">لطفاً درصورت داشتن توضیحات اضافی درمورد سفارش و نحوه ارسال در فیلد زیر وارد کنید.</div>
                            </div>
                            <div class="card-content">
                                <textarea class="default min-h-28" name="additional_notes"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-80 space-y-3">
                        <!-- Courier -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">حمل و نقل</h2>
                            </div>
                            <div class="card-content">
                                @if (! $couriers->isEmpty())
                                    <div class="space-y-3">
                                        @foreach ($couriers as $courier)
                                            <label class="flex items-start cursor-pointer">
                                                <div class="pt-1">
                                                    <input type="radio" name="courier_id" value="{{ $courier->id }}" @checked($courier->is_default || $couriers->count() === 1)>
                                                </div>
                                                <div class="flex-1 ms-3">
                                                    <div class="flex justify-between">
                                                        <div class="text-sm font-medium">{{ $courier->name }}</div>
                                                        <div class="text-sm font-medium">{{ number_format($courier->cost) }} {{ productCurrency()->label() }}</div>
                                                    </div>
                                                    <div class="mt-1">
                                                        <div class="text-sm">{{ $courier->type->label() }}</div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-danger text-center">بدون پیک فعال</div>
                                @endif
                            </div>
                        </div>

                        <!-- Invoice -->
                        <div data-role="invoice" class="card">
                            <div class="card-content">
                                <div class="text-sm space-y-3">
                                    <div class="flex items-start justify-between">
                                        <div class="text-neutral-600">
                                            <span>جمع کل</span>
                                            <span class="mr-0.5">(<span data-role="cart-total-quantity"></span>)</span>
                                        </div>
                                        <div class="text-neutral-600">
                                            <span data-role="cart-total-base-price"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start justify-between">
                                        <div class="text-neutral-600">هزینه ارسال</div>
                                        <div class="text-neutral-600">
                                            <span data-role="cart-shipping-cost"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start justify-between">
                                        <div class="text-neutral-600">تخفیف</div>
                                        <div class="text-neutral-600">
                                            <span data-role="cart-invoice-discount-amount"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start justify-between">
                                        <div class="text-neutral-600">مالیات</div>
                                        <div class="text-neutral-600">
                                            <span data-role="cart-total-tax-amount"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start justify-between font-medium">
                                        <div>قابل پرداخت</div>
                                        <div>
                                            <span data-role="cart-payable-amount"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">شیوه پرداخت</h2>
                            </div>

                            <div class="card-content">
                                @if (! $paymentMethods->isEmpty())
                                    <div class="space-y-3">
                                        @foreach ($paymentMethods as $paymentMethod)
                                            <label class="flex items-center" data-role="payment-method" data-id="{{ $paymentMethod->id }}">
                                                <input type="radio" name="payment_method" value="{{ $paymentMethod->id }}" @checked($paymentMethod->is_default)>
                                                <span class="mr-3">{{ $paymentMethod->title }}</span>

                                                @if ($paymentMethod->id === 'wallet')
                                                    <span class="mr-auto">{{ number_format($walletBalance) }} تومان</span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-danger text-center">بدون درگاه پرداخت فعال</div>
                                @endif
                            </div>
                        </div>

                        <button class="w-full block btn btn-success text-center text-sm" data-role="cart-finalize">ادامه سفارش</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        $('#apply-discount').on('click', async function () {
            const form = new FormManager('[data-role="discount-form"]');
            const $form = $('[data-role="discount-form"]');

            form.clearValidationErrors();
            $form.attr('data-disabled', true);

            try {
                await axios.patch('{{ route('client.cart.finalizing.ajax.apply-discount') }}', {
                    code: $form.find('input[type="text"]').val(),
                });

                $form.remove();
                $('[data-role="discount-applied"]').show();
            } catch (e) {
                form.handleRequestError(e, {
                    404: () => toast.error('کد تخفیف مورد نظر یافت نشد.'),
                });
            }

            $form.removeAttr('data-disabled');
        });

        async function getStats(province_id, city_id) {
            $('[data-role="cart-finalize"]').prop('disabled', true);
            $('[data-role="invoice"]').attr('data-disabled', true);

            try {
                const { data } = await axios.get(API.cart.details, {
                    params: {
                        province_id: province_id,
                        city_id: city_id,
                        courier_id: getSelectedCourier(),
                    },
                });

                cartManager.updateDetails(data);

                $('[data-role="loading"]').remove();
                $('[data-role="container"]').removeClass('hidden');

                if (data.invoice.payable_amount > {{ $walletBalance }}) {
                    $('[data-role="payment-method"][data-id="wallet"]').attr('data-disabled', true);
                    $('[name="payment_method"][value="wallet"]').prop('checked', false);
                } else {
                    $('[data-role="payment-method"][data-id="wallet"]').removeAttr('data-disabled');
                }

                // Discount
                $('[data-role="discount-applied"]').toggle(data.invoice.is_discount_applied);
                data.invoice.is_discount_applied && $('[data-role="discount-form"]').remove();
            } catch {}

            $('[data-role="invoice"]').attr('data-disabled', false);
        }

        function initProvince() {
            const $selector = $('#province_id');
            const provinces = locationProvider.all().map(element => {
                return '<option value="'+ element.id +'">'+ element.name +'</option>';
            });

            $selector.html(provinces.join(''));
            $selector.selectbox();

            // Event
            $selector.on('change', function () {
                initCity();
                getStatsWithParams();
            });
        }

        function initCity() {
            const $selector = $('#city_id');
            const province = $('#province_id').val();

            if (province) {
                const cities = locationProvider.province(province).cities.map(element => {
                    return '<option value="'+ element.id +'">'+ element.name +'</option>';
                });

                $selector.html(cities);
            }

            $selector.selectbox();

            function onChangeCity() {
                getStatsWithParams();
            }

            // Event
            $selector.off('change', onChangeCity);
            $selector.on('change', onChangeCity);
        }

        function initManualAddress() {
            $('[name="address_id"]').on('change', function () {
                if ($(this).val() == 0) {
                    $('[data-role="manual-address"]').removeClass('hidden');
                } else {
                    $('[data-role="manual-address"]').addClass('hidden');
                }

                getStatsWithParams();
            });

            $('#is_self').on('click', function () {
                const isChecked = $(this).prop('checked');

                if (isChecked) {
                    $('#first_name, #last_name').prop('readonly', user.full_name.length > 0);
                    $('#number').prop('readonly', true);

                    $('#first_name').val(user.first_name);
                    $('#last_name').val(user.last_name);
                    $('#number').val(user.number);
                } else {
                    $('#first_name, #last_name, #number').prop('readonly', false).val('');
                }
            });
        }

        function getSelectedProvince() {
            const $address = $('[name="address_id"]:checked')

            if ($address.val() == 0) {
                return $('#province_id').val();
            }

            return $address.data('province');
        }

        function getSelectedCity() {
            const $address = $('[name="address_id"]:checked')

            if ($address.val() == 0) {
                return $('#city_id').val();
            }

            return $address.data('city');
        }

        function getSelectedCourier() {
            return $('[name="courier_id"]:checked').val();
        }

        function getStatsWithParams() {
            const province_id = getSelectedProvince();
            const city_id = getSelectedCity();

            getStats(province_id, city_id);
        }

        async function validateForm($form) {
            $form.attr('data-disabled', true);

            const form = new FormManager('#finalizing-form');

            form.clearValidationErrors();

            try {
                await axios.post('{{ route('client.cart.finalizing.ajax.validate-form') }}', new FormData(form.form.get(0)));

                toast.dismissAll();

                $form.get(0).submit();
            } catch (e) {
                form.handleRequestError(e);
                $form.attr('data-disabled', false);
            }
        }

        function init () {
            getStats();

            initProvince();
            initCity();
            initManualAddress();

            $('#finalizing-form').submit(function () {
                validateForm(
                    $(this)
                );

                return false;
            });
        }

        init();
    </script>
@endpush
