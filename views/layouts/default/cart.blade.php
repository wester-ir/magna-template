<div class="relative">
    <a href="{{ route('client.cart.index') }}" rel="nofollow">
        <i class="fi fi-rr-basket-shopping-simple text-[20px]"></i>
        <span data-role="cart-total-quantity" class="flex items-center justify-center text-center absolute -right-[6px] -bottom-[6px] bg-green-500 text-white text-xs px-1.5 pt-[3px] min-w-4 h-4 leading-none rounded-full">
            {{ get_cart_total_quantity() }}
        </span>
    </a>
</div>
