@props(['product', 'status' => true])

<button {{ $attributes }} data-role="like-product" data-id="{{ $product->id }}" data-state="visibility" data-value="{{ as_string($status) }}">
    <i class="fi fi-rr-heart" data-value="false"></i>
    <i class="fi fi-sr-heart text-red-500" data-value="true"></i>
</button>

@pushOnce('bottom-scripts')
    <script>
        $('[data-role="like-product"]').on('click', async function () {
            $(this).prop('disabled', true);

            const id = $(this).data('id');
            const val = $(this).attr('data-value') === 'true';

            $(this).attr('data-value', ! val);

            try {
                if (val) {
                    await axios.delete(API.product.unlike.replace('@id', id));
                } else {
                    await axios.post(API.product.like.replace('@id', id));
                }
            } catch (e) {
                $(this).attr('data-value', ! val);
            }

            $(this).prop('disabled', false);
        });
    </script>
@endPushOnce
