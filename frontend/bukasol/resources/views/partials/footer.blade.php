@push('styles')
    <style>
        .footer {
            flex-shrink: 0;
            height: 0px;
        }

        .custom-footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            /* padding: 10px; */
            width: 100%;
        }

        /* Sticky Footer for Large Screens */
        @media (min-width: 768px) {
            .custom-footer {
                position: sticky;
                bottom: 0;
            }
        }

        /* Fixed Footer for Mobile Screens */
        @media (max-width: 576px) {
            .custom-footer {
                position: fixed;
                bottom: 0;
                left: 0;
            }

            body {
                padding-bottom: 50px;
                /* Prevent content from hiding under the footer */
            }
        }
    </style>
@endpush

{{-- <footer class="footer fixed-bottom text-center bg-transparent p-0 m-0 d-flex align-items-end h-100 pt-1" style="min-height: 4dvh">
    <div class="container-fluid w-100 h-100 {{ Request::is('login') ? 'bg-transparent' : 'border-2 border-dark-subtle' }}">
        @if (Request::is('login'))
            <span class="text-light fw-light">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</span>
        @elseif ($pageTitle === 'Dashboard Admin')
            <span class="text-dark fw-light">&copy; Tim Abdimas Telkom University @2025</span>
        @elseif ($pageTitle === 'Footer')
            <span class="text-dark fw-light">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</span>
        @endif
    </div>
</footer> --}}

{{-- <footer class="footer mt-auto p-0 m-0 text-center {{ Request::is('login') ? 'bg-transparent' : 'bg-white' }}">
    <div class="container-fluid p-0 m-0">
        @if (Request::is('login'))
            <span class="text-light fw-light">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</span>
        @elseif ($pageTitle === 'Dashboard Admin')
            <span class="text-dark fw-light">&copy; Tim Abdimas Telkom University @2025</span>
        @elseif ($pageTitle === 'Footer')
            <span class="text-dark fw-light">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</span>
        @endif
    </div>
</footer> --}}

<div class="custom-footer text-light text-center p-0 m-0 py-2 test {{ Request::is('login') ? 'bg-transparent' : 'bg-secondary-subtle' }}" id="footer" style="min-height: 40px; max-height: 40px">
    <div class="container-fluid m-0 w-100 h-100">
        @if (Request::is('login'))
            <span class="text-light fw-light">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</span>
        @elseif ($role === 'Admin' || $role === 'Teacher' || $role === 'Student')
            <span class="text-dark fw-light">&copy; Tim Abdimas Telkom University @2025</span>
        @elseif ($pageTitle === 'Footer')
            <span class="text-dark fw-light">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</span>
        @endif
    </div>
</div>
