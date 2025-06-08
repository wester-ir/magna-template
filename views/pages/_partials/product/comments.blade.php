<div>
    <h3 class="font-semibold text-lg">نظرات کاربران</h3>

    <div class="flex flex-col lg:flex-row lg:space-x-5 lg:space-x-reverse lg:mb-0 mt-3">
        <div class="comments" data-status="loading">
            <div class="comments-list">
                <div class="comment p-3">نظری ثبت نشده است.</div>
            </div>

            <div class="comments-loading">
                <x-tpl::loading />
            </div>

            <div class="comments-pagination mt-3">
                <button class="previous-comments btn btn-default text-sm">قبلی</button>
                <button class="next-comments btn btn-default text-sm mr-auto">بعدی</button>
            </div>
        </div>

        <form id="comment_form" class="form border border-neutral-200 rounded-lg p-5 lg:w-96 mt-5 lg:mt-0">
            @if (auth()->check())
                <div class="form-control" data-form-field-id="title">
                    <label for="comment_title">عنوان</label>
                    <input id="comment_title" class="default" type="text" name="comment_title" value="">
                </div>

                <div class="form-control" data-form-field-id="text">
                    <label for="comment_text">نظر *</label>
                    <textarea id="comment_text" class="default min-h-[5rem] py-2" name="comment_text"></textarea>
                </div>

                <div class="form-control" data-form-field-id="recommendation_status">
                    <div class="label">به دیگران ... *</div>
                    <input type="hidden" name="comment_recommendation_status" value="1">
                    <div id="comment-recommendation-status" class="grid grid-cols-3 gap-2 text-center text-xs cursor-pointer">
                        <div class="border border-neutral-100 hover:border-green-400 py-2 rounded-md text-center text-green-500 data-[active=true]:border-green-400 data-[active=true]:font-bold" data-active="true" data-value="1">توصیه می کنم</div>
                        <div class="border border-neutral-100 hover:border-red-400 py-2 rounded-md text-center text-red-600 data-[active=true]:border-red-400 data-[active=true]:font-bold" data-value="-1">توصیه نمی کنم</div>
                        <div class="border border-neutral-100 hover:border-neutral-400 py-2 rounded-md text-center text-neutral-600 data-[active=true]:border-neutral-400 data-[active=true]:font-bold" data-value="0">بدون نظر</div>
                    </div>
                </div>

                <div class="form-control" data-form-field-id="is_user_anonymous">
                    <label class="inline-form-control" for="comment_is_user_anonymous">
                        <input id="comment_is_user_anonymous" type="checkbox" name="comment_is_user_anonymous" value="1">
                        <span>بصورت ناشناس</span>
                    </label>
                </div>

                <button type="button" class="submit-comment btn btn-success w-32">ارسال</button>
            @else
                <div class="text-sm text-neutral-500">برای ثبت نظر ابتدا باید وارد حساب کاربری خود شوید.</div>
                <div class="text-center">
                    <a href="{{ route('auth.login') }}" class="btn btn-default inline-flex mt-3">ورود به حساب کاربری</a>
                </div>
            @endif
        </form>
    </div>
</div>

@push('bottom-scripts')
    <script>
        // Submit comment
        $('.submit-comment').on('click', async function () {
            const form = new FormManager('#comment_form');

            form.lock().clearValidationErrors();

            let title = $('#comment_title').val(),
                text = $('#comment_text').val(),
                recommendationStatus = $('[name="comment_recommendation_status"]').attr('value'),
                isUserAnonymous = $('#comment_is_user_anonymous').is(':checked');

            try {
                const { data } = await axios.post('{{ route('client.product.ajax.comments.create', $product) }}', {
                    title: title,
                    text: text,
                    recommendation_status: recommendationStatus,
                    is_user_anonymous: isUserAnonymous,
                });

                form.reset();

                if (! data.is_approved) {
                    toast.success('نظر شما پس از تایید مدیریت نمایش داده خواهد شد.');
                } else {
                    location.reload();
                }
            } catch (e) {
                form.handleRequestError(e);
            }

            form.unlock();
        });

        // Recommendation status
        $('#comment-recommendation-status div').on('click', function () {
            const value = $(this).data('value');

            $(this).attr('data-active', true).siblings().attr('data-active', false);
            $('[name="comment_recommendation_status"]').attr('data-value', value);
        });

        $(document).ready(function () {
            // Get comments
            getComments();
        });

        async function getComments(cursor = null) {
            $('.comments').attr('data-status', 'loading');

            try {
                const { data } = await axios.get('{{ route('client.product.ajax.comments.index', $product) }}', {
                    params: {
                        cursor: cursor,
                    },
                });

                renderComments(data);
            } catch (e) {

            }

            $('.comments').attr('data-status', 'done');
        }

        function renderComments(data) {
            let html = '';

            if (data.data.length === 0) {
                html = '<div class="comment p-3">نظری ثبت نشده است.</div>';
            } else {
                data.data.forEach((comment) => {
                    html += renderComment(comment);
                });
            }

            $('.comments-list').html(html);

            $('.previous-comments').attr('data-cursor', data.meta.prev_cursor);
            $('.next-comments').attr('data-cursor', data.meta.next_cursor);
            $('.previous-comments').attr('data-disabled', data.meta.prev_cursor === null);
            $('.next-comments').attr('data-disabled', data.meta.next_cursor === null);
        }

        function renderComment(comment) {
            const user = comment.full_name ? '<span>'+ comment.full_name +'</span>' : '<span>کاربر</span>';
            const isBuyer = comment.is_buyer && '<span class="badge badge-secondary">خریدار</span>';

            let recommendationStatus = null;

            switch (comment.recommendation_status) {
                case 'recommended':
                    recommendationStatus = '<span class="badge badge-success">توصیه می کنم</span>';
                break;
                case 'not_recommended':
                    recommendationStatus = '<span class="badge badge-danger">توصیه نمی کنم</span>';
                break;
            }

            return '<div class="comment" data-id="'+ comment.id +'">'+
                    '<div class="comment-header">'+
                        '<div class="comment-details">'+
                            user+
                            isBuyer+
                        '</div>'+

                        '<div class="comment-details">'+
                            '<span class="comment-date">'+ comment.created_at +'</span>'+
                            recommendationStatus+
                        '</div>'+
                    '</div>'+

                    '<div class="comment-body">'+
                        comment.text +
                    '</div>'+

                    (comment.reply_text ? '<div class="comment-body">'+
                        '<div class="font-bold">پاسخ:</div>'+
                        '<div>'+ comment.reply_text + '</div>' +
                        '</div>' : '')+

                    (comment.can.delete ? ('<div class="comment-footer">'+
                        '<div class="mr-auto">'+
                            '<button class="delete-comment" data-id="'+ comment.id +'">حذف</button>'+
                        '</div>'+
                    '</div>') : '')+
                '</div>';
        }

        $('.next-comments, .prev-comments').on('click', function () {
            const cursor = $(this).attr('data-cursor');

            getComments(cursor);
        });

        $(document).on('click', '.delete-comment', function () {
            modal.defaults.confirmDanger(async () => {
                $(this).prop('disabled', true);

                const id = $(this).data('id');

                try {
                    const route = '{{ route('client.product.ajax.comments.destroy', ['product' => $product, 'comment' => '@id']) }}'.replace('@id', id);

                    await axios.delete(route);

                    $('.comment[data-id="'+ id +'"]').remove();
                } catch (e) {}

                $(this).prop('disabled', false);
            });
        });
    </script>
@endpush
