<div class="product-tabs">
    <ul>
        @php
            $activeTab = match (true) {
                ! $product->attributes->isEmpty() => 'attributes',
                ! empty($product->content->full_description) => 'full_description',
                ! empty($product->content->sizing) => 'sizing',

                default => null,
            };
        @endphp

        @if (! $product->attributes->isEmpty())
            <li data-tab="attributes" data-active="{{ as_string($activeTab === 'attributes') }}">مشخصات</li>
        @endif

        @if ($product->content->full_description)
            <li data-tab="full_description" data-active="{{ as_string($activeTab === 'full_description') }}">توضیحات تکمیلی</li>
        @endif

        @if ($product->content->sizing)
            <li data-tab="sizing" data-active="{{ as_string($activeTab === 'sizing') }}">سایزبندی</li>
        @endif
    </ul>

    <div class="tab-sections">
        <!-- Attributes -->
        @if (! $product->attributes->isEmpty())
            <div class="tab-section" data-id="attributes" data-active="{{ as_string($activeTab === 'attributes') }}">
                <ul class="divide-y">
                    @foreach ($product->merged_attributes as $attribute)
                        <li class="flex">
                            <div class="text-neutral-500 w-52 px-3 py-2">{{ is_string($attribute['attribute']) ? $attribute['attribute'] : $attribute['attribute']->name }}</div>
                            <div class="px-3 py-2 space-y-2">
                                @foreach ($attribute['items'] as $item)
                                    <div>{{ is_string($item) ? $item : $item->name }}</div>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Full Description -->
        @if ($product->content->full_description)
            <div class="tab-section leading-[29px] [&>p]:mt-2 [&>p:first-child]:mt-0" data-id="full_description" data-active="{{ as_string($activeTab === 'full_description') }}">
                {!! $product->content->full_description !!}
            </div>
        @endif

        <!-- Sizing -->
        @if ($product->content->sizing)
            <div class="tab-section overflow-x-auto" data-id="sizing" data-active="{{ as_string($activeTab === 'sizing') }}">
                <table class="sizing-table">
                    <thead>
                        <tr>
                            @foreach ($product->content->sizing[0] as $th)
                                <th>{{ $th }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @for ($i = 1; $i < count($product->content->sizing); $i++)
                            <tr>
                                @foreach ($product->content->sizing[$i] as $index => $td)
                                    @if ($index === 0)
                                        <th>{{ $td }}</th>
                                    @else
                                        <td>{{ $td }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
