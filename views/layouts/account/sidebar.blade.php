<div class="page-sidebar">
    <div class="card overflow-hidden">
        <div @class([
            'card-content border-b border-neutral-200',
            'hidden md:block' => ! request()->routeIs('client.account.index'),
        ])>
            <div class="flex items-center mb-5">
                <div class="flex items-center">
                    <img src="{{ template_asset('/assets/images/user.png') }}" class="w-14 h-14 shadow rounded-full">

                    <div class="mr-3">
                        <div class="font-semibold" data-role="users-full-name">{{ auth()->user()->full_name ?: 'نام کاربری' }}</div>
                        <div class="text-neutral-500 mt-1">{{ auth()->user()->number }}</div>
                    </div>
                </div>

                <button class="btn mr-auto" id="edit-name">
                    <i class="fi fi-rr-edit"></i>
                </button>
            </div>

            <ul class="space-y-3">
                <li class="flex items-center justify-between">
                    <div>کلاب</div>
                    <div>{{ number_format(auth()->user()->points) }} امتیاز</div>
                </li>
                @if (is_wallet_feature_active())
                    <li>
                        <a class="flex items-center justify-between" href="{{ route('client.account.wallet.index') }}">
                            <div>کیف پول</div>
                            <div>{{ number_format(auth()->user()->wallet->balance) }} تومان</div>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="navigation">
            <a href="{{ route('client.account.index') }}" class="item" data-active="{{ as_string(request()->routeIs('client.account.index')) }}">
                <i class="fi fi-rr-home"></i>
                <span class="label">داشبورد</span>
            </a>
            <a href="{{ route('client.account.orders.index') }}" class="item" data-active="{{ as_string(request()->routeIs('client.account.orders.*')) }}">
                <i class="fi fi-rr-shopping-bag"></i>
                <span class="label">سفارش‌ها</span>
            </a>
            <a href="{{ route('client.account.addresses.index') }}" class="item" data-active="{{ as_string(request()->routeIs('client.account.addresses.*')) }}">
                <i class="fi fi-rr-address-book"></i>
                <span class="label">آدرس‌ها</span>
            </a>
            <a href="{{ route('client.account.notifications.index') }}" class="item" data-active="{{ as_string(request()->routeIs('client.account.notifications.*')) }}">
                <i class="fi fi-rr-bell"></i>
                <span class="label">اعلان‌ها</span>

                @if ($unreadNotifications)
                    <span class="badge badge-danger">{{ $unreadNotifications }}</span>
                @endif
            </a>
            @feature('favorites')
                <a href="{{ route('client.account.favorites.index') }}" class="item" data-active="{{ as_string(request()->routeIs('client.account.favorites.*')) }}">
                    <i class="fi fi-rr-heart"></i>
                    <span class="label">لایک‌ها</span>
                </a>
            @endfeature
        </div>
    </div>
</div>

<template id="edit-name-template">
    <div id="edit-name-form" class="form">
        <div class="form-row">
            <div class="form-control" data-form-field-id="first_name">
                <label for="first_name">نام</label>
                <input id="first_name" type="text" name="first_name" class="default">
            </div>

            <div class="form-control" data-form-field-id="last_name">
                <label for="last_name">نام خانوادگی</label>
                <input id="last_name" type="text" name="last_name" class="default">
            </div>
        </div>
    </div>
</template>

@push('bottom-scripts')
    <script>
        $('#edit-name').on('click', function () {
            const container = document.createElement('div');

            container.innerHTML = document.getElementById('edit-name-template').innerHTML;

            // Set initial data
            $(container).find('#first_name').val(window.user.first_name);
            $(container).find('#last_name').val(window.user.last_name);

            modal.create({
                title: 'ویرایش نام',
                size: 'semi-large',
                body: container,
                buttons: [{
                    id: 'edit-name-btn',
                    label: 'ویرایش',
                    className: 'btn btn-success btn-sm',
                    onClick: async function () {
                        const btn = $('#edit-name-btn')
                        const form = new FormManager('#edit-name-form');

                        btn.prop('disabled', true);
                        form.clearValidationErrors();

                        try {
                            const { data } = await axios.patch('{{ route('client.account.ajax.user.update-name') }}', {
                                first_name: $('#first_name').val(),
                                last_name: $('#last_name').val(),
                            });

                            toast.success('نام و نام خانوادگی با موفقیت تغییر یافت.');
                            modal.dismissAll();

                            $('[data-role="users-full-name"]').text(data.data.full_name);

                            // Update data
                            window.user.first_name = data.data.first_name;
                            window.user.last_name = data.data.last_name;
                            window.user.full_name = data.data.full_name;
                        } catch (e) {
                            if (e.request && e.request.status === 422) {
                                form.displayErrors(e.response.data.errors);
                            }

                            form.handleRequestError(e);
                        }

                        btn.prop('disabled', false);
                    },
                }],
            });
        });
    </script>
@endpush
