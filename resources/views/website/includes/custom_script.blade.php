<script>
    $('#addCouponBtn').on('click', function() {
        const modelType = $(this).data('model-type'); // যেমন 'course'
        const modelId = $(this).data('model-id');     // যেমন 123

        Swal.fire({
            title: 'আপনার কুপন কোড দিন',
            input: 'text',
            inputLabel: 'কুপন',
            inputPlaceholder: 'যেমন: SAVE20',
            showCancelButton: true,
            confirmButtonText: 'প্রয়োগ করুন',
            cancelButtonText: 'বাতিল',
            showLoaderOnConfirm: true,
            preConfirm: (couponCode) => {
                return $.ajax({
                    url: '/apply-coupon',
                    method: 'get',
                    data: {
                        coupon: couponCode,
                        model_type: modelType,
                        model_id: modelId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message || 'অবৈধ কুপন কোড');
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`ত্রুটি: ${error.message}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    icon: 'success',
                    title: 'কুপন প্রয়োগ করা হয়েছে!',
                    text: result.value.message || 'ডিসকাউন্ট যুক্ত করা হয়েছে।',
                });
                location.reload();
            }
        });
    });


</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function wrapBengaliOneWithBold(node) {
            if (node.nodeType === Node.TEXT_NODE) {
                const parts = node.nodeValue.split(/(১)/); // keep the matched "১"
                if (parts.length > 1) {
                    const fragment = document.createDocumentFragment();
                    parts.forEach(part => {
                        if (part === '১') {
                            const span = document.createElement('span');
                            span.style.fontWeight = '700';
                            span.textContent = '১';
                            fragment.appendChild(span);
                        } else {
                            fragment.appendChild(document.createTextNode(part));
                        }
                    });
                    node.parentNode.replaceChild(fragment, node);
                }
            } else if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== 'SCRIPT' && node.tagName !== 'STYLE') {
                Array.from(node.childNodes).forEach(wrapBengaliOneWithBold);
            }
        }

        wrapBengaliOneWithBold(document.body);
    });
</script>
