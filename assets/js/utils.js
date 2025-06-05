function scrollToTab(tabId) {
    const tab = document.getElementById(tabId);

    if (tab) {
        tab.scrollIntoView({ block: 'nearest', inline: 'start' });
    }
}

function handleCartErrors(result, ignore = []) {
    const reason = result.reason;

    // Ignore
    if (ignore.indexOf(reason) > -1) {
        return;
    }

    switch (reason) {
        case 'maximum_allowed_quantity_exceeded':
            toast.error('موجودی محصول در سبد خرید از حداکثر گذشته است.');
            break;
        case 'maximum_allowed_quantity_reached':
            toast.error('موجودی محصول در سبد خرید به حداکثر رسیده است.');
            break;
        case 'product_deleted':
            toast.error('محصول مورد نظر حذف شده است.');
            break;
        case 'cart_capacity_full':
            toast.error('ظرفیت سبد خرید پر شده است.');
            break;
    }
}
