@php
    $categories = get_category_tree();
@endphp

<div id="categories" class="navbar-indicator-trigger" data-is-hovering="false">
    <button class="navbar-item navbar-category-item">
        <i class="fi fi-rr-menu-burger text-lg"></i>
        <span class="label">دسته‌بندی کالاها</span>
    </button>

    <div class="navbar-category-dropdown flex hidden">
        <div class="category-parents">
            @foreach ($categories as $index => $category)
                <div class="item" data-id="{{ $category->id }}" data-is-active="{{ as_string($index === 0) }}">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>

        <div class="category-content">
            @foreach ($categories as $index => $category)
                <div class="category-children" data-children-of="{{ $category->id }}" data-is-visible="{{ as_string($index === 0) }}">
                    <div class="mb-5">
                        <a href="{{ route('client.category', $category) }}" class="default flex items-center">
                            <span>همه محصولات {{ $category->name }}</span>
                            <span class="fi fi-rr-angle-small-left flex mr-1"></span>
                        </a>
                    </div>

                    {{ call_template_func('render_categories', $category) }}
                </div>
            @endforeach
        </div>
    </div>
</div>
