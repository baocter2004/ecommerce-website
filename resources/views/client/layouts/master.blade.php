<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shoppers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('client.layouts.partials.css')

</head>

<body>

    <div class="site-wrap">
        <header class="site-navbar" role="banner">

            @include('client.layouts.partials.header-top')

            @include('client.layouts.partials.header-nav')

        </header>

        <div class="mt-6 mb-2">
            @yield('content')
        </div>
        <footer class="site-footer border-top">
            @include('client.layouts.partials.footer')
        </footer>
    </div>
    @include('client.layouts.partials.js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Use event delegation for favorite buttons
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.favorite-btn');
                if (!btn) return;

                const productId = btn.getAttribute('data-product-id');
                const icon = btn.querySelector('i');
                
                fetch(`/wishlist/toggle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = '{{ route('login') }}';
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;

                    if (data.status === 'added') {
                        icon.classList.remove('bi-heart', 'text-muted');
                        icon.classList.add('bi-heart-fill', 'text-danger');
                        btn.setAttribute('title', 'Bỏ yêu thích');
                    } else if (data.status === 'removed') {
                        icon.classList.remove('bi-heart-fill', 'text-danger');
                        icon.classList.add('bi-heart', 'text-muted');
                        btn.setAttribute('title', 'Yêu thích');
                    }
                    
                    // Update wishlist counter in header
                    const counter = document.getElementById('wishlist-count');
                    if (counter) {
                        counter.innerText = data.count;
                        if (data.count === 0) {
                            counter.classList.add('d-none');
                        } else {
                            counter.classList.remove('d-none');
                        }
                    } else if (data.count > 0) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
    <style>
        /* Hide default dropdown arrow */
        .dropdown-toggle::after {
            display: none !important;
        }
    </style>
</body>

</html>
